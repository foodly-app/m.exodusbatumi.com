<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#3232ff">
    <title>@yield('title', 'FOODLY - მობილური პანელი')</title>
    
    <!-- Foodly Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo/RedDit.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo/RedDit.png') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Flag Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.2.3/css/flag-icons.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Georgian:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --foodly-primary: #3232ff;
            --foodly-dark: #070738;
            --foodly-red: #ff4500;
            --foodly-light: #f5f9fc;
        }
        
        * {
            font-family: 'Noto Sans Georgian', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        body {
            background-color: var(--foodly-light);
            min-height: 100vh;
            padding-bottom: 80px; /* Space for bottom nav */
            overflow-x: hidden;
        }
        
        /* Mobile-First Navigation */
        .mobile-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            background: linear-gradient(135deg, var(--foodly-primary) 0%, var(--foodly-dark) 100%);
            box-shadow: 0 2px 20px rgba(7, 7, 56, 0.2);
            height: 60px;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 100%;
            padding: 0 1rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header-logo {
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        
        .header-logo img {
            height: 36px;
            width: auto;
            transition: transform 0.2s ease;
        }
        
        .header-logo:active img {
            transform: scale(0.95);
        }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .header-icon {
            color: white;
            text-decoration: none;
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .header-icon:hover,
        .header-icon:active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
        }
        
        .header-actions .btn-light {
            background: rgba(255, 255, 255, 0.95);
            border: none;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            transition: all 0.2s ease;
        }
        
        .header-actions .btn-light:hover,
        .header-actions .btn-light:active {
            background: white;
            transform: scale(0.95);
        }
        
        /* Content Area */
        .main-content {
            margin-top: 60px;
            padding: 1.5rem 1rem;
            padding-bottom: 80px;
            min-height: calc(100vh - 60px - 65px);
        }
        
        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1020;
            background: white;
            box-shadow: 0 -2px 15px rgba(0, 0, 0, 0.08);
            border-top: 1px solid rgba(50, 50, 255, 0.1);
            display: flex;
            justify-content: space-around;
            padding: 0.5rem 0.25rem;
            height: 65px;
        }
        
        .bottom-nav .nav-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            text-decoration: none;
            font-size: 0.7rem;
            font-weight: 500;
            transition: all 0.2s ease;
            border-radius: 12px;
            padding: 0.5rem;
            position: relative;
        }
        
        .bottom-nav .nav-item i {
            font-size: 1.4rem;
            margin-bottom: 0.25rem;
            transition: all 0.2s ease;
        }
        
        .bottom-nav .nav-item span {
            transition: all 0.2s ease;
        }
        
        .bottom-nav .nav-item.active {
            color: var(--foodly-primary);
        }
        
        .bottom-nav .nav-item.active i {
            transform: scale(1.1);
        }
        
        .bottom-nav .nav-item:active {
            transform: scale(0.92);
        }
        
        .bottom-nav .nav-item-primary {
            background: linear-gradient(135deg, var(--foodly-primary) 0%, var(--foodly-dark) 100%);
            color: white;
            border-radius: 50%;
            width: 56px;
            height: 56px;
            margin-top: -28px;
            box-shadow: 0 4px 12px rgba(50, 50, 255, 0.3);
            flex: 0 0 56px;
        }
        
        .bottom-nav .nav-item-primary i {
            font-size: 1.8rem;
            margin-bottom: 0;
        }
        
        .bottom-nav .nav-item-primary span {
            display: none;
        }
        
        .bottom-nav .nav-item-primary:active {
            transform: scale(0.9);
        }
        
        /* Cards Mobile Optimized */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            background: white;
            margin-bottom: 1rem;
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--foodly-primary) 0%, var(--foodly-dark) 100%);
            color: white;
            border-radius: 16px 16px 0 0 !important;
            padding: 1rem;
            font-weight: 600;
            font-size: 1rem;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        /* Buttons Mobile Optimized */
        .btn {
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            border: none;
        }
        
        .btn-primary {
            background: var(--foodly-primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--foodly-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(50, 50, 255, 0.3);
        }
        
        .btn-danger {
            background: var(--foodly-red);
        }
        
        .btn-lg {
            padding: 1rem 2rem;
            font-size: 1rem;
            width: 100%;
        }
        
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
        }
        
        /* Form Controls Mobile */
        .form-control, .form-select {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            padding: 0.875rem 1rem;
            font-size: 1rem;
            transition: all 0.2s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--foodly-primary);
            box-shadow: 0 0 0 0.2rem rgba(50, 50, 255, 0.15);
        }
        
        /* Country Code Selector */
        .country-code-select {
            max-width: 120px;
            padding-left: 2.5rem;
            border-right: none;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            background-color: #f8f9fa;
            background-repeat: no-repeat;
            background-position: 0.75rem center;
            background-size: 1.5em 1.5em;
        }
        
        .country-code-select option {
            padding-left: 2rem;
            background-repeat: no-repeat;
            background-position: 0.5rem center;
            background-size: 1.2em 1.2em;
        }
        
        /* Generate flag backgrounds for each country */
        .country-code-select option[data-flag="fi-ge"] { background-image: url('https://flagcdn.com/ge.svg'); }
        .country-code-select option[data-flag="fi-us"] { background-image: url('https://flagcdn.com/us.svg'); }
        .country-code-select option[data-flag="fi-gb"] { background-image: url('https://flagcdn.com/gb.svg'); }
        .country-code-select option[data-flag="fi-ru"] { background-image: url('https://flagcdn.com/ru.svg'); }
        .country-code-select option[data-flag="fi-tr"] { background-image: url('https://flagcdn.com/tr.svg'); }
        .country-code-select option[data-flag="fi-es"] { background-image: url('https://flagcdn.com/es.svg'); }
        .country-code-select option[data-flag="fi-fr"] { background-image: url('https://flagcdn.com/fr.svg'); }
        .country-code-select option[data-flag="fi-de"] { background-image: url('https://flagcdn.com/de.svg'); }
        .country-code-select option[data-flag="fi-it"] { background-image: url('https://flagcdn.com/it.svg'); }
        .country-code-select option[data-flag="fi-pl"] { background-image: url('https://flagcdn.com/pl.svg'); }
        .country-code-select option[data-flag="fi-ua"] { background-image: url('https://flagcdn.com/ua.svg'); }
        .country-code-select option[data-flag="fi-pt"] { background-image: url('https://flagcdn.com/pt.svg'); }
        .country-code-select option[data-flag="fi-nl"] { background-image: url('https://flagcdn.com/nl.svg'); }
        .country-code-select option[data-flag="fi-sa"] { background-image: url('https://flagcdn.com/sa.svg'); }
        .country-code-select option[data-flag="fi-il"] { background-image: url('https://flagcdn.com/il.svg'); }
        
        .country-code-select:focus {
            background-color: white;
        }
        
        #country_code + .form-control {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            border-left: none;
        }
        
        .form-label {
            font-weight: 600;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            color: #495057;
        }
        
        /* Badges */
        .badge {
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.75rem;
        }
        
        .badge.bg-pending { background-color: #ffc107 !important; }
        .badge.bg-confirmed { background-color: #17a2b8 !important; }
        .badge.bg-awaiting_payment { background-color: #fd7e14 !important; }
        .badge.bg-paid { background-color: #28a745 !important; }
        .badge.bg-completed { background-color: #6c757d !important; }
        .badge.bg-cancelled { background-color: #dc3545 !important; }
        .badge.bg-no_show { background-color: #6c757d !important; }
        
        /* List Items */
        .list-group-item {
            border-left: 3px solid transparent;
            transition: all 0.2s ease;
        }
        
        .list-group-item:active {
            transform: scale(0.98);
            background-color: #f5f9fc;
        }
        
        .list-group-item-action:hover,
        .list-group-item-action:focus {
            background-color: #f5f9fc;
            border-left-color: #3232ff;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
        
        .container-main {
            padding: 2rem 0;
        }
        
        .footer {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 2rem 0;
            margin-top: 3rem;
            border-top: 1px solid rgba(0,0,0,0.1);
        }
        
        .nav-pills .nav-link {
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            margin: 0 0.25rem;
            transition: all 0.3s ease;
        }
        
        .nav-pills .nav-link.active {
            background: #3232ff;
        }
        
        .table {
            border-radius: 10px;
            overflow: hidden;
        }
        
        .table thead {
            background: linear-gradient(135deg, #3232ff 0%, #070738 100%);
            color: white;
        }

        /* Mobile-specific Utilities */
        .main-content {
            padding: 1rem;
            max-width: 100%;
        }

        /* Alerts Mobile Optimization */
        .alert {
            border-radius: 12px;
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 1rem;
        }

        .alert i {
            margin-right: 8px;
        }

        /* Footer Mobile */
        .footer {
            background: #f5f9fc;
            border-top: 1px solid rgba(50, 50, 255, 0.1);
            margin-top: 3rem;
            padding-bottom: 80px; /* Space for bottom nav */
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .main-content {
                padding: 0.75rem;
            }
            
            .card-header {
                padding: 1rem;
                font-size: 1.1rem;
            }

            .card-body {
                padding: 1rem;
            }

            h1, .h1 { font-size: 1.75rem; }
            h2, .h2 { font-size: 1.5rem; }
            h3, .h3 { font-size: 1.25rem; }
            h4, .h4 { font-size: 1.1rem; }

            .btn-lg {
                padding: 14px 24px;
                font-size: 16px;
            }

            .table {
                font-size: 14px;
            }

            .badge {
                font-size: 11px;
                padding: 4px 8px;
            }
        }

        /* Hide bottom nav on desktop */
        @media (min-width: 769px) {
            .bottom-nav {
                display: none;
            }

            body {
                padding-bottom: 0;
            }

            .footer {
                padding-bottom: 2rem;
            }

            .main-content {
                max-width: 1200px;
                margin: 0 auto;
                margin-top: 80px;
                padding: 2rem 2rem 3rem;
            }

            .mobile-header {
                padding: 1rem 2rem;
                height: 70px;
            }

            .header-logo img {
                height: 42px;
            }

            .card {
                margin-bottom: 1.5rem;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Mobile Header -->
    <header class="mobile-header">
        <div class="header-content">
            <a href="{{ route('mobile.dashboard') }}" class="header-logo">
                <img src="{{ asset('images/logo/logo-h-white.png') }}" alt="Foodly" height="32">
            </a>
            @auth
            <div class="header-actions">
                <button onclick="requestNotificationPermission()" class="header-icon" id="notification-bell" title="შეტყობინებების ჩართვა">
                    <i class="bi bi-bell" id="notification-icon" style="font-size: 20px;"></i>
                </button>
                <a href="{{ route('mobile.profile') }}" class="header-icon">
                    <i class="bi bi-person-circle" style="font-size: 24px;"></i>
                </a>
            </div>
            @else
            <div class="header-actions">
                <a href="{{ route('mobile.login') }}" class="btn btn-sm btn-light">
                    <i class="bi bi-box-arrow-in-right"></i> შესვლა
                </a>
            </div>
            @endauth
        </div>
    </header>

    <!-- Main Content -->
    <div class="main-content">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i>
            <strong>შეცდომა!</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </div>

    <!-- Bottom Navigation (Mobile Only) -->
    @auth
    <nav class="bottom-nav">
        <a href="{{ route('mobile.dashboard') }}" class="nav-item {{ request()->routeIs('mobile.dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door"></i>
            <span>მთავარი</span>
        </a>
        <a href="{{ route('mobile.reservations.index') }}" class="nav-item {{ request()->routeIs('mobile.reservations.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check"></i>
            <span>რეზერვაციები</span>
        </a>
        <a href="{{ route('mobile.booking.restaurants') }}" class="nav-item nav-item-primary {{ request()->routeIs('mobile.booking.*') ? 'active' : '' }}">
            <i class="bi bi-plus-lg"></i>
        </a>
        <a href="{{ route('mobile.payments.history') }}" class="nav-item {{ request()->routeIs('mobile.payments.*') ? 'active' : '' }}">
            <i class="bi bi-credit-card"></i>
            <span>გადახდები</span>
        </a>
        <a href="{{ route('mobile.profile') }}" class="nav-item {{ request()->routeIs('mobile.profile') ? 'active' : '' }}">
            <i class="bi bi-person"></i>
            <span>პროფილი</span>
        </a>
    </nav>
    @endauth



    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Pusher Beams SDK -->
    <script src="https://js.pusher.com/beams/1.0/push-notifications-cdn.js"></script>
    
    @auth
    <script>
        // Initialize Pusher Beams for Push Notifications
        document.addEventListener('DOMContentLoaded', function() {
            const userId = '{{ auth()->id() }}';
            const beamsInstanceId = '{{ config('services.pusher.beams.instance_id') }}';
            
            if (!beamsInstanceId || beamsInstanceId === '') {
                console.error('Pusher Beams instance ID not configured');
                return;
            }

            const beamsClient = new PusherPushNotifications.Client({
                instanceId: beamsInstanceId,
            });

            // Request notification permission
            window.requestNotificationPermission = function() {
                beamsClient.start()
                    .then(() => beamsClient.addDeviceInterest(`user-${userId}`))
                    .then(() => {
                        console.log('Successfully subscribed to push notifications');
                        
                        // Get device ID and save to backend
                        beamsClient.getDeviceId()
                            .then(deviceId => {
                                // Save device token to backend
                                fetch('/mobile/notifications/register-device', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        device_id: deviceId,
                                        platform: 'web',
                                        user_id: userId
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // Update notification icon
                                        const notifIcon = document.getElementById('notification-icon');
                                        if (notifIcon) {
                                            notifIcon.classList.remove('bi-bell');
                                            notifIcon.classList.add('bi-bell-fill');
                                            notifIcon.style.color = '#4CAF50';
                                        }
                                        
                                        // Show success message
                                        const alertDiv = document.createElement('div');
                                        alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed';
                                        alertDiv.style.cssText = 'top: 70px; left: 50%; transform: translateX(-50%); z-index: 9999; min-width: 300px;';
                                        alertDiv.innerHTML = `
                                            <i class="bi bi-check-circle"></i> შეტყობინებები ჩართულია
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                        `;
                                        document.body.appendChild(alertDiv);
                                        
                                        setTimeout(() => alertDiv.remove(), 3000);
                                    }
                                })
                                .catch(err => console.error('Failed to register device:', err));
                            })
                            .catch(err => console.error('Failed to get device ID:', err));
                    })
                    .catch((err) => {
                        console.error('Push notification error:', err);
                        alert('შეტყობინებების ჩართვა ვერ მოხერხდა. გთხოვთ, დართოთ ნებართვა.');
                    });
            };

            // Auto-start if previously granted
            if ('Notification' in window && Notification.permission === 'granted') {
                beamsClient.start()
                    .then(() => beamsClient.addDeviceInterest(`user-${userId}`))
                    .catch(err => console.error('Auto-start failed:', err));
            }

            // Handle notification clicks
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.addEventListener('message', (event) => {
                    if (event.data && event.data.notification) {
                        const notification = event.data.notification;
                        
                        // Navigate to URL if specified
                        if (notification.data && notification.data.url) {
                            window.location.href = notification.data.url;
                        }
                    }
                });
            }
        });
    </script>
    @endauth
    
    @stack('scripts')
</body>
</html>