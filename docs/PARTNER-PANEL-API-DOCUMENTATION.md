# Partner Panel API Documentation

> **📋 სრული API დოკუმენტაცია Partner Panel Frontend-ისთვის**  
> ბოლო განახლება: 21 ოქტომბერი, 2025  
> Base URL: `/api/partner`

---

## 📑 სარჩევი

- [1. Authentication](#1-authentication)
- [2. User Profile](#2-user-profile)
- [3. Organizations](#3-organizations)
- [4. Team Management](#4-team-management)
- [5. Invitations](#5-invitations)
- [6. Restaurants](#6-restaurants)
- [7. Places](#7-places)
- [8. Tables](#8-tables)
- [9. Reservations](#9-reservations)
- [10. Dashboard & Analytics](#10-dashboard--analytics)
- [11. Booking Management](#11-booking-management)
- [12. Backups](#12-backups)
- [13. Menu Management](#13-menu-management)

---

## 🔐 Authentication Headers

ყველა დაცული ენდპოინტისთვის (გარდა `/login`-ისა) საჭიროა:

```javascript
headers: {
  'Authorization': 'Bearer {access_token}',
  'Accept': 'application/json',
  'Content-Type': 'application/json'
}
```

---

## 1. Authentication

### 1.1 Login
```http
POST /api/partner/login
```

**Request Body:**
```json
{
  "email": "partner@example.com",
  "password": "password123"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "partner@example.com",
      "role": "owner",
      "avatar_url": "https://..."
    },
    "access_token": "1|xxxxxxxxxxxxx",
    "token_type": "Bearer"
  }
}
```

---

### 1.2 Get Current User
```http
GET /api/partner/me
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "partner@example.com",
    "role": "owner",
    "avatar_url": "https://...",
    "organizations": [
      {
        "id": 1,
        "name": "My Restaurant Group",
        "role": "owner"
      }
    ]
  }
}
```

---

### 1.3 Logout
```http
POST /api/partner/logout
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

---

### 1.4 Initial Dashboard
```http
GET /api/partner/initial-dashboard
```

> 🎯 პირველი რესტორნის dashboard-ზე გადასვლისთვის login-ის შემდეგ

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "redirect_to": "organization",
    "organization_id": 1,
    "restaurant_id": 5,
    "total_restaurants": 3
  }
}
```

---

## 2. User Profile

### 2.1 Get Profile
```http
GET /api/partner/profile
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "partner@example.com",
    "phone": "+995591234567",
    "avatar_url": "https://...",
    "role": "owner",
    "created_at": "2025-01-15T10:00:00Z"
  }
}
```

---

### 2.2 Update Profile
```http
PUT /api/partner/profile
```

**Request Body:**
```json
{
  "name": "John Smith",
  "phone": "+995591234567"
}
```

---

### 2.3 Upload Avatar
```http
POST /api/partner/profile/avatar
Content-Type: multipart/form-data
```

**Request Body:**
```
avatar: [File]
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "avatar_url": "https://cloudinary.com/..."
  }
}
```

---

### 2.4 Delete Avatar
```http
DELETE /api/partner/profile/avatar
```

---

### 2.5 Change Password
```http
PUT /api/partner/profile/password
```

**Request Body:**
```json
{
  "current_password": "old_password",
  "password": "new_password",
  "password_confirmation": "new_password"
}
```

---

## 3. Organizations

### 3.1 List Organizations
```http
GET /api/partner/organizations
```

**Success Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "My Restaurant Group",
      "description": "Premium dining restaurants",
      "role": "owner",
      "restaurants_count": 5,
      "team_members_count": 12,
      "created_at": "2025-01-01T00:00:00Z"
    }
  ]
}
```

---

### 3.2 Get Organization
```http
GET /api/partner/organizations/{organization}
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "My Restaurant Group",
    "description": "Premium dining restaurants",
    "role": "owner",
    "restaurants": [
      {
        "id": 5,
        "name": "Restaurant A",
        "status": "active"
      }
    ],
    "team": [
      {
        "id": 2,
        "name": "Manager Name",
        "role": "manager",
        "email": "manager@example.com"
      }
    ]
  }
}
```

---

### 3.3 Update Organization
```http
PUT /api/partner/organizations/{organization}
```

**Permission Required:** `edit-organization`

**Request Body:**
```json
{
  "name": "Updated Restaurant Group",
  "description": "New description"
}
```

---

## 4. Team Management

### 4.1 List Team Members
```http
GET /api/partner/organizations/{organization}/team
```

**Permission Required:** `view-staff`

**Success Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 2,
      "name": "Jane Manager",
      "email": "jane@example.com",
      "role": "manager",
      "avatar_url": "https://...",
      "joined_at": "2025-02-01T00:00:00Z",
      "status": "active"
    }
  ]
}
```

---

### 4.2 Get Team Member
```http
GET /api/partner/organizations/{organization}/team/{user}
```

**Permission Required:** `view-staff`

---

### 4.3 Create Team Member
```http
POST /api/partner/organizations/{organization}/team
```

**Permission Required:** `create-staff`

**Request Body:**
```json
{
  "name": "New Staff",
  "email": "staff@example.com",
  "role": "staff",
  "password": "password123",
  "password_confirmation": "password123"
}
```

---

### 4.4 Update Team Member Role
```http
PUT /api/partner/organizations/{organization}/team/{user}/role
```

**Permission Required:** `edit-staff`

**Request Body:**
```json
{
  "role": "manager"
}
```

**Available Roles:**
- `owner` - სრული წვდომა
- `manager` - მენეჯერი (ყველაფერი გარდა წაშლისა)
- `staff` - თანამშრომელი (მხოლოდ ნახვა)

---

### 4.5 Delete Team Member
```http
DELETE /api/partner/organizations/{organization}/team/{user}
```

**Permission Required:** `delete-staff` (owner only)

---

## 5. Invitations

### 5.1 List Invitations
```http
GET /api/partner/organizations/{organization}/invitations
```

**Permission Required:** `view-staff`

**Success Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "email": "newstaff@example.com",
      "role": "staff",
      "status": "pending",
      "expires_at": "2025-11-01T00:00:00Z",
      "sent_at": "2025-10-21T10:00:00Z"
    }
  ]
}
```

---

### 5.2 Send Invitation
```http
POST /api/partner/organizations/{organization}/invitations
```

**Permission Required:** `invite-staff`

**Request Body:**
```json
{
  "email": "newstaff@example.com",
  "role": "staff"
}
```

---

### 5.3 Resend Invitation
```http
POST /api/partner/invitations/{invitation}/resend
```

**Permission Required:** `invite-staff`

---

### 5.4 Delete Invitation
```http
DELETE /api/partner/invitations/{invitation}
```

**Permission Required:** `invite-staff`

---

### 5.5 Public Invitation (არ საჭიროებს auth-ს)

#### Get Invitation Details
```http
GET /api/invitations/{token}
```

#### Accept Invitation
```http
POST /api/invitations/{token}/accept
```

**Request Body:**
```json
{
  "name": "John Doe",
  "password": "password123",
  "password_confirmation": "password123"
}
```

---

## 6. Restaurants

### 6.1 List All User's Restaurants
```http
GET /api/partner/restaurants
```

> 🎯 ყველა რესტორნის სია (ყველა ორგანიზაციიდან)

**Success Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 5,
      "name": "Restaurant A",
      "organization_id": 1,
      "organization_name": "My Restaurant Group",
      "status": "active",
      "address": "123 Main St, Tbilisi",
      "phone": "+995322123456",
      "images": ["https://..."],
      "rating": 4.5
    }
  ]
}
```

---

### 6.2 List Organization Restaurants
```http
GET /api/partner/organizations/{organization}/restaurants
```

---

### 6.3 Get Restaurant
```http
GET /api/partner/organizations/{organization}/restaurants/{restaurant}
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 5,
    "name": "Restaurant A",
    "organization_id": 1,
    "status": "active",
    "address": "123 Main St, Tbilisi",
    "phone": "+995322123456",
    "email": "info@restaurant-a.ge",
    "website": "https://restaurant-a.ge",
    "description": {
      "ka": "ქართული აღწერა",
      "en": "English description"
    },
    "images": [
      {
        "id": 1,
        "url": "https://...",
        "is_primary": true
      }
    ],
    "cuisine_types": ["Georgian", "European"],
    "features": ["wifi", "parking", "outdoor_seating"],
    "working_hours": {
      "monday": {"open": "10:00", "close": "23:00"},
      "tuesday": {"open": "10:00", "close": "23:00"}
    },
    "location": {
      "latitude": 41.7151,
      "longitude": 44.8271
    },
    "rating": 4.5,
    "reviews_count": 142,
    "places_count": 3,
    "tables_count": 25
  }
}
```

---

### 6.4 Create Restaurant
```http
POST /api/partner/organizations/{organization}/restaurants
```

**Request Body:**
```json
{
  "name": {
    "ka": "რესტორანი A",
    "en": "Restaurant A"
  },
  "address": "123 Main St, Tbilisi",
  "phone": "+995322123456",
  "email": "info@restaurant-a.ge",
  "description": {
    "ka": "ქართული აღწერა",
    "en": "English description"
  },
  "latitude": 41.7151,
  "longitude": 44.8271,
  "cuisine_types": ["Georgian", "European"],
  "features": ["wifi", "parking"]
}
```

---

### 6.5 Update Restaurant
```http
PUT /api/partner/organizations/{organization}/restaurants/{restaurant}
```

---

### 6.6 Upload Images
```http
POST /api/partner/organizations/{organization}/restaurants/{restaurant}/images
Content-Type: multipart/form-data
```

**Request Body:**
```
images[]: [File, File, ...]
is_primary: boolean (optional, for first image)
```

---

### 6.7 Delete Image
```http
DELETE /api/partner/organizations/{organization}/restaurants/{restaurant}/images/{imageId}
```

---

### 6.8 Update Status
```http
PUT /api/partner/organizations/{organization}/restaurants/{restaurant}/status
```

**Request Body:**
```json
{
  "status": "active" // or "inactive", "closed"
}
```

---

### 6.9 Get Settings
```http
GET /api/partner/organizations/{organization}/restaurants/{restaurant}/settings
```

---

### 6.10 Update Settings
```http
PUT /api/partner/organizations/{organization}/restaurants/{restaurant}/settings
```

**Request Body:**
```json
{
  "reservation_enabled": true,
  "online_payment_required": false,
  "advance_booking_days": 30,
  "cancellation_hours": 24,
  "min_party_size": 1,
  "max_party_size": 20
}
```

---

## 7. Places

> 🏛️ Places = დარბაზები/სივრცეები (მაგ: Main Hall, VIP Room, Terrace)

### 7.1 List Places
```http
GET /api/partner/organizations/{organization}/restaurants/{restaurant}/places
```

**Success Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": {
        "ka": "მთავარი დარბაზი",
        "en": "Main Hall"
      },
      "description": {
        "ka": "აღწერა",
        "en": "Description"
      },
      "capacity": 50,
      "status": "active",
      "tables_count": 10,
      "available_tables": 7
    }
  ]
}
```

---

### 7.2 Get Place
```http
GET /api/partner/organizations/{organization}/restaurants/{restaurant}/places/{place}
```

---

### 7.3 Create Place
```http
POST /api/partner/organizations/{organization}/restaurants/{restaurant}/places
```

**Request Body:**
```json
{
  "name": {
    "ka": "ტერასა",
    "en": "Terrace"
  },
  "description": {
    "ka": "ღია ტერასა",
    "en": "Open terrace"
  },
  "capacity": 30,
  "status": "active"
}
```

---

### 7.4 Update Place
```http
PUT /api/partner/organizations/{organization}/restaurants/{restaurant}/places/{place}
```

---

### 7.5 Delete Place
```http
DELETE /api/partner/organizations/{organization}/restaurants/{restaurant}/places/{place}
```

---

### 7.6 Update Place Status
```http
PUT /api/partner/organizations/{organization}/restaurants/{restaurant}/places/{place}/status
```

**Request Body:**
```json
{
  "status": "active" // or "inactive"
}
```

---

## 8. Tables

### 8.1 List Tables
```http
GET /api/partner/organizations/{organization}/restaurants/{restaurant}/tables
```

**Query Parameters:**
```
?place_id=1          // Filter by place
&status=active       // Filter by status
&min_capacity=4      // Min capacity
&max_capacity=8      // Max capacity
```

**Success Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "table_number": "T-01",
      "place_id": 1,
      "place_name": {
        "ka": "მთავარი დარბაზი",
        "en": "Main Hall"
      },
      "capacity": 4,
      "min_capacity": 2,
      "max_capacity": 6,
      "status": "active",
      "is_available": true,
      "location": {
        "x": 100,
        "y": 150
      }
    }
  ]
}
```

---

### 8.2 Get Table
```http
GET /api/partner/organizations/{organization}/restaurants/{restaurant}/tables/{table}
```

---

### 8.3 Create Table
```http
POST /api/partner/organizations/{organization}/restaurants/{restaurant}/tables
```

**Request Body:**
```json
{
  "table_number": "T-15",
  "place_id": 1,
  "capacity": 4,
  "min_capacity": 2,
  "max_capacity": 6,
  "status": "active",
  "location": {
    "x": 100,
    "y": 150
  }
}
```

---

### 8.4 Update Table
```http
PUT /api/partner/organizations/{organization}/restaurants/{restaurant}/tables/{table}
```

---

### 8.5 Delete Table
```http
DELETE /api/partner/organizations/{organization}/restaurants/{restaurant}/tables/{table}
```

---

### 8.6 Update Table Status
```http
PUT /api/partner/organizations/{organization}/restaurants/{restaurant}/tables/{table}/status
```

**Request Body:**
```json
{
  "status": "active" // or "inactive", "maintenance"
}
```

---

### 8.7 Check Table Availability
```http
GET /api/partner/organizations/{organization}/restaurants/{restaurant}/tables/{table}/availability
```

**Query Parameters:**
```
?date=2025-10-25
&time=19:00
&duration=2 (hours)
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "is_available": true,
    "table_id": 1,
    "table_number": "T-01",
    "available_time_slots": [
      "18:00", "18:30", "19:00", "19:30"
    ]
  }
}
```

---

### 8.8 Get Table Reservations
```http
GET /api/partner/organizations/{organization}/restaurants/{restaurant}/tables/{table}/reservations
```

**Query Parameters:**
```
?date_from=2025-10-21
&date_to=2025-10-31
&status=confirmed
```

---

## 9. Reservations

### 9.1 List Restaurant Reservations
```http
GET /api/partner/organizations/{organization}/restaurants/{restaurant}/reservations
```

**Query Parameters:**
```
?date_from=2025-10-21
&date_to=2025-10-31
&status=confirmed        // pending, confirmed, cancelled, completed, no-show
&place_id=1
&table_id=5
&page=1
&per_page=20
```

**Success Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 100,
      "reservation_number": "RES-20251021-0001",
      "customer_name": "John Doe",
      "customer_phone": "+995591234567",
      "customer_email": "john@example.com",
      "party_size": 4,
      "date": "2025-10-25",
      "time": "19:00",
      "duration": 2,
      "status": "confirmed",
      "table": {
        "id": 1,
        "table_number": "T-01",
        "place_name": "Main Hall"
      },
      "special_requests": "Window seat preferred",
      "payment_status": "pending",
      "deposit_amount": 50.00,
      "created_at": "2025-10-21T10:00:00Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "total": 45,
    "per_page": 20
  }
}
```

---

### 9.2 Get Reservation
```http
GET /api/partner/organizations/{organization}/restaurants/{restaurant}/reservations/{reservation}
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 100,
    "reservation_number": "RES-20251021-0001",
    "customer": {
      "name": "John Doe",
      "phone": "+995591234567",
      "email": "john@example.com",
      "total_reservations": 5,
      "total_cancelled": 0
    },
    "party_size": 4,
    "date": "2025-10-25",
    "time": "19:00",
    "duration": 2,
    "status": "confirmed",
    "table": {
      "id": 1,
      "table_number": "T-01",
      "place_id": 1,
      "place_name": "Main Hall",
      "capacity": 4
    },
    "special_requests": "Window seat preferred",
    "payment": {
      "status": "pending",
      "deposit_amount": 50.00,
      "total_amount": 50.00,
      "payment_method": null
    },
    "timeline": [
      {
        "action": "created",
        "timestamp": "2025-10-21T10:00:00Z",
        "user": "Customer"
      },
      {
        "action": "confirmed",
        "timestamp": "2025-10-21T10:05:00Z",
        "user": "Manager Name"
      }
    ],
    "created_at": "2025-10-21T10:00:00Z",
    "updated_at": "2025-10-21T10:05:00Z"
  }
}
```

---

### 9.3 Today's Reservations
```http
GET /api/partner/organizations/{organization}/restaurants/{restaurant}/reservations/today
```

---

### 9.4 Upcoming Reservations
```http
GET /api/partner/organizations/{organization}/restaurants/{restaurant}/reservations/upcoming
```

**Query Parameters:**
```
?days=7  // Next 7 days (default)
```

---

### 9.5 Calendar View
```http
GET /api/partner/organizations/{organization}/restaurants/{restaurant}/reservations/calendar
```

**Query Parameters:**
```
?start=2025-10-01
&end=2025-10-31
```

**Success Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 100,
      "title": "John Doe - 4 guests",
      "start": "2025-10-25T19:00:00Z",
      "end": "2025-10-25T21:00:00Z",
      "backgroundColor": "#28a745",
      "borderColor": "#28a745",
      "extendedProps": {
        "reservation_number": "RES-20251021-0001",
        "customer_phone": "+995591234567",
        "table_number": "T-01",
        "status": "confirmed"
      }
    }
  ]
}
```

