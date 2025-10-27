<?php

namespace App\Services;

use Exception;

class RestaurantService
{
    public function __construct(
        private readonly HttpClient $client
    ) {}

    /**
     * Get all user's restaurants (from all organizations)
     *
     * @return array
     * @throws Exception
     */
    public function getAllRestaurants(): array
    {
        return $this->client->get('/api/partner/restaurants');
    }

    /**
     * Get organization restaurants
     *
     * @param int $organizationId
     * @return array
     * @throws Exception
     */
    public function getRestaurants(int $organizationId): array
    {
        return $this->client->get("/api/partner/organizations/{$organizationId}/restaurants");
    }

    /**
     * Get restaurant details
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @return array
     * @throws Exception
     */
    public function getRestaurant(int $organizationId, int $restaurantId): array
    {
        return $this->client->get("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}");
    }

    /**
     * Create new restaurant
     *
     * @param int $organizationId
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function createRestaurant(int $organizationId, array $data): array
    {
        return $this->client->post("/api/partner/organizations/{$organizationId}/restaurants", $data);
    }

    /**
     * Update restaurant
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function updateRestaurant(int $organizationId, int $restaurantId, array $data): array
    {
        return $this->client->put("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}", $data);
    }

    /**
     * Update restaurant status
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param string $status
     * @return array
     * @throws Exception
     */
    public function updateStatus(int $organizationId, int $restaurantId, string $status): array
    {
        return $this->client->put("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}/status", [
            'status' => $status
        ]);
    }

    /**
     * Upload restaurant images
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param array $files
     * @param bool $isPrimary
     * @return array
     * @throws Exception
     */


    /**
     * Delete restaurant image
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $imageId
     * @return array
     * @throws Exception
     */
    public function deleteImage(int $organizationId, int $restaurantId, int $imageId): array
    {
        return $this->client->delete("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}/images/{$imageId}");
    }

    /**
     * Get restaurant settings
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @return array
     * @throws Exception
     */
    public function getSettings(int $organizationId, int $restaurantId): array
    {
        return $this->client->get("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}/settings");
    }

    /**
     * Update restaurant settings
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param array $settings
     * @return array
     * @throws Exception
     */
    public function updateSettings(int $organizationId, int $restaurantId, array $settings): array
    {
        return $this->client->put("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}/settings", $settings);
    }

    /**
     * Get restaurant places
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @return array
     * @throws Exception
     */
    public function getPlaces(int $organizationId, int $restaurantId): array
    {
        return $this->client->get("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}/places");
    }

    /**
     * Get place details
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $placeId
     * @return array
     * @throws Exception
     */
    public function getPlace(int $organizationId, int $restaurantId, int $placeId): array
    {
        return $this->client->get("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}/places/{$placeId}");
    }

    /**
     * Create new place
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function createPlace(int $organizationId, int $restaurantId, array $data): array
    {
        return $this->client->post("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}/places", $data);
    }

    /**
     * Update place
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $placeId
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function updatePlace(int $organizationId, int $restaurantId, int $placeId, array $data): array
    {
        return $this->client->put("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}/places/{$placeId}", $data);
    }

    /**
     * Delete place
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $placeId
     * @return array
     * @throws Exception
     */
    public function deletePlace(int $organizationId, int $restaurantId, int $placeId): array
    {
        return $this->client->delete("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}/places/{$placeId}");
    }

    /**
     * Update place status
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $placeId
     * @param string $status
     * @return array
     * @throws Exception
     */
    public function updatePlaceStatus(int $organizationId, int $restaurantId, int $placeId, string $status): array
    {
        return $this->client->put("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}/places/{$placeId}/status", [
            'status' => $status
        ]);
    }

    /**
     * Get restaurant tables
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param array $filters
     * @return array
     * @throws Exception
     */
    public function getTables(int $organizationId, int $restaurantId, array $filters = []): array
    {
        return $this->client->get("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}/tables", $filters);
    }

    /**
     * Get table details
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $tableId
     * @return array
     * @throws Exception
     */
    public function getTable(int $organizationId, int $restaurantId, int $tableId): array
    {
        return $this->client->get("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}/tables/{$tableId}");
    }

    /**
     * Create new table
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function createTable(int $organizationId, int $restaurantId, array $data): array
    {
        return $this->client->post("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}/tables", $data);
    }

    /**
     * Update table
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $tableId
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function updateTable(int $organizationId, int $restaurantId, int $tableId, array $data): array
    {
        return $this->client->put("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}/tables/{$tableId}", $data);
    }

    /**
     * Delete table
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $tableId
     * @return array
     * @throws Exception
     */
    public function deleteTable(int $organizationId, int $restaurantId, int $tableId): array
    {
        return $this->client->delete("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}/tables/{$tableId}");
    }

