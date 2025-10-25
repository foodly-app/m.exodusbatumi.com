<?php

namespace App\Services;

use Exception;

class MobileBookingService
{
    public function __construct(
        private readonly HttpClient $client
    ) {}

    /**
     * Get available time slots for booking
     *
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function getAvailableSlots(array $params): array
    {
        $requiredParams = [
            'restaurant_id',
            'date',
            'party_size'
        ];

        foreach ($requiredParams as $param) {
            if (!isset($params[$param])) {
                throw new Exception("Missing required parameter: {$param}");
            }
        }

        return $this->client->post('/api/partner/booking/available-slots', $params);
    }

    /**
     * Check specific time slot availability
     *
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function checkTimeSlot(array $params): array
    {
        $requiredParams = [
            'restaurant_id',
            'date',
            'time',
            'party_size'
        ];

        foreach ($requiredParams as $param) {
            if (!isset($params[$param])) {
                throw new Exception("Missing required parameter: {$param}");
            }
        }

        return $this->client->post('/api/partner/booking/check-time-slot', $params);
    }

    /**
     * Send OTP for phone verification
     *
     * @param string $phone
     * @return array
     * @throws Exception
     */
    public function sendOtp(string $phone): array
    {
        return $this->client->post('/api/partner/booking/otp/send', [
            'phone' => $phone
        ]);
    }

    /**
     * Verify OTP code
     *
     * @param string $phone
     * @param string $code
     * @return array
     * @throws Exception
     */
    public function verifyOtp(string $phone, string $code): array
    {
        return $this->client->post('/api/partner/booking/otp/verify', [
            'phone' => $phone,
            'code' => $code
        ]);
    }

    /**
     * Resend OTP code
     *
     * @param string $phone
     * @return array
     * @throws Exception
     */
    public function resendOtp(string $phone): array
    {
        return $this->client->post('/api/partner/booking/otp/resend', [
            'phone' => $phone
        ]);
    }

    /**
     * Create new reservation
     *
     * @param array $reservationData
     * @return array
     * @throws Exception
     */
    public function createReservation(array $reservationData): array
    {
        $requiredFields = [
            'restaurant_id',
            'customer_name',
            'customer_phone',
            'party_size',
            'date',
            'time'
        ];

        foreach ($requiredFields as $field) {
            if (!isset($reservationData[$field])) {
                throw new Exception("Missing required field: {$field}");
            }
        }

        // Set default duration if not provided
        $reservationData['duration'] = $reservationData['duration'] ?? 2;

        return $this->client->post('/api/partner/booking/reserve', $reservationData);
    }

    /**
     * Get customer reservation history
     *
     * @param string $phone
     * @return array
     * @throws Exception
     */
    public function getCustomerHistory(string $phone): array
    {
        return $this->client->post('/api/partner/booking/customer-history', [
            'phone' => $phone
        ]);
    }

