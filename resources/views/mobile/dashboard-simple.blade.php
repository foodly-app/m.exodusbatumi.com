@extends('mobile.layouts.app')

@section('title', 'მთავარი - FOODLY')

@section('content')
<div class="container-fluid px-0">
    <!-- Welcome Card -->
    <div class="card mb-3">
        <div class="card-body">
            <h4 class="mb-2">
                <i class="bi bi-emoji-smile text-primary"></i> გამარჯობა, {{ session('partner_user.name', 'მენეჯერი') }}!
            </h4>
            <p class="text-muted mb-0 small">
                <i class="bi bi-calendar-event"></i> {{ now()->translatedFormat('l, d F Y') }}
            </p>
        </div>
    </div>

    <!-- Loading State -->
    <div id="loading-state" class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">იტვირთება...</span>
        </div>
        <p class="text-muted mt-3">მონაცემების ჩატვირთვა...</p>
    </div>

    <!-- Main Content (Hidden initially) -->
    <div id="dashboard-content" style="display: none;">
        <!-- Stats Cards -->
        <div class="row g-2 mb-3">
            <div class="col-6">
                <div class="card text-white h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body text-center py-3">
                        <i class="bi bi-calendar-day" style="font-size: 2rem;"></i>
                        <h2 class="fw-bold mt-2 mb-0" id="today-count">0</h2>
                        <p class="mb-0 small">დღეს</p>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="card text-white h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <div class="card-body text-center py-3">
                        <i class="bi bi-calendar-check" style="font-size: 2rem;"></i>
                        <h2 class="fw-bold mt-2 mb-0" id="upcoming-count">0</h2>
                        <p class="mb-0 small">მომავალი</p>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="card text-white h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <div class="card-body text-center py-3">
                        <i class="bi bi-people" style="font-size: 2rem;"></i>
                        <h2 class="fw-bold mt-2 mb-0" id="guests-count">0</h2>
                        <p class="mb-0 small">სტუმრები</p>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="card text-white h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                    <div class="card-body text-center py-3">
                        <i class="bi bi-grid-3x3" style="font-size: 2rem;"></i>
                        <h2 class="fw-bold mt-2 mb-0" id="tables-occupied">0</h2>
                        <p class="mb-0 small">დაკავებული</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Summary -->
        <div class="card mb-3">
            <div class="card-body p-3">
                <h6 class="mb-3">
                    <i class="bi bi-pie-chart text-primary"></i> სტატუსის განაწილება
                </h6>
                <div class="row g-2">
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <div class="bg-success rounded-circle p-2 me-2">
                                <i class="bi bi-check-circle text-white"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">დადასტურებული</small>
                                <strong id="confirmed-count">0</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning rounded-circle p-2 me-2">
                                <i class="bi bi-clock text-white"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">მოლოდინში</small>
                                <strong id="pending-count">0</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 mt-2">
                        <div class="d-flex align-items-center">
                            <div class="bg-danger rounded-circle p-2 me-2">
                                <i class="bi bi-x-circle text-white"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">გაუქმებული</small>
                                <strong id="cancelled-count">0</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 mt-2">
                        <div class="d-flex align-items-center">
                            <div class="bg-secondary rounded-circle p-2 me-2">
                                <i class="bi bi-check-all text-white"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">დასრულებული</small>
                                <strong id="completed-count">0</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Reservations -->
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center p-2">
                <h6 class="mb-0 small">
                    <i class="bi bi-calendar-day text-primary"></i> დღევანდელი რეზერვაციები
                </h6>
                <a href="{{ route('mobile.reservations.index') }}" class="btn btn-sm btn-outline-primary">
                    ყველა
                </a>
            </div>
            <div class="card-body p-0">
                <div id="today-reservations-list">
                    <div class="text-center py-4 text-muted">
                        <div class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">იტვირთება...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mb-3">
            <div class="card-header p-2">
                <h6 class="mb-0 small">
                    <i class="bi bi-lightning-charge text-warning"></i> სწრაფი მოქმედებები
                </h6>
            </div>
            <div class="card-body p-2">
                <div class="row g-2">
                    <div class="col-6">
                        <a href="#" class="btn btn-outline-primary w-100 py-2">
                            <i class="bi bi-calendar-plus d-block mb-1" style="font-size: 1.3rem;"></i>
                            <small>ახალი ჯავშანი</small>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('mobile.reservations.index') }}" class="btn btn-outline-success w-100 py-2">
                            <i class="bi bi-list-check d-block mb-1" style="font-size: 1.3rem;"></i>
                            <small>რეზერვაციები</small>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('mobile.restaurant.working-hours') }}" class="btn btn-outline-info w-100 py-2">
                            <i class="bi bi-clock-history d-block mb-1" style="font-size: 1.3rem;"></i>
                            <small>სამუშაო საათები</small>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('mobile.settings.index') }}" class="btn btn-outline-warning w-100 py-2">
                            <i class="bi bi-gear d-block mb-1" style="font-size: 1.3rem;"></i>
                            <small>პარამეტრები</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Error State -->
    <div id="error-state" class="alert alert-danger" style="display: none;">
        <i class="bi bi-exclamation-triangle"></i>
        <strong>შეცდომა!</strong>
        <p class="mb-2" id="error-message"></p>
        <button class="btn btn-sm btn-danger" onclick="loadDashboardData()">
            <i class="bi bi-arrow-clockwise"></i> ხელახლა ცდა
        </button>
    </div>