    /**
     * Update table status
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $tableId
     * @param string $status
     * @return array
     * @throws Exception
     */
    public function updateTableStatus(int $organizationId, int $restaurantId, int $tableId, string $status): array
    {
        return $this->client->put("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}/tables/{$tableId}/status", [
            'status' => $status
        ]);
    }

    /**
     * Check table availability
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $tableId
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function checkTableAvailability(int $organizationId, int $restaurantId, int $tableId, array $params): array
    {
        return $this->client->get("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}/tables/{$tableId}/availability", $params);
    }

    /**
     * Get table reservations
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $tableId
     * @param array $filters
     * @return array
     * @throws Exception
     */
    public function getTableReservations(int $organizationId, int $restaurantId, int $tableId, array $filters = []): array
    {
        return $this->client->get("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}/tables/{$tableId}/reservations", $filters);
    }

    /**
     * Get mobile-optimized restaurant data
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @return array
     */
    public function getMobileRestaurantData(int $organizationId, int $restaurantId): array
    {
        try {
            $restaurant = $this->getRestaurant($organizationId, $restaurantId);
            
            if (!$restaurant['success']) {
                return $restaurant;
            }

            $data = $restaurant['data'];

            return [
                'success' => true,
                'data' => [
                    'id' => $data['id'],
                    'name' => $data['name'],
                    'status' => $data['status'],
                    'status_badge' => $this->getStatusBadgeClass($data['status']),
                    'address' => $data['address'],
                    'phone' => $data['phone'],
                    'email' => $data['email'] ?? '',
                    'website' => $data['website'] ?? '',
                    'description' => $data['description'] ?? ['ka' => '', 'en' => ''],
                    'images' => collect($data['images'] ?? [])->map(function ($image) {
                        return [
                            'id' => $image['id'],
                            'url' => $image['url'],
                            'is_primary' => $image['is_primary'] ?? false,
                            'thumbnail' => $this->generateThumbnailUrl($image['url'])
                        ];
                    })->toArray(),
                    'primary_image' => collect($data['images'] ?? [])->firstWhere('is_primary', true)['url'] ?? null,
                    'cuisine_types' => $data['cuisine_types'] ?? [],
                    'features' => $data['features'] ?? [],
                    'working_hours' => $data['working_hours'] ?? [],
                    'location' => $data['location'] ?? null,
                    'rating' => $data['rating'] ?? 0,
                    'reviews_count' => $data['reviews_count'] ?? 0,
                    'places_count' => $data['places_count'] ?? 0,
                    'tables_count' => $data['tables_count'] ?? 0,
                    'formatted_rating' => number_format($data['rating'] ?? 0, 1),
                    'can_edit' => true,
                    'can_delete' => $data['status'] !== 'active'
                ]
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get mobile places management data
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @return array
     */
    public function getMobilePlacesData(int $organizationId, int $restaurantId): array
    {
        try {
            $places = $this->getPlaces($organizationId, $restaurantId);
            
            if (!$places['success']) {
                return $places;
            }

            return [
                'success' => true,
                'data' => collect($places['data'])->map(function ($place) {
                    return [
                        'id' => $place['id'],
                        'name' => $place['name'],
                        'description' => $place['description'] ?? ['ka' => '', 'en' => ''],
                        'capacity' => $place['capacity'],
                        'status' => $place['status'],
                        'status_badge' => $this->getStatusBadgeClass($place['status']),
                        'tables_count' => $place['tables_count'] ?? 0,
                        'available_tables' => $place['available_tables'] ?? 0,
                        'occupancy_rate' => $this->calculateOccupancyRate($place['tables_count'] ?? 0, $place['available_tables'] ?? 0),
                        'can_edit' => true,
                        'can_delete' => ($place['tables_count'] ?? 0) === 0
                    ];
                })->toArray()
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get mobile tables management data
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param array $filters
     * @return array
     */
    public function getMobileTablesData(int $organizationId, int $restaurantId, array $filters = []): array
    {
        try {
            $tables = $this->getTables($organizationId, $restaurantId, $filters);
            
            if (!$tables['success']) {
                return $tables;
            }

            return [
                'success' => true,
                'data' => collect($tables['data'])->map(function ($table) {
                    return [
                        'id' => $table['id'],
                        'table_number' => $table['table_number'],
                        'place_id' => $table['place_id'],
                        'place_name' => $table['place_name'],
                        'capacity' => $table['capacity'],
                        'min_capacity' => $table['min_capacity'],
                        'max_capacity' => $table['max_capacity'],
                        'status' => $table['status'],
                        'status_badge' => $this->getTableStatusBadgeClass($table['status']),
                        'is_available' => $table['is_available'] ?? true,
                        'location' => $table['location'] ?? null,
                        'capacity_range' => $table['min_capacity'] . '-' . $table['max_capacity'] . ' guests',
                        'can_edit' => true,
                        'can_delete' => !($table['is_available'] ?? false)
                    ];
                })->toArray()
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get available cuisine types
     *
     * @return array
     */
    public function getCuisineTypes(): array
    {
        return [
            'Georgian' => 'ქართული',
            'European' => 'ევროპული',
            'Asian' => 'აზიური',
            'Italian' => 'იტალიური',
            'French' => 'ფრანგული',
            'Mediterranean' => 'ხმელთაშუაზღვისპირეთის',
            'American' => 'ამერიკული',
            'Mexican' => 'მექსიკური',
            'Japanese' => 'იაპონური',
            'Chinese' => 'ჩინური',
            'Indian' => 'ინდური',
            'Thai' => 'ტაილანდური'
        ];
    }

    /**
     * Get available features
     *
     * @return array
     */
    public function getAvailableFeatures(): array
    {
        return [
            'wifi' => 'Wi-Fi',
            'parking' => 'პარკინგი',
            'outdoor_seating' => 'ღია ტერასა',
            'live_music' => 'ცოცხალი მუსიკა',
            'private_dining' => 'პრივატული დარბაზი',
            'bar' => 'ბარი',
            'delivery' => 'მიტანა',
            'takeaway' => 'გატანა',
            'wheelchair_accessible' => 'ხელმისაწვდომი',
            'pet_friendly' => 'შინაურ ცხოველებთან შესაძლებელი',
            'air_conditioning' => 'კონდიციონერი',
            'heating' => 'გათბობა'
        ];
    }

    /**
     * Calculate occupancy rate
     *
     * @param int $totalTables
     * @param int $availableTables
     * @return float
     */
    private function calculateOccupancyRate(int $totalTables, int $availableTables): float
    {
        if ($totalTables === 0) {
            return 0;
        }

        $occupiedTables = $totalTables - $availableTables;
        return round(($occupiedTables / $totalTables) * 100, 1);
    }

    /**
     * Get status badge CSS class
     *
     * @param string $status
     * @return string
     */
    private function getStatusBadgeClass(string $status): string
    {
        return match ($status) {
            'active' => 'badge bg-success',
            'inactive' => 'badge bg-warning',
            'closed' => 'badge bg-danger',
            default => 'badge bg-secondary'
        };
    }

    /**
     * Get table status badge CSS class
     *
     * @param string $status
     * @return string
     */
    private function getTableStatusBadgeClass(string $status): string
    {
        return match ($status) {
            'active' => 'badge bg-success',
            'inactive' => 'badge bg-warning',
            'maintenance' => 'badge bg-info',
            default => 'badge bg-secondary'
        };
    }

    /**
     * Generate thumbnail URL
     *
     * @param string $imageUrl
     * @return string
     */
    private function generateThumbnailUrl(string $imageUrl): string
    {
        // If using Cloudinary or similar service, generate thumbnail URL
        if (str_contains($imageUrl, 'cloudinary.com')) {
            return str_replace('/upload/', '/upload/w_300,h_200,c_fill/', $imageUrl);
        }

        return $imageUrl;
    }

    /**
     * Validate restaurant data
     *
     * @param array $data
     * @return array
     */
    public function validateRestaurantData(array $data): array
    {
        $errors = [];

        // Required fields
        if (!isset($data['name']['ka']) || empty($data['name']['ka'])) {
            $errors['name.ka'] = 'Georgian name is required';
        }
        
        if (!isset($data['name']['en']) || empty($data['name']['en'])) {
            $errors['name.en'] = 'English name is required';
        }

        if (!isset($data['address']) || empty($data['address'])) {
            $errors['address'] = 'Address is required';
        }

        if (!isset($data['phone']) || empty($data['phone'])) {
            $errors['phone'] = 'Phone number is required';
        }

        // Phone validation
        if (isset($data['phone']) && !preg_match('/^\+995\d{9}$/', $data['phone'])) {
            $errors['phone'] = 'Phone number must be in format +995XXXXXXXXX';
        }

        // Email validation
        if (isset($data['email']) && !empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        }

        // Coordinates validation
        if (isset($data['latitude']) && (!is_numeric($data['latitude']) || $data['latitude'] < -90 || $data['latitude'] > 90)) {
            $errors['latitude'] = 'Invalid latitude';
        }

        if (isset($data['longitude']) && (!is_numeric($data['longitude']) || $data['longitude'] < -180 || $data['longitude'] > 180)) {
            $errors['longitude'] = 'Invalid longitude';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
}