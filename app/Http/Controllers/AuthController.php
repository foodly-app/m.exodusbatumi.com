<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {}

    /**
     * Login user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required', 'string']
            ]);

            Log::info('Login attempt:', $credentials);

            $result = $this->authService->login($credentials);

            Log::info('Login result:', $result);

            // Store token in session for subsequent requests
            if (isset($result['token'])) {
                session(['partner_token' => $result['token']]);
                session(['partner_user' => $result['user']]);
            }

            return response()->json([
                'success' => true,
                'token' => $result['token'] ?? null,
                'user' => $result['user'] ?? null
            ]);
        } catch (\Exception $e) {
            Log::error('Login failed:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 401);
        }
    }

    /**
     * Get authenticated user info
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        try {
            $user = $this->authService->me();

            return response()->json([
                'success' => true,
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 401);
        }
    }

    /**
     * Logout user
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            // Only call API logout if we have a session token
            if (session('partner_token')) {
                $this->authService->logout();
            }

            // Clear session data
            session()->forget(['partner_token', 'partner_user']);
            session()->flush();

            return response()->json([
                'success' => true,
                'message' => 'Successfully logged out'
            ]);
        } catch (\Exception $e) {
            // Even if API logout fails, clear local session
            session()->forget(['partner_token', 'partner_user']);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get initial dashboard data
     *
     * @return JsonResponse
     */
    public function initialDashboard(): JsonResponse
    {
        try {
            $dashboard = $this->authService->initialDashboard();

            // Return the API response directly (it already has success and data structure)
            return response()->json($dashboard);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user profile
     *
     * @return JsonResponse
     */
    public function getProfile(): JsonResponse
    {
        try {
            $profile = $this->authService->getProfile();

            // Return the API response directly (it already has success and user structure)
            return response()->json($profile);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user profile
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateProfile(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'name' => ['sometimes', 'string', 'max:255'],
                'email' => ['sometimes', 'email', 'max:255'],
                'phone' => ['nullable', 'string', 'max:20'],
                'language' => ['sometimes', 'string', 'in:ka,en'],
            ]);

            // Remove empty phone field if it's empty string
            if (isset($data['phone']) && $data['phone'] === '') {
                $data['phone'] = null;
            }

            $profile = $this->authService->updateProfile($data);

            // Return the API response directly
            return response()->json($profile);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Upload user avatar
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadAvatar(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'avatar' => ['required', 'image', 'max:2048']
            ]);

            $avatar = $this->authService->uploadAvatar($request->file('avatar'));

            // Return the API response directly
            return response()->json($avatar);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Delete user avatar
     *
     * @return JsonResponse
     */
    public function deleteAvatar(): JsonResponse
    {
        try {
            $result = $this->authService->deleteAvatar();

            // Return the API response directly
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Change user password
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function changePassword(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'current_password' => ['required', 'string'],
                'new_password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            $result = $this->authService->changePassword($data);

            // Return the API response directly
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}