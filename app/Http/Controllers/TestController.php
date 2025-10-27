<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Services\HttpClient;
use Exception;
use Illuminate\Support\Facades\Http;

class TestController extends Controller
{
    public function __construct(
        private readonly AuthService $authService,
        private readonly HttpClient $httpClient
    ) {}

    /**
     * Test authentication with predefined credentials
     */
    public function testAuth()
    {
        $credentials = [
            'email' => 'manager@exodusrestaurant.com',
            'password' => 'Manager_321'
        ];

        $apiUrl = config('services.partner.url');
        $loginUrl = $apiUrl . '/api/partner/login';

        try {
            // Direct API call to test
            $response = Http::timeout(10)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ])
                ->post($loginUrl, $credentials);

            $responseBody = $response->json();

            $html = $this->buildHtmlHeader('🔐 Authentication Test');
            
            $html .= '<h2>📧 Test Credentials:</h2>';
            $html .= '<ul>';
            $html .= '<li><strong>Email:</strong> ' . $credentials['email'] . '</li>';
            $html .= '<li><strong>Password:</strong> ' . $credentials['password'] . '</li>';
            $html .= '</ul>';
            $html .= '<hr>';

            $html .= '<h2>🌐 API Info:</h2>';
            $html .= '<p><strong>API URL:</strong> ' . $apiUrl . '</p>';
            $html .= '<p><strong>Login Endpoint:</strong> ' . $loginUrl . '</p>';
            $html .= '<hr>';

            $html .= '<h2>📊 Response Status:</h2>';
            $statusClass = $response->successful() ? 'success' : 'error';
            $html .= '<p class="' . $statusClass . '"><strong>HTTP Status:</strong> ' . $response->status() . '</p>';
            $html .= '<p class="' . $statusClass . '"><strong>Success:</strong> ' . ($response->successful() ? '✅ YES' : '❌ NO') . '</p>';
            $html .= '<hr>';

            $html .= '<h2>📦 Response Body:</h2>';
            $html .= '<pre>' . json_encode($responseBody, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</pre>';

            if ($response->successful() && isset($responseBody['token'])) {
                $html .= '<hr>';
                $html .= '<h2 class="success">✅ Authentication Successful!</h2>';
                $html .= '<div class="success-box">';
                $html .= '<p><strong>Token:</strong> <code>' . substr($responseBody['token'], 0, 50) . '...</code></p>';
                
                if (isset($responseBody['user'])) {
                    $user = $responseBody['user'];
                    $html .= '<h3>👤 User Information:</h3>';
                    $html .= '<ul>';
                    $html .= '<li><strong>ID:</strong> ' . $user['id'] . '</li>';
                    $html .= '<li><strong>Name:</strong> ' . $user['name'] . '</li>';
                    $html .= '<li><strong>Email:</strong> ' . $user['email'] . '</li>';
                    $html .= '<li><strong>Type:</strong> ' . $user['type'] . '</li>';
                    $html .= '<li><strong>Status:</strong> <span class="badge-' . $user['status'] . '">' . $user['status'] . '</span></li>';
                    $html .= '</ul>';

                    if (isset($user['roles']) && count($user['roles']) > 0) {
                        $html .= '<h3>🔐 Roles:</h3>';
                        $html .= '<ul>';
                        foreach ($user['roles'] as $role) {
                            $html .= '<li>' . $role['name'] . '</li>';
                        }
                        $html .= '</ul>';
                    }

                    if (isset($user['organizations']) && count($user['organizations']) > 0) {
                        $html .= '<h3>🏢 Organizations:</h3>';
                        foreach ($user['organizations'] as $org) {
                            $html .= '<div class="org-card">';
                            $html .= '<strong>' . $org['name'] . '</strong><br>';
                            $html .= '<small>ID: ' . $org['id'] . ' | Status: ' . $org['status'] . '</small>';
                            $html .= '</div>';
                        }
                    }
                }
                $html .= '</div>';

                // Test using AuthService
                $html .= '<hr>';
                $html .= '<h2>🔧 AuthService Test:</h2>';
                try {
                    $serviceResponse = $this->authService->login($credentials);
                    $html .= '<pre>' . json_encode($serviceResponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
                    
                    if ($serviceResponse['success']) {
                        $html .= '<p class="success">✅ AuthService login successful!</p>';
                        $html .= '<p><strong>Session Token:</strong> ' . (session('partner_token') ? 'Stored ✅' : 'Not stored ❌') . '</p>';
                        $html .= '<p><strong>Session User:</strong> ' . (session('partner_user') ? 'Stored ✅' : 'Not stored ❌') . '</p>';
                    }
                } catch (Exception $e) {
                    $html .= '<p class="error">❌ AuthService Error: ' . $e->getMessage() . '</p>';
                }

            } else {
                $html .= '<hr>';
                $html .= '<h2 class="error">❌ Authentication Failed!</h2>';
                if (isset($responseBody['message'])) {
                    $html .= '<p class="error"><strong>Error:</strong> ' . $responseBody['message'] . '</p>';
                }
            }

            $html .= $this->buildHtmlFooter();
            return response($html)->header('Content-Type', 'text/html');

        } catch (Exception $e) {
            $html = $this->buildHtmlHeader('🔐 Authentication Test - Error');
            $html .= '<div class="error-box">';
            $html .= '<h2>❌ Exception Occurred</h2>';
            $html .= '<p><strong>Error:</strong> ' . $e->getMessage() . '</p>';
            $html .= '<p><strong>File:</strong> ' . $e->getFile() . ':' . $e->getLine() . '</p>';
            $html .= '</div>';
            $html .= $this->buildHtmlFooter();
            return response($html, 500)->header('Content-Type', 'text/html');
        }
    }

