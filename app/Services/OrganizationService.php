<?php

namespace App\Services;

use Exception;

class OrganizationService
{
    public function __construct(
        private readonly HttpClient $client
    ) {}

    /**
     * Get user's organizations list
     *
     * @return array
     * @throws Exception
     */
    public function getOrganizations(): array
    {
        return $this->client->get('/api/partner/organizations');
    }

    /**
     * Get organization details
     *
     * @param int $organizationId
     * @return array
     * @throws Exception
     */
    public function getOrganization(int $organizationId): array
    {
        return $this->client->get("/api/partner/organizations/{$organizationId}");
    }

    /**
     * Update organization
     *
     * @param int $organizationId
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function updateOrganization(int $organizationId, array $data): array
    {
        return $this->client->put("/api/partner/organizations/{$organizationId}", $data);
    }

    /**
     * Get organization team members
     *
     * @param int $organizationId
     * @return array
     * @throws Exception
     */
    public function getTeamMembers(int $organizationId): array
    {
        return $this->client->get("/api/partner/organizations/{$organizationId}/team");
    }

    /**
     * Get team member details
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
     * @param string $role
     * @return array
     * @throws Exception
     */
    public function updateTeamMemberRole(int $organizationId, int $userId, string $role): array
    {
        return $this->client->put("/api/partner/organizations/{$organizationId}/team/{$userId}/role", [
            'role' => $role
        ]);
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
     * Get organization invitations
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
     * Resend invitation
     *
     * @param int $invitationId
     * @return array
     * @throws Exception
     */
    public function resendInvitation(int $invitationId): array
    {
        return $this->client->post("/api/partner/invitations/{$invitationId}/resend");
    }

    /**
     * Delete invitation
     *
     * @param int $invitationId
     * @return array
     * @throws Exception
     */
    public function deleteInvitation(int $invitationId): array
    {
        return $this->client->delete("/api/partner/invitations/{$invitationId}");
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
     * Get mobile-optimized organization data
     *
     * @param int $organizationId
     * @return array
     */
    public function getMobileOrganizationData(int $organizationId): array
    {
        try {
            $organization = $this->getOrganization($organizationId);
            
            if (!$organization['success']) {
                return $organization;
            }

            $orgData = $organization['data'];

            // Format for mobile consumption
            return [
                'success' => true,
                'data' => [
                    'id' => $orgData['id'],
                    'name' => $orgData['name'],
                    'description' => $orgData['description'] ?? '',
                    'role' => $orgData['role'],
                    'can_edit' => in_array($orgData['role'], ['owner', 'manager']),
                    'can_delete_staff' => $orgData['role'] === 'owner',
                    'can_invite_staff' => in_array($orgData['role'], ['owner', 'manager']),
                    'restaurants' => collect($orgData['restaurants'] ?? [])->map(function ($restaurant) {
                        return [
                            'id' => $restaurant['id'],
                            'name' => $restaurant['name'],
                            'status' => $restaurant['status'],
                            'status_badge' => $this->getStatusBadgeClass($restaurant['status']),
                            'can_manage' => $restaurant['status'] === 'active'
                        ];
                    })->toArray(),
                    'team' => collect($orgData['team'] ?? [])->map(function ($member) use ($orgData) {
                        return [
                            'id' => $member['id'],
                            'name' => $member['name'],
                            'email' => $member['email'],
                            'role' => $member['role'],
                            'role_display' => $this->getRoleDisplay($member['role']),
                            'role_badge' => $this->getRoleBadgeClass($member['role']),
                            'can_edit' => $orgData['role'] === 'owner' || 
                                        ($orgData['role'] === 'manager' && $member['role'] !== 'owner'),
                            'can_delete' => $orgData['role'] === 'owner' && $member['role'] !== 'owner'
                        ];
                    })->toArray(),
                    'counts' => [
                        'restaurants' => count($orgData['restaurants'] ?? []),
                        'team_members' => count($orgData['team'] ?? []),
                        'active_restaurants' => collect($orgData['restaurants'] ?? [])->where('status', 'active')->count()
                    ]
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
     * Get mobile team management data
     *
     * @param int $organizationId
     * @return array
     */
    public function getMobileTeamData(int $organizationId): array
    {
        try {
            // Get team members and invitations in parallel
            $teamResponse = $this->getTeamMembers($organizationId);
            $invitationsResponse = $this->getInvitations($organizationId);

            if (!$teamResponse['success']) {
                return $teamResponse;
            }

            $team = $teamResponse['data'];
            $invitations = $invitationsResponse['success'] ? $invitationsResponse['data'] : [];

            return [
                'success' => true,
                'data' => [
                    'active_members' => collect($team)->map(function ($member) {
                        return [
                            'id' => $member['id'],
                            'name' => $member['name'],
                            'email' => $member['email'],
                            'role' => $member['role'],
                            'role_display' => $this->getRoleDisplay($member['role']),
                            'role_badge' => $this->getRoleBadgeClass($member['role']),
                            'avatar_url' => $member['avatar_url'] ?? null,
                            'joined_at' => $member['joined_at'] ?? null,
                            'formatted_joined_date' => isset($member['joined_at']) ? 
                                \Carbon\Carbon::parse($member['joined_at'])->locale('ka')->format('d M, Y') : null,
                            'status' => $member['status'] ?? 'active'
                        ];
                    })->toArray(),
                    'pending_invitations' => collect($invitations)->map(function ($invitation) {
                        return [
                            'id' => $invitation['id'],
                            'email' => $invitation['email'],
                            'role' => $invitation['role'],
                            'role_display' => $this->getRoleDisplay($invitation['role']),
                            'status' => $invitation['status'],
                            'sent_at' => $invitation['sent_at'],
                            'expires_at' => $invitation['expires_at'],
                            'formatted_sent_date' => \Carbon\Carbon::parse($invitation['sent_at'])->locale('ka')->format('d M, Y H:i'),
                            'is_expired' => \Carbon\Carbon::parse($invitation['expires_at'])->isPast()
                        ];
                    })->toArray(),
                    'counts' => [
                        'total_members' => count($team),
                        'pending_invitations' => count($invitations),
                        'owners' => collect($team)->where('role', 'owner')->count(),
                        'managers' => collect($team)->where('role', 'manager')->count(),
                        'staff' => collect($team)->where('role', 'staff')->count()
                    ]
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
     * Get available roles for mobile selection
     *
     * @return array
     */
    public function getAvailableRoles(): array
    {
        return [
            'owner' => [
                'value' => 'owner',
                'display' => 'მფლობელი',
                'description' => 'სრული წვდომა ყველაფერზე',
                'permissions' => ['all']
            ],
            'manager' => [
                'value' => 'manager',
                'display' => 'მენეჯერი',
                'description' => 'მართვა და რედაქტირება (გარდა წაშლისა)',
                'permissions' => ['view', 'edit', 'create']
            ],
            'staff' => [
                'value' => 'staff',
                'display' => 'თანამშრომელი',
                'description' => 'მხოლოდ ნახვის უფლება',
                'permissions' => ['view']
            ]
        ];
    }

    /**
     * Get role display name
     *
     * @param string $role
     * @return string
     */
    private function getRoleDisplay(string $role): string
    {
        $roles = $this->getAvailableRoles();
        return $roles[$role]['display'] ?? ucfirst($role);
    }

    /**
     * Get role badge CSS class
     *
     * @param string $role
     * @return string
     */
    private function getRoleBadgeClass(string $role): string
    {
        return match ($role) {
            'owner' => 'badge bg-primary',
            'manager' => 'badge bg-success',
            'staff' => 'badge bg-secondary',
            default => 'badge bg-light text-dark'
        };
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
     * Validate team member data
     *
     * @param array $data
     * @return array
     */
    public function validateTeamMemberData(array $data): array
    {
        $errors = [];

        // Required fields
        $requiredFields = [
            'name' => 'Name is required',
            'email' => 'Email is required',
            'role' => 'Role is required'
        ];

        foreach ($requiredFields as $field => $message) {
            if (!isset($data[$field]) || empty($data[$field])) {
                $errors[$field] = $message;
            }
        }

        // Email validation
        if (isset($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        }

        // Role validation
        $availableRoles = array_keys($this->getAvailableRoles());
        if (isset($data['role']) && !in_array($data['role'], $availableRoles)) {
            $errors['role'] = 'Invalid role selected';
        }

        // Password validation for new members
        if (isset($data['password'])) {
            if (strlen($data['password']) < 8) {
                $errors['password'] = 'Password must be at least 8 characters';
            }
            if (!isset($data['password_confirmation']) || $data['password'] !== $data['password_confirmation']) {
                $errors['password_confirmation'] = 'Password confirmation does not match';
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
}