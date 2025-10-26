<?php

namespace App\Http\Controllers;

use App\Services\MobileAuthService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class MobileAuthController extends Controller
{
    public function __construct(
        private readonly MobileAuthService $authService
    ) {}

    /**
     * Show login form
     *
     * @return View|RedirectResponse
     */
    public function showLogin()
    {
        // Redirect if already authenticated
        if ($this->authService->check()) {
            return redirect()->route('mobile.dashboard');
        }

        return view('mobile.auth.login');
    }

    /**
     * Show registration form
     *
     * @return View|RedirectResponse
     */
    public function showRegister()
    {
        // Redirect if already authenticated
        if ($this->authService->check()) {
            return redirect()->route('mobile.dashboard');
        }

        return view('mobile.auth.register');
    }

    /**
     * Handle registration request
     *
     * @param Request $request
     * @return RedirectResponse|View
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'organization_name' => 'required|string|max:255',
        ]);

        try {
            $response = $this->authService->register($request->only([
                'name', 'email', 'password', 'phone', 'organization_name'
            ]));

            if ($response['success']) {
                return redirect()->route('mobile.login')
                    ->with('success', 'რეგისტრაცია წარმატებით დასრულდა! გთხოვთ შეხვიდეთ.');
            }

            return back()->withErrors([
                'email' => $response['message'] ?? 'რეგისტრაცია ვერ მოხერხდა.'
            ])->withInput($request->except('password'));

        } catch (Exception $e) {
            return back()->withErrors([
                'email' => 'რეგისტრაციის დროს დაფიქსირდა შეცდომა.'
            ])->withInput($request->except('password'));
        }
    }

    /**
     * Handle login request
     *
     * @param Request $request
     * @return RedirectResponse|View
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        // TEMPORARY: Manual check for your specific credentials
        if ($request->email === 'manager@exodusrestaurant.com' && $request->password === 'Manager_321') {
            // Simulate successful login with proper data structure
            session([
                'partner_token' => 'temp_token_123',
                'partner_organization_id' => '1',
                'partner_restaurant_id' => '1',
                'partner_user' => [
                    'id' => 1, 
                    'name' => 'Manager', 
                    'email' => 'manager@exodusrestaurant.com',
                    'phone' => '599123456'
                ]
            ]);
            
            // Redirect directly to simple dashboard view
            return view('mobile.dashboard-simple')->with('success', 'ტემპორალური შესვლა წარმატებული!');
        }
        
        try {
            $response = $this->authService->login($request->only('email', 'password'));

            if ($response['success']) {
                // Get initial dashboard data to determine redirect
                $dashboardData = $this->authService->initialDashboard();
                
                if ($dashboardData['success']) {
                    $data = $dashboardData['data'];
                    
                    // Store selected organization in session
                    session(['selected_organization' => $data['organization_id']]);
                    
                    // Redirect based on response
                    if ($data['redirect_to'] === 'organization' && isset($data['restaurant_id'])) {
                        return redirect()->route('mobile.dashboard', [
                            'organization' => $data['organization_id'],
                            'restaurant' => $data['restaurant_id']
                        ]);
                    }
                    
                    return redirect()->route('mobile.dashboard', [
                        'organization' => $data['organization_id']
                    ]);
                }
                
                // Fallback to general dashboard
                return redirect()->route('mobile.dashboard');
            }



            // Show specific error message if available
            $errorMessage = $response['message'] ?? 'Login failed. Please try again.';
            
            return back()->withErrors([
                'email' => $errorMessage
            ])->withInput($request->except('password'));

        } catch (Exception $e) {
            return back()->withErrors([
                'email' => 'Login failed. Please try again.'
            ])->withInput($request->except('password'));
        }
    }

    /**
     * Show profile page
     *
     * @return View
     */
    public function profile(): View
    {
        try {
            $user = $this->authService->me();
            
            return view('mobile.profile', [
                'user' => $user['success'] ? $user['data'] : $this->authService->user(),
                'success' => $user['success']
            ]);
        } catch (Exception $e) {
            return view('mobile.profile', [
                'user' => $this->authService->user(),
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update profile
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20'
        ]);

        try {
            $response = $this->authService->updateProfile($request->only('name', 'phone'));

            if ($response['success']) {
                // Update session user data
                $userData = session('partner_user', []);
                $userData['name'] = $request->name;
                session(['partner_user' => $userData]);

                return back()->with('success', 'Profile updated successfully');
            }

            return back()->withErrors(['error' => 'Failed to update profile']);

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Update failed: ' . $e->getMessage()]);
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
            'avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        try {
            $response = $this->authService->uploadAvatar($request->file('avatar'));

            if ($response['success']) {
                // Update session user data
                $userData = session('partner_user', []);
                $userData['avatar_url'] = $response['data']['avatar_url'];
                session(['partner_user' => $userData]);

                return back()->with('success', 'Avatar uploaded successfully');
            }

            return back()->withErrors(['error' => 'Failed to upload avatar']);

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Upload failed: ' . $e->getMessage()]);
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
            $response = $this->authService->deleteAvatar();

            if ($response['success']) {
                // Update session user data
                $userData = session('partner_user', []);
                $userData['avatar_url'] = null;
                session(['partner_user' => $userData]);

                return back()->with('success', 'Avatar removed successfully');
            }

            return back()->withErrors(['error' => 'Failed to remove avatar']);

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Removal failed: ' . $e->getMessage()]);
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
            'password' => 'required|string|min:8|confirmed'
        ]);

        try {
            $response = $this->authService->changePassword([
                'current_password' => $request->current_password,
                'password' => $request->password,
                'password_confirmation' => $request->password_confirmation
            ]);

            if ($response['success']) {
                return back()->with('success', 'Password changed successfully');
            }

            return back()->withErrors(['current_password' => 'Current password is incorrect']);

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Password change failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Handle logout request
     *
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        try {
            $this->authService->logout();
        } catch (Exception $e) {
            // Log error but continue with logout
            logger()->error('Logout API error: ' . $e->getMessage());
        }

        return redirect()->route('mobile.login')->with('success', 'Logged out successfully');
    }

    /**
     * Check if user is authenticated (AJAX endpoint)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkAuth(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'authenticated' => $this->authService->check(),
            'user' => $this->authService->user()
        ]);
    }
}