</div>

@push('scripts')
<script>
// DEMO MODE - Using mock data since we don't have real API access
const DEMO_MODE = true;

const API_BASE_URL = 'https://api.foodlyapp.ge/api/partner';
const token = '{{ session("partner_token") }}';
const organizationId = '{{ session("partner_organization_id") }}';
const restaurantId = '{{ session("partner_restaurant_id") }}';

// Mock data for demo
const mockDashboardData = {
    success: true,
    data: {
        today_reservations_count: 12,
        total_reservations_count: 45,
        total_guests: 48,
        occupied_tables: 8,
        status_breakdown: {
            confirmed: 8,
            pending: 3,
            cancelled: 1,
            completed: 0
        }
    }
};

const mockReservationsData = {
    success: true,
    data: [
        {
            id: 1,
            customer_name: 'გიორგი მამულაშვილი',
            time: '19:00',
            number_of_guests: 4,
            table_number: 'T-05',
            status: 'confirmed'
        },
        {
            id: 2,
            customer_name: 'ნინო ბერიძე',
            time: '20:00',
            number_of_guests: 2,
            table_number: 'T-12',
            status: 'pending'
        },
        {
            id: 3,
            customer_name: 'David Smith',
            time: '21:00',
            number_of_guests: 6,
            table_number: 'T-08',
            status: 'confirmed'
        }
    ]
};

// Load dashboard data
async function loadDashboardData() {
    showLoading();
    
    try {
        if (DEMO_MODE) {
            // Use mock data in demo mode
            await new Promise(resolve => setTimeout(resolve, 800)); // Simulate network delay
            updateDashboard(mockDashboardData, mockReservationsData);
            showContent();
            return;
        }

        // Real API calls
        const dashboardResponse = await fetch(`${API_BASE_URL}/organizations/${organizationId}/dashboard`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });

        if (!dashboardResponse.ok) {
            throw new Error('Failed to load dashboard data');
        }

        const dashboardData = await dashboardResponse.json();

        // Fetch today's reservations
        const reservationsResponse = await fetch(`${API_BASE_URL}/organizations/${organizationId}/restaurants/${restaurantId}/reservations/today`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });

        if (!reservationsResponse.ok) {
            throw new Error('Failed to load today\'s reservations');
        }

        const reservationsData = await reservationsResponse.json();

        // Update dashboard with fetched data
        updateDashboard(dashboardData, reservationsData);
        showContent();
    } catch (error) {
        console.error('Error loading dashboard:', error);
        showError(error.message);
    }
}

