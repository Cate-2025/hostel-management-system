# Hostel Management System - Part 1: Authentication & User Roles

## Project Overview
This is a Laravel-based Hostel Management System with complete authentication and role-based access control implemented. The system distinguishes between two main user roles: **Admin** and **Student**.

## Part 1: Authentication & User Roles Setup

### Features Implemented

#### 1. User Authentication (Breeze/UI)
- ✅ User registration with email verification
- ✅ User login/logout functionality
- ✅ Password reset functionality
- ✅ Remember me option
- ✅ Secure password hashing (bcrypt)

#### 2. Role-Based Access Control
- ✅ Two roles: **admin** and **student**
- ✅ Role-based middleware for route protection
- ✅ Helper methods in User model: `isAdmin()`, `isStudent()`

#### 3. Database Setup
- ✅ Modified `users` table with `role` enum field
- ✅ `students` table with student-specific information
- ✅ `rooms` table for room management
- ✅ `room_allocations` table for tracking student-room assignments
- ✅ `payments` table for payment tracking

#### 4. Middleware Protection
- ✅ `AdminMiddleware` - Restricts access to admin-only routes
- ✅ `StudentMiddleware` - Restricts access to student-only routes

#### 5. Seeders
- ✅ `AdminSeeder` - Creates admin accounts
- ✅ `StudentSeeder` - Creates sample student accounts
- ✅ `RoomSeeder` - Creates room data

### Models Created

1. **User** (Modified)
   - Added `role` field (enum: admin/student)
   - Methods: `isAdmin()`, `isStudent()`
   - Relationship: `hasOne(Student)`

2. **Student**
   - Fields: roll_number, enrollment_number, program, semester, contact_number, parent_contact, address
   - Relationships: `belongsTo(User)`, `belongsToMany(Room)`, `hasMany(Payment)`

3. **Room**
   - Fields: room_number, capacity, available_beds, floor, type, status
   - Relationships: `belongsToMany(Student)`

4. **Payment**
   - Fields: amount, payment_date, due_date, status, payment_method, transaction_id
   - Relationships: `belongsTo(Student)`

### Controllers Created

1. **AdminController** - Admin dashboard and management functions
2. **StudentController** - Student dashboard and profile functions
3. **RegisteredUserController** (Modified) - Enhanced student registration

### Middleware Created

1. **AdminMiddleware** - Verify admin role
2. **StudentMiddleware** - Verify student role

## Setup Instructions

### Prerequisites
- PHP 8.2+
- Composer
- SQLite or MySQL database
- Node.js & npm (for frontend assets)

### Installation Steps

1. **Clone/Navigate to Project**
   ```bash
   cd C:\Users\DELL\Desktop\hostel-management-system
   ```

2. **Install Dependencies**
   ```bash
   php C:\Users\DELL\AppData\Local\Bin\composer.phar install
   ```

3. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

4. **Run Migrations**
   ```bash
   php artisan migrate
   ```

5. **Seed the Database**
   ```bash
   php artisan db:seed
   ```

6. **Build Frontend Assets** (After Breeze setup)
   ```bash
   npm install && npm run build
   ```

7. **Start Development Server**
   ```bash
   php artisan serve
   ```

### Default Test Accounts

#### Admin Accounts
- **Email:** admin@hostel.com
- **Password:** password123

- **Email:** manager@hostel.com
- **Password:** password123

#### Student Accounts
- **John Doe:** john@student.com / password123
- **Jane Smith:** jane@student.com / password123
- **Mike Johnson:** mike@student.com / password123

## Authentication Flow

### Registration
1. User fills registration form with personal and academic information
2. Student role is automatically assigned
3. Student profile is created linked to the user
4. Email verification (if enabled)
5. User redirected to student dashboard

### Login
1. User enters email and password
2. System validates credentials
3. Role is checked to redirect to appropriate dashboard
4. Admin → Admin Dashboard
5. Student → Student Dashboard

## Authorization & Middleware

### Middleware Routes
```
Admin Routes:
- /admin/*  → Protected by AdminMiddleware

Student Routes:
- /student/* → Protected by StudentMiddleware
```

### Registering Middleware
Add to `app/Http/Kernel.php`:
```php
protected $routeMiddleware = [
    // ... existing middleware
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
    'student' => \App\Http\Middleware\StudentMiddleware::class,
];
```

### Route Protection Example
```php
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
});

Route::middleware(['auth', 'student'])->group(function () {
    Route::get('/student/dashboard', [StudentController::class, 'dashboard']);
});
```

## Database Schema

### Users Table
```sql
- id
- name
- email (unique)
- email_verified_at
- password
- role (enum: admin, student) ← NEW
- remember_token
- created_at, updated_at
```

### Students Table
```sql
- id
- user_id (foreign key)
- roll_number (unique)
- enrollment_number (unique)
- program
- semester
- contact_number
- parent_contact
- address
- created_at, updated_at
```

## Security Features

1. **Password Hashing** - bcrypt with 12 rounds (configurable)
2. **CSRF Protection** - All forms protected with @csrf
3. **Role-Based Authorization** - Middleware checks before action execution
4. **Email Verification** - Optional but recommended
5. **Secure Password Reset** - Token-based recovery

## Next Steps (Parts 2-5)

### Part 2: Student Management (CRUD)
- Student registration form
- Student profile management
- Student data updates/deletion
- Student listing

### Part 3: Room Management (CRUD)
- Room creation and configuration
- Room status tracking
- Room availability management
- Maintenance tracking

### Part 4: Allocation Workflow
- Room allocation algorithm
- Capacity management
- Allocation date tracking
- Release procedures

### Part 5: Payment & Reports
- Payment recording system
- Receipt generation
- Payment status tracking
- Admin reports generation

## Configuration Files

### .env
```
APP_NAME="Hostel Management System"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
DB_CONNECTION=sqlite
DB_DATABASE=database.sqlite
```

## Support & Documentation

- Laravel Documentation: https://laravel.com/docs
- Breeze Documentation: https://laravel.com/docs/breeze
- Authentication Guide: https://laravel.com/docs/authentication

## Notes

- All seeders create sample data for testing
- Admin accounts are pre-seeded with specific credentials
- Student registration creates both User and Student records
- Role assignments are enforced at the database level (enum)