---

### 9.6 Search Reservations
```http
GET /api/partner/organizations/{organization}/restaurants/{restaurant}/reservations/search
```

**Query Parameters:**
```
?q=john              // Search by name, phone, email
&date=2025-10-25     // Specific date
```

---

### 9.7 Reservation Statistics
```http
GET /api/partner/organizations/{organization}/restaurants/{restaurant}/reservations/statistics
```

**Query Parameters:**
```
?date_from=2025-10-01
&date_to=2025-10-31
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "total_reservations": 142,
    "confirmed": 98,
    "pending": 15,
    "cancelled": 20,
    "completed": 85,
    "no_show": 9,
    "total_guests": 568,
    "avg_party_size": 4,
    "revenue": {
      "total": 7500.00,
      "deposits": 4900.00,
      "paid": 2600.00
    }
  }
}
```

---

### 9.8 Update Reservation Status
```http
PUT /api/partner/organizations/{organization}/restaurants/{restaurant}/reservations/{reservation}/status
```

**Request Body:**
```json
{
  "status": "confirmed",  // pending, confirmed, cancelled, completed, no-show
  "reason": "Customer request" // Optional for cancellation
}
```

---

### 9.9 Quick Status Actions

#### Confirm Reservation
```http
POST /api/partner/organizations/{organization}/restaurants/{restaurant}/reservations/{reservation}/confirm
```