// Update dashboard UI with data
function updateDashboard(dashboardData, reservationsData) {
    const data = dashboardData.data;
    const reservations = reservationsData.data;

    // Update stats cards
    document.getElementById('today-count').textContent = data.today_reservations_count || 0;
    document.getElementById('upcoming-count').textContent = calculateUpcoming(data);
    document.getElementById('guests-count').textContent = data.total_guests || 0;
    document.getElementById('tables-occupied').textContent = data.occupied_tables || 0;

    // Update status counts
    updateStatusCounts(data);

    // Update today's reservations list
    updateTodayReservations(reservations);
}

// Calculate upcoming reservations (total - today)
function calculateUpcoming(data) {
    const total = data.total_reservations_count || 0;
    const today = data.today_reservations_count || 0;
    return Math.max(0, total - today);
}

// Update status breakdown
function updateStatusCounts(data) {
    const statusBreakdown = data.status_breakdown || {};
    document.getElementById('confirmed-count').textContent = statusBreakdown.confirmed || 0;
    document.getElementById('pending-count').textContent = statusBreakdown.pending || 0;
    document.getElementById('cancelled-count').textContent = statusBreakdown.cancelled || 0;
    document.getElementById('completed-count').textContent = statusBreakdown.completed || 0;
}

// Update today's reservations list
function updateTodayReservations(reservations) {
    const container = document.getElementById('today-reservations-list');
    
    if (!reservations || reservations.length === 0) {
        container.innerHTML = `
            <div class="text-center py-4 text-muted">
                <i class="bi bi-calendar-x" style="font-size: 2rem;"></i>
                <p class="mb-0 mt-2">დღეს რეზერვაციები არ არის</p>
            </div>
        `;
        return;
    }

    // Show first 3 reservations
    const displayReservations = reservations.slice(0, 3);
    
    container.innerHTML = displayReservations.map(reservation => {
        const statusColor = getStatusColor(reservation.status);
        const statusText = getStatusText(reservation.status);
        
        return `
            <div class="border-bottom p-2">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <div>
                        <strong class="d-block">${reservation.customer_name || 'N/A'}</strong>
                        <small class="text-muted">
                            <i class="bi bi-clock"></i> ${reservation.time || 'N/A'}
                        </small>
                    </div>
                    <span class="badge bg-${statusColor}">${statusText}</span>
                </div>
                <div class="d-flex gap-2 small text-muted">
                    <span><i class="bi bi-people"></i> ${reservation.number_of_guests || 0}</span>
                    ${reservation.table_number ? `<span><i class="bi bi-grid"></i> მაგიდა ${reservation.table_number}</span>` : ''}
                </div>
            </div>
        `;
    }).join('');
}

// Get status color for badge
function getStatusColor(status) {
    const colors = {
        'confirmed': 'success',
        'pending': 'warning',
        'cancelled': 'danger',
        'completed': 'secondary',
        'seated': 'info'
    };
    return colors[status] || 'secondary';
}

// Get status text in Georgian
function getStatusText(status) {
    const texts = {
        'confirmed': 'დადასტურებული',
        'pending': 'მოლოდინში',
        'cancelled': 'გაუქმებული',
        'completed': 'დასრულებული',
        'seated': 'დასხდა'
    };
    return texts[status] || status;
}

// Show loading state
function showLoading() {
    document.getElementById('loading-state').style.display = 'block';
    document.getElementById('dashboard-content').style.display = 'none';
    document.getElementById('error-state').style.display = 'none';
}

// Show content state
function showContent() {
    document.getElementById('loading-state').style.display = 'none';
    document.getElementById('dashboard-content').style.display = 'block';
    document.getElementById('error-state').style.display = 'none';
}

// Show error state
function showError(message) {
    document.getElementById('loading-state').style.display = 'none';
    document.getElementById('dashboard-content').style.display = 'none';
    document.getElementById('error-state').style.display = 'block';
    document.getElementById('error-message').textContent = message;
}

// Load data on page load
document.addEventListener('DOMContentLoaded', function() {
    loadDashboardData();
    
    // Auto-refresh every 30 seconds
    setInterval(loadDashboardData, 30000);
});
</script>
@endpush
@endsection