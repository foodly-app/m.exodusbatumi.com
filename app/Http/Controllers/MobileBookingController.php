<?php

namespace App\Http\Controllers;

use App\Services\MobileBookingService;
use App\Services\MobileRestaurantService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MobileBookingController extends Controller
{
    public function __construct(
        private readonly MobileBookingService $bookingService,
        private readonly MobileRestaurantService $restaurantService
    ) {}

    /**
     * Show restaurants list for booking (simplified - uses session)
     *
     * @param Request $request
     * @return View|\Illuminate\Http\RedirectResponse
     */
    public function restaurantsSimplified(Request $request)
    {
        // Get dashboard data from session
        $dashboardData = session('dashboard_data');
        
        if (!$dashboardData) {
            return redirect()->route('mobile.dashboard')
                ->with('error', 'გთხოვთ, ჯერ გაიაროთ ავტორიზაცია');
        }
        
        return view('mobile.booking.restaurants', [
            'restaurants' => $dashboardData['restaurants'] ?? [],
            'organizationId' => $dashboardData['organization']['id'] ?? null,
            'error' => null
        ]);
    }

    /**
     * Show restaurants list for booking
     *
     * @param Request $request
     * @param int $organizationId
     * @return View
     */
    public function restaurants(Request $request, int $organizationId): View
    {
        try {
            $restaurants = $this->restaurantService->getRestaurants($organizationId);
            
            return view('mobile.booking.restaurants', [
                'restaurants' => $restaurants['success'] ? $restaurants['data'] : [],
                'organizationId' => $organizationId,
                'error' => !$restaurants['success'] ? $restaurants['error'] : null
            ]);

        } catch (Exception $e) {
            return view('mobile.booking.restaurants', [
                'restaurants' => [],
                'organizationId' => $organizationId,
                'error' => 'Failed to load restaurants: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show date selection
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $restaurantId
     * @return View
     */
    public function selectDate(Request $request, int $organizationId, int $restaurantId): View
    {
        try {
            $restaurant = $this->restaurantService->getRestaurant($organizationId, $restaurantId);
            
            // Get party size from query or default to 2
            $partySize = $request->query('party_size', 2);
            
            return view('mobile.booking.select-date', [
                'restaurant' => $restaurant['success'] ? $restaurant['data'] : null,
                'organizationId' => $organizationId,
                'restaurantId' => $restaurantId,
                'partySize' => $partySize,
                'error' => !$restaurant['success'] ? $restaurant['error'] : null
            ]);

        } catch (Exception $e) {
            return view('mobile.booking.select-date', [
                'restaurant' => null,
                'organizationId' => $organizationId,
                'restaurantId' => $restaurantId,
                'partySize' => 2,
                'error' => 'Failed to load restaurant: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show time selection
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $restaurantId
     * @return View|\Illuminate\Http\RedirectResponse
     */
    public function selectTime(Request $request, int $organizationId, int $restaurantId)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'party_size' => 'required|integer|min:1|max:20'
        ]);

        try {
            $restaurant = $this->restaurantService->getRestaurant($organizationId, $restaurantId);
            
            // Get available slots
            $slots = $this->bookingService->getMobileAvailableSlots([
                'restaurant_id' => $restaurantId,
                'date' => $request->date,
                'party_size' => $request->party_size,
                'place_id' => $request->place_id
            ]);

            return view('mobile.booking.select-time', [
                'restaurant' => $restaurant['success'] ? $restaurant['data'] : null,
                'availableSlots' => $slots['success'] ? $slots['data'] : null,
                'organizationId' => $organizationId,
                'restaurantId' => $restaurantId,
                'date' => $request->date,
                'partySize' => $request->party_size,
                'placeId' => $request->place_id,
                'error' => !$slots['success'] ? $slots['error'] : null
            ]);

        } catch (Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Failed to load available times: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show booking details form
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $restaurantId
     * @return View|\Illuminate\Http\RedirectResponse
     */
    public function details(Request $request, int $organizationId, int $restaurantId)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|string',
            'party_size' => 'required|integer|min:1|max:20',
            'table_id' => 'required|integer'
        ]);

        try {
            $restaurant = $this->restaurantService->getRestaurant($organizationId, $restaurantId);
            
            // Check slot availability again
            $slotCheck = $this->bookingService->checkTimeSlot([
                'restaurant_id' => $restaurantId,
                'date' => $request->date,
                'time' => $request->time,
                'party_size' => $request->party_size,
                'table_id' => $request->table_id
            ]);

            if (!$slotCheck['success'] || !$slotCheck['data']['is_available']) {
                return redirect()->route('mobile.booking.select-time', [
                    'organization' => $organizationId,
                    'restaurant' => $restaurantId,
                    'date' => $request->date,
                    'party_size' => $request->party_size
                ])->withErrors(['error' => 'Selected time slot is no longer available']);
            }

            return view('mobile.booking.details', [
                'restaurant' => $restaurant['success'] ? $restaurant['data'] : null,
                'organizationId' => $organizationId,
                'restaurantId' => $restaurantId,
                'date' => $request->date,
                'time' => $request->time,
                'partySize' => $request->party_size,
                'tableId' => $request->table_id,
                'duration' => $request->duration ?? 2,
                'error' => !$restaurant['success'] ? $restaurant['error'] : null
            ]);

        } catch (Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Failed to load booking details: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Create booking
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $restaurantId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request, int $organizationId, int $restaurantId)
    {
        // Validate booking data
        $validation = $this->bookingService->validateBookingData($request->all());
        
        if (!$validation['valid']) {
            return back()->withErrors($validation['errors'])->withInput();
        }

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|regex:/^\+995\d{9}$/',
            'customer_email' => 'nullable|email|max:255',
            'party_size' => 'required|integer|min:1|max:20',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|string',
            'table_id' => 'required|integer',
            'duration' => 'nullable|integer|min:1|max:8',
            'special_requests' => 'nullable|string|max:500',
            'otp_code' => 'nullable|string|size:6'
        ]);

        try {
            $bookingData = array_merge($request->all(), [
                'restaurant_id' => $restaurantId
            ]);

            $result = $this->bookingService->completeMobileBooking($bookingData);

            if ($result['success']) {
                return redirect()->route('mobile.reservations.show', [
                    'organization' => $organizationId,
                    'restaurant' => $restaurantId,
                    'reservation' => $result['data']['reservation']['id']
                ])->with('success', 'Reservation created successfully!');
            }

            return back()->withErrors([
                'error' => $result['error']
            ])->withInput();

        } catch (Exception $e) {
            return back()->withErrors([
                'error' => 'Booking failed: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Send OTP (AJAX endpoint)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|regex:/^\+995\d{9}$/'
        ]);

        try {
            $response = $this->bookingService->sendOtp($request->phone);
            
            return response()->json($response);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify OTP (AJAX endpoint)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|regex:/^\+995\d{9}$/',
            'code' => 'required|string|size:6'
        ]);

        try {
            $response = $this->bookingService->verifyOtp($request->phone, $request->code);
            
            return response()->json($response);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Resend OTP (AJAX endpoint)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|regex:/^\+995\d{9}$/'
        ]);

        try {
            $response = $this->bookingService->resendOtp($request->phone);
            
            return response()->json($response);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get customer history (AJAX endpoint)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomerHistory(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|regex:/^\+995\d{9}$/'
        ]);

        try {
            $response = $this->bookingService->getMobileCustomerData($request->phone);
            
            return response()->json($response);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available slots (AJAX endpoint)
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $restaurantId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailableSlots(Request $request, int $organizationId, int $restaurantId)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'party_size' => 'required|integer|min:1|max:20',
            'place_id' => 'nullable|integer'
        ]);

        try {
            $slots = $this->bookingService->getMobileAvailableSlots([
                'restaurant_id' => $restaurantId,
                'date' => $request->date,
                'party_size' => $request->party_size,
                'place_id' => $request->place_id
            ]);

            return response()->json($slots);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check time slot (AJAX endpoint)
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $restaurantId
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkTimeSlot(Request $request, int $organizationId, int $restaurantId)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|string',
            'party_size' => 'required|integer|min:1|max:20',
            'table_id' => 'nullable|integer',
            'duration' => 'nullable|integer|min:1|max:8'
        ]);

        try {
            $response = $this->bookingService->checkTimeSlot([
                'restaurant_id' => $restaurantId,
                'date' => $request->date,
                'time' => $request->time,
                'party_size' => $request->party_size,
                'table_id' => $request->table_id,
                'duration' => $request->duration ?? 2
            ]);

            return response()->json($response);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate booking data (AJAX endpoint)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateBookingData(Request $request)
    {
        try {
            $validation = $this->bookingService->validateBookingData($request->all());
            
            return response()->json($validation);

        } catch (Exception $e) {
            return response()->json([
                'valid' => false,
                'errors' => ['general' => $e->getMessage()]
            ], 500);
        }
    }
}