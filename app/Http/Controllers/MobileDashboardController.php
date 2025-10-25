<?php

namespace App\Http\Controllers;

use App\Services\MobileDashboardService;
use App\Services\MobileOrganizationService;
use App\Services\MobileRestaurantService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MobileDashboardController extends Controller
{
    public function __construct(
        private readonly MobileDashboardService $dashboardService,
        private readonly MobileOrganizationService $organizationService,
        private readonly MobileRestaurantService $restaurantService
    ) {}

    /**
     * Main dashboard - redirect to appropriate view
     *
     * @param Request $request
     * @param int|null $organizationId
     * @param int|null $restaurantId
     * @return View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request, ?int $organizationId = null, ?int $restaurantId = null)
    {
        try {
            // If no organization specified, get user's organizations and redirect to first one
            if (!$organizationId) {
                $organizations = $this->organizationService->getOrganizations();
                
                if ($organizations['success'] && !empty($organizations['data'])) {
                    $firstOrg = $organizations['data'][0];
                    return redirect()->route('mobile.dashboard', ['organization' => $firstOrg['id']]);
                }
                
                return view('mobile.dashboard', [
                    'error' => 'No organizations found. Please contact administrator.',
                    'organizations' => [],
                    'selectedOrganization' => null,
                    'selectedRestaurant' => null,
                    'summary' => null
                ]);
            }

            // Set selected organization in session
            session(['selected_organization' => $organizationId]);

            // Get organizations for navigation
            $organizations = $this->organizationService->getOrganizations();
            $orgsData = $organizations['success'] ? $organizations['data'] : [];

            // Get selected organization data
            $selectedOrg = collect($orgsData)->firstWhere('id', $organizationId);

            // If restaurant is specified, show restaurant dashboard
            if ($restaurantId) {
                return $this->restaurantDashboard($organizationId, $restaurantId, $orgsData, $selectedOrg);
            }

            // Show organization dashboard
            return $this->organizationDashboard($organizationId, $orgsData, $selectedOrg);

        } catch (Exception $e) {
            return view('mobile.dashboard', [
                'error' => 'Failed to load dashboard: ' . $e->getMessage(),
                'organizations' => [],
                'selectedOrganization' => null,
                'selectedRestaurant' => null,
                'summary' => null
            ]);
        }
    }

    /**
     * Organization dashboard view
     *
     * @param int $organizationId
     * @param array $organizations
     * @param array|null $selectedOrg
     * @return View
     */
    private function organizationDashboard(int $organizationId, array $organizations, ?array $selectedOrg): View
    {
        try {
            // Get organization dashboard data
            $dashboard = $this->dashboardService->getOrganizationDashboard($organizationId);
            $summary = $dashboard['success'] ? $dashboard['data'] : null;

            // Get organization restaurants for quick access
            $restaurants = $this->restaurantService->getRestaurants($organizationId);
            $restaurantsData = $restaurants['success'] ? $restaurants['data'] : [];

            return view('mobile.dashboard', [
                'type' => 'organization',
                'organizations' => $organizations,
                'selectedOrganization' => $selectedOrg,
                'selectedRestaurant' => null,
                'summary' => $summary,
                'restaurants' => $restaurantsData,
                'error' => null
            ]);

        } catch (Exception $e) {
            return view('mobile.dashboard', [
                'type' => 'organization',
                'organizations' => $organizations,
                'selectedOrganization' => $selectedOrg,
                'selectedRestaurant' => null,
                'summary' => null,
                'restaurants' => [],
                'error' => 'Failed to load organization data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Restaurant dashboard view
     *
     * @param int $organizationId
     * @param int $restaurantId
     * @param array $organizations
     * @param array|null $selectedOrg
     * @return View
     */
    private function restaurantDashboard(int $organizationId, int $restaurantId, array $organizations, ?array $selectedOrg): View
    {
        try {
            // Get restaurant dashboard data
            $dashboard = $this->dashboardService->getRestaurantDashboard($organizationId, $restaurantId);
            $summary = $dashboard['success'] ? $dashboard['data'] : null;

            // Get restaurant details
            $restaurant = $this->restaurantService->getRestaurant($organizationId, $restaurantId);
            $restaurantData = $restaurant['success'] ? $restaurant['data'] : null;

            // Get today's reservations for quick view
            $todayReservations = $this->dashboardService->getTodayReservations($organizationId, $restaurantId);
            $todayData = $todayReservations['success'] ? $todayReservations['data'] : [];

            // Get upcoming reservations
            $upcomingReservations = $this->dashboardService->getUpcomingReservations($organizationId, $restaurantId, 3);
            $upcomingData = $upcomingReservations['success'] ? $upcomingReservations['data'] : [];

            return view('mobile.dashboard', [
                'type' => 'restaurant',
                'organizations' => $organizations,
                'selectedOrganization' => $selectedOrg,
                'selectedRestaurant' => $restaurantData,
                'summary' => $summary,
                'todayReservations' => $todayData,
                'upcomingReservations' => $upcomingData,
                'error' => null
            ]);

        } catch (Exception $e) {
            return view('mobile.dashboard', [
                'type' => 'restaurant',
                'organizations' => $organizations,
                'selectedOrganization' => $selectedOrg,
                'selectedRestaurant' => null,
                'summary' => null,
                'todayReservations' => [],
                'upcomingReservations' => [],
                'error' => 'Failed to load restaurant data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get dashboard stats (AJAX endpoint)
     *
     * @param Request $request
     * @param int $organizationId
     * @param int|null $restaurantId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStats(Request $request, int $organizationId, ?int $restaurantId = null): \Illuminate\Http\JsonResponse
    {
        try {
            $period = $request->query('period', 'month');

            if ($restaurantId) {
                // Restaurant stats
                $stats = $this->dashboardService->getReservationStatistics($organizationId, $restaurantId, [
                    'date_from' => $this->getDateFromPeriod($period)['from'],
                    'date_to' => $this->getDateFromPeriod($period)['to']
                ]);
            } else {
                // Organization stats
                $stats = $this->dashboardService->getOrganizationStats($organizationId, $period);
            }

            return response()->json($stats);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get calendar data (AJAX endpoint)
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $restaurantId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCalendarData(Request $request, int $organizationId, int $restaurantId): \Illuminate\Http\JsonResponse
    {
        try {
            $start = $request->query('start', now()->startOfMonth()->format('Y-m-d'));
            $end = $request->query('end', now()->endOfMonth()->format('Y-m-d'));

            $calendar = $this->dashboardService->getCalendarData($organizationId, $restaurantId, $start, $end);

            return response()->json($calendar);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search reservations (AJAX endpoint)
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $restaurantId
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchReservations(Request $request, int $organizationId, int $restaurantId): \Illuminate\Http\JsonResponse
    {
        try {
            $query = $request->query('q', '');
            $filters = $request->only(['date', 'status']);

            if (empty($query)) {
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            }

            $results = $this->dashboardService->searchReservations($organizationId, $restaurantId, $query, $filters);

            return response()->json($results);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get analytics data (AJAX endpoint)
     *
     * @param Request $request
     * @param int $organizationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAnalytics(Request $request, int $organizationId): \Illuminate\Http\JsonResponse
    {
        try {
            $filters = $request->only(['date_from', 'date_to', 'restaurant_id']);
            
            // Set default date range if not provided
            if (!isset($filters['date_from'])) {
                $dateRange = $this->getDateFromPeriod($request->query('period', 'month'));
                $filters['date_from'] = $dateRange['from'];
                $filters['date_to'] = $dateRange['to'];
            }

            $analytics = $this->dashboardService->getAnalytics($organizationId, $filters);

            return response()->json([
                'success' => true,
                'data' => $analytics
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Switch organization (AJAX endpoint)
     *
     * @param Request $request
     * @param int $organizationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function switchOrganization(Request $request, int $organizationId): \Illuminate\Http\JsonResponse
    {
        try {
            // Update session
            session(['selected_organization' => $organizationId]);

            // Get organization data
            $organization = $this->organizationService->getOrganization($organizationId);

            if ($organization['success']) {
                $orgData = $organization['data'];
                
                // Get first restaurant if available
                $firstRestaurant = !empty($orgData['restaurants']) ? $orgData['restaurants'][0] : null;

                return response()->json([
                    'success' => true,
                    'data' => [
                        'organization' => $orgData,
                        'redirect_url' => $firstRestaurant ? 
                            route('mobile.dashboard', ['organization' => $organizationId, 'restaurant' => $firstRestaurant['id']]) :
                            route('mobile.dashboard', ['organization' => $organizationId])
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'error' => 'Organization not found'
            ], 404);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get mobile summary data (AJAX endpoint)
     *
     * @param Request $request
     * @param int $organizationId
     * @param int|null $restaurantId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMobileSummary(Request $request, int $organizationId, ?int $restaurantId = null): \Illuminate\Http\JsonResponse
    {
        try {
            $summary = $this->dashboardService->getMobileDashboardSummary($organizationId, $restaurantId);

            return response()->json($summary);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get date range from period
     *
     * @param string $period
     * @return array
     */
    private function getDateFromPeriod(string $period): array
    {
        return match ($period) {
            'today' => [
                'from' => now()->format('Y-m-d'),
                'to' => now()->format('Y-m-d')
            ],
            'week' => [
                'from' => now()->startOfWeek()->format('Y-m-d'),
                'to' => now()->endOfWeek()->format('Y-m-d')
            ],
            'year' => [
                'from' => now()->startOfYear()->format('Y-m-d'),
                'to' => now()->endOfYear()->format('Y-m-d')
            ],
            default => [ // month
                'from' => now()->startOfMonth()->format('Y-m-d'),
                'to' => now()->endOfMonth()->format('Y-m-d')
            ]
        };
    }
}