#### Cancel Reservation
```http
POST /api/partner/organizations/{organization}/restaurants/{restaurant}/reservations/{reservation}/cancel
```

**Request Body:**
```json
{
  "reason": "Customer cancelled" // Optional
}
```

#### Mark as Paid
```http
POST /api/partner/organizations/{organization}/restaurants/{restaurant}/reservations/{reservation}/paid
```

#### Mark as Completed
```http
POST /api/partner/organizations/{organization}/restaurants/{restaurant}/reservations/{reservation}/complete
```

#### Mark as No-Show
```http
POST /api/partner/organizations/{organization}/restaurants/{restaurant}/reservations/{reservation}/no-show
```

---

### 9.10 Initiate Payment
```http
POST /api/partner/organizations/{organization}/restaurants/{restaurant}/reservations/{reservation}/initiate-payment
```

**Request Body:**
```json
{
  "amount": 50.00,
  "return_url": "https://app.foodly.ge/reservations/payment-success"
}
```

---

### 9.11 Place Reservations
```http
GET /api/partner/organizations/{organization}/restaurants/{restaurant}/places/{place}/reservations
```

---

## 10. Dashboard & Analytics

### 10.1 Organization Dashboard
```http
GET /api/partner/organizations/{organization}/dashboard
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "overview": {
      "total_restaurants": 5,
      "total_reservations_today": 42,
      "total_reservations_month": 856,
      "total_revenue_month": 45600.00
    },
    "restaurants": [
      {
        "id": 5,
        "name": "Restaurant A",
        "reservations_today": 12,
        "reservations_pending": 3,
        "tables_occupied": 8,
        "total_tables": 25
      }
    ],
    "recent_reservations": [...],
    "trending": {
      "most_booked_restaurant": "Restaurant A",
      "peak_hour": "19:00",
      "avg_party_size": 4
    }
  }
}
```

