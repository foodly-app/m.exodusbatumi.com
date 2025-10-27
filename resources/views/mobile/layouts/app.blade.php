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
    
    <!-- Custom Mobile Styles -->
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    
    @stack('styles')
</head>
<body>
    <!-- Mobile Header -->
    <header class="mobile-header">
        <div class="header-content">
            <a href="{{ route('mobile.dashboard') }}" class="header-logo">
                <img src="{{ asset('images/logo/SVG/White/White-01.svg') }}" alt="Foodly" height="32">
            </a>
            @if(session('partner_token'))
            <div class="header-actions">
                <!-- Notification Bell -->
                <button onclick="requestNotificationPermission()" class="header-icon notification-bell" id="notification-bell" title="შეტყობინებების ჩართვა">
                    <i class="bi bi-bell" id="notification-icon" style="font-size: 22px;"></i>
                    <!-- Uncomment to show badge with count -->
                    <!-- <span class="notification-badge">3</span> -->
                </button>
                
                <!-- User Dropdown -->
                <div class="user-dropdown">
                    <button class="header-icon" id="userDropdownBtn" type="button">
                        <i class="bi bi-person-circle" style="font-size: 26px;"></i>
                    </button>
                    <div class="dropdown-menu" id="userDropdownMenu">
                        <a href="{{ route('mobile.profile.index') }}" class="dropdown-item">
                            <i class="bi bi-person"></i>
                            <span>პროფილი</span>
                        </a>
                        <a href="{{ route('mobile.settings.index') }}" class="dropdown-item">
                            <i class="bi bi-gear"></i>
                            <span>პარამეტრები</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <form action="{{ route('mobile.logout') }}" method="POST" id="logoutForm">
                            @csrf
                            <button type="submit" class="dropdown-item logout">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>გასვლა</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @else
            <div class="header-actions">
                <a href="{{ route('mobile.login') }}" class="btn btn-sm btn-light">
                    <i class="bi bi-box-arrow-in-right"></i> შესვლა
                </a>
            </div>
            @endif
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
    @if(session('partner_token'))
    <nav class="bottom-nav">
        <a href="{{ route('mobile.dashboard') }}" class="nav-item {{ request()->routeIs('mobile.dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door"></i>
            <span>მთავარი</span>
        </a>
        <a href="{{ route('mobile.reservations.index') }}" class="nav-item {{ request()->routeIs('mobile.reservations.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check"></i>
            <span>რეზერვაციები</span>
        </a>
        <a href="#" class="nav-item nav-item-primary">
            <i class="bi bi-plus-lg"></i>
        </a>
        <a href="#" class="nav-item">
            <i class="bi bi-credit-card"></i>
            <span>გადახდები</span>
        </a>
        <a href="{{ route('mobile.settings.index') }}" class="nav-item {{ request()->routeIs('mobile.settings.*') ? 'active' : '' }}">
            <i class="bi bi-gear"></i>
            <span>პარამეტრები</span>
        </a>
    </nav>
    @endif



    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Pusher Beams SDK -->
    <script src="https://js.pusher.com/beams/1.0/push-notifications-cdn.js"></script>
    
    @if(session('partner_token'))
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
    @endif
    
    <script>
        // User Dropdown Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const userDropdownBtn = document.getElementById('userDropdownBtn');
            const userDropdownMenu = document.getElementById('userDropdownMenu');
            
            if (userDropdownBtn && userDropdownMenu) {
                // Toggle dropdown on button click
                userDropdownBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userDropdownMenu.classList.toggle('show');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!userDropdownBtn.contains(e.target) && !userDropdownMenu.contains(e.target)) {
                        userDropdownMenu.classList.remove('show');
                    }
                });
                
                // Prevent dropdown from closing when clicking inside
                userDropdownMenu.addEventListener('click', function(e) {
                    if (e.target.tagName !== 'A' && e.target.tagName !== 'BUTTON') {
                        e.stopPropagation();
                    }
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>