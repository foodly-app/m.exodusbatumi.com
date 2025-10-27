<?php

namespace App\Http\Controllers;

use App\Services\ProfileService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function __construct(
        private readonly ProfileService $profileService
    ) {}

    /**
     * Show profile page
     *
     * @return View
     */
    public function index(): View
    {
        try {
            // Try to get fresh data from API
            $profileData = $this->profileService->getProfile();
            
            if (isset($profileData['user'])) {
                $user = $profileData['user'];
            } else {
                // Fallback to session data
                $user = $this->profileService->getSessionUser();
            }

            return view('mobile.profile.index', [
                'user' => $user,
                'error' => null
            ]);

        } catch (Exception $e) {
            Log::error('Profile load failed', [
                'error' => $e->getMessage()
            ]);

            // Use session data as fallback
            return view('mobile.profile.index', [
                'user' => $this->profileService->getSessionUser(),
                'error' => 'Could not load profile data from server'
            ]);
        }
    }

    /**
     * Update profile
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255'
        ]);

        try {
            $response = $this->profileService->updateProfile($request->only([
                'name',
                'phone',
                'email'
            ]));

            if (isset($response['user']) || isset($response['success'])) {
                return back()->with('success', 'პროფილი წარმატებით განახლდა!');
            }

            return back()->withErrors(['error' => $response['message'] ?? 'განახლება ვერ მოხერხდა']);

        } catch (Exception $e) {
            Log::error('Profile update failed', [
                'error' => $e->getMessage(),
                'data' => $request->only(['name', 'phone', 'email'])
            ]);

            return back()->withErrors(['error' => 'განახლება ვერ მოხერხდა: ' . $e->getMessage()]);
        }
    }

    /**
     * Upload avatar
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function uploadAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $response = $this->profileService->uploadAvatar($request->file('avatar'));

            if (isset($response['user']) || isset($response['success'])) {
                return back()->with('success', 'ავატარი წარმატებით აიტვირთა!');
            }

            return back()->withErrors(['error' => $response['message'] ?? 'ატვირთვა ვერ მოხერხდა']);

        } catch (Exception $e) {
            Log::error('Avatar upload failed', [
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => 'ატვირთვა ვერ მოხერხდა: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete avatar
     *
     * @return RedirectResponse
     */
    public function deleteAvatar(): RedirectResponse
    {
        try {
            $response = $this->profileService->deleteAvatar();

            if (isset($response['success'])) {
                return back()->with('success', 'ავატარი წარმატებით წაიშალა!');
            }

            return back()->withErrors(['error' => $response['message'] ?? 'წაშლა ვერ მოხერხდა']);

        } catch (Exception $e) {
            Log::error('Avatar delete failed', [
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => 'წაშლა ვერ მოხერხდა: ' . $e->getMessage()]);
        }
    }

    /**
     * Change password
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function changePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $response = $this->profileService->changePassword($request->only([
                'current_password',
                'password',
                'password_confirmation'
            ]));

            if (isset($response['success'])) {
                return back()->with('success', 'პაროლი წარმატებით შეიცვალა!');
            }

            return back()->withErrors(['error' => $response['message'] ?? 'პაროლის შეცვლა ვერ მოხერხდა']);

        } catch (Exception $e) {
            Log::error('Password change failed', [
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => 'პაროლის შეცვლა ვერ მოხერხდა: ' . $e->getMessage()]);
        }
    }
}