---

### 10.2 Organization Stats
```http
GET /api/partner/organizations/{organization}/dashboard/stats
```

**Query Parameters:**
```
?period=month  // today, week, month, year
```

---

### 10.3 Organization Overview
```http
GET /api/partner/organizations/{organization}/dashboard/overview
```

---

### 10.4 Restaurant Dashboard
```http
GET /api/partner/organizations/{organization}/restaurants/{restaurant}/dashboard
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "today": {
      "total_reservations": 12,
      "confirmed": 8,
      "pending": 3,
      "cancelled": 1,
      "total_guests": 48,
      "tables_occupied": 6,
      "tables_available": 19
    },
    "upcoming": [...],
    "recent_activity": [...],
    "statistics": {
      "this_week": {...},
      "this_month": {...}
    }
  }
}
```

---

### 10.5 Analytics - Reservations
```http
GET /api/partner/organizations/{organization}/analytics/reservations
```

**Query Parameters:**
```
?date_from=2025-10-01
&date_to=2025-10-31
&restaurant_id=5
&group_by=day  // day, week, month
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "total": 142,
    "trend": "up",
    "change_percentage": 15.5,
    "by_status": {
      "confirmed": 98,
      "pending": 15,
      "cancelled": 20,
      "completed": 85
    },
    "timeline": [
      {
        "date": "2025-10-01",
        "count": 5
      }
    ]
  }
}
```

