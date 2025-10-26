@extends('mobile.layouts.app')

@section('title', 'პარამეტრები - FOODLY')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">
            <i class="bi bi-gear text-primary"></i> პარამეტრები
        </h4>
    </div>

    <!-- Profile Section -->
    <div class="card mb-3">
        <div class="card-body p-3">
            <h6 class="text-muted mb-3">
                <i class="bi bi-person-circle"></i> პროფილი
            </h6>
            
            <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                <div class="position-relative">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 60px; height: 60px; font-size: 24px;">
                        <i class="bi bi-person"></i>
                    </div>
                    <button class="btn btn-sm btn-light position-absolute bottom-0 end-0 rounded-circle p-1" 
                            style="width: 24px; height: 24px;">
                        <i class="bi bi-camera" style="font-size: 12px;"></i>
                    </button>
                </div>
                <div class="ms-3 flex-grow-1">
                    <h6 class="mb-0">{{ session('partner_user.name', 'მომხმარებელი') }}</h6>
                    <small class="text-muted">{{ session('partner_user.email', 'email@example.com') }}</small>
                </div>
                <a href="{{ route('mobile.settings.profile') }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-pencil"></i>
                </a>
            </div>

            <a href="{{ route('mobile.settings.profile') }}" class="d-flex align-items-center text-decoration-none text-dark py-2">
                <i class="bi bi-person-badge text-primary me-3" style="font-size: 20px;"></i>
                <div class="flex-grow-1">
                    <div class="fw-semibold">პროფილის რედაქტირება</div>
                    <small class="text-muted">სახელი, ელფოსტა, ტელეფონი</small>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>

            <a href="{{ route('mobile.settings.password') }}" class="d-flex align-items-center text-decoration-none text-dark py-2 border-top">
                <i class="bi bi-shield-lock text-primary me-3" style="font-size: 20px;"></i>
                <div class="flex-grow-1">
                    <div class="fw-semibold">პაროლის შეცვლა</div>
                    <small class="text-muted">უსაფრთხოების პარამეტრები</small>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>
        </div>
    </div>

    <!-- Notifications Section -->
    <div class="card mb-3">
        <div class="card-body p-3">
            <h6 class="text-muted mb-3">
                <i class="bi bi-bell"></i> შეტყობინებები
            </h6>
            
            <div class="d-flex align-items-center justify-content-between py-2">
                <div class="d-flex align-items-center">
                    <i class="bi bi-calendar-check text-primary me-3" style="font-size: 20px;"></i>
                    <div>
                        <div class="fw-semibold">ახალი რეზერვაციები</div>
                        <small class="text-muted">შეტყობინება ახალი დაჯავშნისას</small>
                    </div>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="notifNewReservation" checked>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between py-2 border-top">
                <div class="d-flex align-items-center">
                    <i class="bi bi-clock-history text-primary me-3" style="font-size: 20px;"></i>
                    <div>
                        <div class="fw-semibold">რეზერვაციის დრო</div>
                        <small class="text-muted">შეტყობინება 30 წუთით ადრე</small>
                    </div>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="notifReservationTime" checked>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between py-2 border-top">
                <div class="d-flex align-items-center">
                    <i class="bi bi-credit-card text-primary me-3" style="font-size: 20px;"></i>
                    <div>
                        <div class="fw-semibold">გადახდები</div>
                        <small class="text-muted">წარმატებული გადახდების შესახებ</small>
                    </div>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="notifPayments" checked>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between py-2 border-top">
                <div class="d-flex align-items-center">
                    <i class="bi bi-x-circle text-primary me-3" style="font-size: 20px;"></i>
                    <div>
                        <div class="fw-semibold">გაუქმებები</div>
                        <small class="text-muted">რეზერვაციის გაუქმებისას</small>
                    </div>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="notifCancellations" checked>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between py-2 border-top">
                <div class="d-flex align-items-center">
                    <i class="bi bi-megaphone text-primary me-3" style="font-size: 20px;"></i>
                    <div>
                        <div class="fw-semibold">მარკეტინგი</div>
                        <small class="text-muted">აქციები და სიახლეები</small>
                    </div>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="notifMarketing">
                </div>
            </div>
        </div>
    </div>

    <!-- Restaurant Settings -->
    <div class="card mb-3">
        <div class="card-body p-3">
            <h6 class="text-muted mb-3">
                <i class="bi bi-shop"></i> რესტორნის პარამეტრები
            </h6>
            
            <a href="#" class="d-flex align-items-center text-decoration-none text-dark py-2">
                <i class="bi bi-info-circle text-primary me-3" style="font-size: 20px;"></i>
                <div class="flex-grow-1">
                    <div class="fw-semibold">ზოგადი ინფორმაცია</div>
                    <small class="text-muted">სახელი, მისამართი, საათები</small>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>

            <a href="#" class="d-flex align-items-center text-decoration-none text-dark py-2 border-top">
                <i class="bi bi-grid-3x3 text-primary me-3" style="font-size: 20px;"></i>
                <div class="flex-grow-1">
                    <div class="fw-semibold">მაგიდები და ადგილები</div>
                    <small class="text-muted">დარბაზების და მაგიდების მართვა</small>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>

            <a href="#" class="d-flex align-items-center text-decoration-none text-dark py-2 border-top">
                <i class="bi bi-calendar3 text-primary me-3" style="font-size: 20px;"></i>
                <div class="flex-grow-1">
                    <div class="fw-semibold">სამუშაო საათები</div>
                    <small class="text-muted">გახსნის და დახურვის დრო</small>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>

            <a href="#" class="d-flex align-items-center text-decoration-none text-dark py-2 border-top">
                <i class="bi bi-image text-primary me-3" style="font-size: 20px;"></i>
                <div class="flex-grow-1">
                    <div class="fw-semibold">ფოტოები</div>
                    <small class="text-muted">რესტორნის სურათების მართვა</small>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>
        </div>
    </div>

    <!-- Team Section -->
    <div class="card mb-3">
        <div class="card-body p-3">
            <h6 class="text-muted mb-3">
                <i class="bi bi-people"></i> გუნდი
            </h6>
            
            <a href="#" class="d-flex align-items-center text-decoration-none text-dark py-2">
                <i class="bi bi-person-plus text-primary me-3" style="font-size: 20px;"></i>
                <div class="flex-grow-1">
                    <div class="fw-semibold">თანამშრომლები</div>
                    <small class="text-muted">თანამშრომლების მართვა</small>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>

            <a href="#" class="d-flex align-items-center text-decoration-none text-dark py-2 border-top">
                <i class="bi bi-shield-check text-primary me-3" style="font-size: 20px;"></i>
                <div class="flex-grow-1">
                    <div class="fw-semibold">როლები და უფლებები</div>
                    <small class="text-muted">წვდომის კონტროლი</small>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>
        </div>
    </div>

    <!-- App Settings -->
    <div class="card mb-3">
        <div class="card-body p-3">
            <h6 class="text-muted mb-3">
                <i class="bi bi-phone"></i> აპლიკაცია
            </h6>
            
            <div class="d-flex align-items-center justify-content-between py-2">
                <div class="d-flex align-items-center">
                    <i class="bi bi-moon-stars text-primary me-3" style="font-size: 20px;"></i>
                    <div>
                        <div class="fw-semibold">ბნელი თემა</div>
                        <small class="text-muted">მუქი ფერის რეჟიმი</small>
                    </div>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="darkMode">
                </div>
            </div>

            <a href="#" class="d-flex align-items-center text-decoration-none text-dark py-2 border-top">
                <i class="bi bi-translate text-primary me-3" style="font-size: 20px;"></i>
                <div class="flex-grow-1">
                    <div class="fw-semibold">ენა</div>
                    <small class="text-muted">ქართული</small>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>

            <a href="{{ route('mobile.settings.about') }}" class="d-flex align-items-center text-decoration-none text-dark py-2 border-top">
                <i class="bi bi-info-square text-primary me-3" style="font-size: 20px;"></i>
                <div class="flex-grow-1">
                    <div class="fw-semibold">აპლიკაციის შესახებ</div>
                    <small class="text-muted">ვერსია 1.0.0</small>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>
        </div>
    </div>

    <!-- Support Section -->
    <div class="card mb-3">
        <div class="card-body p-3">
            <h6 class="text-muted mb-3">
                <i class="bi bi-question-circle"></i> დახმარება
            </h6>
            
            <a href="#" class="d-flex align-items-center text-decoration-none text-dark py-2">
                <i class="bi bi-life-preserver text-primary me-3" style="font-size: 20px;"></i>
                <div class="flex-grow-1">
                    <div class="fw-semibold">დახმარების ცენტრი</div>
                    <small class="text-muted">ხშირად დასმული კითხვები</small>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>

            <a href="tel:+995322152024" class="d-flex align-items-center text-decoration-none text-dark py-2 border-top">
                <i class="bi bi-telephone text-primary me-3" style="font-size: 20px;"></i>
                <div class="flex-grow-1">
                    <div class="fw-semibold">დაგვიკავშირდით</div>
                    <small class="text-muted">+995 322 15 20 24</small>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>

            <a href="mailto:support@foodlyapp.ge" class="d-flex align-items-center text-decoration-none text-dark py-2 border-top">
                <i class="bi bi-envelope text-primary me-3" style="font-size: 20px;"></i>
                <div class="flex-grow-1">
                    <div class="fw-semibold">ელფოსტა</div>
                    <small class="text-muted">support@foodlyapp.ge</small>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>
        </div>
    </div>

    <!-- Legal Section -->
    <div class="card mb-3">
        <div class="card-body p-3">
            <a href="#" class="d-flex align-items-center text-decoration-none text-dark py-2">
                <i class="bi bi-file-text text-primary me-3" style="font-size: 20px;"></i>
                <div class="flex-grow-1">
                    <div class="fw-semibold">მომსახურების პირობები</div>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>

            <a href="#" class="d-flex align-items-center text-decoration-none text-dark py-2 border-top">
                <i class="bi bi-shield-lock text-primary me-3" style="font-size: 20px;"></i>
                <div class="flex-grow-1">
                    <div class="fw-semibold">კონფიდენციალურობის პოლიტიკა</div>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>
        </div>
    </div>

    <!-- Logout Button -->
    <div class="card mb-4 border-danger">
        <div class="card-body p-3">
            <form action="{{ route('mobile.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger w-100 d-flex align-items-center justify-content-center">
                    <i class="bi bi-box-arrow-right me-2"></i>
                    <span class="fw-semibold">გასვლა</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Version Info -->
    <div class="text-center text-muted mb-4">
        <small>
            <i class="bi bi-c-circle"></i> 2025 FOODLY Partner Panel<br>
            Version 1.0.0 • Build 100
        </small>
    </div>
</div>

@push('scripts')
<script>
    // Save notification settings
    document.querySelectorAll('.form-check-input').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const setting = this.id;
            const value = this.checked;
            
            // Save to localStorage
            localStorage.setItem(setting, value);
            
            // Here you can also send to backend API
            console.log(`Setting ${setting} changed to ${value}`);
            
            // Show toast notification
            showToast(value ? 'ჩართულია' : 'გამორთულია');
        });
        
        // Load saved settings
        const savedValue = localStorage.getItem(toggle.id);
        if (savedValue !== null) {
            toggle.checked = savedValue === 'true';
        }
    });
    
    function showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'alert alert-success position-fixed';
        toast.style.cssText = 'top: 70px; left: 50%; transform: translateX(-50%); z-index: 9999; min-width: 200px; text-align: center;';
        toast.innerHTML = `<i class="bi bi-check-circle"></i> ${message}`;
        document.body.appendChild(toast);
        
        setTimeout(() => toast.remove(), 2000);
    }
</script>
@endpush
@endsection
