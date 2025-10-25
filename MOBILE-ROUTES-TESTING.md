# Mobile Partner Panel - Routes Testing Guide

## 🔐 Authentication Routes (Guest)

### Login
- **GET** `/mobile/login` - შესვლის გვერდი
- **POST** `/mobile/login` - ავტორიზაცია

## 🏠 Main Dashboard Routes (Auth Required)

### Profile Management
- **GET** `/mobile/profile` - პროფილის ნახვა
- **PUT** `/mobile/profile` - პროფილის განახლება
- **POST** `/mobile/profile/avatar` - ავატარის ატვირთვა
- **DELETE** `/mobile/profile/avatar` - ავატარის წაშლა
- **PUT** `/mobile/profile/password` - პაროლის შეცვლა

### Dashboard & Analytics
- **GET** `/mobile/dashboard/{org?}/{restaurant?}` - მთავარი dashboard
- **GET** `/mobile/organizations/{org}/dashboard/stats` - ორგანიზაციის სტატისტიკა
- **GET** `/mobile/organizations/{org}/restaurants/{restaurant}/calendar` - კალენდარი
- **GET** `/mobile/organizations/{org}/restaurants/{restaurant}/search` - რეზერვაციების ძიება
- **GET** `/mobile/organizations/{org}/analytics` - ანალიტიკა
- **POST** `/mobile/organizations/{org}/switch` - ორგანიზაციის გადართვა
- **GET** `/mobile/organizations/{org}/summary/{restaurant?}` - შეჯამება

### Authentication Helpers
- **POST** `/mobile/logout` - გასვლა
- **GET** `/mobile/check-auth` - ავტორიზაციის შემოწმება

## 🏢 Organization Management

### Organization CRUD
- **GET** `/mobile/organizations/{org}` - ორგანიზაციის ნახვა
- **PUT** `/mobile/organizations/{org}` - ორგანიზაციის განახლება
- **GET** `/mobile/organizations/{org}/data` - ორგანიზაციის მონაცემები (AJAX)

### Team Management
- **GET** `/mobile/organizations/{org}/team` - გუნდის მართვა
- **GET** `/mobile/organizations/{org}/team/data` - გუნდის მონაცემები (AJAX)
- **POST** `/mobile/organizations/{org}/team` - ახალი წევრის დამატება
- **PUT** `/mobile/organizations/{org}/team/{user}/role` - როლის შეცვლა
- **DELETE** `/mobile/organizations/{org}/team/{user}` - წევრის წაშლა

### Invitations
- **POST** `/mobile/organizations/{org}/invitations` - მოწვევის გაგზავნა
- **POST** `/mobile/organizations/{org}/invitations/{invitation}/resend` - მოწვევის თავიდან გაგზავნა
- **DELETE** `/mobile/organizations/{org}/invitations/{invitation}` - მოწვევის წაშლა

## 🍽️ Restaurant Management

### Restaurant CRUD
- **GET** `/mobile/organizations/{org}/restaurants` - რესტორნების სია
- **GET** `/mobile/organizations/{org}/restaurants/create` - ახალი რესტორნის შექმნის ფორმა
- **POST** `/mobile/organizations/{org}/restaurants` - რესტორნის შექმნა
- **GET** `/mobile/organizations/{org}/restaurants/{restaurant}` - რესტორნის ნახვა
- **GET** `/mobile/organizations/{org}/restaurants/{restaurant}/edit` - რესტორნის რედაქტირება
- **PUT** `/mobile/organizations/{org}/restaurants/{restaurant}` - რესტორნის განახლება
- **PUT** `/mobile/organizations/{org}/restaurants/{restaurant}/status` - სტატუსის შეცვლა
- **GET** `/mobile/organizations/{org}/restaurants/{restaurant}/data` - რესტორნის მონაცემები (AJAX)

### Images Management
- **POST** `/mobile/organizations/{org}/restaurants/{restaurant}/images` - ფოტოების ატვირთვა
- **DELETE** `/mobile/organizations/{org}/restaurants/{restaurant}/images/{image}` - ფოტოს წაშლა

### Settings
- **GET** `/mobile/organizations/{org}/restaurants/{restaurant}/settings` - პარამეტრები
- **PUT** `/mobile/organizations/{org}/restaurants/{restaurant}/settings` - პარამეტრების განახლება

### Places (დარბაზები)
- **GET** `/mobile/organizations/{org}/restaurants/{restaurant}/places` - დარბაზების სია
- **POST** `/mobile/organizations/{org}/restaurants/{restaurant}/places` - ახალი დარბაზის შექმნა
- **PUT** `/mobile/organizations/{org}/restaurants/{restaurant}/places/{place}` - დარბაზის განახლება
- **DELETE** `/mobile/organizations/{org}/restaurants/{restaurant}/places/{place}` - დარბაზის წაშლა

### Tables (მაგიდები)
- **GET** `/mobile/organizations/{org}/restaurants/{restaurant}/tables` - მაგიდების სია
- **POST** `/mobile/organizations/{org}/restaurants/{restaurant}/tables` - ახალი მაგიდის შექმნა
- **PUT** `/mobile/organizations/{org}/restaurants/{restaurant}/tables/{table}` - მაგიდის განახლება
- **PUT** `/mobile/organizations/{org}/restaurants/{restaurant}/tables/{table}/status` - მაგიდის სტატუსი
- **DELETE** `/mobile/organizations/{org}/restaurants/{restaurant}/tables/{table}` - მაგიდის წაშლა
- **GET** `/mobile/organizations/{org}/restaurants/{restaurant}/tables/{table}/availability` - ხელმისაწვდომობა

## 📋 Reservation Management

