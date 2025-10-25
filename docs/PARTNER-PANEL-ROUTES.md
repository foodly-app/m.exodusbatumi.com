# Partner Panel API Routes - სრული დოკუმენტაცია

📅 **შექმნის თარიღი:** 2025-10-17  
🏢 **სისტემა:** Organization-centric Multi-tenant System  
🔐 **ავთენტიფიკაცია:** Sanctum Token-based Authentication  
⚡ **სტატუსი:** ✅ ACTIVE (Production)

---

## 📋 სარჩევი

- [1. Authentication & Authorization](#1-authentication--authorization)
- [2. User Profile Management](#2-user-profile-management)
- [3. Organization Management](#3-organization-management)
- [4. Team Management](#4-team-management)
- [5. Invitation Management](#5-invitation-management)
- [6. Restaurant Management](#6-restaurant-management)
- [7. Place Management](#7-place-management)
- [8. Table Management](#8-table-management)
- [9. Reservation Management](#9-reservation-management)
- [10. Dashboard & Analytics](#10-dashboard--analytics)
- [11. Menu Management](#11-menu-management)
- [12. Booking Management](#12-booking-management)
- [13. System Backups](#13-system-backups)
- [14. Availability Management](#14-availability-management)

---

## 🔐 1. Authentication & Authorization

### 1.1 Partner Login (Public)
**POST** `/partner/login`
- **Controller:** `PartnerAuthController@login`
- **Auth:** None (Public)
- **Description:** პარტნიორის სისტემაში შესვლა
- **Request:**
  ```json
  {
    "email": "partner@example.com",
    "password": "password123"
  }
  ```
- **Response:**
  ```json
  {
    "token": "sanctum_token_here",
    "user": { ... },
    "organizations": [ ... ]
  }
  ```

### 1.2 Get Current User
**GET** `/partner/me`
- **Controller:** `PartnerAuthController@me`
- **Auth:** ✅ Required
- **Permissions:** None
- **Description:** მიმდინარე მომხმარებლის ინფორმაცია

### 1.3 Logout
**POST** `/partner/logout`
- **Controller:** `PartnerAuthController@logout`
- **Auth:** ✅ Required
- **Permissions:** None
- **Description:** სისტემიდან გასვლა და ტოკენის გაუქმება

---

## 👤 2. User Profile Management

### 2.1 Get Profile
**GET** `/partner/profile`
- **Controller:** `ProfileController@show`
- **Auth:** ✅ Required
- **Permissions:** None (საკუთარი პროფილი)
- **Description:** პროფილის ნახვა

### 2.2 Update Profile
**PUT** `/partner/profile`
- **Controller:** `ProfileController@update`
- **Auth:** ✅ Required
- **Permissions:** None
- **Description:** პროფილის განახლება
- **Request:**
  ```json
  {
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+995555123456"
  }
  ```

### 2.3 Update Avatar
**POST** `/partner/profile/avatar`
- **Controller:** `ProfileController@updateAvatar`
- **Auth:** ✅ Required
- **Content-Type:** multipart/form-data
- **Request:**
  ```
  avatar: [File]
  ```

### 2.4 Delete Avatar
**DELETE** `/partner/profile/avatar`
- **Controller:** `ProfileController@deleteAvatar`
- **Auth:** ✅ Required

### 2.5 Change Password
**PUT** `/partner/profile/password`
- **Controller:** `ProfileController@changePassword`
- **Auth:** ✅ Required
- **Request:**
  ```json
  {
    "current_password": "old_password",
    "password": "new_password",
    "password_confirmation": "new_password"
  }
  ```

---

## 🏢 3. Organization Management

### 3.1 List Organizations
**GET** `/partner/organizations`
- **Controller:** `OrganizationController@index`
- **Auth:** ✅ Required
- **Permissions:** `view-organization`
- **Description:** მომხმარებლის ყველა ორგანიზაციის სია

### 3.2 Get Organization Details
**GET** `/partner/organizations/{organization}`
- **Controller:** `OrganizationController@show`
- **Auth:** ✅ Required
- **Permissions:** `view-organization`
- **Description:** კონკრეტული ორგანიზაციის დეტალები

### 3.3 Update Organization
**PUT** `/partner/organizations/{organization}`
- **Controller:** `OrganizationController@update`
- **Auth:** ✅ Required
- **Permissions:** `edit-organization` (Owner + Manager)
- **Request:**
  ```json
  {
    "name": "My Restaurant Group",
    "description": "Best restaurants in Georgia",
    "contact_email": "info@restaurants.ge",
    "contact_phone": "+995555123456"
  }
  ```

---

## 👥 4. Team Management

### 4.1 List Team Members
**GET** `/partner/organizations/{organization}/team`
- **Controller:** `TeamController@index`
- **Auth:** ✅ Required
- **Permissions:** `view-staff` (Owner + Manager)
- **Description:** ორგანიზაციის გუნდის წევრების სია

### 4.2 Get Team Member Details
**GET** `/partner/organizations/{organization}/team/{user}`
- **Controller:** `TeamController@show`
- **Auth:** ✅ Required
- **Permissions:** `view-staff`

### 4.3 Create Team Member
**POST** `/partner/organizations/{organization}/team`
- **Controller:** `TeamController@store`
- **Auth:** ✅ Required
- **Permissions:** `create-staff` (Owner + Manager)
- **Request:**
  ```json
  {
    "name": "Staff Member",
    "email": "staff@example.com",
    "role": "manager",
    "phone": "+995555123456"
  }
  ```

### 4.4 Update Team Member Role
**PUT** `/partner/organizations/{organization}/team/{user}/role`
- **Controller:** `TeamController@updateRole`
- **Auth:** ✅ Required
- **Permissions:** `edit-staff` (Owner + Manager)
- **Request:**
  ```json
  {
    "role": "staff"
  }
  ```

### 4.5 Delete Team Member
**DELETE** `/partner/organizations/{organization}/team/{user}`
- **Controller:** `TeamController@destroy`
- **Auth:** ✅ Required
- **Permissions:** `delete-staff` (Owner only)

---

## ✉️ 5. Invitation Management

### 5.1 Public: View Invitation (No Auth)
**GET** `/invitations/{token}`
- **Controller:** `PublicInvitationController@show`
- **Auth:** None (Public)
- **Description:** მოწვევის დეტალების ნახვა ტოკენით

### 5.2 Public: Accept Invitation (No Auth)
**POST** `/invitations/{token}/accept`
- **Controller:** `PublicInvitationController@accept`
- **Auth:** None (Public)
- **Request:**
  ```json
  {
    "name": "New User",
    "email": "user@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }
  ```

### 5.3 List Organization Invitations
**GET** `/partner/organizations/{organization}/invitations`
- **Controller:** `InvitationController@index`
- **Auth:** ✅ Required
- **Permissions:** `view-staff` (Owner + Manager)
- **Description:** ორგანიზაციის მოწვევების სია

### 5.4 Send Invitation
**POST** `/partner/organizations/{organization}/invitations`
- **Controller:** `InvitationController@store`
- **Auth:** ✅ Required
- **Permissions:** `invite-staff` (Owner + Manager)
- **Request:**
  ```json
  {
    "email": "newuser@example.com",
    "role": "staff"
  }
  ```

### 5.5 Resend Invitation
**POST** `/partner/invitations/{invitation}/resend`
- **Controller:** `InvitationController@resend`
- **Auth:** ✅ Required
- **Permissions:** `invite-staff`

### 5.6 Delete Invitation
**DELETE** `/partner/invitations/{invitation}`
- **Controller:** `InvitationController@destroy`
- **Auth:** ✅ Required
- **Permissions:** `invite-staff`

---

## 🍽️ 6. Restaurant Management

### 6.1 List All User Restaurants
**GET** `/partner/restaurants`
- **Controller:** `RestaurantController@index`
- **Auth:** ✅ Required
- **Description:** მომხმარებლის ყველა რესტორანი (ყველა ორგანიზაციიდან)

### 6.2 List Organization Restaurants
**GET** `/partner/organizations/{organization}/restaurants`
- **Controller:** `RestaurantController@organizationRestaurants`
- **Auth:** ✅ Required
- **Description:** კონკრეტული ორგანიზაციის რესტორნები

### 6.3 Create Restaurant
**POST** `/partner/organizations/{organization}/restaurants`
- **Controller:** `RestaurantController@store`
- **Auth:** ✅ Required
- **Request:**
  ```json
  {
    "name_en": "Georgian Restaurant",
    "name_ka": "ქართული რესტორანი",
    "description_en": "Traditional Georgian cuisine",
    "description_ka": "ტრადიციული ქართული სამზარეულო",
    "address": "123 Rustaveli Ave, Tbilisi",
    "latitude": 41.6941,
    "longitude": 44.8337,
    "phone": "+995555123456",
    "email": "info@restaurant.ge",
    "capacity": 50,
    "cuisine_type": "georgian"
  }
  ```

### 6.4 Get Restaurant Details
**GET** `/partner/organizations/{organization}/restaurants/{restaurant}`
- **Controller:** `RestaurantController@show`
- **Auth:** ✅ Required

### 6.5 Update Restaurant
**PUT/PATCH** `/partner/organizations/{organization}/restaurants/{restaurant}`
- **Controller:** `RestaurantController@update`
- **Auth:** ✅ Required

### 6.6 Upload Restaurant Images
**POST** `/partner/organizations/{organization}/restaurants/{restaurant}/images`
- **Controller:** `RestaurantController@uploadImages`
- **Auth:** ✅ Required
- **Content-Type:** multipart/form-data
- **Request:**
  ```
  images[]: [File]
  images[]: [File]
  ```

### 6.7 Delete Restaurant Image
**DELETE** `/partner/organizations/{organization}/restaurants/{restaurant}/images/{imageId}`
- **Controller:** `RestaurantController@deleteImage`
- **Auth:** ✅ Required

### 6.8 Update Restaurant Status
**PUT** `/partner/organizations/{organization}/restaurants/{restaurant}/status`
- **Controller:** `RestaurantController@updateStatus`
- **Auth:** ✅ Required
- **Request:**
  ```json
  {
    "status": "active"
  }
  ```

### 6.9 Get Restaurant Settings
**GET** `/partner/organizations/{organization}/restaurants/{restaurant}/settings`
- **Controller:** `RestaurantController@settings`
- **Auth:** ✅ Required

### 6.10 Update Restaurant Settings
**PUT** `/partner/organizations/{organization}/restaurants/{restaurant}/settings`
- **Controller:** `RestaurantController@updateSettings`
- **Auth:** ✅ Required
- **Request:**
  ```json
  {
    "reservation_interval": 30,
    "max_party_size": 10,
    "advance_booking_days": 30,
    "auto_confirm_reservations": true,
    "allow_waitlist": true
  }
  ```

---

## 📍 7. Place Management

**Prefix:** `/partner/organizations/{organization}/restaurants/{restaurant}/places`

### 7.1 List Places
**GET** `/`
- **Controller:** `PlaceController@index`
- **Auth:** ✅ Required
- **Permissions:** `edit-restaurant`
- **Description:** რესტორნის ადგილების/ზონების სია (მაგ: Main Hall, Terrace, VIP Room)

### 7.2 Create Place
**POST** `/`
- **Controller:** `PlaceController@store`
- **Auth:** ✅ Required
- **Permissions:** `edit-restaurant`
- **Request:**
  ```json
  {
    "name_en": "Main Hall",
    "name_ka": "მთავარი დარბაზი",
    "description_en": "Main dining area",
    "description_ka": "მთავარი სასადილო ფართი",
    "capacity": 40,
    "is_active": true
  }
  ```

### 7.3 Get Place Details
**GET** `/{place}`
- **Controller:** `PlaceController@show`
- **Auth:** ✅ Required
- **Permissions:** `edit-restaurant`

### 7.4 Update Place
**PUT/PATCH** `/{place}`
- **Controller:** `PlaceController@update`
- **Auth:** ✅ Required
- **Permissions:** `edit-restaurant`

### 7.5 Delete Place
**DELETE** `/{place}`
- **Controller:** `PlaceController@destroy`
- **Auth:** ✅ Required
- **Permissions:** `edit-restaurant`

### 7.6 Update Place Status
**PUT** `/{place}/status`
- **Controller:** `PlaceController@updateStatus`
- **Auth:** ✅ Required
- **Permissions:** `edit-restaurant`

### 7.7 Get Place Tables
**GET** `/{place}/tables`
- **Controller:** `PlaceController@tables`
- **Auth:** ✅ Required
- **Permissions:** `edit-restaurant`

### 7.8 Get Place Reservations
**GET** `/{place}/reservations`
- **Controller:** `ReservationController@placeReservations`
- **Auth:** ✅ Required

---

## 🪑 8. Table Management

**Prefix:** `/partner/organizations/{organization}/restaurants/{restaurant}/tables`

### 8.1 List Tables
**GET** `/`
- **Controller:** `TableController@index`
- **Auth:** ✅ Required
- **Permissions:** `edit-restaurant`

### 8.2 Create Table
**POST** `/`
- **Controller:** `TableController@store`
- **Auth:** ✅ Required
- **Permissions:** `edit-restaurant`
- **Request:**
  ```json
  {
    "place_id": 1,
    "table_number": "T-101",
    "name_en": "Table 1",
    "name_ka": "მაგიდა 1",
    "min_capacity": 2,
    "max_capacity": 4,
    "is_active": true
  }
  ```

### 8.3 Get Table Details
**GET** `/{table}`
- **Controller:** `TableController@show`
- **Auth:** ✅ Required
- **Permissions:** `edit-restaurant`

### 8.4 Update Table
**PUT/PATCH** `/{table}`
- **Controller:** `TableController@update`
- **Auth:** ✅ Required
- **Permissions:** `edit-restaurant`

### 8.5 Delete Table
**DELETE** `/{table}`
- **Controller:** `TableController@destroy`
- **Auth:** ✅ Required
- **Permissions:** `edit-restaurant`

### 8.6 Update Table Status
**PUT** `/{table}/status`
- **Controller:** `TableController@updateStatus`
- **Auth:** ✅ Required
- **Permissions:** `edit-restaurant`

### 8.7 Get Table Availability
**GET** `/{table}/availability`
- **Controller:** `TableController@availability`
- **Auth:** ✅ Required
- **Query Params:**
  ```
  ?date=2025-10-17&time=19:00
  ```

### 8.8 Get Table Reservations
**GET** `/{table}/reservations`
- **Controller:** `ReservationController@tableReservations`
- **Auth:** ✅ Required

---

## 📅 9. Reservation Management

**Prefix:** `/partner/organizations/{organization}/restaurants/{restaurant}/reservations`

### 9.1 List All Reservations
**GET** `/`
- **Controller:** `ReservationController@restaurantReservations`
- **Auth:** ✅ Required
- **Query Params:**
  ```
  ?date=2025-10-17
  ?status=confirmed
  ?page=1
  ?per_page=20
  ```

### 9.2 Get Reservation Statistics
**GET** `/statistics`
- **Controller:** `ReservationController@statistics`
- **Auth:** ✅ Required
- **Query Params:**
  ```
  ?start_date=2025-10-01&end_date=2025-10-31
  ```

### 9.3 Today's Reservations
**GET** `/today`
- **Controller:** `ReservationController@todayReservations`
- **Auth:** ✅ Required

### 9.4 Upcoming Reservations
**GET** `/upcoming`
- **Controller:** `ReservationController@upcomingReservations`
- **Auth:** ✅ Required

### 9.5 Search Reservations
**GET** `/search`
- **Controller:** `ReservationController@search`
- **Auth:** ✅ Required
- **Query Params:**
  ```
  ?query=John
  ?phone=555123456
  ?email=john@example.com
  ```

### 9.6 Export Reservations
**GET** `/export`
- **Controller:** `ReservationController@export`
- **Auth:** ✅ Required
- **Query Params:**
  ```
  ?format=csv
  ?start_date=2025-10-01
  ?end_date=2025-10-31
  ```

### 9.7 Get Single Reservation
**GET** `/{reservation}`
- **Controller:** `ReservationController@show`
- **Auth:** ✅ Required

### 9.8 Update Reservation Status
**PUT** `/{reservation}/status`
- **Controller:** `ReservationController@updateStatus`
- **Auth:** ✅ Required
- **Request:**
  ```json
  {
    "status": "confirmed"
  }
  ```

### 9.9 Quick Status Actions

#### 9.9.1 Confirm Reservation
**POST** `/{reservation}/confirm`
- **Controller:** `ReservationController@confirm`
- **Auth:** ✅ Required

#### 9.9.2 Cancel Reservation
**POST** `/{reservation}/cancel`
- **Controller:** `ReservationController@cancel`
- **Auth:** ✅ Required
- **Request:**
  ```json
  {
    "reason": "Customer requested cancellation"
  }
  ```

#### 9.9.3 Seat Customers
**POST** `/{reservation}/seat`
- **Controller:** `ReservationController@seat`
- **Auth:** ✅ Required

#### 9.9.4 Complete Reservation
**POST** `/{reservation}/complete`
- **Controller:** `ReservationController@complete`
- **Auth:** ✅ Required

#### 9.9.5 Mark as No-Show
**POST** `/{reservation}/no-show`
- **Controller:** `ReservationController@noShow`
- **Auth:** ✅ Required

---

## 📊 10. Dashboard & Analytics

**Prefix:** `/partner/organizations/{organization}`

### 10.1 Organization Dashboard
**GET** `/dashboard`
- **Controller:** `DashboardController@index`
- **Auth:** ✅ Required
- **Description:** ორგანიზაციის მთავარი დეშბორდი

### 10.2 Dashboard Stats
**GET** `/dashboard/stats`
- **Controller:** `DashboardController@stats`
- **Auth:** ✅ Required

### 10.3 Dashboard Overview
**GET** `/dashboard/overview`
- **Controller:** `DashboardController@overview`
- **Auth:** ✅ Required

### 10.4 Restaurant Dashboard
**GET** `/restaurants/{restaurant}/dashboard`
- **Controller:** `DashboardController@restaurantDashboard`
- **Auth:** ✅ Required

### 10.5 Analytics - Reservations
**GET** `/analytics/reservations`
- **Controller:** `AnalyticsController@reservations`
- **Auth:** ✅ Required
- **Query Params:**
  ```
  ?start_date=2025-10-01
  ?end_date=2025-10-31
  ?restaurant_id=1
  ```

### 10.6 Analytics - Revenue
**GET** `/analytics/revenue`
- **Controller:** `AnalyticsController@revenue`
- **Auth:** ✅ Required

### 10.7 Analytics - Popular Tables
**GET** `/analytics/popular-tables`
- **Controller:** `AnalyticsController@popularTables`
- **Auth:** ✅ Required

### 10.8 Analytics - Peak Hours
**GET** `/analytics/peak-hours`
- **Controller:** `AnalyticsController@peakHours`
- **Auth:** ✅ Required

### 10.9 Analytics - Customer Insights
**GET** `/analytics/customer-insights`
- **Controller:** `AnalyticsController@customerInsights`
- **Auth:** ✅ Required

---

## 🍔 11. Menu Management

**Prefix:** `/partner/organizations/{organization}/restaurants/{restaurant}`

### 11.1 Menu Overview

#### 11.1.1 Get Full Menu
**GET** `/menu`
- **Controller:** `MenuController@index`
- **Auth:** ✅ Required
- **Permissions:** `view-menu`

#### 11.1.2 Get Menu Structure (Hierarchical)
**GET** `/menu/structure`
- **Controller:** `MenuController@structure`
- **Auth:** ✅ Required
- **Permissions:** `view-menu`

#### 11.1.3 Get Categories List (Flat)
**GET** `/menu/categories-list`
- **Controller:** `MenuController@categories`
- **Auth:** ✅ Required
- **Permissions:** `view-menu`

#### 11.1.4 Get Menu Statistics
**GET** `/menu/statistics`
- **Controller:** `MenuController@statistics`
- **Auth:** ✅ Required
- **Permissions:** `view-menu`

### 11.2 Menu Operations

#### 11.2.1 Reorder Menu Items
**PUT** `/menu/reorder`
- **Controller:** `MenuController@reorder`
- **Auth:** ✅ Required
- **Permissions:** `edit-menu`
- **Request:**
  ```json
  {
    "items": [
      {"id": 1, "order": 1},
      {"id": 2, "order": 2}
    ]
  }
  ```

#### 11.2.2 Bulk Update Availability
**POST** `/menu/bulk-availability`
- **Controller:** `MenuController@bulkUpdateAvailability`
- **Auth:** ✅ Required
- **Permissions:** `edit-menu`
- **Request:**
  ```json
  {
    "item_ids": [1, 2, 3],
    "is_available": false
  }
  ```

#### 11.2.3 Bulk Update Status
**POST** `/menu/bulk-status`
- **Controller:** `MenuController@bulkUpdateStatus`
- **Auth:** ✅ Required
- **Permissions:** `edit-menu`

### 11.3 Menu Categories

#### 11.3.1 List Categories
**GET** `/menu-categories`
- **Controller:** `CategoryController@index`
- **Auth:** ✅ Required
- **Permissions:** `view-menu`

#### 11.3.2 Create Category
**POST** `/menu-categories`
- **Controller:** `CategoryController@store`
- **Auth:** ✅ Required
- **Permissions:** `manage-menu-categories`
- **Request:**
  ```json
  {
    "name_en": "Appetizers",
    "name_ka": "წაახარისხებელი",
    "description_en": "Start your meal",
    "description_ka": "დაიწყეთ თქვენი სადილი",
    "order": 1,
    "is_active": true
  }
  ```

#### 11.3.3 Show Category
**GET** `/menu-categories/{category}`
- **Controller:** `CategoryController@show`
- **Auth:** ✅ Required
- **Permissions:** `view-menu`

#### 11.3.4 Update Category
**PUT/PATCH** `/menu-categories/{category}`
- **Controller:** `CategoryController@update`
- **Auth:** ✅ Required
- **Permissions:** `manage-menu-categories`

#### 11.3.5 Delete Category
**DELETE** `/menu-categories/{category}`
- **Controller:** `CategoryController@destroy`
- **Auth:** ✅ Required
- **Permissions:** `manage-menu-categories`

#### 11.3.6 Update Category Status
**PATCH** `/menu-categories/{category}/status`
- **Controller:** `CategoryController@updateStatus`
- **Auth:** ✅ Required
- **Permissions:** `manage-menu-categories`

#### 11.3.7 Get Category Items
**GET** `/menu-categories/{category}/items`
- **Controller:** `CategoryController@items`
- **Auth:** ✅ Required
- **Permissions:** `view-menu`

#### 11.3.8 Reorder Categories
**PUT** `/menu-categories/reorder`
- **Controller:** `CategoryController@reorder`
- **Auth:** ✅ Required
- **Permissions:** `edit-menu`

#### 11.3.9 Move Category Up
**PATCH** `/menu-categories/{category}/move-up`
- **Controller:** `CategoryController@moveUp`
- **Auth:** ✅ Required
- **Permissions:** `edit-menu`

#### 11.3.10 Move Category Down
**PATCH** `/menu-categories/{category}/move-down`
- **Controller:** `CategoryController@moveDown`
- **Auth:** ✅ Required
- **Permissions:** `edit-menu`

### 11.4 Menu Items

#### 11.4.1 List Items
**GET** `/menu-items`
- **Controller:** `ItemController@index`
- **Auth:** ✅ Required
- **Permissions:** `view-menu`

#### 11.4.2 Create Item
**POST** `/menu-items`
- **Controller:** `ItemController@store`
- **Auth:** ✅ Required
- **Permissions:** `create-menu-item`
- **Request:**
  ```json
  {
    "category_id": 1,
    "name_en": "Khachapuri",
    "name_ka": "ხაჭაპური",
    "description_en": "Traditional Georgian cheese bread",
    "description_ka": "ტრადიციული ქართული ყველიანი პური",
    "price": 12.50,
    "is_available": true,
    "is_vegetarian": true,
    "is_vegan": false,
    "allergens": ["dairy", "gluten"]
  }
  ```

#### 11.4.3 Show Item
**GET** `/menu-items/{item}`
- **Controller:** `ItemController@show`
- **Auth:** ✅ Required
- **Permissions:** `view-menu`

#### 11.4.4 Update Item
**PUT/PATCH** `/menu-items/{item}`
- **Controller:** `ItemController@update`
- **Auth:** ✅ Required
- **Permissions:** `edit-menu`

#### 11.4.5 Delete Item
**DELETE** `/menu-items/{item}`
- **Controller:** `ItemController@destroy`
- **Auth:** ✅ Required
- **Permissions:** `delete-menu-item`

#### 11.4.6 Update Item Status
**PATCH** `/menu-items/{item}/status`
- **Controller:** `ItemController@updateStatus`
- **Auth:** ✅ Required
- **Permissions:** `edit-menu`

#### 11.4.7 Toggle Availability
**PATCH** `/menu-items/{item}/availability`
- **Controller:** `ItemController@toggleAvailability`
- **Auth:** ✅ Required
- **Permissions:** `edit-menu`

#### 11.4.8 Duplicate Item
**POST** `/menu-items/{item}/duplicate`
- **Controller:** `ItemController@duplicate`
- **Auth:** ✅ Required
- **Permissions:** `edit-menu`

### 11.5 Item Images

#### 11.5.1 Upload Images
**POST** `/menu-items/{item}/images`
- **Controller:** `ItemController@uploadImages`
- **Auth:** ✅ Required
- **Permissions:** `edit-menu`
- **Content-Type:** multipart/form-data

#### 11.5.2 Delete Image
**DELETE** `/menu-items/{item}/images/{imageId}`
- **Controller:** `ItemController@deleteImage`
- **Auth:** ✅ Required
- **Permissions:** `edit-menu`

#### 11.5.3 Reorder Images
**PUT** `/menu-items/{item}/images/reorder`
- **Controller:** `ItemController@reorderImages`
- **Auth:** ✅ Required
- **Permissions:** `edit-menu`

#### 11.5.4 Set Primary Image
**PATCH** `/menu-items/{item}/images/{imageId}/primary`
- **Controller:** `ItemController@setPrimaryImage`
- **Auth:** ✅ Required
- **Permissions:** `edit-menu`

### 11.6 Item Variants (Size variations)

#### 11.6.1 List Variants
**GET** `/menu-items/{item}/variants`
- **Controller:** `ItemController@getVariants`
- **Auth:** ✅ Required
- **Permissions:** `edit-menu`

#### 11.6.2 Add Variant
**POST** `/menu-items/{item}/variants`
- **Controller:** `ItemController@addVariant`
- **Auth:** ✅ Required
- **Permissions:** `edit-menu`
- **Request:**
  ```json
  {
    "name_en": "Large",
    "name_ka": "დიდი",
    "price": 15.00
  }
  ```

#### 11.6.3 Update Variant
**PUT** `/menu-items/{item}/variants/{variant}`
- **Controller:** `ItemController@updateVariant`
- **Auth:** ✅ Required
- **Permissions:** `edit-menu`

#### 11.6.4 Delete Variant
**DELETE** `/menu-items/{item}/variants/{variant}`
- **Controller:** `ItemController@deleteVariant`
- **Auth:** ✅ Required
- **Permissions:** `edit-menu`

### 11.7 Item Modifiers (Add-ons)

#### 11.7.1 List Modifiers
**GET** `/menu-items/{item}/modifiers`
- **Controller:** `ItemController@getModifiers`
- **Auth:** ✅ Required
- **Permissions:** `edit-menu`

#### 11.7.2 Add Modifier
**POST** `/menu-items/{item}/modifiers`
- **Controller:** `ItemController@addModifier`
- **Auth:** ✅ Required
- **Permissions:** `edit-menu`
- **Request:**
  ```json
  {
    "name_en": "Extra Cheese",
    "name_ka": "დამატებითი ყველი",
    "price": 2.50
  }
  ```

#### 11.7.3 Update Modifier
**PUT** `/menu-items/{item}/modifiers/{modifier}`
- **Controller:** `ItemController@updateModifier`
- **Auth:** ✅ Required
- **Permissions:** `edit-menu`

#### 11.7.4 Delete Modifier
**DELETE** `/menu-items/{item}/modifiers/{modifier}`
- **Controller:** `ItemController@deleteModifier`
- **Auth:** ✅ Required
- **Permissions:** `edit-menu`

### 11.8 Restaurant-Specific Menu Settings

#### 11.8.1 Get Restaurant Menu Visibility
**GET** `/menu-visibility`
- **Controller:** `MenuController@getRestaurantVisibility`
- **Auth:** ✅ Required
- **Permissions:** `edit-menu`

#### 11.8.2 Update Restaurant Menu Visibility
**PUT** `/menu-visibility`
- **Controller:** `MenuController@updateRestaurantVisibility`
- **Auth:** ✅ Required
- **Permissions:** `edit-menu`

#### 11.8.3 Get Restaurant Pricing
**GET** `/menu-pricing`
- **Controller:** `MenuController@getRestaurantPricing`
- **Auth:** ✅ Required
- **Permissions:** `edit-menu`

#### 11.8.4 Update Restaurant Pricing
**PUT** `/menu-pricing`
- **Controller:** `MenuController@updateRestaurantPricing`
- **Auth:** ✅ Required
- **Permissions:** `edit-menu`

---

## 📅 12. Booking Management

**Prefix:** `/partner/booking`

### 12.1 Get Available Slots
**POST** `/available-slots`
- **Controller:** `BookingAvailabilityController@getAvailableSlots`
- **Auth:** ✅ Required
- **Request:**
  ```json
  {
    "restaurant_id": 1,
    "date": "2025-10-17",
    "party_size": 4
  }
  ```

### 12.2 Check Time Slot
**POST** `/check-time-slot`
- **Controller:** `BookingAvailabilityController@checkTimeSlot`
- **Auth:** ✅ Required
- **Request:**
  ```json
  {
    "restaurant_id": 1,
    "date": "2025-10-17",
    "time": "19:00",
    "party_size": 4
  }
  ```

### 12.3 Send OTP
**POST** `/otp/send`
- **Controller:** `OtpController@sendOtp`
- **Auth:** ✅ Required
- **Request:**
  ```json
  {
    "phone": "+995555123456"
  }
  ```

### 12.4 Verify OTP
**POST** `/otp/verify`
- **Controller:** `OtpController@verifyOtp`
- **Auth:** ✅ Required
- **Request:**
  ```json
  {
    "phone": "+995555123456",
    "code": "123456"
  }
  ```

### 12.5 Resend OTP
**POST** `/otp/resend`
- **Controller:** `OtpController@resendOtp`
- **Auth:** ✅ Required

### 12.6 Create Reservation (Partner-assisted)
**POST** `/reserve`
- **Controller:** `ReservationBookingController@store`
- **Auth:** ✅ Required
- **Request:**
  ```json
  {
    "restaurant_id": 1,
    "date": "2025-10-17",
    "time": "19:00",
    "party_size": 4,
    "customer_name": "John Doe",
    "customer_phone": "+995555123456",
    "customer_email": "john@example.com",
    "special_requests": "Window table please"
  }
  ```

### 12.7 Get Customer History
**POST** `/customer-history`
- **Controller:** `ReservationBookingController@customerHistory`
- **Auth:** ✅ Required
- **Request:**
  ```json
  {
    "phone": "+995555123456"
  }
  ```

---

## 💾 13. System Backups

**Prefix:** `/partner/backups`  
**Permissions:** `admin-backups` (Owner only)

### 13.1 List Backups
**GET** `/`
- **Controller:** `BackupController@index`
- **Auth:** ✅ Required
- **Permissions:** `admin-backups`

### 13.2 Create Backup
**POST** `/`
- **Controller:** `BackupController@store`
- **Auth:** ✅ Required
- **Permissions:** `admin-backups`

### 13.3 Get Backup Status
**GET** `/status`
- **Controller:** `BackupController@status`
- **Auth:** ✅ Required
- **Permissions:** `admin-backups`

### 13.4 Cleanup Old Backups
**POST** `/cleanup`
- **Controller:** `BackupController@cleanup`
- **Auth:** ✅ Required
- **Permissions:** `admin-backups`

### 13.5 Download Backup
**GET** `/{filename}/download`
- **Controller:** `BackupController@download`
- **Auth:** ✅ Required
- **Permissions:** `admin-backups`

### 13.6 Delete Backup
**DELETE** `/{filename}`
- **Controller:** `BackupController@destroy`
- **Auth:** ✅ Required
- **Permissions:** `admin-backups`

---

## ⏰ 14. Availability Management

**Prefix:** `/partner/organizations/{organization}/restaurants/{restaurant}/availability`  
**Permissions:** `edit-restaurant`

### 14.1 Get Availability Overview
**GET** `/`
- **Controller:** `AvailabilityController@index`
- **Auth:** ✅ Required
- **Permissions:** `edit-restaurant`

### 14.2 Get Calendar View
**GET** `/calendar`
- **Controller:** `AvailabilityController@calendar`
- **Auth:** ✅ Required
- **Permissions:** `edit-restaurant`

### 14.3 Update Working Hours
**PUT** `/hours`
- **Controller:** `AvailabilityController@updateHours`
- **Auth:** ✅ Required
- **Permissions:** `edit-restaurant`
- **Request:**
  ```json
  {
    "monday": {
      "is_open": true,
      "shifts": [
        {"start": "12:00", "end": "15:00"},
        {"start": "18:00", "end": "23:00"}
      ]
    },
    "tuesday": { ... }
  }
  ```

### 14.4 Update Special Hours
**PUT** `/special-hours`
- **Controller:** `AvailabilityController@updateSpecialHours`
- **Auth:** ✅ Required
- **Permissions:** `edit-restaurant`
- **Request:**
  ```json
  {
    "date": "2025-12-31",
    "is_open": true,
    "shifts": [
      {"start": "18:00", "end": "02:00"}
    ]
  }
  ```

### 14.5 Block Time Slot
**POST** `/block-time`
- **Controller:** `AvailabilityController@blockTime`
- **Auth:** ✅ Required
- **Permissions:** `edit-restaurant`
- **Request:**
  ```json
  {
    "date": "2025-10-17",
    "start_time": "19:00",
    "end_time": "21:00",
    "reason": "Private event"
  }
  ```

### 14.6 Unblock Time Slot
**DELETE** `/unblock-time/{blockId}`
- **Controller:** `AvailabilityController@unblockTime`
- **Auth:** ✅ Required
- **Permissions:** `edit-restaurant`

---

## 🔐 Permissions Summary

| Permission | Description | Roles |
|---|---|---|
| `view-organization` | ორგანიზაციის ნახვა | All Partners |
| `edit-organization` | ორგანიზაციის რედაქტირება | Owner, Manager |
| `view-staff` | გუნდის წევრების ნახვა | Owner, Manager |
| `create-staff` | გუნდის წევრის დამატება | Owner, Manager |
| `edit-staff` | გუნდის წევრის რედაქტირება | Owner, Manager |
| `delete-staff` | გუნდის წევრის წაშლა | Owner |
| `invite-staff` | მოწვევის გაგზავნა | Owner, Manager |
| `view-menu` | მენიუს ნახვა | All Partners |
| `edit-menu` | მენიუს რედაქტირება | Owner, Manager |
| `manage-menu-categories` | კატეგორიების მართვა | Owner, Manager |
| `create-menu-item` | ახალი პროდუქტის დამატება | Owner, Manager |
| `delete-menu-item` | პროდუქტის წაშლა | Owner, Manager |
| `edit-restaurant` | რესტორნის პარამეტრების რედაქტირება | Owner, Manager |
| `admin-backups` | სისტემის backup-ების მართვა | Owner |

---

## 📝 Notes

1. **Authentication:** ყველა დაცული route საჭიროებს Sanctum token-ს
2. **Organization Context:** უმეტესი route საჭიროებს `{organization}` parameter-ს
3. **Multi-tenant:** სისტემა მხარს უჭერს მრავალ ორგანიზაციას და რესტორანს
4. **Permissions:** Middleware ამოწმებს user-ის permissions-ს თითოეულ route-ზე
5. **Legacy Routes:** `routes/Api/partners.php` არის deprecated და არ უნდა გამოვიყენოთ

---

## 🔗 External Routes

Partner Panel ასევე იყენებს შემდეგ ფაილებს:
- `routes/Api/partner/menu.php` - მენიუს სრული მენეჯმენტი
- `routes/Api/partner/restaurants.php` - დამატებითი restaurant routes (legacy controllers)

---

**Last Updated:** 2025-10-17  
**Version:** 1.0  
**Status:** ✅ Production Ready