---

### 10.6 Analytics - Revenue
```http
GET /api/partner/organizations/{organization}/analytics/revenue
```

**Query Parameters:**
```
?date_from=2025-10-01
&date_to=2025-10-31
&restaurant_id=5
```

---

### 10.7 Analytics - Popular Tables
```http
GET /api/partner/organizations/{organization}/analytics/popular-tables
```

---

### 10.8 Analytics - Peak Hours
```http
GET /api/partner/organizations/{organization}/analytics/peak-hours
```

---

### 10.9 Analytics - Customer Insights
```http
GET /api/partner/organizations/{organization}/analytics/customer-insights
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "total_customers": 456,
    "new_customers": 89,
    "returning_customers": 367,
    "avg_party_size": 4,
    "top_customers": [
      {
        "name": "John Doe",
        "total_reservations": 15,
        "total_spent": 750.00
      }
    ]
  }
}
```

---

## 11. Booking Management

> 📅 Partner-assisted booking system

### 11.1 Get Available Slots
```http
POST /api/partner/booking/available-slots
```

**Request Body:**
```json
{
  "restaurant_id": 5,
  "date": "2025-10-25",
  "party_size": 4,
  "place_id": 1  // Optional
}
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "date": "2025-10-25",
    "available_slots": [
      {
        "time": "18:00",
        "available_tables": 5,
        "tables": [
          {
            "id": 1,
            "table_number": "T-01",
            "capacity": 4,
            "place_name": "Main Hall"
          }
        ]
      }
    ]
  }
}
```