    /**
     * Get mobile-optimized available slots with UI formatting
     *
     * @param array $params
     * @return array
     */
    public function getMobileAvailableSlots(array $params): array
    {
        try {
            $response = $this->getAvailableSlots($params);

            if ($response['success']) {
                $slots = collect($response['data']['available_slots'])->map(function ($slot) {
                    return [
                        'time' => $slot['time'],
                        'formatted_time' => \Carbon\Carbon::parse($slot['time'])->format('H:i'),
                        'available_tables' => $slot['available_tables'],
                        'tables' => collect($slot['tables'])->map(function ($table) {
                            return [
                                'id' => $table['id'],
                                'table_number' => $table['table_number'],
                                'capacity' => $table['capacity'],
                                'place_name' => $table['place_name'],
                                'display_name' => $table['table_number'] . ' (' . $table['place_name'] . ')',
                            ];
                        })->toArray()
                    ];
                });

                return [
                    'success' => true,
                    'data' => [
                        'date' => $response['data']['date'],
                        'formatted_date' => \Carbon\Carbon::parse($response['data']['date'])->locale('ka')->format('l, d F Y'),
                        'available_slots' => $slots->toArray()
                    ]
                ];
            }

            return $response;
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Complete booking flow for mobile
     *
     * @param array $bookingData
     * @return array
     */
    public function completeMobileBooking(array $bookingData): array
    {
        try {
            // Step 1: Verify OTP if provided
            if (isset($bookingData['phone']) && isset($bookingData['otp_code'])) {
                $otpVerification = $this->verifyOtp($bookingData['phone'], $bookingData['otp_code']);
                
                if (!$otpVerification['success']) {
                    return [
                        'success' => false,
                        'error' => 'OTP verification failed',
                        'details' => $otpVerification
                    ];
                }
                
                $bookingData['otp_verified'] = true;
            }

            // Step 2: Check slot availability again
            $slotCheck = $this->checkTimeSlot([
                'restaurant_id' => $bookingData['restaurant_id'],
                'date' => $bookingData['date'],
                'time' => $bookingData['time'],
                'party_size' => $bookingData['party_size'],
                'table_id' => $bookingData['table_id'] ?? null,
                'duration' => $bookingData['duration'] ?? 2
            ]);

            if (!$slotCheck['success'] || !$slotCheck['data']['is_available']) {
                return [
                    'success' => false,
                    'error' => 'Selected time slot is no longer available',
                    'details' => $slotCheck
                ];
            }

            // Step 3: Create reservation
            $reservation = $this->createReservation($bookingData);

            if ($reservation['success']) {
                return [
                    'success' => true,
                    'data' => [
                        'reservation' => $reservation['data'],
                        'message' => 'Reservation created successfully',
                        'next_action' => 'confirmation'
                    ]
                ];
            }

            return $reservation;

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Format customer data for mobile display
     *
     * @param string $phone
     * @return array
     */
    public function getMobileCustomerData(string $phone): array
    {
        try {
            $customerHistory = $this->getCustomerHistory($phone);

            if ($customerHistory['success']) {
                $data = $customerHistory['data'];
                
                return [
                    'success' => true,
                    'data' => [
                        'customer' => $data['customer'],
                        'statistics' => $data['statistics'],
                        'recent_reservations' => collect($data['recent_reservations'])->take(5)->map(function ($reservation) {
                            return [
                                'id' => $reservation['id'],
                                'restaurant_name' => $reservation['restaurant_name'] ?? 'Unknown',
                                'date' => $reservation['date'],
                                'formatted_date' => \Carbon\Carbon::parse($reservation['date'])->locale('ka')->format('d M, Y'),
                                'status' => $reservation['status'],
                                'party_size' => $reservation['party_size']
                            ];
                        })->toArray(),
                        'is_returning_customer' => $data['statistics']['total_reservations'] > 0,
                        'reliability_score' => $this->calculateReliabilityScore($data['statistics'])
                    ]
                ];
            }

            return $customerHistory;

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Calculate customer reliability score
     *
     * @param array $statistics
     * @return float
     */
    private function calculateReliabilityScore(array $statistics): float
    {
        $total = $statistics['total_reservations'];
        
        if ($total === 0) {
            return 0.0;
        }

        $completed = $statistics['completed'];
        $noShow = $statistics['no_show'];
        $cancelled = $statistics['cancelled'];

        // Base score from completion rate
        $completionRate = ($completed / $total) * 100;
        
        // Penalty for no-shows (more severe than cancellations)
        $noShowPenalty = ($noShow / $total) * 30;
        $cancelPenalty = ($cancelled / $total) * 10;

        $score = $completionRate - $noShowPenalty - $cancelPenalty;

        return max(0, min(100, round($score, 1)));
    }

    /**
     * Validate booking data for mobile
     *
     * @param array $data
     * @return array
     */
    public function validateBookingData(array $data): array
    {
        $errors = [];

        // Required fields validation
        $requiredFields = [
            'restaurant_id' => 'Restaurant selection is required',
            'customer_name' => 'Customer name is required',
            'customer_phone' => 'Phone number is required',
            'party_size' => 'Party size is required',
            'date' => 'Date is required',
            'time' => 'Time is required'
        ];

        foreach ($requiredFields as $field => $message) {
            if (!isset($data[$field]) || empty($data[$field])) {
                $errors[$field] = $message;
            }
        }

        // Phone number format validation
        if (isset($data['customer_phone']) && !preg_match('/^\+995\d{9}$/', $data['customer_phone'])) {
            $errors['customer_phone'] = 'Phone number must be in format +995XXXXXXXXX';
        }

        // Email validation if provided
        if (isset($data['customer_email']) && !empty($data['customer_email']) && !filter_var($data['customer_email'], FILTER_VALIDATE_EMAIL)) {
            $errors['customer_email'] = 'Invalid email format';
        }

        // Party size validation
        if (isset($data['party_size']) && ($data['party_size'] < 1 || $data['party_size'] > 20)) {
            $errors['party_size'] = 'Party size must be between 1 and 20';
        }

        // Date validation
        if (isset($data['date'])) {
            $date = \Carbon\Carbon::parse($data['date']);
            if ($date->isPast() && !$date->isToday()) {
                $errors['date'] = 'Cannot book for past dates';
            }
            if ($date->diffInDays(now()) > 60) {
                $errors['date'] = 'Cannot book more than 60 days in advance';
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
}