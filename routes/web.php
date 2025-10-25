<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;


Route::get('/', function () {
    return 'working';
});

// Test API connection
Route::get('/test-api', function () {
    $apiUrl = config('services.partner.url');
    $testUrl = $apiUrl . '/api/partner/test';
    
    try {
        $response = Http::timeout(10)->get($testUrl);
        
        return response()->json([
            'api_url' => $apiUrl,
            'test_url' => $testUrl,
            'status' => $response->status(),
            'success' => $response->successful(),
            'body' => $response->body()
        ]);
    } catch (Exception $e) {
        return response()->json([
            'api_url' => $apiUrl,
            'error' => $e->getMessage(),
            'success' => false
        ]);
    }
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