---

### 11.2 Check Time Slot
```http
POST /api/partner/booking/check-time-slot
```

**Request Body:**
```json
{
  "restaurant_id": 5,
  "date": "2025-10-25",
  "time": "19:00",
  "party_size": 4,
  "duration": 2,
  "table_id": 1  // Optional
}
```

---

### 11.3 Send OTP
```http
POST /api/partner/booking/otp/send
```

**Request Body:**
```json
{
  "phone": "+995591234567"
}
```

---

### 11.4 Verify OTP
```http
POST /api/partner/booking/otp/verify
```

**Request Body:**
```json
{
  "phone": "+995591234567",
  "code": "123456"
}
```

---

### 11.5 Resend OTP
```http
POST /api/partner/booking/otp/resend
```

---

### 11.6 Create Reservation
```http
POST /api/partner/booking/reserve
```

**Request Body:**
```json
{
  "restaurant_id": 5,
  "table_id": 1,
  "customer_name": "John Doe",
  "customer_phone": "+995591234567",
  "customer_email": "john@example.com",
  "party_size": 4,
  "date": "2025-10-25",
  "time": "19:00",
  "duration": 2,
  "special_requests": "Window seat",
  "otp_verified": true
}
```

---

### 11.7 Customer History
```http
POST /api/partner/booking/customer-history
```

