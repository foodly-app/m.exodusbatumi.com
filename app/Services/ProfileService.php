<?php

namespace App\Services;

use Exception;

class ProfileService
{
    public function __construct(
        private readonly HttpClient $client
    ) {}

    /**
     * Get current user profile
     *
     * @return array
     * @throws Exception
     */
    public function getProfile(): array
    {
        return $this->client->get('/api/partner/me');
    }

    /**
     * Update user profile
     *
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function updateProfile(array $data): array
    {
        $response = $this->client->put('/api/partner/profile', $data);
        
        // Update session user data if successful
        if (isset($response['user'])) {
            session(['partner_user' => $response['user']]);
        }
        
        return $response;
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
        $response = $this->client->post('/api/partner/profile/avatar', [
            'avatar' => $file
        ]);
        
        // Update session user data if successful
        if (isset($response['user'])) {
            session(['partner_user' => $response['user']]);
        }
        
        return $response;
    }

    /**
     * Delete user avatar
     *
     * @return array
     * @throws Exception
     */
    public function deleteAvatar(): array
    {
        $response = $this->client->delete('/api/partner/profile/avatar');
        
        // Update session user data if successful
        if (isset($response['user'])) {
            session(['partner_user' => $response['user']]);
        }
        
        return $response;
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
     * Get user from session
     *
     * @return array|null
     */
    public function getSessionUser(): ?array
    {
        return session('partner_user');
    }
}
