<?php

namespace App\Services;

use Exception;

class ReservationService
{
    public function __construct(
        private readonly HttpClient $client
    ) {}

    /**
     * Get reservations list for mobile with pagination
     *
     * @throws Exception
     */
    public function getReservations(int $organizationId, int $restaurantId, array $filters = []): array
    {
        $params = array_merge([
            'page' => 1,
            'per_page' => 20,
        ], $filters);

        return $this->client->get("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}/reservations", $params);
    }

    /**
     * Get single reservation details
     *
     * @throws Exception
     */
    public function getReservation(int $organizationId, int $restaurantId, int $reservationId): array
    {
        return $this->client->get("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}/reservations/{$reservationId}");
    }

    /**
     * Update reservation status
     *
     * @throws Exception
     */
    public function updateStatus(int $organizationId, int $restaurantId, int $reservationId, string $status, ?string $reason = null): array
    {
        $data = ['status' => $status];

        if ($reason) {
            $data['reason'] = $reason;
        }

        return $this->client->put("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}/reservations/{$reservationId}/status", $data);
    }

    /**
     * Confirm reservation (quick action)
     *
     * @throws Exception
     */
    public function confirm(int $organizationId, int $restaurantId, int $reservationId): array
    {
        return $this->client->post("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}/reservations/{$reservationId}/confirm");
    }

    /**
     * Cancel reservation
     *
     * @throws Exception
     */
    public function cancel(int $organizationId, int $restaurantId, int $reservationId, ?string $reason = null): array
    {
        $data = $reason ? ['reason' => $reason] : [];

        return $this->client->post("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}/reservations/{$reservationId}/cancel", $data);
    }

    /**
     * Mark reservation as paid
     *
     * @throws Exception
     */
    public function markAsPaid(int $organizationId, int $restaurantId, int $reservationId): array
    {
        return $this->client->post("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}/reservations/{$reservationId}/paid");
    }

    /**
     * Mark reservation as completed
     *
     * @throws Exception
     */
    public function markAsCompleted(int $organizationId, int $restaurantId, int $reservationId): array
    {
        return $this->client->post("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}/reservations/{$reservationId}/complete");
    }

    /**
     * Mark reservation as no-show
     *
     * @throws Exception
     */
    public function markAsNoShow(int $organizationId, int $restaurantId, int $reservationId): array
    {
        return $this->client->post("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}/reservations/{$reservationId}/no-show");
    }

    /**
     * Initiate payment for reservation
     *
     * @throws Exception
     */
    public function initiatePayment(int $organizationId, int $restaurantId, int $reservationId, float $amount, string $returnUrl): array
    {
        return $this->client->post("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}/reservations/{$reservationId}/initiate-payment", [
            'amount' => $amount,
            'return_url' => $returnUrl,
        ]);
    }

    /**
     * Get reservations by place
     *
     * @throws Exception
     */
    public function getReservationsByPlace(int $organizationId, int $restaurantId, int $placeId, array $filters = []): array
    {
        return $this->client->get("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}/places/{$placeId}/reservations", $filters);
    }

    /**
     * Get mobile-optimized reservation list
     */
    public function getMobileReservationList(int $organizationId, int $restaurantId, array $filters = []): array
    {
        try {
            // Default filters for mobile optimization
            $mobileFilters = array_merge([
                'per_page' => 15, // Smaller page size for mobile
                'date_from' => now()->format('Y-m-d'), // Start from today by default
            ], $filters);

            $response = $this->getReservations($organizationId, $restaurantId, $mobileFilters);

            if ($response['success']) {
                // Format data for mobile consumption
                $reservations = collect($response['data'])->map(function ($reservation) {
                    return [
                        'id' => $reservation['id'],
                        'reservation_number' => $reservation['reservation_number'],
                        'customer_name' => $reservation['customer_name'],
                        'customer_phone' => $reservation['customer_phone'],
                        'party_size' => $reservation['party_size'],
                        'date' => $reservation['date'],
                        'time' => $reservation['time'],
                        'status' => $reservation['status'],
                        'table' => $reservation['table'] ?? null,
                        'payment_status' => $reservation['payment_status'] ?? 'pending',
                        'formatted_date' => \Carbon\Carbon::parse($reservation['date'])->locale('ka')->format('d M, Y'),
                        'formatted_time' => \Carbon\Carbon::parse($reservation['time'])->format('H:i'),
                        'status_badge_class' => $this->getStatusBadgeClass($reservation['status']),
                        'can_confirm' => $reservation['status'] === 'pending',
                        'can_cancel' => in_array($reservation['status'], ['pending', 'confirmed']),
                        'can_complete' => $reservation['status'] === 'confirmed',
                    ];
                });

                return [
                    'success' => true,
                    'data' => $reservations->toArray(),
                    'meta' => $response['meta'] ?? null,
                ];
            }

            return $response;
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get status badge CSS class for mobile UI
     */
    private function getStatusBadgeClass(string $status): string
    {
        return match ($status) {
            'pending' => 'badge bg-warning',
            'confirmed' => 'badge bg-success',
            'cancelled' => 'badge bg-danger',
            'completed' => 'badge bg-primary',
            'no-show' => 'badge bg-secondary',
            default => 'badge bg-light text-dark',
        };
    }

    /**
     * Get reservation counts by status for mobile dashboard
     */
    public function getStatusCounts(int $organizationId, int $restaurantId, array $dateRange = []): array
    {
        try {
            $filters = array_merge([
                'date_from' => $dateRange['from'] ?? now()->format('Y-m-d'),
                'date_to' => $dateRange['to'] ?? now()->addDays(7)->format('Y-m-d'),
            ], $dateRange);

            $stats = $this->client->get("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}/reservations/statistics", $filters);

            if ($stats['success']) {
                return [
                    'success' => true,
                    'data' => [
                        'total' => $stats['data']['total_reservations'],
                        'pending' => $stats['data']['pending'],
                        'confirmed' => $stats['data']['confirmed'],
                        'cancelled' => $stats['data']['cancelled'],
                        'completed' => $stats['data']['completed'],
                        'no_show' => $stats['data']['no_show'],
                    ],
                ];
            }

            return $stats;
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
