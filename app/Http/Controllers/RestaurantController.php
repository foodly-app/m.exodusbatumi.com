<?php

namespace App\Http\Controllers;

use App\Services\RestaurantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function __construct(
        private readonly RestaurantService $restaurantService
    ) {}

    /**
     * Get list of restaurants
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $restaurants = $this->restaurantService->list($request->query());

            return response()->json([
                'success' => true,
                'data' => $restaurants
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get restaurant by ID
     *
     * @param int $organizationId
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $organizationId, int $id): JsonResponse
    {
        try {
            $restaurant = $this->restaurantService->get($organizationId, $id);

            return response()->json([
                'success' => true,
                'data' => $restaurant
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Create restaurant
     *
     * @param Request $request
     * @param int $organizationId
     * @return JsonResponse
     */
    public function store(Request $request, int $organizationId): JsonResponse
    {
        try {
            $data = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:20'],
                'address' => ['required', 'string'],
                'description' => ['sometimes', 'string'],
                'capacity' => ['sometimes', 'integer', 'min:1'],
            ]);

            $restaurant = $this->restaurantService->create($organizationId, $data);

            return response()->json([
                'success' => true,
                'data' => $restaurant
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Update restaurant
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $organizationId, int $id): JsonResponse
    {
        try {
            $data = $request->validate([
                'name' => ['sometimes', 'string', 'max:255'],
                'phone' => ['sometimes', 'string', 'max:20'],
                'address' => ['sometimes', 'string'],
                'description' => ['sometimes', 'string'],
                'capacity' => ['sometimes', 'integer', 'min:1'],
            ]);

            $restaurant = $this->restaurantService->update($organizationId, $id, $data);

            return response()->json([
                'success' => true,
                'data' => $restaurant
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Upload restaurant images
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $id
     * @return JsonResponse
     */
    public function uploadImages(Request $request, int $organizationId, int $id): JsonResponse
    {
        try {
            $request->validate([
                'images' => ['required', 'array'],
                'images.*' => ['image', 'max:2048'],
            ]);

            $images = $this->restaurantService->uploadImages($organizationId, $id, $request->all());

            return response()->json([
                'success' => true,
                'data' => $images
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Delete restaurant image
     *
     * @param int $organizationId
     * @param int $id
     * @param int $imageId
     * @return JsonResponse
     */
    public function deleteImage(int $organizationId, int $id, int $imageId): JsonResponse
    {
        try {
            $this->restaurantService->deleteImage($organizationId, $id, $imageId);

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update restaurant status
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $id
     * @return JsonResponse
     */
    public function updateStatus(Request $request, int $organizationId, int $id): JsonResponse
    {
        try {
            $data = $request->validate([
                'status' => ['required', 'string', 'in:active,inactive,maintenance'],
            ]);

            $restaurant = $this->restaurantService->updateStatus($organizationId, $id, $data);

            return response()->json([
                'success' => true,
                'data' => $restaurant
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Get restaurant settings
     *
     * @param int $organizationId
     * @param int $id
     * @return JsonResponse
     */
    public function settings(int $organizationId, int $id): JsonResponse
    {
        try {
            $settings = $this->restaurantService->getSettings($organizationId, $id);

            return response()->json([
                'success' => true,
                'data' => $settings
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update restaurant settings
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $id
     * @return JsonResponse
     */
    public function updateSettings(Request $request, int $organizationId, int $id): JsonResponse
    {
        try {
            $data = $request->validate([
                'timezone' => ['sometimes', 'string'],
                'currency' => ['sometimes', 'string'],
                'language' => ['sometimes', 'string', 'in:ka,en'],
                'booking_enabled' => ['sometimes', 'boolean'],
                'advance_booking_days' => ['sometimes', 'integer', 'min:1'],
            ]);

            $settings = $this->restaurantService->updateSettings($organizationId, $id, $data);

            return response()->json([
                'success' => true,
                'data' => $settings
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Get restaurant reservations
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $id
     * @return JsonResponse
     */
    public function reservations(Request $request, int $organizationId, int $id): JsonResponse
    {
        try {
            $reservations = $this->restaurantService->getReservations($organizationId, $id, $request->query());

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
     * Get restaurant tables
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $id
     * @return JsonResponse
     */
    public function tables(Request $request, int $organizationId, int $id): JsonResponse
    {
        try {
            $tables = $this->restaurantService->getTables($organizationId, $id, $request->query());

            return response()->json([
                'success' => true,
                'data' => $tables
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get restaurant places
     *
     * @param int $organizationId
     * @param int $id
     * @return JsonResponse
     */
    public function places(int $organizationId, int $id): JsonResponse
    {
        try {
            $places = $this->restaurantService->getPlaces($organizationId, $id);

            return response()->json([
                'success' => true,
                'data' => $places
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get restaurant statistics
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $id
     * @return JsonResponse
     */
    public function statistics(Request $request, int $organizationId, int $id): JsonResponse
    {
        try {
            $statistics = $this->restaurantService->getStatistics($organizationId, $id, $request->query());

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
     * Get restaurant dashboard
     *
     * @param int $organizationId
     * @param int $id
     * @return JsonResponse
     */
    public function dashboard(int $organizationId, int $id): JsonResponse
    {
        try {
            $dashboard = $this->restaurantService->getDashboard($organizationId, $id);

            return response()->json([
                'success' => true,
                'data' => $dashboard
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}