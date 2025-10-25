<?php

namespace App\Services;

class TokenService
{
    /**
     * Get the current authentication token from session
     *
     * @return string|null
     */
    public function getToken(): ?string
    {
        return session('partner_token');
    }

    /**
     * Set the authentication token in session
     *
     * @param string $token
     * @return void
     */
    public function setToken(string $token): void
    {
        session(['partner_token' => $token]);
    }

    /**
     * Clear the authentication token from session
     *
     * @return void
     */
    public function clearToken(): void
    {
        session()->forget('partner_token');
    }

    /**
     * Check if a token exists
     *
     * @return bool
     */
    public function hasToken(): bool
    {
        return session()->has('partner_token') && !empty(session('partner_token'));
    }
}