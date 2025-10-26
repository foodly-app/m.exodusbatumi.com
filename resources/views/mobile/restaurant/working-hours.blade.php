@extends('mobile.layouts.app')

@section('title', 'სამუშაო საათები - FOODLY')

@section('content')
<div class="container-fluid px-0">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('mobile.settings.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> უკან
        </a>
    </div>

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">
                <i class="bi bi-calendar3 text-primary"></i> სამუშაო საათები
            </h4>
            <p class="text-muted small mb-0">რესტორნის გახსნის და დახურვის დრო</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card mb-3">
        <div class="card-body p-3">
            <div class="row g-2">
                <div class="col-6">
                    <button type="button" class="btn btn-outline-primary w-100 btn-sm" onclick="applyToAll()">
                        <i class="bi bi-arrow-repeat"></i> ყველა დღეზე გადატანა
                    </button>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-outline-secondary w-100 btn-sm" onclick="resetAll()">
                        <i class="bi bi-arrow-clockwise"></i> საწყისი მნიშვნელობები
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Working Hours Form -->
    <form id="workingHoursForm" method="POST" action="{{ route('mobile.restaurant.working-hours.update') }}">
        @csrf
        @method('PUT')

        <!-- Monday -->
        <div class="card mb-3">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-calendar-day text-primary me-2" style="font-size: 24px;"></i>
                        <div>
                            <h6 class="mb-0">ორშაბათი</h6>
                            <small class="text-muted">Monday</small>
                        </div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="monday_enabled" name="monday[enabled]" checked onchange="toggleDay('monday')">
                        <label class="form-check-label" for="monday_enabled"></label>
                    </div>
                </div>
                
                <div id="monday_hours">
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small">გახსნა</label>
                            <input type="time" class="form-control" name="monday[open]" value="10:00">
                        </div>
                        <div class="col-6">
                            <label class="form-label small">დახურვა</label>
                            <input type="time" class="form-control" name="monday[close]" value="23:00">
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary w-100" onclick="addBreak('monday')">
                        <i class="bi bi-plus-circle"></i> შესვენების დამატება
                    </button>
                    <div id="monday_breaks"></div>
                </div>
            </div>
        </div>

        <!-- Tuesday -->
        <div class="card mb-3">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-calendar-day text-primary me-2" style="font-size: 24px;"></i>
                        <div>
                            <h6 class="mb-0">სამშაბათი</h6>
                            <small class="text-muted">Tuesday</small>
                        </div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="tuesday_enabled" name="tuesday[enabled]" checked onchange="toggleDay('tuesday')">
                    </div>
                </div>
                
                <div id="tuesday_hours">
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small">გახსნა</label>
                            <input type="time" class="form-control" name="tuesday[open]" value="10:00">
                        </div>
                        <div class="col-6">
                            <label class="form-label small">დახურვა</label>
                            <input type="time" class="form-control" name="tuesday[close]" value="23:00">
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary w-100" onclick="addBreak('tuesday')">
                        <i class="bi bi-plus-circle"></i> შესვენების დამატება
                    </button>
                    <div id="tuesday_breaks"></div>
                </div>
            </div>
        </div>

        <!-- Wednesday -->
        <div class="card mb-3">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-calendar-day text-primary me-2" style="font-size: 24px;"></i>
                        <div>
                            <h6 class="mb-0">ოთხშაბათი</h6>
                            <small class="text-muted">Wednesday</small>
                        </div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="wednesday_enabled" name="wednesday[enabled]" checked onchange="toggleDay('wednesday')">
                    </div>
                </div>
                
                <div id="wednesday_hours">
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small">გახსნა</label>
                            <input type="time" class="form-control" name="wednesday[open]" value="10:00">
                        </div>
                        <div class="col-6">
                            <label class="form-label small">დახურვა</label>
                            <input type="time" class="form-control" name="wednesday[close]" value="23:00">
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary w-100" onclick="addBreak('wednesday')">
                        <i class="bi bi-plus-circle"></i> შესვენების დამატება
                    </button>
                    <div id="wednesday_breaks"></div>
                </div>
            </div>
        </div>

        <!-- Thursday -->
        <div class="card mb-3">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-calendar-day text-primary me-2" style="font-size: 24px;"></i>
                        <div>
                            <h6 class="mb-0">ხუთშაბათი</h6>
                            <small class="text-muted">Thursday</small>
                        </div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="thursday_enabled" name="thursday[enabled]" checked onchange="toggleDay('thursday')">
                    </div>
                </div>
                
                <div id="thursday_hours">
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small">გახსნა</label>
                            <input type="time" class="form-control" name="thursday[open]" value="10:00">
                        </div>
                        <div class="col-6">
                            <label class="form-label small">დახურვა</label>
                            <input type="time" class="form-control" name="thursday[close]" value="23:00">
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary w-100" onclick="addBreak('thursday')">
                        <i class="bi bi-plus-circle"></i> შესვენების დამატება
                    </button>
                    <div id="thursday_breaks"></div>
                </div>
            </div>
        </div>

        <!-- Friday -->
        <div class="card mb-3">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-calendar-day text-primary me-2" style="font-size: 24px;"></i>
                        <div>
                            <h6 class="mb-0">პარასკევი</h6>
                            <small class="text-muted">Friday</small>
                        </div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="friday_enabled" name="friday[enabled]" checked onchange="toggleDay('friday')">
                    </div>
                </div>
                
                <div id="friday_hours">
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small">გახსნა</label>
                            <input type="time" class="form-control" name="friday[open]" value="10:00">
                        </div>
                        <div class="col-6">
                            <label class="form-label small">დახურვა</label>
                            <input type="time" class="form-control" name="friday[close]" value="01:00">
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary w-100" onclick="addBreak('friday')">
                        <i class="bi bi-plus-circle"></i> შესვენების დამატება
                    </button>
                    <div id="friday_breaks"></div>
                </div>
            </div>
        </div>

        <!-- Saturday -->
        <div class="card mb-3">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-calendar-day text-primary me-2" style="font-size: 24px;"></i>
                        <div>
                            <h6 class="mb-0">შაბათი</h6>
                            <small class="text-muted">Saturday</small>
                        </div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="saturday_enabled" name="saturday[enabled]" checked onchange="toggleDay('saturday')">
                    </div>
                </div>
                
                <div id="saturday_hours">
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small">გახსნა</label>
                            <input type="time" class="form-control" name="saturday[open]" value="10:00">
                        </div>
                        <div class="col-6">
                            <label class="form-label small">დახურვა</label>
                            <input type="time" class="form-control" name="saturday[close]" value="01:00">
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary w-100" onclick="addBreak('saturday')">
                        <i class="bi bi-plus-circle"></i> შესვენების დამატება
                    </button>
                    <div id="saturday_breaks"></div>
                </div>
            </div>
        </div>

        <!-- Sunday -->
        <div class="card mb-3">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-calendar-day text-primary me-2" style="font-size: 24px;"></i>
                        <div>
                            <h6 class="mb-0">კვირა</h6>
                            <small class="text-muted">Sunday</small>
                        </div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="sunday_enabled" name="sunday[enabled]" checked onchange="toggleDay('sunday')">
                    </div>
                </div>
                
                <div id="sunday_hours">
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small">გახსნა</label>
                            <input type="time" class="form-control" name="sunday[open]" value="10:00">
                        </div>
                        <div class="col-6">
                            <label class="form-label small">დახურვა</label>
                            <input type="time" class="form-control" name="sunday[close]" value="23:00">
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary w-100" onclick="addBreak('sunday')">
                        <i class="bi bi-plus-circle"></i> შესვენების დამატება
                    </button>
                    <div id="sunday_breaks"></div>
                </div>
            </div>
        </div>

        <!-- Special Hours -->
        <div class="card mb-3 border-info">
            <div class="card-body p-3">
                <h6 class="text-info mb-3">
                    <i class="bi bi-star"></i> სპეციალური საათები
                </h6>
                <p class="text-muted small mb-3">დაამატეთ განსაკუთრებული დღეების (დღესასწაულები, ღონისძიებები) საათები</p>
                
                <div id="special_hours_container"></div>
                
                <button type="button" class="btn btn-sm btn-outline-info w-100" onclick="addSpecialHour()">
                    <i class="bi bi-plus-circle"></i> სპეციალური საათის დამატება
                </button>
            </div>
        </div>

        <!-- Info Card -->
        <div class="alert alert-info mb-3">
            <i class="bi bi-info-circle"></i>
            <strong>შენიშვნა:</strong> 
            <ul class="mb-0 mt-2 small">
                <li>დახურვის დრო შეიძლება იყოს შუაღამის შემდეგ (მაგ: 01:00)</li>
                <li>შესვენება გამოიყენება საუზმე/სადილის საათებისთვის</li>
                <li>გამორთული დღე მოჩვენებს როგორც "დახურული"</li>
            </ul>
        </div>

        <!-- Save Button -->
        <div class="d-grid gap-2 mb-4">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-check-circle"></i> ცვლილებების შენახვა
            </button>
            <a href="{{ route('mobile.settings.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-x-circle"></i> გაუქმება
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    let specialHourCount = 0;

    // Toggle day visibility
    function toggleDay(day) {
        const checkbox = document.getElementById(day + '_enabled');
        const hoursDiv = document.getElementById(day + '_hours');
        
        if (checkbox.checked) {
            hoursDiv.style.display = 'block';
        } else {
            hoursDiv.style.display = 'none';
        }
    }

    // Add break time
    function addBreak(day) {
        const breakContainer = document.getElementById(day + '_breaks');
        const breakId = Date.now();
        
        const breakDiv = document.createElement('div');
        breakDiv.className = 'border rounded p-2 mt-2';
        breakDiv.id = 'break_' + breakId;
        breakDiv.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <small class="text-muted">შესვენება</small>
                <button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="removeBreak(${breakId})">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            <div class="row g-2">
                <div class="col-6">
                    <input type="time" class="form-control form-control-sm" name="${day}[breaks][${breakId}][start]" placeholder="დაწყება">
                </div>
                <div class="col-6">
                    <input type="time" class="form-control form-control-sm" name="${day}[breaks][${breakId}][end]" placeholder="დასრულება">
                </div>
            </div>
        `;
        
        breakContainer.appendChild(breakDiv);
    }

    // Remove break
    function removeBreak(breakId) {
        const breakDiv = document.getElementById('break_' + breakId);
        if (breakDiv) {
            breakDiv.remove();
        }
    }

    // Add special hour
    function addSpecialHour() {
        const container = document.getElementById('special_hours_container');
        const specialId = specialHourCount++;
        
        const specialDiv = document.createElement('div');
        specialDiv.className = 'border rounded p-3 mb-3';
        specialDiv.id = 'special_' + specialId;
        specialDiv.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <small class="fw-semibold">სპეციალური საათი #${specialId + 1}</small>
                <button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="removeSpecial(${specialId})">
                    <i class="bi bi-trash"></i> წაშლა
                </button>
            </div>
            
            <div class="mb-2">
                <label class="form-label small">თარიღი</label>
                <input type="date" class="form-control form-control-sm" name="special[${specialId}][date]" required>
            </div>
            
            <div class="mb-2">
                <label class="form-label small">აღწერა (არასავალდებულო)</label>
                <input type="text" class="form-control form-control-sm" name="special[${specialId}][description]" placeholder="მაგ: შობის საღამო">
            </div>
            
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="special_closed_${specialId}" name="special[${specialId}][closed]" onchange="toggleSpecialHours(${specialId})">
                <label class="form-check-label small" for="special_closed_${specialId}">
                    დახურულია ამ დღეს
                </label>
            </div>
            
            <div id="special_hours_${specialId}">
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label small">გახსნა</label>
                        <input type="time" class="form-control form-control-sm" name="special[${specialId}][open]">
                    </div>
                    <div class="col-6">
                        <label class="form-label small">დახურვა</label>
                        <input type="time" class="form-control form-control-sm" name="special[${specialId}][close]">
                    </div>
                </div>
            </div>
        `;
        
        container.appendChild(specialDiv);
    }

    // Remove special hour
    function removeSpecial(specialId) {
        const specialDiv = document.getElementById('special_' + specialId);
        if (specialDiv) {
            specialDiv.remove();
        }
    }

    // Toggle special hours inputs
    function toggleSpecialHours(specialId) {
        const checkbox = document.getElementById('special_closed_' + specialId);
        const hoursDiv = document.getElementById('special_hours_' + specialId);
        
        if (checkbox.checked) {
            hoursDiv.style.display = 'none';
        } else {
            hoursDiv.style.display = 'block';
        }
    }

    // Apply Monday hours to all days
    function applyToAll() {
        if (!confirm('გსურთ ორშაბათის საათების ყველა დღეზე გადატანა?')) {
            return;
        }
        
        const mondayOpen = document.querySelector('[name="monday[open]"]').value;
        const mondayClose = document.querySelector('[name="monday[close]"]').value;
        const mondayEnabled = document.getElementById('monday_enabled').checked;
        
        const days = ['tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        
        days.forEach(day => {
            document.querySelector(`[name="${day}[open]"]`).value = mondayOpen;
            document.querySelector(`[name="${day}[close]"]`).value = mondayClose;
            document.getElementById(day + '_enabled').checked = mondayEnabled;
            toggleDay(day);
        });
        
        showToast('საათები გადატანილია ყველა დღეზე');
    }

    // Reset all to default
    function resetAll() {
        if (!confirm('გსურთ საწყისი მნიშვნელობების აღდგენა?')) {
            return;
        }
        
        const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        const defaultOpen = '10:00';
        const defaultClose = '23:00';
        
        days.forEach(day => {
            document.querySelector(`[name="${day}[open]"]`).value = defaultOpen;
            document.querySelector(`[name="${day}[close]"]`).value = day === 'friday' || day === 'saturday' ? '01:00' : defaultClose;
            document.getElementById(day + '_enabled').checked = true;
            toggleDay(day);
            
            // Clear breaks
            const breaksContainer = document.getElementById(day + '_breaks');
            breaksContainer.innerHTML = '';
        });
        
        // Clear special hours
        document.getElementById('special_hours_container').innerHTML = '';
        specialHourCount = 0;
        
        showToast('საწყისი მნიშვნელობები აღდგენილია');
    }

    // Show toast notification
    function showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'alert alert-success position-fixed';
        toast.style.cssText = 'top: 70px; left: 50%; transform: translateX(-50%); z-index: 9999; min-width: 300px; text-align: center;';
        toast.innerHTML = `<i class="bi bi-check-circle"></i> ${message}`;
        document.body.appendChild(toast);
        
        setTimeout(() => toast.remove(), 3000);
    }

    // Form validation before submit
    document.getElementById('workingHoursForm').addEventListener('submit', function(e) {
        // Add any validation logic here
        console.log('Form submitted');
    });
</script>
@endpush
@endsection