    /**
     * Test token validation
     */
    public function testToken()
    {
        $html = $this->buildHtmlHeader('🎫 Token Test');

        $sessionToken = session('partner_token');
        $sessionUser = session('partner_user');

        $html .= '<h2>📋 Session Information:</h2>';
        $html .= '<p><strong>Session Token:</strong> ' . ($sessionToken ? '✅ Present' : '❌ Not found') . '</p>';
        
        if ($sessionToken) {
            $html .= '<div class="token-box">';
            $html .= '<code>' . $sessionToken . '</code>';
            $html .= '</div>';
        }

        $html .= '<p><strong>Session User:</strong> ' . ($sessionUser ? '✅ Present' : '❌ Not found') . '</p>';
        
        if ($sessionUser) {
            $html .= '<pre>' . json_encode($sessionUser, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
        }

        $html .= '<hr>';

        // Test token with API call
        if ($sessionToken) {
            $html .= '<h2>🔍 Token Validation Test:</h2>';
            
            try {
                $apiUrl = config('services.partner.url');
                $meUrl = $apiUrl . '/api/partner/me';

                $response = Http::timeout(10)
                    ->withToken($sessionToken)
                    ->withHeaders([
                        'Accept' => 'application/json',
                    ])
                    ->get($meUrl);

                $html .= '<p><strong>Endpoint:</strong> ' . $meUrl . '</p>';
                $html .= '<p><strong>HTTP Status:</strong> <span class="' . ($response->successful() ? 'success' : 'error') . '">' . $response->status() . '</span></p>';
                
                $html .= '<h3>Response:</h3>';
                $html .= '<pre>' . json_encode($response->json(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';

                if ($response->successful()) {
                    $html .= '<p class="success">✅ Token is valid!</p>';
                } else {
                    $html .= '<p class="error">❌ Token validation failed!</p>';
                }

            } catch (Exception $e) {
                $html .= '<p class="error">❌ Error: ' . $e->getMessage() . '</p>';
            }
        } else {
            $html .= '<div class="warning-box">';
            $html .= '<p>⚠️ No token found in session. Please login first.</p>';
            $html .= '<a href="/test/auth" class="btn">Test Authentication</a>';
            $html .= '</div>';
        }

        $html .= $this->buildHtmlFooter();
        return response($html)->header('Content-Type', 'text/html');
    }

    /**
     * Test API connection
     */
    public function testApi()
    {
        $html = $this->buildHtmlHeader('🌐 API Connection Test');

        $apiUrl = config('services.partner.url');
        $timeout = config('services.partner.timeout', 30);

        $html .= '<h2>⚙️ Configuration:</h2>';
        $html .= '<ul>';
        $html .= '<li><strong>API URL:</strong> ' . $apiUrl . '</li>';
        $html .= '<li><strong>Timeout:</strong> ' . $timeout . 's</li>';
        $html .= '</ul>';
        $html .= '<hr>';

        // Test 1: Basic connectivity
        $html .= '<h2>1️⃣ Basic Connectivity Test:</h2>';
        try {
            $start = microtime(true);
            $response = Http::timeout(10)->get($apiUrl);
            $duration = round((microtime(true) - $start) * 1000, 2);

            $html .= '<p><strong>Status:</strong> <span class="' . ($response->successful() ? 'success' : 'error') . '">' . $response->status() . '</span></p>';
            $html .= '<p><strong>Response Time:</strong> ' . $duration . 'ms</p>';
            $html .= '<p class="' . ($response->successful() ? 'success' : 'error') . '">' . ($response->successful() ? '✅ API is reachable' : '❌ API is not reachable') . '</p>';
        } catch (Exception $e) {
            $html .= '<p class="error">❌ Connection failed: ' . $e->getMessage() . '</p>';
        }

        $html .= '<hr>';

        // Test 2: Health endpoint (if exists)
        $html .= '<h2>2️⃣ Health Check:</h2>';
        try {
            $healthUrl = $apiUrl . '/health';
            $response = Http::timeout(10)->get($healthUrl);
            
            $html .= '<p><strong>Endpoint:</strong> ' . $healthUrl . '</p>';
            $html .= '<p><strong>Status:</strong> <span class="' . ($response->successful() ? 'success' : 'error') . '">' . $response->status() . '</span></p>';
            
            if ($response->successful()) {
                $html .= '<pre>' . json_encode($response->json(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
            }
        } catch (Exception $e) {
            $html .= '<p class="error">❌ Health check failed: ' . $e->getMessage() . '</p>';
        }

        $html .= '<hr>';

        // Test 3: Partner API endpoints
        $html .= '<h2>3️⃣ Partner API Endpoints Test:</h2>';
        
        $endpoints = [
            '/api/partner/login' => 'POST',
            '/api/partner/me' => 'GET (requires auth)',
            '/api/partner/initial-dashboard' => 'GET (requires auth)',
        ];

        $html .= '<table class="endpoint-table">';
        $html .= '<thead><tr><th>Endpoint</th><th>Method</th><th>Status</th></tr></thead>';
        $html .= '<tbody>';

        foreach ($endpoints as $endpoint => $method) {
            $fullUrl = $apiUrl . $endpoint;
            $html .= '<tr>';
            $html .= '<td><code>' . $endpoint . '</code></td>';
            $html .= '<td>' . $method . '</td>';
            
            try {
                // Just check if endpoint responds (not calling with actual data)
                $response = Http::timeout(5)->get($fullUrl);
                $status = $response->status();
                $statusClass = in_array($status, [200, 401, 422]) ? 'success' : 'warning';
                $html .= '<td class="' . $statusClass . '">' . $status . ' ' . ($status == 401 ? '(Auth required ✅)' : '') . '</td>';
            } catch (Exception $e) {
                $html .= '<td class="error">Error</td>';
            }
            
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';

        $html .= $this->buildHtmlFooter();
        return response($html)->header('Content-Type', 'text/html');
    }

    /**
     * Build HTML header
     */
    private function buildHtmlHeader($title): string
    {
        return '<html><head><title>' . $title . '</title><style>
            body { 
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                padding: 20px;
                background: #f5f5f5;
                color: #333;
            }
            .container {
                max-width: 1200px;
                margin: 0 auto;
                background: white;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            h1 { color: #2c3e50; border-bottom: 3px solid #3498db; padding-bottom: 10px; }
            h2 { color: #34495e; margin-top: 30px; }
            h3 { color: #555; }
            .success { color: #27ae60; font-weight: bold; }
            .error { color: #e74c3c; font-weight: bold; }
            .warning { color: #f39c12; font-weight: bold; }
            pre { 
                background: #2c3e50;
                color: #ecf0f1;
                padding: 15px;
                border-radius: 5px;
                overflow-x: auto;
                font-size: 13px;
            }
            code {
                background: #ecf0f1;
                padding: 2px 6px;
                border-radius: 3px;
                font-family: "Monaco", "Courier New", monospace;
                font-size: 13px;
            }
            .success-box {
                background: #d4edda;
                border: 1px solid #c3e6cb;
                border-radius: 5px;
                padding: 15px;
                margin: 10px 0;
            }
            .error-box {
                background: #f8d7da;
                border: 1px solid #f5c6cb;
                border-radius: 5px;
                padding: 15px;
                margin: 10px 0;
            }
            .warning-box {
                background: #fff3cd;
                border: 1px solid #ffeaa7;
                border-radius: 5px;
                padding: 15px;
                margin: 10px 0;
            }
            .org-card {
                background: #f8f9fa;
                padding: 10px;
                margin: 5px 0;
                border-left: 3px solid #3498db;
                border-radius: 3px;
            }
            .token-box {
                background: #2c3e50;
                color: #ecf0f1;
                padding: 10px;
                border-radius: 5px;
                word-break: break-all;
                font-family: monospace;
                margin: 10px 0;
            }
            .badge-active { 
                background: #27ae60;
                color: white;
                padding: 2px 8px;
                border-radius: 3px;
                font-size: 12px;
            }
            .endpoint-table {
                width: 100%;
                border-collapse: collapse;
                margin: 15px 0;
            }
            .endpoint-table th,
            .endpoint-table td {
                border: 1px solid #ddd;
                padding: 12px;
                text-align: left;
            }
            .endpoint-table th {
                background: #3498db;
                color: white;
            }
            .endpoint-table tr:nth-child(even) {
                background: #f8f9fa;
            }
            .btn {
                display: inline-block;
                padding: 10px 20px;
                background: #3498db;
                color: white;
                text-decoration: none;
                border-radius: 5px;
                margin: 10px 5px;
            }
            .btn:hover {
                background: #2980b9;
            }
            ul { line-height: 1.8; }
            hr {
                border: none;
                border-top: 1px solid #ddd;
                margin: 30px 0;
            }
        </style></head><body><div class="container"><h1>' . $title . '</h1>';
    }

    /**
     * Build HTML footer
     */
    private function buildHtmlFooter(): string
    {
        return '<hr>
        <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 5px;">
            <h3>🧪 Quick Navigation:</h3>
            <a href="/test/auth" class="btn">🔐 Test Auth</a>
            <a href="/test/token" class="btn">🎫 Test Token</a>
            <a href="/test/api" class="btn">🌐 Test API</a>
            <a href="/test/profile" class="btn">👤 Test Profile</a>
            <a href="/test/reservations" class="btn">📋 Test Reservations</a>
            <a href="/test/settings" class="btn">⚙️ Test Settings</a>
            <a href="/mobile/login" class="btn">📱 Mobile Login</a>
        </div>
        </div></body></html>';
    }
    
    /**
     * Test Profile API
     */
    public function testProfile()
    {
        $html = $this->buildHtmlHeader('👤 Profile API Test');
        
        $sessionToken = session('partner_token');
        $sessionUser = session('partner_user');
        
        if (!$sessionToken) {
            $html .= '<div class="warning-box">';
            $html .= '<p>⚠️ No token found. Please login first.</p>';
            $html .= '<a href="/test/auth" class="btn">Test Authentication</a>';
            $html .= '</div>';
            $html .= $this->buildHtmlFooter();
            return response($html)->header('Content-Type', 'text/html');
        }
        
        $html .= '<h2>📋 Session User:</h2>';
        if ($sessionUser) {
            $html .= '<pre>' . json_encode($sessionUser, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
        } else {
            $html .= '<p class="error">❌ No user in session</p>';
        }
        
        $html .= '<hr>';
        
        // Test GET /api/partner/me
        $html .= '<h2>1️⃣ Test GET /api/partner/me:</h2>';
        try {
            $apiUrl = config('services.partner.url');
            $response = Http::timeout(10)
                ->withToken($sessionToken)
                ->get($apiUrl . '/api/partner/me');
            
            $html .= '<p><strong>Status:</strong> <span class="' . ($response->successful() ? 'success' : 'error') . '">' . $response->status() . '</span></p>';
            $html .= '<pre>' . json_encode($response->json(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
            
            if ($response->successful()) {
                $html .= '<p class="success">✅ Profile API working!</p>';
            }
        } catch (Exception $e) {
            $html .= '<p class="error">❌ Error: ' . $e->getMessage() . '</p>';
        }
        
        $html .= '<hr>';
        $html .= '<h2>📝 Available Profile Endpoints:</h2>';
        $html .= '<ul>';
        $html .= '<li>GET <code>/api/partner/me</code> - Get profile</li>';
        $html .= '<li>PUT <code>/api/partner/profile</code> - Update profile</li>';
        $html .= '<li>POST <code>/api/partner/profile/avatar</code> - Upload avatar</li>';
        $html .= '<li>DELETE <code>/api/partner/profile/avatar</code> - Delete avatar</li>';
        $html .= '<li>PUT <code>/api/partner/profile/password</code> - Change password</li>';
        $html .= '</ul>';
        
        $html .= $this->buildHtmlFooter();
        return response($html)->header('Content-Type', 'text/html');
    }
    
    /**
     * Test Reservations API
     */
    public function testReservations()
    {
        $html = $this->buildHtmlHeader('📋 Reservations API Test');
        
        $sessionToken = session('partner_token');
        
        if (!$sessionToken) {
            $html .= '<div class="warning-box">';
            $html .= '<p>⚠️ No token found. Please login first.</p>';
            $html .= '<a href="/test/auth" class="btn">Test Authentication</a>';
            $html .= '</div>';
            $html .= $this->buildHtmlFooter();
            return response($html)->header('Content-Type', 'text/html');
        }
        
        // Get dashboard data to find restaurant
        $html .= '<h2>1️⃣ Getting Restaurant from initial-dashboard:</h2>';
        try {
            $apiUrl = config('services.partner.url');
            $dashboardResponse = Http::timeout(10)
                ->withToken($sessionToken)
                ->get($apiUrl . '/api/partner/initial-dashboard');
            
            $dashboardData = $dashboardResponse->json();
            
            if (isset($dashboardData['selected_restaurant'])) {
                $restaurant = $dashboardData['selected_restaurant'];
                $html .= '<p class="success">✅ Found Restaurant: <strong>' . $restaurant['name'] . '</strong> (ID: ' . $restaurant['id'] . ')</p>';
                
                $html .= '<hr>';
                $html .= '<h2>2️⃣ Test Reservations List:</h2>';
                
                // Test reservations endpoint
                $reservationsUrl = $apiUrl . '/api/partner/restaurants/' . $restaurant['id'] . '/reservations';
                $reservationsResponse = Http::timeout(10)
                    ->withToken($sessionToken)
                    ->get($reservationsUrl);
                
                $html .= '<p><strong>Endpoint:</strong> <code>' . $reservationsUrl . '</code></p>';
                $html .= '<p><strong>Status:</strong> <span class="' . ($reservationsResponse->successful() ? 'success' : 'error') . '">' . $reservationsResponse->status() . '</span></p>';
                $html .= '<pre>' . json_encode($reservationsResponse->json(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
                
            } else {
                $html .= '<p class="error">❌ No selected restaurant found in dashboard</p>';
            }
            
        } catch (Exception $e) {
            $html .= '<p class="error">❌ Error: ' . $e->getMessage() . '</p>';
        }
        
        $html .= '<hr>';
        $html .= '<h2>📝 Available Reservation Endpoints:</h2>';
        $html .= '<ul>';
        $html .= '<li>GET <code>/api/partner/restaurants/{id}/reservations</code> - List reservations</li>';
        $html .= '<li>GET <code>/api/partner/restaurants/{id}/reservations/{reservation_id}</code> - Get single reservation</li>';
        $html .= '<li>POST <code>/api/partner/reservations/{id}/confirm</code> - Confirm reservation</li>';
        $html .= '<li>POST <code>/api/partner/reservations/{id}/cancel</code> - Cancel reservation</li>';
        $html .= '</ul>';
        
        $html .= $this->buildHtmlFooter();
        return response($html)->header('Content-Type', 'text/html');
    }
    
    /**
     * Test Settings API
     */
    public function testSettings()
    {
        $html = $this->buildHtmlHeader('⚙️ Settings API Test');
        
        $sessionToken = session('partner_token');
        
        if (!$sessionToken) {
            $html .= '<div class="warning-box">';
            $html .= '<p>⚠️ No token found. Please login first.</p>';
            $html .= '<a href="/test/auth" class="btn">Test Authentication</a>';
            $html .= '</div>';
            $html .= $this->buildHtmlFooter();
            return response($html)->header('Content-Type', 'text/html');
        }
        
        // Get dashboard data to find restaurant
        $html .= '<h2>1️⃣ Getting Restaurant from initial-dashboard:</h2>';
        try {
            $apiUrl = config('services.partner.url');
            $dashboardResponse = Http::timeout(10)
                ->withToken($sessionToken)
                ->get($apiUrl . '/api/partner/initial-dashboard');
            
            $dashboardData = $dashboardResponse->json();
            
            if (isset($dashboardData['selected_restaurant'])) {
                $restaurant = $dashboardData['selected_restaurant'];
                $html .= '<p class="success">✅ Found Restaurant: <strong>' . $restaurant['name'] . '</strong> (ID: ' . $restaurant['id'] . ')</p>';
                
                $html .= '<hr>';
                $html .= '<h2>2️⃣ Test Settings Endpoints:</h2>';
                
                // Test settings endpoint
                $settingsUrl = $apiUrl . '/api/partner/restaurants/' . $restaurant['id'] . '/settings';
                $settingsResponse = Http::timeout(10)
                    ->withToken($sessionToken)
                    ->get($settingsUrl);
                
                $html .= '<h3>Settings:</h3>';
                $html .= '<p><strong>Endpoint:</strong> <code>' . $settingsUrl . '</code></p>';
                $html .= '<p><strong>Status:</strong> <span class="' . ($settingsResponse->successful() ? 'success' : 'error') . '">' . $settingsResponse->status() . '</span></p>';
                $html .= '<pre>' . json_encode($settingsResponse->json(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
                
                // Test working hours endpoint
                $hoursUrl = $apiUrl . '/api/partner/restaurants/' . $restaurant['id'] . '/working-hours';
                $hoursResponse = Http::timeout(10)
                    ->withToken($sessionToken)
                    ->get($hoursUrl);
                
                $html .= '<h3>Working Hours:</h3>';
                $html .= '<p><strong>Endpoint:</strong> <code>' . $hoursUrl . '</code></p>';
                $html .= '<p><strong>Status:</strong> <span class="' . ($hoursResponse->successful() ? 'success' : 'error') . '">' . $hoursResponse->status() . '</span></p>';
                $html .= '<pre>' . json_encode($hoursResponse->json(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
                
            } else {
                $html .= '<p class="error">❌ No selected restaurant found in dashboard</p>';
            }
            
        } catch (Exception $e) {
            $html .= '<p class="error">❌ Error: ' . $e->getMessage() . '</p>';
        }
        
        $html .= '<hr>';
        $html .= '<h2>📝 Available Settings Endpoints:</h2>';
        $html .= '<ul>';
        $html .= '<li>GET <code>/api/partner/restaurants/{id}/settings</code> - Get settings</li>';
        $html .= '<li>PUT <code>/api/partner/restaurants/{id}/settings</code> - Update settings</li>';
        $html .= '<li>GET <code>/api/partner/restaurants/{id}/working-hours</code> - Get working hours</li>';
        $html .= '<li>PUT <code>/api/partner/restaurants/{id}/working-hours</code> - Update working hours</li>';
        $html .= '</ul>';
        
        $html .= $this->buildHtmlFooter();
        return response($html)->header('Content-Type', 'text/html');
    }
}
