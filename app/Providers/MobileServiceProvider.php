<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\HttpClient;
use App\Services\TokenService;
use App\Services\MobileAuthService;
use App\Services\MobileDashboardService;
use App\Services\MobileReservationService;
use App\Services\MobileBookingService;
use App\Services\MobileOrganizationService;
use App\Services\MobileRestaurantService;

class MobileServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register TokenService as singleton
        $this->app->singleton(TokenService::class, function ($app) {
            return new TokenService();
        });

        // Register HttpClient as singleton
        $this->app->singleton(HttpClient::class, function ($app) {
            return new HttpClient($app->make(TokenService::class));
        });

        // Register Mobile services as singletons
        $this->app->singleton(MobileAuthService::class, function ($app) {
            return new MobileAuthService($app->make(HttpClient::class));
        });

        $this->app->singleton(MobileDashboardService::class, function ($app) {
            return new MobileDashboardService($app->make(HttpClient::class));
        });

        $this->app->singleton(MobileReservationService::class, function ($app) {
            return new MobileReservationService($app->make(HttpClient::class));
        });

        $this->app->singleton(MobileBookingService::class, function ($app) {
            return new MobileBookingService($app->make(HttpClient::class));
        });

        $this->app->singleton(MobileOrganizationService::class, function ($app) {
            return new MobileOrganizationService($app->make(HttpClient::class));
        });

        $this->app->singleton(MobileRestaurantService::class, function ($app) {
            return new MobileRestaurantService($app->make(HttpClient::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // You can add any booting logic here if needed
    }
}