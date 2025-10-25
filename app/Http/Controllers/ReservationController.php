<?php

namespace App\Http\Controllers;

use App\Services\ReservationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct(
        private readonly ReservationService $reservationService
    ) {}

    /**
     * Get list of all reservations
     *
     * @param Request $request
     * @param int|null $organizationId
     * @param int|null $restaurantId
     * @return JsonResponse
     */
    public function index(Request $request, int $organizationId = null, int $restaurantId = null): JsonResponse
    {
        try {
            $reservations = $this->reservationService->list(
                $organizationId, 
                $restaurantId, 
                $request->query()
            );

            // Return the API response directly (it already has success and data structure)
            return response()->json($reservations);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Get reservations calendar
     *
     * @param Request $request
     * @param int|null $organizationId
     * @param int|null $restaurantId
     * @return JsonResponse
     */
    public function calendar(Request $request, int $organizationId = null, int $restaurantId = null): JsonResponse
    {
        try {
            $params = $request->query();
            
            // If organization and restaurant IDs are provided via route parameters, use them
            if ($organizationId && $restaurantId) {
                $params['organization_id'] = $organizationId;
                $params['restaurant_id'] = $restaurantId;
            }
            
            $calendar = $this->reservationService->calendar($params);

            return response()->json($calendar);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get reservation by ID
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $organizationId, int $restaurantId, int $id): JsonResponse
    {
        try {
            $reservation = $this->reservationService->get($organizationId, $restaurantId, $id);

            return response()->json($reservation);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Create reservation
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $restaurantId
     * @return JsonResponse
     */
    public function store(Request $request, int $organizationId, int $restaurantId): JsonResponse
    {
        try {
            $data = $request->validate([
                'customer_name' => ['required', 'string', 'max:255'],
                'customer_phone' => ['required', 'string', 'max:20'],
                'customer_email' => ['sometimes', 'email'],
                'date' => ['required', 'date'],
                'time' => ['required', 'string'],
                'guests' => ['required', 'integer', 'min:1'],
                'table_id' => ['sometimes', 'integer'],
                'notes' => ['sometimes', 'string'],
            ]);

            $reservation = $this->reservationService->create($organizationId, $restaurantId, $data);

            return response()->json([
                'success' => true,
                'data' => $reservation
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Update reservation
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $organizationId, int $restaurantId, int $id): JsonResponse
    {
        try {
            $data = $request->validate([
                'customer_name' => ['sometimes', 'string', 'max:255'],
                'customer_phone' => ['sometimes', 'string', 'max:20'],
                'customer_email' => ['sometimes', 'email'],
                'date' => ['sometimes', 'date'],
                'time' => ['sometimes', 'string'],
                'guests' => ['sometimes', 'integer', 'min:1'],
                'table_id' => ['sometimes', 'integer'],
                'notes' => ['sometimes', 'string'],
            ]);

            $reservation = $this->reservationService->update($organizationId, $restaurantId, $id, $data);

            return response()->json([
                'success' => true,
                'data' => $reservation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Update reservation status
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $id
     * @return JsonResponse
     */
    public function updateStatus(Request $request, int $organizationId, int $restaurantId, int $id): JsonResponse
    {
        try {
            $data = $request->validate([
                'status' => ['required', 'string', 'in:pending,confirmed,cancelled,completed,no_show']
            ]);

            $reservation = $this->reservationService->updateStatus($organizationId, $restaurantId, $id, $data);

            return response()->json([
                'success' => true,
                'data' => $reservation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Confirm reservation
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $id
     * @return JsonResponse
     */
    public function confirm(int $organizationId, int $restaurantId, int $id): JsonResponse
    {
        try {
            $reservation = $this->reservationService->confirm($organizationId, $restaurantId, $id);

            return response()->json([
                'success' => true,
                'data' => $reservation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Cancel reservation
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $id
     * @return JsonResponse
     */
    public function cancel(Request $request, int $organizationId, int $restaurantId, int $id): JsonResponse
    {
        try {
            $data = $request->validate([
                'reason' => ['sometimes', 'string']
            ]);

            $reservation = $this->reservationService->cancel($organizationId, $restaurantId, $id, $data);

            return response()->json([
                'success' => true,
                'data' => $reservation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Mark reservation as paid
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $id
     * @return JsonResponse
     */
    public function markAsPaid(int $organizationId, int $restaurantId, int $id): JsonResponse
    {
        try {
            $reservation = $this->reservationService->markAsPaid($organizationId, $restaurantId, $id);

            return response()->json([
                'success' => true,
                'data' => $reservation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Complete reservation
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $id
     * @return JsonResponse
     */
    public function complete(int $organizationId, int $restaurantId, int $id): JsonResponse
    {
        try {
            $reservation = $this->reservationService->complete($organizationId, $restaurantId, $id);

            return response()->json([
                'success' => true,
                'data' => $reservation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Mark reservation as no-show
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $id
     * @return JsonResponse
     */
    public function noShow(int $organizationId, int $restaurantId, int $id): JsonResponse
    {
        try {
            $reservation = $this->reservationService->noShow($organizationId, $restaurantId, $id);

            return response()->json([
                'success' => true,
                'data' => $reservation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Assign table to reservation
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $id
     * @return JsonResponse
     */
    public function assignTable(Request $request, int $organizationId, int $restaurantId, int $id): JsonResponse
    {
        try {
            $data = $request->validate([
                'table_id' => ['required', 'integer']
            ]);

            $reservation = $this->reservationService->assignTable($organizationId, $restaurantId, $id, $data);

            return response()->json([
                'success' => true,
                'data' => $reservation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Get reservation statistics
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function statistics(Request $request): JsonResponse
    {
        try {
            $statistics = $this->reservationService->statistics($request->query());

            return response()->json([
                'success' => true,
                'data' => $statistics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search reservations
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $reservations = $this->reservationService->search($request->query());

            return response()->json([
                'success' => true,
                'data' => $reservations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add note to reservation
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $id
     * @return JsonResponse
     */
    public function addNote(Request $request, int $organizationId, int $restaurantId, int $id): JsonResponse
    {
        try {
            $data = $request->validate([
                'note' => ['required', 'string']
            ]);

            $reservation = $this->reservationService->addNote($organizationId, $restaurantId, $id, $data);

            return response()->json([
                'success' => true,
                'data' => $reservation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Delete reservation
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $organizationId, int $restaurantId, int $id): JsonResponse
    {
        try {
            $this->reservationService->delete($organizationId, $restaurantId, $id);

            return response()->json([
                'success' => true,
                'message' => 'Reservation deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}