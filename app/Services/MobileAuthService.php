<?php

namespace App\Services;

use Exception;

class MobileAuthService
{
    public function __construct(
        private readonly HttpClient $client
    ) {}

    /**
     * Authenticate user and get token
     *
     * @param array $credentials
     * @return array
     * @throws Exception
     */
    public function login(array $credentials): array
    {
        $response = $this->client->post('/api/partner/login', $credentials);
        
        if ($response['success'] ?? false) {
            // Store authentication data in session (file-based, no database)
            if (isset($response['data']['access_token'])) {
                session(['partner_token' => $response['data']['access_token']]);
            }
            if (isset($response['data']['user'])) {
                session(['partner_user' => $response['data']['user']]);
            }
            
            // Store login timestamp for session management
            session(['partner_login_at' => now()->timestamp]);
            
            // Extend session lifetime for mobile users (8 hours)
            config(['session.lifetime' => 480]);
        }
        
        return $response;
    }

    /**
     * Get current authenticated user
     *
     * @return array
     * @throws Exception
     */
    public function me(): array
    {
        return $this->client->get('/api/partner/me');
    }

    /**
     * Register new partner for mobile
     *
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function register(array $data): array
    {
        $response = $this->client->post('/api/partner/register', $data);
        
        if ($response['success'] ?? false) {
            // Store token if registration includes auto-login
            if (isset($response['data']['access_token'])) {
                session(['partner_token' => $response['data']['access_token']]);
            }
            if (isset($response['data']['user'])) {
                session(['partner_user' => $response['data']['user']]);
            }
        }
        
        return $response;
    }

    /**
     * Check if user is authenticated
     *
     * @return bool
     */
    public function check(): bool
    {
        return session()->has('partner_token') && !empty(session('partner_token'));
    }

    /**
     * Get initial dashboard data after login
     *
     * @return array
     * @throws Exception
     */
    public function initialDashboard(): array
    {
        return $this->client->get('/api/partner/initial-dashboard');
    }

    /**
     * Update user profile for mobile
     *
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function updateProfile(array $data): array
    {
        return $this->client->put('/api/partner/profile', $data);
    }

    /**
     * Upload user avatar
     *
     * @param $file
     * @return array
     * @throws Exception
     */
    public function uploadAvatar($file): array
    {
        return $this->client->post('/api/partner/profile/avatar', [
            'avatar' => $file
        ]);
    }

    /**
     * Delete user avatar
     *
     * @return array
     * @throws Exception
     */
    public function deleteAvatar(): array
    {
        return $this->client->delete('/api/partner/profile/avatar');
    }

    /**
     * Change password
     *
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function changePassword(array $data): array
    {
        return $this->client->put('/api/partner/profile/password', $data);
    }

    /**
     * Logout user
     *
     * @return array
     * @throws Exception
     */
    public function logout(): array
    {
        try {
            $response = $this->client->post('/api/partner/logout');
            
            // Clear all session data (file-based storage)
            session()->forget([
                'partner_token', 
                'partner_user', 
                'partner_login_at',
                'selected_organization',
                'mobile_user',
                'mobile_data'
            ]);
            
            return $response;
        } catch (Exception $e) {
            // Clear session even if API call fails (no database dependency)
            session()->flush(); // Clear entire session
            throw $e;
        }
    }



    /**
     * Get current user from session
     *
     * @return array|null
     */
    public function user(): ?array
    {
        return session('partner_user');
    }

    /**
     * Get current token from session
     *
     * @return string|null
     */
    public function token(): ?string
    {
        return session('partner_token');
    }
}