### Reservations List & View
- **GET** `/mobile/organizations/{org}/restaurants/{restaurant}/reservations` - რეზერვაციების სია
- **GET** `/mobile/organizations/{org}/restaurants/{restaurant}/reservations/upcoming` - მომავალი რეზერვაციები
- **GET** `/mobile/organizations/{org}/restaurants/{restaurant}/reservations/data` - მონაცემები (AJAX)
- **GET** `/mobile/organizations/{org}/restaurants/{restaurant}/reservations/counts` - რაოდენობები (AJAX)
- **GET** `/mobile/organizations/{org}/restaurants/{restaurant}/reservations/{reservation}` - რეზერვაციის დეტალები

### Reservation Actions
- **POST** `/mobile/organizations/{org}/restaurants/{restaurant}/reservations/{reservation}/confirm` - დადასტურება
- **POST** `/mobile/organizations/{org}/restaurants/{restaurant}/reservations/{reservation}/cancel` - გაუქმება
- **POST** `/mobile/organizations/{org}/restaurants/{restaurant}/reservations/{reservation}/paid` - გადახდილად მონიშვნა
- **POST** `/mobile/organizations/{org}/restaurants/{restaurant}/reservations/{reservation}/complete` - დასრულება
- **POST** `/mobile/organizations/{org}/restaurants/{restaurant}/reservations/{reservation}/no-show` - no-show მონიშვნა
- **PUT** `/mobile/organizations/{org}/restaurants/{restaurant}/reservations/{reservation}/status` - სტატუსის შეცვლა
- **POST** `/mobile/organizations/{org}/restaurants/{restaurant}/reservations/{reservation}/payment` - გადახდის ინიცირება

### Bulk Actions
- **POST** `/mobile/organizations/{org}/restaurants/{restaurant}/reservations/bulk-action` - ნაკრები მოქმედებები

## 📝 Booking System (Partner-Assisted)

### Booking Flow
- **GET** `/mobile/organizations/{org}/booking/restaurants` - რესტორნების არჩევა
- **GET** `/mobile/organizations/{org}/restaurants/{restaurant}/booking/select-date` - თარიღის არჩევა
- **GET** `/mobile/organizations/{org}/restaurants/{restaurant}/booking/select-time` - დროის არჩევა
- **GET** `/mobile/organizations/{org}/restaurants/{restaurant}/booking/details` - დეტალების შეყვანა
- **POST** `/mobile/organizations/{org}/restaurants/{restaurant}/booking/create` - რეზერვაციის შექმნა

### Booking AJAX Endpoints
- **GET** `/mobile/organizations/{org}/restaurants/{restaurant}/booking/available-slots` - ხელმისაწვდომი დრო
- **POST** `/mobile/organizations/{org}/restaurants/{restaurant}/booking/check-time-slot` - დროის შემოწმება
- **POST** `/mobile/organizations/{org}/restaurants/{restaurant}/booking/validate` - ვალიდაცია

### OTP & Customer Management
- **POST** `/mobile/booking/otp/send` - OTP-ს გაგზავნა
- **POST** `/mobile/booking/otp/verify` - OTP-ს ვერიფიკაცია
- **POST** `/mobile/booking/otp/resend` - OTP-ს თავიდან გაგზავნა
- **POST** `/mobile/booking/customer-history` - კლიენტის ისტორია

## 💳 Payment Routes

### Payment Management
- **GET** `/mobile/payments/history` - გადახდების ისტორია
- **GET** `/mobile/payments/organizations/{org}/restaurants/{restaurant}/reservations/{reservation}/success` - წარმატებული გადახდა
- **GET** `/mobile/payments/organizations/{org}/restaurants/{restaurant}/reservations/{reservation}/failed` - ვერ განხორციელებული გადახდა

## 🔗 Fallback Route
- **GET** `/mobile` - redirect to dashboard

---

## 🧪 Testing Instructions

### 1. Authentication Testing
```bash
# Login page
curl -X GET http://localhost:8000/mobile/login

# Login request
curl -X POST http://localhost:8000/mobile/login \
  -d "email=test@example.com&password=password123"
```

### 2. Dashboard Testing  
```bash
# Main dashboard
curl -X GET http://localhost:8000/mobile/dashboard/1/5 \
  -H "Cookie: laravel_session=your_session_cookie"

# Stats
curl -X GET http://localhost:8000/mobile/organizations/1/dashboard/stats?period=month
```

### 3. Organization Testing
```bash
# Organization info
curl -X GET http://localhost:8000/mobile/organizations/1

# Team management  
curl -X GET http://localhost:8000/mobile/organizations/1/team
```

### 4. Restaurant Testing
```bash
# Restaurant list
curl -X GET http://localhost:8000/mobile/organizations/1/restaurants

# Restaurant details
curl -X GET http://localhost:8000/mobile/organizations/1/restaurants/5
```

### 5. Reservation Testing
```bash
# Reservations list
curl -X GET http://localhost:8000/mobile/organizations/1/restaurants/5/reservations

# Confirm reservation
curl -X POST http://localhost:8000/mobile/organizations/1/restaurants/5/reservations/100/confirm
```

### 6. Booking Testing
```bash
# Available slots
curl -X GET "http://localhost:8000/mobile/organizations/1/restaurants/5/booking/available-slots?date=2025-10-27&party_size=4"

# Create booking
curl -X POST http://localhost:8000/mobile/organizations/1/restaurants/5/booking/create \
  -d "customer_name=John Doe&customer_phone=+995591234567&date=2025-10-27&time=19:00&party_size=4&table_id=1"
```

---

**Note:** ყველა authenticated route საჭიროებს session cookie-ს ან valid token-ს Authorization header-ში.