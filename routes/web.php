<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\TestController;


Route::get('/', function () {
    return redirect()->route('mobile.login');
});

// Test routes
Route::prefix('test')->group(function () {
    Route::get('/auth', [TestController::class, 'testAuth'])->name('test.auth');
    Route::get('/token', [TestController::class, 'testToken'])->name('test.token');
    Route::get('/api', [TestController::class, 'testApi'])->name('test.api');
    
    // Test new implementations
    Route::get('/profile', [TestController::class, 'testProfile'])->name('test.profile');
    Route::get('/reservations', [TestController::class, 'testReservations'])->name('test.reservations');
    Route::get('/settings', [TestController::class, 'testSettings'])->name('test.settings');
});

// Clear all Laravel caches
Route::get('/clear-cache', function () {
    $commands = [
        'config:clear' => 'Configuration cache cleared',
        'cache:clear' => 'Application cache cleared',
        'route:clear' => 'Route cache cleared',
        'view:clear' => 'View cache cleared',
        'optimize:clear' => 'Optimization cache cleared'
    ];
    
    $results = [];
    foreach ($commands as $command => $message) {
        try {
            Artisan::call($command);
            $results[] = "✅ {$message}";
        } catch (Exception $e) {
            $results[] = "❌ Failed to clear {$command}: " . $e->getMessage();
        }
    }
    
    $currentUrl = config('services.partner.url');
    $results[] = "";
    $results[] = "🔧 Current API URL: {$currentUrl}";
    
    return response('<h1>🧹 Cache Clearing Results</h1><pre>' . implode("\n", $results) . '</pre>')
        ->header('Content-Type', 'text/html');
});

// Include mobile partner panel routes
require __DIR__.'/mobile.php';