**Request Body:**
```json
{
  "phone": "+995591234567"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "customer": {
      "name": "John Doe",
      "phone": "+995591234567",
      "email": "john@example.com"
    },
    "statistics": {
      "total_reservations": 15,
      "completed": 12,
      "cancelled": 2,
      "no_show": 1
    },
    "recent_reservations": [...]
  }
}
```

---

## 12. Backups

> 🔐 **Permission Required:** `admin-backups` (Owner only)

### 12.1 List Backups
```http
GET /api/partner/backups
```

**Success Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "filename": "backup-2025-10-21-10-00-00.zip",
      "size": 15728640,
      "size_formatted": "15.0 MB",
      "created_at": "2025-10-21T10:00:00Z",
      "type": "full"
    }
  ]
}
```

---

### 12.2 Create Backup
```http
POST /api/partner/backups
```

**Request Body:**
```json
{
  "type": "full"  // full, database-only
}
```

---

### 12.3 Backup Status
```http
GET /api/partner/backups/status
```

---

### 12.4 Download Backup
```http
GET /api/partner/backups/{filename}/download
```

---

### 12.5 Delete Backup
```http
DELETE /api/partner/backups/{filename}
```

---

### 12.6 Cleanup Old Backups
```http
POST /api/partner/backups/cleanup
```

**Request Body:**
```json
{
  "keep_days": 30
}
```

---

## 13. Menu Management

> 🍽️ სრული მენიუს მართვის სისტემა

### 13.1 List Categories
```http
GET /api/partner/organizations/{organization}/restaurants/{restaurant}/menu/categories
```

---

### 13.2 Create Category
```http
POST /api/partner/organizations/{organization}/restaurants/{restaurant}/menu/categories
```

**Request Body:**
```json
{
  "name": {
    "ka": "ცხელი კერძები",
    "en": "Hot Dishes"
  },
  "description": {
    "ka": "აღწერა",
    "en": "Description"
  },
  "order": 1,
  "is_active": true
}
```

---

### 13.3 List Menu Items
```http
GET /api/partner/organizations/{organization}/restaurants/{restaurant}/menu/items
```

**Query Parameters:**
```
?category_id=1
&is_available=true
```

---

### 13.4 Create Menu Item
```http
POST /api/partner/organizations/{organization}/restaurants/{restaurant}/menu/items
```

**Request Body:**
```json
{
  "category_id": 1,
  "name": {
    "ka": "ხინკალი",
    "en": "Khinkali"
  },
  "description": {
    "ka": "ქართული ხინკალი",
    "en": "Georgian dumplings"
  },
  "price": 12.50,
  "is_available": true,
  "ingredients": ["beef", "onion", "spices"],
  "allergens": ["gluten"],
  "dietary": ["halal"]
}
```

---

### 13.5 Upload Menu Item Image
```http
POST /api/partner/organizations/{organization}/restaurants/{restaurant}/menu/items/{item}/image
Content-Type: multipart/form-data
```

---

## 📊 Status Codes & Error Handling

### Success Codes
- `200` - OK (GET, PUT, DELETE წარმატებული)
- `201` - Created (POST წარმატებული)
- `204` - No Content (DELETE წარმატებული, response body-ს გარეშე)

### Error Codes
- `400` - Bad Request (არასწორი მოთხოვნა)
- `401` - Unauthorized (არ არის ავტორიზებული)
- `403` - Forbidden (არ აქვს უფლება)
- `404` - Not Found (არ მოიძებნა)
- `422` - Validation Error (ვალიდაციის შეცდომა)
- `500` - Server Error (სერვერის შეცდომა)

### Error Response Format
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

---

## 🔑 Permissions System

| Permission | Description | Roles |
|-----------|-------------|-------|
| `view-organization` | ორგანიზაციის ნახვა | All |
| `edit-organization` | ორგანიზაციის რედაქტირება | Owner, Manager |
| `view-staff` | გუნდის ნახვა | Owner, Manager |
| `create-staff` | გუნდის დამატება | Owner, Manager |
| `edit-staff` | გუნდის რედაქტირება | Owner, Manager |
| `delete-staff` | გუნდის წაშლა | Owner |
| `invite-staff` | მოწვევის გაგზავნა | Owner, Manager |
| `admin-backups` | სარეზერვო ასლების მართვა | Owner |

---

## 🌍 Localization

ყველა multilingual field მხარს უჭერს:
- `ka` - ქართული
- `en` - English

**მაგალითი:**
```json
{
  "name": {
    "ka": "რესტორანი",
    "en": "Restaurant"
  }
}
```

---

## 📱 Pagination

პაგინაცია გამოიყენება `GET` ლისტინგ ენდპოინტებზე:

**Query Parameters:**
```
?page=1
&per_page=20
```

**Response Meta:**
```json
{
  "meta": {
    "current_page": 1,
    "from": 1,
    "to": 20,
    "total": 142,
    "per_page": 20,
    "last_page": 8
  }
}
```

---

## 🔍 Filtering & Sorting

**Filtering:**
```
?status=active
&date_from=2025-10-01
&date_to=2025-10-31
```

**Sorting:**
```
?sort_by=created_at
&sort_order=desc
```

---

## 🚀 Rate Limiting

- **Authentication endpoints:** 5 requests/minute
- **Standard endpoints:** 60 requests/minute
- **Heavy operations:** 10 requests/minute

---

## 📝 Notes

1. **Route Model Binding:** ყველა `{organization}`, `{restaurant}`, `{reservation}` და ა.შ. არის ID
2. **Soft Deletes:** უმეტესი წაშლა არის soft delete (შეგიძლიათ აღდგენა)
3. **Timestamps:** ყველა timestamp არის UTC timezone-ში
4. **File Uploads:** მაქსიმუმ 10MB per file
5. **Images:** მხარდაჭერილი ფორმატები: jpg, png, webp

---

## 🔗 Additional Resources

- Menu Management API: `/partner/menu.php`
- Public Invitation Flow: `/invitations/{token}`
- Payment Integration: BOG Payment Gateway

---

**📅 ბოლო განახლება:** 21 ოქტომბერი, 2025  
**📧 Support:** dev@foodly.ge
