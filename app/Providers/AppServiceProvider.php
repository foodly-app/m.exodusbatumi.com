<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\HttpClient;
use App\Services\TokenService;
use App\Services\AuthService;
use App\Services\DashboardService;
use App\Services\ReservationService;
use App\Services\BookingService;
use App\Services\OrganizationService;
use App\Services\RestaurantService;
use App\Services\ProfileService;
use App\Services\RestaurantSettingsService;

class AppServiceProvider extends ServiceProvider
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

        // Register services as singletons
        $this->app->singleton(AuthService::class, function ($app) {
            return new AuthService($app->make(HttpClient::class));
        });

        $this->app->singleton(DashboardService::class, function ($app) {
            return new DashboardService($app->make(HttpClient::class));
        });

        $this->app->singleton(ReservationService::class, function ($app) {
            return new ReservationService($app->make(HttpClient::class));
        });

        $this->app->singleton(BookingService::class, function ($app) {
            return new BookingService($app->make(HttpClient::class));
        });

        $this->app->singleton(OrganizationService::class, function ($app) {
            return new OrganizationService($app->make(HttpClient::class));
        });

        $this->app->singleton(RestaurantService::class, function ($app) {
            return new RestaurantService($app->make(HttpClient::class));
        });

        $this->app->singleton(ProfileService::class, function ($app) {
            return new ProfileService($app->make(HttpClient::class));
        });

        $this->app->singleton(RestaurantSettingsService::class, function ($app) {
            return new RestaurantSettingsService($app->make(HttpClient::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
