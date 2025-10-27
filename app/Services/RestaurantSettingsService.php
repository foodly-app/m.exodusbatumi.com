<?php

namespace App\Services;

use Exception;

class RestaurantSettingsService
{
    public function __construct(
        private readonly HttpClient $client
    ) {}

    /**
     * Get restaurant settings
     *
     * @param int $restaurantId
     * @return array
     * @throws Exception
     */
    public function getSettings(int $restaurantId): array
    {
        return $this->client->get("/api/partner/restaurants/{$restaurantId}/settings");
    }

    /**
     * Update restaurant settings
     *
     * @param int $restaurantId
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function updateSettings(int $restaurantId, array $data): array
    {
        return $this->client->put("/api/partner/restaurants/{$restaurantId}/settings", $data);
    }

    /**
     * Get restaurant working hours
     *
     * @param int $restaurantId
     * @return array
     * @throws Exception
     */
    public function getWorkingHours(int $restaurantId): array
    {
        return $this->client->get("/api/partner/restaurants/{$restaurantId}/working-hours");
    }

    /**
     * Update restaurant working hours
     *
     * @param int $restaurantId
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function updateWorkingHours(int $restaurantId, array $data): array
    {
        return $this->client->put("/api/partner/restaurants/{$restaurantId}/working-hours", $data);
    }

    /**
     * Get selected restaurant from session
     *
     * @return array|null
     */
    public function getSelectedRestaurant(): ?array
    {
        return session('selected_restaurant');
    }
}
