# Quick Setup Guide - After Installation

## Complete the Setup (Run in Terminal)

### 1. Generate Application Key
```bash
php artisan key:generate
```

### 2. Create SQLite Database
```bash
# The database.sqlite file will be created automatically on migration
```

### 3. Run All Migrations
```bash
php artisan migrate
```

### 4. Seed the Database with Admin and Student Accounts
```bash
php artisan db:seed
```

### 5. (Optional) Install Frontend Dependencies
```bash
npm install
npm run build
```

### 6. Start Development Server
```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## Login Credentials

### Admin Account
- **Email:** admin@hostel.com
- **Password:** password123

### Student Accounts
- **John Doe:** john@student.com / password123
- **Jane Smith:** jane@student.com / password123
- **Mike Johnson:** mike@student.com / password123

## File Structure Created

### Models
- `app/Models/User.php` - Updated with role field and relationships
- `app/Models/Student.php` - Student profile linked to User
- `app/Models/Room.php` - Room management
- `app/Models/Payment.php` - Payment tracking

### Migrations
- `database/migrations/*_add_role_to_users_table.php`
- `database/migrations/*_create_students_table.php`
- `database/migrations/*_create_rooms_table.php`
- `database/migrations/*_create_room_allocations_table.php`
- `database/migrations/*_create_payments_table.php`

### Middleware
- `app/Http/Middleware/AdminMiddleware.php` - Verify admin access
- `app/Http/Middleware/StudentMiddleware.php` - Verify student access

### Controllers
- `app/Http/Controllers/AdminController.php` - Admin dashboard functions
- `app/Http/Controllers/StudentController.php` - Student dashboard functions
- `app/Http/Controllers/Auth/RegisteredUserController.php` - Enhanced registration

### Seeders
- `database/seeders/AdminSeeder.php` - Creates admin accounts
- `database/seeders/StudentSeeder.php` - Creates student accounts with profiles
- `database/seeders/RoomSeeder.php` - Creates sample rooms
- `database/seeders/DatabaseSeeder.php` - Master seeder

## Register Middleware in Routes

Add to `routes/web.php` after Breeze setup:

```php
Route::middleware(['auth', 'verified'])->group(function () {
    // Admin Routes
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/students', [AdminController::class, 'students'])->name('admin.students');
        Route::get('/rooms', [AdminController::class, 'rooms'])->name('admin.rooms');
        Route::get('/payments', [AdminController::class, 'payments'])->name('admin.payments');
    });
    
    // Student Routes
    Route::middleware('student')->prefix('student')->group(function () {
        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
        Route::get('/profile', [StudentController::class, 'profile'])->name('student.profile');
        Route::get('/room', [StudentController::class, 'roomDetails'])->name('student.room');
        Route::get('/payments', [StudentController::class, 'payments'])->name('student.payments');
    });
});
```

## Register Middleware in Kernel

Add to `app/Http/Kernel.php` in `$routeMiddleware` array:

```php
'admin' => \App\Http\Middleware\AdminMiddleware::class,
'student' => \App\Http\Middleware\StudentMiddleware::class,
```

## Troubleshooting

### Missing vendor directory
Run: `php composer.phar install` (or with full path if in different directory)

### Database errors
Ensure `database.sqlite` exists in the project root, or create it:
```bash
touch database/database.sqlite
```

### Artisan commands not working
Make sure you're in the project directory:
```bash
cd C:\Users\DELL\Desktop\hostel-management-system
```

## Next Steps

After completing setup:
1. Verify admin login works
2. Verify student login works
3. Test role-based access control
4. Check database tables are created correctly
5. Proceed with Part 2: Student Management CRUD
