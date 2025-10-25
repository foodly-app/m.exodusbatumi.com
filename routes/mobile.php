<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MobileAuthController;
use App\Http\Controllers\MobileDashboardController;
use App\Http\Controllers\MobileReservationController;
use App\Http\Controllers\MobileBookingController;
use App\Http\Controllers\MobileOrganizationController;
use App\Http\Controllers\MobileRestaurantController;

/*
|--------------------------------------------------------------------------
| Mobile Partner Panel Routes
|--------------------------------------------------------------------------
|
| These routes handle the mobile management interface for restaurant partners.
| All routes are prefixed with 'mobile' and use appropriate middleware.
|
*/

// Guest routes (login only)
Route::prefix('mobile')->name('mobile.')->middleware(['web', 'mobile.guest'])->group(function () {
    Route::get('/login', [MobileAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [MobileAuthController::class, 'login']);
});

// Authenticated routes
Route::prefix('mobile')->name('mobile.')->middleware(['web', 'mobile.auth'])->group(function () {
    
    // Authentication
    Route::post('/logout', [MobileAuthController::class, 'logout'])->name('logout');
    Route::get('/check-auth', [MobileAuthController::class, 'checkAuth'])->name('check-auth');
    
    // Profile management
    Route::get('/profile', [MobileAuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [MobileAuthController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/avatar', [MobileAuthController::class, 'uploadAvatar'])->name('profile.upload-avatar');
    Route::delete('/profile/avatar', [MobileAuthController::class, 'deleteAvatar'])->name('profile.delete-avatar');
    Route::put('/profile/password', [MobileAuthController::class, 'changePassword'])->name('profile.change-password');
    
    // Dashboard routes
    Route::get('/dashboard/{organization?}/{restaurant?}', function () {
        return view('mobile.dashboard-simple');
    })->name('dashboard');
    Route::get('/organizations/{organization}/dashboard/stats', [MobileDashboardController::class, 'getStats'])->name('dashboard.stats');
    Route::get('/organizations/{organization}/restaurants/{restaurant}/calendar', [MobileDashboardController::class, 'getCalendarData'])->name('dashboard.calendar');
    Route::get('/organizations/{organization}/restaurants/{restaurant}/search', [MobileDashboardController::class, 'searchReservations'])->name('dashboard.search');
    Route::get('/organizations/{organization}/analytics', [MobileDashboardController::class, 'getAnalytics'])->name('dashboard.analytics');
    Route::post('/organizations/{organization}/switch', [MobileDashboardController::class, 'switchOrganization'])->name('dashboard.switch-organization');
    Route::get('/organizations/{organization}/summary/{restaurant?}', [MobileDashboardController::class, 'getMobileSummary'])->name('dashboard.summary');
    
    // Organization management
    Route::prefix('organizations/{organization}')->name('organizations.')->group(function () {
        Route::get('/', [MobileOrganizationController::class, 'show'])->name('show');
        Route::put('/', [MobileOrganizationController::class, 'update'])->name('update');
        Route::get('/data', [MobileOrganizationController::class, 'getOrganizationData'])->name('data');
        
        // Team management
        Route::get('/team', [MobileOrganizationController::class, 'team'])->name('team');
        Route::get('/team/data', [MobileOrganizationController::class, 'getTeamData'])->name('team.data');
        Route::post('/team', [MobileOrganizationController::class, 'createTeamMember'])->name('team.create');
        Route::put('/team/{user}/role', [MobileOrganizationController::class, 'updateTeamMemberRole'])->name('team.update-role');
        Route::delete('/team/{user}', [MobileOrganizationController::class, 'deleteTeamMember'])->name('team.delete');
        
        // Invitations
        Route::post('/invitations', [MobileOrganizationController::class, 'sendInvitation'])->name('invitations.send');
        Route::post('/invitations/{invitation}/resend', [MobileOrganizationController::class, 'resendInvitation'])->name('invitations.resend');
        Route::delete('/invitations/{invitation}', [MobileOrganizationController::class, 'deleteInvitation'])->name('invitations.delete');
        
        // Restaurants management
        Route::get('/restaurants', [MobileOrganizationController::class, 'restaurants'])->name('restaurants');
        Route::get('/restaurants/create', [MobileOrganizationController::class, 'createRestaurant'])->name('restaurants.create');
        Route::post('/restaurants', [MobileOrganizationController::class, 'storeRestaurant'])->name('restaurants.store');
    });
    
    // Restaurant management
    Route::prefix('organizations/{organization}/restaurants/{restaurant}')->name('restaurants.')->group(function () {
        Route::get('/', [MobileRestaurantController::class, 'show'])->name('show');
        Route::get('/edit', [MobileRestaurantController::class, 'edit'])->name('edit');
        Route::put('/', [MobileRestaurantController::class, 'update'])->name('update');
        Route::put('/status', [MobileRestaurantController::class, 'updateStatus'])->name('update-status');
        Route::get('/data', [MobileRestaurantController::class, 'getRestaurantData'])->name('data');
        
        // Images
        Route::post('/images', [MobileRestaurantController::class, 'uploadImages'])->name('upload-images');
        Route::delete('/images/{image}', [MobileRestaurantController::class, 'deleteImage'])->name('delete-image');
        
        // Settings
        Route::get('/settings', [MobileRestaurantController::class, 'settings'])->name('settings');
        Route::put('/settings', [MobileRestaurantController::class, 'updateSettings'])->name('update-settings');
        
        // Places management
        Route::get('/places', [MobileRestaurantController::class, 'places'])->name('places');
        Route::post('/places', [MobileRestaurantController::class, 'createPlace'])->name('places.create');
        Route::put('/places/{place}', [MobileRestaurantController::class, 'updatePlace'])->name('places.update');
        Route::delete('/places/{place}', [MobileRestaurantController::class, 'deletePlace'])->name('places.delete');
        
        // Tables management
        Route::get('/tables', [MobileRestaurantController::class, 'tables'])->name('tables');
        Route::post('/tables', [MobileRestaurantController::class, 'createTable'])->name('tables.create');
        Route::put('/tables/{table}', [MobileRestaurantController::class, 'updateTable'])->name('tables.update');
        Route::put('/tables/{table}/status', [MobileRestaurantController::class, 'updateTableStatus'])->name('tables.update-status');
        Route::delete('/tables/{table}', [MobileRestaurantController::class, 'deleteTable'])->name('tables.delete');
        Route::get('/tables/{table}/availability', [MobileRestaurantController::class, 'checkTableAvailability'])->name('tables.check-availability');
        
        // Reservations management
        Route::get('/reservations', [MobileReservationController::class, 'index'])->name('reservations.index');
        Route::get('/reservations/upcoming', [MobileReservationController::class, 'upcoming'])->name('reservations.upcoming');
        Route::get('/reservations/data', [MobileReservationController::class, 'getReservations'])->name('reservations.data');
        Route::get('/reservations/counts', [MobileReservationController::class, 'getStatusCounts'])->name('reservations.counts');
        Route::get('/reservations/{reservation}', [MobileReservationController::class, 'show'])->name('reservations.show');
        
        // Reservation actions
        Route::post('/reservations/{reservation}/confirm', [MobileReservationController::class, 'confirm'])->name('reservations.confirm');
        Route::post('/reservations/{reservation}/cancel', [MobileReservationController::class, 'cancel'])->name('reservations.cancel');
        Route::post('/reservations/{reservation}/paid', [MobileReservationController::class, 'markAsPaid'])->name('reservations.mark-paid');
        Route::post('/reservations/{reservation}/complete', [MobileReservationController::class, 'markAsCompleted'])->name('reservations.mark-completed');
        Route::post('/reservations/{reservation}/no-show', [MobileReservationController::class, 'markAsNoShow'])->name('reservations.mark-no-show');
        Route::put('/reservations/{reservation}/status', [MobileReservationController::class, 'updateStatus'])->name('reservations.update-status');
        Route::post('/reservations/{reservation}/payment', [MobileReservationController::class, 'initiatePayment'])->name('reservations.initiate-payment');
        
        // Bulk actions
        Route::post('/reservations/bulk-action', [MobileReservationController::class, 'bulkAction'])->name('reservations.bulk-action');
    });
    
    // Booking management (partner-assisted)
    Route::prefix('organizations/{organization}')->name('booking.')->group(function () {
        Route::get('/booking/restaurants', [MobileBookingController::class, 'restaurants'])->name('restaurants');
        
        Route::prefix('restaurants/{restaurant}/booking')->group(function () {
            Route::get('/select-date', [MobileBookingController::class, 'selectDate'])->name('select-date');
            Route::get('/select-time', [MobileBookingController::class, 'selectTime'])->name('select-time');
            Route::get('/details', [MobileBookingController::class, 'details'])->name('details');
            Route::post('/create', [MobileBookingController::class, 'create'])->name('create');
            
            // AJAX endpoints for booking
            Route::get('/available-slots', [MobileBookingController::class, 'getAvailableSlots'])->name('available-slots');
            Route::post('/check-time-slot', [MobileBookingController::class, 'checkTimeSlot'])->name('check-time-slot');
            Route::post('/validate', [MobileBookingController::class, 'validateBookingData'])->name('validate');
        });
    });
    
    // OTP and customer management (global booking endpoints)
    Route::prefix('booking')->name('booking.')->group(function () {
        Route::post('/otp/send', [MobileBookingController::class, 'sendOtp'])->name('otp.send');
        Route::post('/otp/verify', [MobileBookingController::class, 'verifyOtp'])->name('otp.verify');
        Route::post('/otp/resend', [MobileBookingController::class, 'resendOtp'])->name('otp.resend');
        Route::post('/customer-history', [MobileBookingController::class, 'getCustomerHistory'])->name('customer-history');
    });
    
    // Payment routes (if needed for mobile interface)
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/history', function() {
            return view('mobile.payments.history');
        })->name('history');
        
        Route::get('/organizations/{organization}/restaurants/{restaurant}/reservations/{reservation}/success', function($org, $rest, $res) {
            return view('mobile.payments.success', compact('org', 'rest', 'res'));
        })->name('success');
        
        Route::get('/organizations/{organization}/restaurants/{restaurant}/reservations/{reservation}/failed', function($org, $rest, $res) {
            return view('mobile.payments.failed', compact('org', 'rest', 'res'));
        })->name('failed');
    });
});

// Fallback route - redirect to mobile dashboard if no specific route
Route::get('/mobile', function () {
    return redirect()->route('mobile.dashboard');
})->middleware(['web', 'mobile.auth']);