<?php

namespace App\Http\Controllers;

use App\Services\MobileReservationService;
use App\Services\MobileRestaurantService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MobileReservationController extends Controller
{
    public function __construct(
        private readonly MobileReservationService $reservationService,
        private readonly MobileRestaurantService $restaurantService
    ) {}

    /**
     * Show reservations list
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $restaurantId
     * @return View
     */
    public function index(Request $request, int $organizationId, int $restaurantId): View
    {
        try {
            $filters = $request->only(['status', 'date_from', 'date_to', 'page']);
            
            // Default to showing upcoming reservations
            if (!isset($filters['date_from'])) {
                $filters['date_from'] = now()->format('Y-m-d');
            }

            $reservations = $this->reservationService->getMobileReservationList($organizationId, $restaurantId, $filters);
            $restaurant = $this->restaurantService->getRestaurant($organizationId, $restaurantId);

            return view('mobile.reservations.index', [
                'reservations' => $reservations['success'] ? $reservations['data'] : [],
                'meta' => $reservations['meta'] ?? null,
                'restaurant' => $restaurant['success'] ? $restaurant['data'] : null,
                'organizationId' => $organizationId,
                'restaurantId' => $restaurantId,
                'filters' => $filters,
                'error' => !$reservations['success'] ? $reservations['error'] : null
            ]);

        } catch (Exception $e) {
            return view('mobile.reservations.index', [
                'reservations' => [],
                'meta' => null,
                'restaurant' => null,
                'organizationId' => $organizationId,
                'restaurantId' => $restaurantId,
                'filters' => [],
                'error' => 'Failed to load reservations: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show upcoming reservations
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $restaurantId
     * @return View
     */
    public function upcoming(Request $request, int $organizationId, int $restaurantId): View
    {
        try {
            $filters = [
                'date_from' => now()->format('Y-m-d'),
                'date_to' => now()->addDays(7)->format('Y-m-d'),
                'status' => 'confirmed,pending'
            ];

            $reservations = $this->reservationService->getMobileReservationList($organizationId, $restaurantId, $filters);
            $restaurant = $this->restaurantService->getRestaurant($organizationId, $restaurantId);

            return view('mobile.reservations.upcoming', [
                'reservations' => $reservations['success'] ? $reservations['data'] : [],
                'restaurant' => $restaurant['success'] ? $restaurant['data'] : null,
                'organizationId' => $organizationId,
                'restaurantId' => $restaurantId,
                'error' => !$reservations['success'] ? $reservations['error'] : null
            ]);

        } catch (Exception $e) {
            return view('mobile.reservations.upcoming', [
                'reservations' => [],
                'restaurant' => null,
                'organizationId' => $organizationId,
                'restaurantId' => $restaurantId,
                'error' => 'Failed to load upcoming reservations: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show reservation details
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $reservationId
     * @return View
     */
    public function show(int $organizationId, int $restaurantId, int $reservationId): View
    {
        try {
            $reservation = $this->reservationService->getReservation($organizationId, $restaurantId, $reservationId);
            $restaurant = $this->restaurantService->getRestaurant($organizationId, $restaurantId);

            return view('mobile.reservations.show', [
                'reservation' => $reservation['success'] ? $reservation['data'] : null,
                'restaurant' => $restaurant['success'] ? $restaurant['data'] : null,
                'organizationId' => $organizationId,
                'restaurantId' => $restaurantId,
                'error' => !$reservation['success'] ? $reservation['error'] : null
            ]);

        } catch (Exception $e) {
            return view('mobile.reservations.show', [
                'reservation' => null,
                'restaurant' => null,
                'organizationId' => $organizationId,
                'restaurantId' => $restaurantId,
                'error' => 'Failed to load reservation: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Confirm reservation
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $reservationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirm(int $organizationId, int $restaurantId, int $reservationId)
    {
        try {
            $response = $this->reservationService->confirm($organizationId, $restaurantId, $reservationId);

            if ($response['success']) {
                return back()->with('success', 'Reservation confirmed successfully');
            }

            return back()->withErrors(['error' => 'Failed to confirm reservation']);

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Confirmation failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Cancel reservation
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $reservationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Request $request, int $organizationId, int $restaurantId, int $reservationId)
    {
        $request->validate([
            'reason' => 'nullable|string|max:255'
        ]);

        try {
            $response = $this->reservationService->cancel(
                $organizationId, 
                $restaurantId, 
                $reservationId, 
                $request->reason
            );

            if ($response['success']) {
                return back()->with('success', 'Reservation cancelled successfully');
            }

            return back()->withErrors(['error' => 'Failed to cancel reservation']);

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Cancellation failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Mark as paid
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $reservationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsPaid(int $organizationId, int $restaurantId, int $reservationId)
    {
        try {
            $response = $this->reservationService->markAsPaid($organizationId, $restaurantId, $reservationId);

            if ($response['success']) {
                return back()->with('success', 'Reservation marked as paid');
            }

            return back()->withErrors(['error' => 'Failed to mark as paid']);

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Update failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Mark as completed
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $reservationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsCompleted(int $organizationId, int $restaurantId, int $reservationId)
    {
        try {
            $response = $this->reservationService->markAsCompleted($organizationId, $restaurantId, $reservationId);

            if ($response['success']) {
                return back()->with('success', 'Reservation completed successfully');
            }

            return back()->withErrors(['error' => 'Failed to mark as completed']);

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Update failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Mark as no-show
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $reservationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsNoShow(int $organizationId, int $restaurantId, int $reservationId)
    {
        try {
            $response = $this->reservationService->markAsNoShow($organizationId, $restaurantId, $reservationId);

            if ($response['success']) {
                return back()->with('success', 'Reservation marked as no-show');
            }

            return back()->withErrors(['error' => 'Failed to mark as no-show']);

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Update failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Update status (AJAX endpoint)
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $reservationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, int $organizationId, int $restaurantId, int $reservationId)
    {
        $request->validate([
            'status' => 'required|string|in:pending,confirmed,cancelled,completed,no-show',
            'reason' => 'nullable|string|max:255'
        ]);

        try {
            $response = $this->reservationService->updateStatus(
                $organizationId,
                $restaurantId,
                $reservationId,
                $request->status,
                $request->reason
            );

            return response()->json($response);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get reservations data (AJAX endpoint)
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $restaurantId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReservations(Request $request, int $organizationId, int $restaurantId)
    {
        try {
            $filters = $request->only(['status', 'date_from', 'date_to', 'page', 'per_page']);
            
            $reservations = $this->reservationService->getMobileReservationList($organizationId, $restaurantId, $filters);

            return response()->json($reservations);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get status counts (AJAX endpoint)
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $restaurantId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatusCounts(Request $request, int $organizationId, int $restaurantId)
    {
        try {
            $dateRange = $request->only(['from', 'to']);
            
            $counts = $this->reservationService->getStatusCounts($organizationId, $restaurantId, $dateRange);

            return response()->json($counts);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Initiate payment (AJAX endpoint)
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $reservationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function initiatePayment(Request $request, int $organizationId, int $restaurantId, int $reservationId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01'
        ]);

        try {
            $returnUrl = route('mobile.payments.success', [
                'organization' => $organizationId,
                'restaurant' => $restaurantId,
                'reservation' => $reservationId
            ]);

            $response = $this->reservationService->initiatePayment(
                $organizationId,
                $restaurantId,
                $reservationId,
                $request->amount,
                $returnUrl
            );

            return response()->json($response);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk actions (AJAX endpoint)
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $restaurantId
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkAction(Request $request, int $organizationId, int $restaurantId)
    {
        $request->validate([
            'action' => 'required|string|in:confirm,cancel,complete,no-show',
            'reservation_ids' => 'required|array|min:1',
            'reservation_ids.*' => 'integer|exists:reservations,id',
            'reason' => 'nullable|string|max:255'
        ]);

        try {
            $results = [];
            $successCount = 0;
            $errorCount = 0;

            foreach ($request->reservation_ids as $reservationId) {
                try {
                    $response = match ($request->action) {
                        'confirm' => $this->reservationService->confirm($organizationId, $restaurantId, $reservationId),
                        'cancel' => $this->reservationService->cancel($organizationId, $restaurantId, $reservationId, $request->reason),
                        'complete' => $this->reservationService->markAsCompleted($organizationId, $restaurantId, $reservationId),
                        'no-show' => $this->reservationService->markAsNoShow($organizationId, $restaurantId, $reservationId)
                    };

                    if ($response['success']) {
                        $successCount++;
                    } else {
                        $errorCount++;
                    }

                    $results[$reservationId] = $response;

                } catch (Exception $e) {
                    $errorCount++;
                    $results[$reservationId] = ['success' => false, 'error' => $e->getMessage()];
                }
            }

            return response()->json([
                'success' => $errorCount === 0,
                'results' => $results,
                'summary' => [
                    'total' => count($request->reservation_ids),
                    'success' => $successCount,
                    'errors' => $errorCount
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}