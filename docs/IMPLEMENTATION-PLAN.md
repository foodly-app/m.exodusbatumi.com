# 📋 Implementation Plan - Mobile Dashboard

## მიზანი
შევქმნათ გამარტივებული routes/controllers რომლებიც იმუშავებენ `initial-dashboard` endpoint-დან მიღებულ მონაცემებზე დაფუძნებით.

---

## 1️⃣ RESERVATIONS (ჯავშნები)

### API Endpoints საჭირო:
- ✅ `GET /api/partner/initial-dashboard` - აბრუნებს selected_restaurant-ს და dashboard data-ს
- 📝 `GET /api/partner/restaurants/{restaurant_id}/reservations` - ჯავშნების სია
- 📝 `GET /api/partner/restaurants/{restaurant_id}/reservations/{id}` - ცალკეული ჯავშანი
- 📝 `POST /api/partner/reservations/{id}/confirm` - დადასტურება
- 📝 `POST /api/partner/reservations/{id}/cancel` - გაუქმება

### Routes:
```php
Route::get('/reservations', [ReservationController::class, 'index'])
Route::get('/reservations/{id}', [ReservationController::class, 'show'])
Route::post('/reservations/{id}/confirm', [ReservationController::class, 'confirm'])
Route::post('/reservations/{id}/cancel', [ReservationController::class, 'cancel'])
```

### Controller Methods საჭირო:
```php
public function index() 
    - გამოიყენოს session-დან selected_restaurant
    - ReservationService->getReservations()
    - return view('mobile.reservations.index')

public function show($id)
    - ReservationService->getReservation($id)
    - return view('mobile.reservations.show')

public function confirm($id)
    - ReservationService->confirm($id)
    - redirect back with success

public function cancel($id)
    - ReservationService->cancel($id)
    - redirect back with success
```

---

## 2️⃣ PROFILE (პროფილი)

### API Endpoints საჭირო:
- ✅ `GET /api/partner/me` - მომხმარებლის ინფო
- 📝 `PUT /api/partner/profile` - პროფილის განახლება
- 📝 `POST /api/partner/profile/avatar` - ავატარის ატვირთვა
- 📝 `DELETE /api/partner/profile/avatar` - ავატარის წაშლა
- 📝 `PUT /api/partner/profile/password` - პაროლის შეცვლა

### Routes:
```php
Route::get('/profile', [ProfileController::class, 'index'])
Route::put('/profile', [ProfileController::class, 'update'])
Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar'])
Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])
Route::put('/profile/password', [ProfileController::class, 'changePassword'])
```

### ProfileService საჭირო:
```php
class ProfileService {
    public function getProfile()
    public function updateProfile($data)
    public function uploadAvatar($file)
    public function deleteAvatar()
    public function changePassword($data)
}
```

---

## 3️⃣ SETTINGS (პარამეტრები)

### API Endpoints საჭირო:
- 📝 `GET /api/partner/restaurants/{id}/settings` - რესტორნის პარამეტრები
- 📝 `PUT /api/partner/restaurants/{id}/working-hours` - სამუშაო საათები
- 📝 `PUT /api/partner/restaurants/{id}/settings` - ზოგადი პარამეტრები

### Routes:
```php
Route::get('/settings', [SettingsController::class, 'index'])
Route::get('/settings/restaurant', [SettingsController::class, 'restaurant'])
Route::put('/settings/restaurant/working-hours', [SettingsController::class, 'updateWorkingHours'])
```

### SettingsService საჭირო:
```php
class SettingsService {
    public function getRestaurantSettings($restaurantId)
    public function updateWorkingHours($restaurantId, $data)
    public function updateSettings($restaurantId, $data)
}
```

---

## 🎯 Implementation Order:

### Phase 1: Setup
1. ✅ შევქმნათ ProfileService
2. ✅ შევქმნათ SettingsService (or RestaurantSettingsService)
3. ✅ დავარეგისტრიროთ AppServiceProvider-ში

### Phase 2: Controllers
1. ✅ ProfileController - create or update
2. ✅ SettingsController - create
3. ✅ ReservationController - update if needed

### Phase 3: Routes
1. ✅ დავამატოთ routes mobile.php-ში
2. ✅ გავატესტოთ TestController-ით

### Phase 4: Views
1. შევქმნათ ან განვაახლოთ blade views
2. გავატესტოთ UI

---

## 📝 Notes:

- **Session-ში შენახული:**
  - `partner_token` - auth token
  - `partner_user` - user object
  - `selected_restaurant` - არჩეული რესტორანი initial-dashboard-დან

- **გამარტივებული approach:**
  - არ გვჭირდება organization_id/restaurant_id parameters routes-ში
  - ყველაფერი მოდის session-დან და initial-dashboard-დან
  - უფრო clean და simple API

