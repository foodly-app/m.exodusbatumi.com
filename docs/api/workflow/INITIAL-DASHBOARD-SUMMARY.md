# 📋 Initial Dashboard Endpoint - Summary

## ✅ რას აკეთებს?

`GET /api/partner/initial-dashboard` ენდპოინტი არის პირველი API call რომელიც უნდა გაკეთდეს Login-ის შემდეგ.

---

## 🎯 რას აბრუნებს?

### 1️⃣ მომხმარებლის ინფორმაცია
```json
{
  "user": {
    "id": 1,
    "name": "გიორგი გელაშვილი",
    "email": "partner@example.com",
    "phone": "+995555123456",
    "roles": ["manager", "owner"],
    "permissions": ["view-organization", "view-reservations"]
  }
}
```

### 2️⃣ ორგანიზაციის ინფორმაცია
```json
{
  "organization": {
    "id": 1,
    "name": "My Restaurant Group",
    "email": "info@example.com",
    "phone": "+995555123456"
  }
}
```

### 3️⃣ ყველა რესტორნის სია (Dropdown-ისთვის)
```json
{
  "restaurants": [
    {
      "id": 1,
      "name": "ჩემი რესტორანი",
      "slug": "chemi-restorani",
      "address": "თბილისი, რუსთაველის 123",
      "logo": "https://...",
      "status": "active"
    },
    {
      "id": 2,
      "name": "მეორე რესტორანი",
      "slug": "meore-restorani",
      "address": "თბილისი, ვაკე",
      "logo": "https://...",
      "status": "active"
    }
  ]
}
```

### 4️⃣ Default არჩეული რესტორანი (ID-ით პირველი)
```json
{
  "selected_restaurant": {
    "id": 1,
    "name": "ჩემი რესტორანი",
    "slug": "chemi-restorani",
    "address": "თბილისი, რუსთაველის 123",
    "logo": "https://...",
    "phone": "+995555999888",
    "email": "restaurant@example.com"
  }
}
```

### 5️⃣ Dashboard სტატისტიკა
```json
{
  "dashboard": {
    "today_stats": {
      "total_reservations": 25,  // ჯამური ჯავშნები დღეს
      "confirmed": 18,            // დადასტურებული
      "pending": 5,               // მოლოდინში
      "completed": 2,             // დასრულებული
      "cancelled": 0              // გაუქმებული
    },
    "upcoming_reservations": 47,  // მომავალი 7 დღე
    "tables": {
      "total": 20,                // ჯამური მაგიდები
      "active": 18                // აქტიური მაგიდები
    },
    "places": {
      "total": 3,                 // ჯამური places
      "active": 3                 // აქტიური places
    },
    "recent_reservations": [      // ბოლო 5 ჯავშანი
      {
        "id": 123,
        "name": "გიორგი გელაშვილი",
        "phone": "+995555111222",
        "guests_count": 4,
        "reservation_date": "2025-10-27",
        "time_from": "19:00:00",
        "time_to": "21:00:00",
        "status": "confirmed"
      }
    ]
  }
}
```

---

## 🔑 Key Features

### ✅ ავტომატური Default რესტორნის არჩევა
- Backend ავტომატურად არჩევს პირველ რესტორანს (ID-ით)
- Frontend არ უნდა აკეთებდეს დამატებით request-ს

### ✅ ყველა რესტორნის სია
- Frontend იღებს სრულ სიას dropdown-ისთვის
- მომხმარებელს შეუძლია სხვა რესტორანზე გადართვა

### ✅ Real-time სტატისტიკა
- დღევანდელი ჯავშნების რაოდენობა
- მომავალი კვირის ჯავშნები
- მაგიდებისა და places-ის რაოდენობა

### ✅ ბოლო ჯავშნები
- ბოლო 5 ჯავშანი (created_at DESC)
- სწრაფი overview რა ხდება რესტორანში

---

## 🔄 Frontend Flow

```
┌─────────────────────────────────────────┐
│ 1. POST /api/partner/login              │
│    → მიიღე token                        │
└──────────────┬──────────────────────────┘
               │
               ▼
┌─────────────────────────────────────────┐
│ 2. შეინახე token localStorage-ში        │
└──────────────┬──────────────────────────┘
               │
               ▼
┌─────────────────────────────────────────┐
│ 3. Redirect to /dashboard               │
└──────────────┬──────────────────────────┘
               │
               ▼
┌─────────────────────────────────────────┐
│ 4. GET /api/partner/initial-dashboard   │
│    Authorization: Bearer {token}        │
└──────────────┬──────────────────────────┘
               │
               ▼
┌─────────────────────────────────────────┐
│ 5. გამოაჩინე Dashboard UI:              │
│    • User info (სახელი, როლი)          │
│    • Restaurant dropdown (ყველა)        │
│    • Default selected restaurant        │
│    • Today stats (ჯავშნები)             │
│    • Recent reservations                │
└─────────────────────────────────────────┘
```

---

## 📝 რა შეიცვალა?

### Before (ძველი სისტემა)
```
Login → Dashboard → გააკეთე მრავალი API call
  ↓
  ├─ GET /organizations
  ├─ GET /organizations/{id}/restaurants
  ├─ GET /restaurants/{id}/dashboard
  └─ GET /reservations
```

### After (ახალი სისტემა) ✅
```
Login → Dashboard → ერთი API call
  ↓
  └─ GET /initial-dashboard
      ↓
      აბრუნებს ყველაფერს ერთბაშად!
```

---

## ✅ რა უპირატესობა აქვს?

1. **🚀 უფრო სწრაფია** - ერთი request ნაცვლად 4-5-ის
2. **💡 მარტივია** - Frontend-ს არ სჭირდება რთული ლოგიკა
3. **🎯 Default Selection** - Backend ავტომატურად არჩევს პირველ რესტორანს
4. **📊 Real-time Stats** - დღევანდელი ჯავშნები და სტატისტიკა
5. **🔄 Flexible** - მომხმარებელს შეუძლია რესტორნის შეცვლა

---

## 🧪 როგორ დავტესტო?

### Postman-ში:
1. Login request-ით მიიღე token
2. `apiToken` environment variable-ში ჩასვი token
3. გააგზავნე `GET /api/partner/initial-dashboard`
4. შეამოწმე response structure

### Frontend-ში:
```javascript
async function loadDashboard() {
  const token = localStorage.getItem('authToken');
  
  const response = await fetch(
    'https://api.foodlyapp.ge/api/partner/initial-dashboard',
    {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept-Language': 'ka'
      }
    }
  );
  
  const result = await response.json();
  
  if (result.success) {
    console.log('User:', result.data.user);
    console.log('Organization:', result.data.organization);
    console.log('Restaurants:', result.data.restaurants);
    console.log('Selected:', result.data.selected_restaurant);
    console.log('Stats:', result.data.dashboard.today_stats);
  }
}
```

---

## 📚 დამატებითი დოკუმენტაცია

- **Frontend Integration გიდი:** [INITIAL-DASHBOARD-FLOW.md](./INITIAL-DASHBOARD-FLOW.md)
- **Test Cases:** [INITIAL-DASHBOARD-TESTS.md](./INITIAL-DASHBOARD-TESTS.md)
- **API დოკუმენტაცია:** `/docs/INITIAL-DASHBOARD-API.md`

---

## 🎉 მზადაა!

ახლა შეგიძლია:
1. ✅ Postman-ში დატესტო
2. ✅ Frontend-ში ინტეგრირება დაიწყო
3. ✅ Dashboard UI-ს აშენება დაიწყო

თუ რაიმე კითხვა გაქვს - მომწერე! 🚀
