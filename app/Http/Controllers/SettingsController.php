<?php

namespace App\Http\Controllers;

use App\Services\RestaurantSettingsService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    public function __construct(
        private readonly RestaurantSettingsService $settingsService
    ) {}

    /**
     * Show settings index page
     *
     * @return View
     */
    public function index(): View
    {
        return view('mobile.settings.index');
    }

    /**
     * Show restaurant settings
     *
     * @return View
     */
    public function restaurant(): View
    {
        try {
            $restaurant = $this->settingsService->getSelectedRestaurant();

            if (!$restaurant) {
                return view('mobile.settings.restaurant', [
                    'restaurant' => null,
                    'settings' => null,
                    'error' => 'რესტორანი არ არის არჩეული'
                ]);
            }

            $restaurantId = $restaurant['id'];
            $settings = $this->settingsService->getSettings($restaurantId);

            return view('mobile.settings.restaurant', [
                'restaurant' => $restaurant,
                'settings' => $settings['data'] ?? null,
                'error' => null
            ]);

        } catch (Exception $e) {
            Log::error('Restaurant settings load failed', [
                'error' => $e->getMessage()
            ]);

            return view('mobile.settings.restaurant', [
                'restaurant' => $this->settingsService->getSelectedRestaurant(),
                'settings' => null,
                'error' => 'პარამეტრების ჩატვირთვა ვერ მოხერხდა'
            ]);
        }
    }

    /**
     * Show working hours settings
     *
     * @return View
     */
    public function workingHours(): View
    {
        try {
            $restaurant = $this->settingsService->getSelectedRestaurant();

            if (!$restaurant) {
                return view('mobile.settings.working-hours', [
                    'restaurant' => null,
                    'workingHours' => null,
                    'error' => 'რესტორანი არ არის არჩეული'
                ]);
            }

            $restaurantId = $restaurant['id'];
            $workingHours = $this->settingsService->getWorkingHours($restaurantId);

            return view('mobile.settings.working-hours', [
                'restaurant' => $restaurant,
                'workingHours' => $workingHours['data'] ?? null,
                'error' => null
            ]);

        } catch (Exception $e) {
            Log::error('Working hours load failed', [
                'error' => $e->getMessage()
            ]);

            return view('mobile.settings.working-hours', [
                'restaurant' => $this->settingsService->getSelectedRestaurant(),
                'workingHours' => null,
                'error' => 'სამუშაო საათების ჩატვირთვა ვერ მოხერხდა'
            ]);
        }
    }

    /**
     * Update restaurant settings
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateSettings(Request $request): RedirectResponse
    {
        try {
            $restaurant = $this->settingsService->getSelectedRestaurant();

            if (!$restaurant) {
                return back()->withErrors(['error' => 'რესტორანი არ არის არჩეული']);
            }

            $restaurantId = $restaurant['id'];
            $response = $this->settingsService->updateSettings($restaurantId, $request->all());

            if (isset($response['success'])) {
                return back()->with('success', 'პარამეტრები წარმატებით განახლდა!');
            }

            return back()->withErrors(['error' => $response['message'] ?? 'განახლება ვერ მოხერხდა']);

        } catch (Exception $e) {
            Log::error('Settings update failed', [
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => 'განახლება ვერ მოხერხდა: ' . $e->getMessage()]);
        }
    }

    /**
     * Update working hours
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateWorkingHours(Request $request): RedirectResponse
    {
        $request->validate([
            'working_hours' => 'required|array',
            'working_hours.*.day' => 'required|string',
            'working_hours.*.is_open' => 'required|boolean',
            'working_hours.*.open_time' => 'nullable|string',
            'working_hours.*.close_time' => 'nullable|string',
        ]);

        try {
            $restaurant = $this->settingsService->getSelectedRestaurant();

            if (!$restaurant) {
                return back()->withErrors(['error' => 'რესტორანი არ არის არჩეული']);
            }

            $restaurantId = $restaurant['id'];
            $response = $this->settingsService->updateWorkingHours($restaurantId, $request->only('working_hours'));

            if (isset($response['success'])) {
                return back()->with('success', 'სამუშაო საათები წარმატებით განახლდა!');
            }

            return back()->withErrors(['error' => $response['message'] ?? 'განახლება ვერ მოხერხდა']);

        } catch (Exception $e) {
            Log::error('Working hours update failed', [
                'error' => $e->getMessage(),
                'data' => $request->only('working_hours')
            ]);

            return back()->withErrors(['error' => 'განახლება ვერ მოხერხდა: ' . $e->getMessage()]);
        }
    }
}
