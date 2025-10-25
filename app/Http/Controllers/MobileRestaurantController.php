<?php

namespace App\Http\Controllers;

use App\Services\MobileRestaurantService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MobileRestaurantController extends Controller
{
    public function __construct(
        private readonly MobileRestaurantService $restaurantService
    ) {}

    /**
     * Show restaurant management
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @return View
     */
    public function show(int $organizationId, int $restaurantId): View
    {
        try {
            $restaurant = $this->restaurantService->getMobileRestaurantData($organizationId, $restaurantId);
            
            return view('mobile.restaurants.show', [
                'restaurant' => $restaurant['success'] ? $restaurant['data'] : null,
                'organizationId' => $organizationId,
                'restaurantId' => $restaurantId,
                'error' => !$restaurant['success'] ? $restaurant['error'] : null
            ]);

        } catch (Exception $e) {
            return view('mobile.restaurants.show', [
                'restaurant' => null,
                'organizationId' => $organizationId,
                'restaurantId' => $restaurantId,
                'error' => 'Failed to load restaurant: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show edit form
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @return View
     */
    public function edit(int $organizationId, int $restaurantId): View
    {
        try {
            $restaurant = $this->restaurantService->getRestaurant($organizationId, $restaurantId);
            $cuisineTypes = $this->restaurantService->getCuisineTypes();
            $features = $this->restaurantService->getAvailableFeatures();
            
            return view('mobile.restaurants.edit', [
                'restaurant' => $restaurant['success'] ? $restaurant['data'] : null,
                'cuisineTypes' => $cuisineTypes,
                'features' => $features,
                'organizationId' => $organizationId,
                'restaurantId' => $restaurantId,
                'error' => !$restaurant['success'] ? $restaurant['error'] : null
            ]);

        } catch (Exception $e) {
            return view('mobile.restaurants.edit', [
                'restaurant' => null,
                'cuisineTypes' => [],
                'features' => [],
                'organizationId' => $organizationId,
                'restaurantId' => $restaurantId,
                'error' => 'Failed to load restaurant: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update restaurant
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $restaurantId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $organizationId, int $restaurantId)
    {
        $validation = $this->restaurantService->validateRestaurantData($request->all());
        
        if (!$validation['valid']) {
            return back()->withErrors($validation['errors'])->withInput();
        }

        try {
            $response = $this->restaurantService->updateRestaurant($organizationId, $restaurantId, $request->all());

            if ($response['success']) {
                return redirect()->route('mobile.restaurants.show', [$organizationId, $restaurantId])
                    ->with('success', 'Restaurant updated successfully');
            }

            return back()->withErrors(['error' => 'Failed to update restaurant'])->withInput();

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Update failed: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Update restaurant status
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $restaurantId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, int $organizationId, int $restaurantId)
    {
        $request->validate([
            'status' => 'required|string|in:active,inactive,closed'
        ]);

        try {
            $response = $this->restaurantService->updateStatus($organizationId, $restaurantId, $request->status);

            if ($response['success']) {
                return back()->with('success', 'Restaurant status updated successfully');
            }

            return back()->withErrors(['error' => 'Failed to update status']);

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Status update failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Upload images
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $restaurantId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadImages(Request $request, int $organizationId, int $restaurantId)
    {
        $request->validate([
            'images' => 'required|array|min:1|max:10',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_primary' => 'nullable|boolean'
        ]);

        try {
            $response = $this->restaurantService->uploadImages(
                $organizationId, 
                $restaurantId, 
                $request->file('images'),
                $request->boolean('is_primary')
            );

            if ($response['success']) {
                return back()->with('success', 'Images uploaded successfully');
            }

            return back()->withErrors(['error' => 'Failed to upload images']);

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Upload failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete image
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $imageId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteImage(int $organizationId, int $restaurantId, int $imageId)
    {
        try {
            $response = $this->restaurantService->deleteImage($organizationId, $restaurantId, $imageId);

            if ($response['success']) {
                return back()->with('success', 'Image deleted successfully');
            }

            return back()->withErrors(['error' => 'Failed to delete image']);

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Deletion failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Show settings
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @return View
     */
    public function settings(int $organizationId, int $restaurantId): View
    {
        try {
            $settings = $this->restaurantService->getSettings($organizationId, $restaurantId);
            
            return view('mobile.restaurants.settings', [
                'settings' => $settings['success'] ? $settings['data'] : null,
                'organizationId' => $organizationId,
                'restaurantId' => $restaurantId,
                'error' => !$settings['success'] ? $settings['error'] : null
            ]);

        } catch (Exception $e) {
            return view('mobile.restaurants.settings', [
                'settings' => null,
                'organizationId' => $organizationId,
                'restaurantId' => $restaurantId,
                'error' => 'Failed to load settings: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update settings
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $restaurantId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSettings(Request $request, int $organizationId, int $restaurantId)
    {
        $request->validate([
            'reservation_enabled' => 'required|boolean',
            'online_payment_required' => 'required|boolean',
            'advance_booking_days' => 'required|integer|min:1|max:365',
            'cancellation_hours' => 'required|integer|min:0|max:168',
            'min_party_size' => 'required|integer|min:1|max:50',
            'max_party_size' => 'required|integer|min:1|max:50'
        ]);

        try {
            $response = $this->restaurantService->updateSettings($organizationId, $restaurantId, $request->all());

            if ($response['success']) {
                return back()->with('success', 'Settings updated successfully');
            }

            return back()->withErrors(['error' => 'Failed to update settings']);

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Settings update failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Show places management
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @return View
     */
    public function places(int $organizationId, int $restaurantId): View
    {
        try {
            $places = $this->restaurantService->getMobilePlacesData($organizationId, $restaurantId);
            
            return view('mobile.restaurants.places', [
                'places' => $places['success'] ? $places['data'] : [],
                'organizationId' => $organizationId,
                'restaurantId' => $restaurantId,
                'error' => !$places['success'] ? $places['error'] : null
            ]);

        } catch (Exception $e) {
            return view('mobile.restaurants.places', [
                'places' => [],
                'organizationId' => $organizationId,
                'restaurantId' => $restaurantId,
                'error' => 'Failed to load places: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Create place
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $restaurantId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createPlace(Request $request, int $organizationId, int $restaurantId)
    {
        $request->validate([
            'name.ka' => 'required|string|max:255',
            'name.en' => 'required|string|max:255',
            'description.ka' => 'nullable|string|max:1000',
            'description.en' => 'nullable|string|max:1000',
            'capacity' => 'required|integer|min:1|max:500',
            'status' => 'required|string|in:active,inactive'
        ]);

        try {
            $response = $this->restaurantService->createPlace($organizationId, $restaurantId, $request->all());

            if ($response['success']) {
                return back()->with('success', 'Place created successfully');
            }

            return back()->withErrors(['error' => 'Failed to create place'])->withInput();

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Creation failed: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Update place
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $placeId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePlace(Request $request, int $organizationId, int $restaurantId, int $placeId)
    {
        $request->validate([
            'name.ka' => 'required|string|max:255',
            'name.en' => 'required|string|max:255',
            'description.ka' => 'nullable|string|max:1000',
            'description.en' => 'nullable|string|max:1000',
            'capacity' => 'required|integer|min:1|max:500',
            'status' => 'required|string|in:active,inactive'
        ]);

        try {
            $response = $this->restaurantService->updatePlace($organizationId, $restaurantId, $placeId, $request->all());

            if ($response['success']) {
                return back()->with('success', 'Place updated successfully');
            }

            return back()->withErrors(['error' => 'Failed to update place'])->withInput();

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Update failed: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Delete place
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $placeId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deletePlace(int $organizationId, int $restaurantId, int $placeId)
    {
        try {
            $response = $this->restaurantService->deletePlace($organizationId, $restaurantId, $placeId);

            if ($response['success']) {
                return back()->with('success', 'Place deleted successfully');
            }

            return back()->withErrors(['error' => 'Failed to delete place']);

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Deletion failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Show tables management
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $restaurantId
     * @return View
     */
    public function tables(Request $request, int $organizationId, int $restaurantId): View
    {
        try {
            $filters = $request->only(['place_id', 'status', 'min_capacity', 'max_capacity']);
            $tables = $this->restaurantService->getMobileTablesData($organizationId, $restaurantId, $filters);
            $places = $this->restaurantService->getPlaces($organizationId, $restaurantId);
            
            return view('mobile.restaurants.tables', [
                'tables' => $tables['success'] ? $tables['data'] : [],
                'places' => $places['success'] ? $places['data'] : [],
                'organizationId' => $organizationId,
                'restaurantId' => $restaurantId,
                'filters' => $filters,
                'error' => !$tables['success'] ? $tables['error'] : null
            ]);

        } catch (Exception $e) {
            return view('mobile.restaurants.tables', [
                'tables' => [],
                'places' => [],
                'organizationId' => $organizationId,
                'restaurantId' => $restaurantId,
                'filters' => [],
                'error' => 'Failed to load tables: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Create table
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $restaurantId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createTable(Request $request, int $organizationId, int $restaurantId)
    {
        $request->validate([
            'table_number' => 'required|string|max:50',
            'place_id' => 'required|integer|exists:places,id',
            'capacity' => 'required|integer|min:1|max:50',
            'min_capacity' => 'required|integer|min:1|max:50',
            'max_capacity' => 'required|integer|min:1|max:50',
            'status' => 'required|string|in:active,inactive,maintenance',
            'location.x' => 'nullable|integer|min:0',
            'location.y' => 'nullable|integer|min:0'
        ]);

        try {
            $response = $this->restaurantService->createTable($organizationId, $restaurantId, $request->all());

            if ($response['success']) {
                return back()->with('success', 'Table created successfully');
            }

            return back()->withErrors(['error' => 'Failed to create table'])->withInput();

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Creation failed: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Update table
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $tableId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateTable(Request $request, int $organizationId, int $restaurantId, int $tableId)
    {
        $request->validate([
            'table_number' => 'required|string|max:50',
            'place_id' => 'required|integer|exists:places,id',
            'capacity' => 'required|integer|min:1|max:50',
            'min_capacity' => 'required|integer|min:1|max:50',
            'max_capacity' => 'required|integer|min:1|max:50',
            'status' => 'required|string|in:active,inactive,maintenance',
            'location.x' => 'nullable|integer|min:0',
            'location.y' => 'nullable|integer|min:0'
        ]);

        try {
            $response = $this->restaurantService->updateTable($organizationId, $restaurantId, $tableId, $request->all());

            if ($response['success']) {
                return back()->with('success', 'Table updated successfully');
            }

            return back()->withErrors(['error' => 'Failed to update table'])->withInput();

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Update failed: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Delete table
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $tableId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteTable(int $organizationId, int $restaurantId, int $tableId)
    {
        try {
            $response = $this->restaurantService->deleteTable($organizationId, $restaurantId, $tableId);

            if ($response['success']) {
                return back()->with('success', 'Table deleted successfully');
            }

            return back()->withErrors(['error' => 'Failed to delete table']);

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Deletion failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Get restaurant data (AJAX endpoint)
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRestaurantData(int $organizationId, int $restaurantId)
    {
        try {
            $restaurant = $this->restaurantService->getMobileRestaurantData($organizationId, $restaurantId);
            
            return response()->json($restaurant);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update table status (AJAX endpoint)
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $tableId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTableStatus(Request $request, int $organizationId, int $restaurantId, int $tableId)
    {
        $request->validate([
            'status' => 'required|string|in:active,inactive,maintenance'
        ]);

        try {
            $response = $this->restaurantService->updateTableStatus($organizationId, $restaurantId, $tableId, $request->status);
            
            return response()->json($response);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check table availability (AJAX endpoint)
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $tableId
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkTableAvailability(Request $request, int $organizationId, int $restaurantId, int $tableId)
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required|string',
            'duration' => 'nullable|integer|min:1|max:8'
        ]);

        try {
            $response = $this->restaurantService->checkTableAvailability(
                $organizationId,
                $restaurantId,
                $tableId,
                $request->only(['date', 'time', 'duration'])
            );
            
            return response()->json($response);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}