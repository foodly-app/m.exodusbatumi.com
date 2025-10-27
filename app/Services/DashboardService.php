<?php

namespace App\Services;

use Exception;

class DashboardService
{
    public function __construct(
        private readonly HttpClient $client
    ) {}

    /**
     * Get organization dashboard data
     *
     * @param int $organizationId
     * @return array
     * @throws Exception
     */
    public function getOrganizationDashboard(int $organizationId): array
    {
        return $this->client->get("/api/partner/organizations/{$organizationId}/dashboard");
    }

    /**
     * Get restaurant dashboard data
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @return array
     * @throws Exception
     */
    public function getRestaurantDashboard(int $organizationId, int $restaurantId): array
    {
        return $this->client->get("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}/dashboard");
    }

    /**
     * Get organization stats for mobile
     *
     * @param int $organizationId
     * @param string $period
     * @return array
     * @throws Exception
     */
    public function getOrganizationStats(int $organizationId, string $period = 'month'): array
    {
        return $this->client->get("/api/partner/organizations/{$organizationId}/dashboard/stats", [
            'period' => $period
        ]);
    }

    /**
     * Get organization overview
     *
     * @param int $organizationId
     * @return array
     * @throws Exception
     */
    public function getOrganizationOverview(int $organizationId): array
    {
        return $this->client->get("/api/partner/organizations/{$organizationId}/dashboard/overview");
    }

    /**
     * Get today's reservations for mobile quick view
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @return array
     * @throws Exception
     */
    public function getTodayReservations(int $organizationId, int $restaurantId): array
    {
        return $this->client->get("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}/reservations/today");
    }

    /**
     * Get upcoming reservations for mobile
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param int $days
     * @return array
     * @throws Exception
     */
    public function getUpcomingReservations(int $organizationId, int $restaurantId, int $days = 7): array
    {
        return $this->client->get("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}/reservations/upcoming", [
            'days' => $days
        ]);
    }

    /**
     * Get reservation statistics for mobile dashboard
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param array $filters
     * @return array
     * @throws Exception
     */
    public function getReservationStatistics(int $organizationId, int $restaurantId, array $filters = []): array
    {
        $params = array_merge([
            'date_from' => $filters['date_from'] ?? now()->startOfMonth()->format('Y-m-d'),
            'date_to' => $filters['date_to'] ?? now()->endOfMonth()->format('Y-m-d')
        ], $filters);

        return $this->client->get("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}/reservations/statistics", $params);
    }

    /**
     * Get calendar view for mobile
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param string $start
     * @param string $end
     * @return array
     * @throws Exception
     */
    public function getCalendarData(int $organizationId, int $restaurantId, string $start, string $end): array
    {
        return $this->client->get("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}/reservations/calendar", [
            'start' => $start,
            'end' => $end
        ]);
    }

    /**
     * Get analytics data for mobile
     *
     * @param int $organizationId
     * @param array $filters
     * @return array
     * @throws Exception
     */
    public function getAnalytics(int $organizationId, array $filters = []): array
    {
        $analytics = [];

        try {
            // Reservation analytics
            $analytics['reservations'] = $this->client->get("/api/partner/organizations/{$organizationId}/analytics/reservations", $filters);
        } catch (Exception $e) {
            $analytics['reservations'] = ['success' => false, 'error' => $e->getMessage()];
        }

        try {
            // Revenue analytics
            $analytics['revenue'] = $this->client->get("/api/partner/organizations/{$organizationId}/analytics/revenue", $filters);
        } catch (Exception $e) {
            $analytics['revenue'] = ['success' => false, 'error' => $e->getMessage()];
        }

        try {
            // Popular tables
            $analytics['popular_tables'] = $this->client->get("/api/partner/organizations/{$organizationId}/analytics/popular-tables", $filters);
        } catch (Exception $e) {
            $analytics['popular_tables'] = ['success' => false, 'error' => $e->getMessage()];
        }

        try {
            // Peak hours
            $analytics['peak_hours'] = $this->client->get("/api/partner/organizations/{$organizationId}/analytics/peak-hours", $filters);
        } catch (Exception $e) {
            $analytics['peak_hours'] = ['success' => false, 'error' => $e->getMessage()];
        }

        try {
            // Customer insights
            $analytics['customer_insights'] = $this->client->get("/api/partner/organizations/{$organizationId}/analytics/customer-insights", $filters);
        } catch (Exception $e) {
            $analytics['customer_insights'] = ['success' => false, 'error' => $e->getMessage()];
        }

        return $analytics;
    }

    /**
     * Search reservations for mobile quick search
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param string $query
     * @param array $filters
     * @return array
     * @throws Exception
     */
    public function searchReservations(int $organizationId, int $restaurantId, string $query, array $filters = []): array
    {
        $params = array_merge(['q' => $query], $filters);

        return $this->client->get("/api/partner/organizations/{$organizationId}/restaurants/{$restaurantId}/reservations/search", $params);
    }

    /**
     * Get mobile-optimized dashboard summary
     *
     * @param int $organizationId
     * @param int|null $restaurantId
     * @return array
     */
    public function getMobileDashboardSummary(int $organizationId, ?int $restaurantId = null): array
    {
        $summary = [];

        try {
            // Organization overview
            $orgOverview = $this->getOrganizationOverview($organizationId);
            $summary['organization'] = $orgOverview['success'] ? $orgOverview['data'] : null;

            // If specific restaurant is selected
            if ($restaurantId) {
                $restDashboard = $this->getRestaurantDashboard($organizationId, $restaurantId);
                $summary['restaurant'] = $restDashboard['success'] ? $restDashboard['data'] : null;

                $todayReservations = $this->getTodayReservations($organizationId, $restaurantId);
                $summary['today_reservations'] = $todayReservations['success'] ? $todayReservations['data'] : [];

                $upcomingReservations = $this->getUpcomingReservations($organizationId, $restaurantId, 3);
                $summary['upcoming_reservations'] = $upcomingReservations['success'] ? $upcomingReservations['data'] : [];
            }

            $summary['success'] = true;
        } catch (Exception $e) {
            $summary['success'] = false;
            $summary['error'] = $e->getMessage();
        }

        return $summary;
    }
}