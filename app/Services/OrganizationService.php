<?php

namespace App\Services;

use Exception;

class OrganizationService
{
    public function __construct(
        private readonly HttpClient $client
    ) {}

    /**
     * Get list of organizations
     *
     * @param array $query
     * @return array
     * @throws Exception
     */
    public function list(array $query = []): array
    {
        return $this->client->get('/api/partner/organizations', $query);
    }

    /**
     * Get organization by ID
     *
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function get(int $id): array
    {
        return $this->client->get("/api/partner/organizations/{$id}");
    }

    /**
     * Update organization details
     *
     * @param int $id
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function update(int $id, array $data): array
    {
        return $this->client->put("/api/partner/organizations/{$id}", $data);
    }

    /**
     * Get organization's statistics
     *
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function getStatistics(int $id): array
    {
        return $this->client->get("/api/partner/organizations/{$id}/statistics");
    }

    /**
     * Get organization's restaurants
     *
     * @param int $id
     * @param array $query
     * @return array
     * @throws Exception
     */
    public function getRestaurants(int $id, array $query = []): array
    {
        return $this->client->get("/api/partner/organizations/{$id}/restaurants", $query);
    }

    /**
     * Get organization dashboard
     *
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function getDashboard(int $id): array
    {
        return $this->client->get("/api/partner/organizations/{$id}/dashboard");
    }

    /**
     * Get organization dashboard stats
     *
     * @param int $id
     * @param array $query
     * @return array
     * @throws Exception
     */
    public function getDashboardStats(int $id, array $query = []): array
    {
        return $this->client->get("/api/partner/organizations/{$id}/dashboard/stats", $query);
    }

    /**
     * Get organization dashboard overview
     *
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function getDashboardOverview(int $id): array
    {
        return $this->client->get("/api/partner/organizations/{$id}/dashboard/overview");
    }

    /**
     * Get team members
     *
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function getTeam(int $id): array
    {
        return $this->client->get("/api/partner/organizations/{$id}/team");
    }

    /**
     * Get team member
     *
     * @param int $organizationId
     * @param int $userId
     * @return array
     * @throws Exception
     */
    public function getTeamMember(int $organizationId, int $userId): array
    {
        return $this->client->get("/api/partner/organizations/{$organizationId}/team/{$userId}");
    }

    /**
     * Create team member
     *
     * @param int $organizationId
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function createTeamMember(int $organizationId, array $data): array
    {
        return $this->client->post("/api/partner/organizations/{$organizationId}/team", $data);
    }

    /**
     * Update team member role
     *
     * @param int $organizationId
     * @param int $userId
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function updateTeamMemberRole(int $organizationId, int $userId, array $data): array
    {
        return $this->client->put("/api/partner/organizations/{$organizationId}/team/{$userId}/role", $data);
    }

    /**
     * Delete team member
     *
     * @param int $organizationId
     * @param int $userId
     * @return array
     * @throws Exception
     */
    public function deleteTeamMember(int $organizationId, int $userId): array
    {
        return $this->client->delete("/api/partner/organizations/{$organizationId}/team/{$userId}");
    }

    /**
     * Get invitations
     *
     * @param int $organizationId
     * @return array
     * @throws Exception
     */
    public function getInvitations(int $organizationId): array
    {
        return $this->client->get("/api/partner/organizations/{$organizationId}/invitations");
    }

    /**
     * Send invitation
     *
     * @param int $organizationId
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function sendInvitation(int $organizationId, array $data): array
    {
        return $this->client->post("/api/partner/organizations/{$organizationId}/invitations", $data);
    }

    /**
     * Get analytics - reservations
     *
     * @param int $organizationId
     * @param array $query
     * @return array
     * @throws Exception
     */
    public function getAnalyticsReservations(int $organizationId, array $query = []): array
    {
        return $this->client->get("/api/partner/organizations/{$organizationId}/analytics/reservations", $query);
    }

    /**
     * Get analytics - revenue
     *
     * @param int $organizationId
     * @param array $query
     * @return array
     * @throws Exception
     */
    public function getAnalyticsRevenue(int $organizationId, array $query = []): array
    {
        return $this->client->get("/api/partner/organizations/{$organizationId}/analytics/revenue", $query);
    }

    /**
     * Get analytics - popular tables
     *
     * @param int $organizationId
     * @param array $query
     * @return array
     * @throws Exception
     */
    public function getAnalyticsPopularTables(int $organizationId, array $query = []): array
    {
        return $this->client->get("/api/partner/organizations/{$organizationId}/analytics/popular-tables", $query);
    }

    /**
     * Get analytics - peak hours
     *
     * @param int $organizationId
     * @param array $query
     * @return array
     * @throws Exception
     */
    public function getAnalyticsPeakHours(int $organizationId, array $query = []): array
    {
        return $this->client->get("/api/partner/organizations/{$organizationId}/analytics/peak-hours", $query);
    }

    /**
     * Get analytics - customer insights
     *
     * @param int $organizationId
     * @param array $query
     * @return array
     * @throws Exception
     */
    public function getAnalyticsCustomerInsights(int $organizationId, array $query = []): array
    {
        return $this->client->get("/api/partner/organizations/{$organizationId}/analytics/customer-insights", $query);
    }
}
