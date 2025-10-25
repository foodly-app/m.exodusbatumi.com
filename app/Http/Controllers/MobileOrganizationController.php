<?php

namespace App\Http\Controllers;

use App\Services\MobileOrganizationService;
use App\Services\MobileRestaurantService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MobileOrganizationController extends Controller
{
    public function __construct(
        private readonly MobileOrganizationService $organizationService,
        private readonly MobileRestaurantService $restaurantService
    ) {}

    /**
     * Show organization management
     *
     * @param int $organizationId
     * @return View
     */
    public function show(int $organizationId): View
    {
        try {
            $organization = $this->organizationService->getMobileOrganizationData($organizationId);
            
            return view('mobile.organizations.show', [
                'organization' => $organization['success'] ? $organization['data'] : null,
                'organizationId' => $organizationId,
                'error' => !$organization['success'] ? $organization['error'] : null
            ]);

        } catch (Exception $e) {
            return view('mobile.organizations.show', [
                'organization' => null,
                'organizationId' => $organizationId,
                'error' => 'Failed to load organization: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update organization
     *
     * @param Request $request
     * @param int $organizationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $organizationId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000'
        ]);

        try {
            $response = $this->organizationService->updateOrganization($organizationId, $request->only('name', 'description'));

            if ($response['success']) {
                return back()->with('success', 'Organization updated successfully');
            }

            return back()->withErrors(['error' => 'Failed to update organization']);

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Update failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Show team management
     *
     * @param int $organizationId
     * @return View
     */
    public function team(int $organizationId): View
    {
        try {
            $team = $this->organizationService->getMobileTeamData($organizationId);
            $roles = $this->organizationService->getAvailableRoles();
            
            return view('mobile.organizations.team', [
                'team' => $team['success'] ? $team['data'] : null,
                'availableRoles' => $roles,
                'organizationId' => $organizationId,
                'error' => !$team['success'] ? $team['error'] : null
            ]);

        } catch (Exception $e) {
            return view('mobile.organizations.team', [
                'team' => null,
                'availableRoles' => [],
                'organizationId' => $organizationId,
                'error' => 'Failed to load team data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Create team member
     *
     * @param Request $request
     * @param int $organizationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createTeamMember(Request $request, int $organizationId)
    {
        $validation = $this->organizationService->validateTeamMemberData($request->all());
        
        if (!$validation['valid']) {
            return back()->withErrors($validation['errors'])->withInput();
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string|in:owner,manager,staff',
            'password' => 'required|string|min:8|confirmed'
        ]);

        try {
            $response = $this->organizationService->createTeamMember($organizationId, $request->all());

            if ($response['success']) {
                return back()->with('success', 'Team member created successfully');
            }

            return back()->withErrors(['error' => 'Failed to create team member']);

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Creation failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Update team member role
     *
     * @param Request $request
     * @param int $organizationId
     * @param int $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateTeamMemberRole(Request $request, int $organizationId, int $userId)
    {
        $request->validate([
            'role' => 'required|string|in:owner,manager,staff'
        ]);

        try {
            $response = $this->organizationService->updateTeamMemberRole($organizationId, $userId, $request->role);

            if ($response['success']) {
                return back()->with('success', 'Team member role updated successfully');
            }

            return back()->withErrors(['error' => 'Failed to update role']);

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Update failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete team member
     *
     * @param int $organizationId
     * @param int $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteTeamMember(int $organizationId, int $userId)
    {
        try {
            $response = $this->organizationService->deleteTeamMember($organizationId, $userId);

            if ($response['success']) {
                return back()->with('success', 'Team member removed successfully');
            }

            return back()->withErrors(['error' => 'Failed to remove team member']);

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Removal failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Send invitation
     *
     * @param Request $request
     * @param int $organizationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendInvitation(Request $request, int $organizationId)
    {
        $request->validate([
            'email' => 'required|email',
            'role' => 'required|string|in:owner,manager,staff'
        ]);

        try {
            $response = $this->organizationService->sendInvitation($organizationId, $request->only('email', 'role'));

            if ($response['success']) {
                return back()->with('success', 'Invitation sent successfully');
            }

            return back()->withErrors(['error' => 'Failed to send invitation']);

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Invitation failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Resend invitation
     *
     * @param int $organizationId
     * @param int $invitationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resendInvitation(int $organizationId, int $invitationId)
    {
        try {
            $response = $this->organizationService->resendInvitation($invitationId);

            if ($response['success']) {
                return back()->with('success', 'Invitation resent successfully');
            }

            return back()->withErrors(['error' => 'Failed to resend invitation']);

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Resend failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete invitation
     *
     * @param int $organizationId
     * @param int $invitationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteInvitation(int $organizationId, int $invitationId)
    {
        try {
            $response = $this->organizationService->deleteInvitation($invitationId);

            if ($response['success']) {
                return back()->with('success', 'Invitation cancelled successfully');
            }

            return back()->withErrors(['error' => 'Failed to cancel invitation']);

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Cancellation failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Get team data (AJAX endpoint)
     *
     * @param int $organizationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTeamData(int $organizationId)
    {
        try {
            $team = $this->organizationService->getMobileTeamData($organizationId);
            
            return response()->json($team);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get organization data (AJAX endpoint)
     *
     * @param int $organizationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrganizationData(int $organizationId)
    {
        try {
            $organization = $this->organizationService->getMobileOrganizationData($organizationId);
            
            return response()->json($organization);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show restaurants management
     *
     * @param int $organizationId
     * @return View
     */
    public function restaurants(int $organizationId): View
    {
        try {
            $restaurants = $this->restaurantService->getRestaurants($organizationId);
            
            return view('mobile.organizations.restaurants', [
                'restaurants' => $restaurants['success'] ? $restaurants['data'] : [],
                'organizationId' => $organizationId,
                'error' => !$restaurants['success'] ? $restaurants['error'] : null
            ]);

        } catch (Exception $e) {
            return view('mobile.organizations.restaurants', [
                'restaurants' => [],
                'organizationId' => $organizationId,
                'error' => 'Failed to load restaurants: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show create restaurant form
     *
     * @param int $organizationId
     * @return View
     */
    public function createRestaurant(int $organizationId): View
    {
        $cuisineTypes = $this->restaurantService->getCuisineTypes();
        $features = $this->restaurantService->getAvailableFeatures();

        return view('mobile.organizations.create-restaurant', [
            'organizationId' => $organizationId,
            'cuisineTypes' => $cuisineTypes,
            'features' => $features
        ]);
    }

    /**
     * Store new restaurant
     *
     * @param Request $request
     * @param int $organizationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeRestaurant(Request $request, int $organizationId)
    {
        $validation = $this->restaurantService->validateRestaurantData($request->all());
        
        if (!$validation['valid']) {
            return back()->withErrors($validation['errors'])->withInput();
        }

        try {
            $response = $this->restaurantService->createRestaurant($organizationId, $request->all());

            if ($response['success']) {
                return redirect()->route('mobile.organizations.restaurants', $organizationId)
                    ->with('success', 'Restaurant created successfully');
            }

            return back()->withErrors(['error' => 'Failed to create restaurant'])->withInput();

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Creation failed: ' . $e->getMessage()])->withInput();
        }
    }
}