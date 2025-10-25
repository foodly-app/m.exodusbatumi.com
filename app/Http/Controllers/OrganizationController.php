<?php

namespace App\Http\Controllers;

use App\Services\OrganizationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function __construct(
        private readonly OrganizationService $organizationService
    ) {}

    /**
     * Get list of organizations
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $organizations = $this->organizationService->list($request->query());

            return response()->json([
                'success' => true,
                'data' => $organizations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get organization by ID
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        try {
            $organization = $this->organizationService->get((int)$id);

            return response()->json([
                'success' => true,
                'data' => $organization
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update organization
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $data = $request->validate([
                'name' => ['sometimes', 'string'],
                'email' => ['sometimes', 'email'],
                'phone' => ['sometimes', 'string'],
                'address' => ['sometimes', 'string'],
            ]);

            $organization = $this->organizationService->update($id, $data);

            return response()->json([
                'success' => true,
                'data' => $organization
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Get organization statistics
     *
     * @param int $id
     * @return JsonResponse
     */
    public function statistics(int $id): JsonResponse
    {
        try {
            $statistics = $this->organizationService->getStatistics($id);

            return response()->json([
                'success' => true,
                'data' => $statistics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get organization dashboard
     *
     * @param string $id
     * @return JsonResponse
     */
    public function dashboard(string $id): JsonResponse
    {
        try {
            $dashboard = $this->organizationService->getDashboard((int)$id);

            return response()->json([
                'success' => true,
                'data' => $dashboard
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get dashboard statistics
     *
     * @param string $id
     * @return JsonResponse
     */
    public function dashboardStats(string $id): JsonResponse
    {
        try {
            $stats = $this->organizationService->getDashboardStats((int)$id);

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get dashboard overview
     *
     * @param string $id
     * @return JsonResponse
     */
    public function dashboardOverview(string $id): JsonResponse
    {
        try {
            $overview = $this->organizationService->getDashboardOverview((int)$id);

            return response()->json([
                'success' => true,
                'data' => $overview
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get team members
     *
     * @param int $id
     * @return JsonResponse
     */
    public function team(int $id): JsonResponse
    {
        try {
            $team = $this->organizationService->getTeam($id);

            return response()->json([
                'success' => true,
                'data' => $team
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get team member by ID
     *
     * @param int $id
     * @param int $memberId
     * @return JsonResponse
     */
    public function teamMember(int $id, int $memberId): JsonResponse
    {
        try {
            $member = $this->organizationService->getTeamMember($id, $memberId);

            return response()->json([
                'success' => true,
                'data' => $member
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Add team member
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function addTeamMember(Request $request, int $id): JsonResponse
    {
        try {
            $data = $request->validate([
                'email' => ['required', 'email'],
                'name' => ['required', 'string'],
                'role' => ['required', 'string'],
            ]);

            $member = $this->organizationService->createTeamMember($id, $data);

            return response()->json([
                'success' => true,
                'data' => $member
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Update team member role
     *
     * @param Request $request
     * @param int $id
     * @param int $memberId
     * @return JsonResponse
     */
    public function updateTeamMemberRole(Request $request, int $id, int $memberId): JsonResponse
    {
        try {
            $data = $request->validate([
                'role' => ['required', 'string'],
            ]);

            $member = $this->organizationService->updateTeamMemberRole($id, $memberId, $data);

            return response()->json([
                'success' => true,
                'data' => $member
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Remove team member
     *
     * @param int $id
     * @param int $memberId
     * @return JsonResponse
     */
    public function removeTeamMember(int $id, int $memberId): JsonResponse
    {
        try {
            $this->organizationService->deleteTeamMember($id, $memberId);

            return response()->json([
                'success' => true,
                'message' => 'Team member removed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get invitations
     *
     * @param int $id
     * @return JsonResponse
     */
    public function invitations(int $id): JsonResponse
    {
        try {
            $invitations = $this->organizationService->getInvitations($id);

            return response()->json([
                'success' => true,
                'data' => $invitations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send invitation
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function sendInvitation(Request $request, int $id): JsonResponse
    {
        try {
            $data = $request->validate([
                'email' => ['required', 'email'],
                'role' => ['required', 'string'],
            ]);

            $invitation = $this->organizationService->sendInvitation($id, $data);

            return response()->json([
                'success' => true,
                'data' => $invitation
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Get analytics - reservations
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function analyticsReservations(Request $request, int $id): JsonResponse
    {
        try {
            $analytics = $this->organizationService->getAnalyticsReservations($id, $request->query());

            return response()->json([
                'success' => true,
                'data' => $analytics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get analytics - revenue
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function analyticsRevenue(Request $request, int $id): JsonResponse
    {
        try {
            $analytics = $this->organizationService->getAnalyticsRevenue($id, $request->query());

            return response()->json([
                'success' => true,
                'data' => $analytics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get analytics - popular tables
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function analyticsPopularTables(Request $request, int $id): JsonResponse
    {
        try {
            $analytics = $this->organizationService->getAnalyticsPopularTables($id, $request->query());

            return response()->json([
                'success' => true,
                'data' => $analytics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get analytics - peak hours
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function analyticsPeakHours(Request $request, int $id): JsonResponse
    {
        try {
            $analytics = $this->organizationService->getAnalyticsPeakHours($id, $request->query());

            return response()->json([
                'success' => true,
                'data' => $analytics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get analytics - customer insights
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function analyticsCustomerInsights(Request $request, int $id): JsonResponse
    {
        try {
            $analytics = $this->organizationService->getAnalyticsCustomerInsights($id, $request->query());

            return response()->json([
                'success' => true,
                'data' => $analytics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}