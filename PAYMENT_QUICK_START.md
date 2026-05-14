# Payment & Reports Module - Quick Start Guide

## Getting Started

### 1. Database Setup

```bash
# Run migrations to create payments table
php artisan migrate
```

The migration creates the `payments` table with:
- Student relationship
- Status tracking (pending, paid, overdue, cancelled)
- Payment method tracking (cash, check, transfer, online)
- Automatic timestamps

### 2. Access Routes

#### Admin Routes (Protected by `admin` middleware)

| Route | Method | Purpose |
|-------|--------|---------|
| `/admin/payments` | GET | View all payments |
| `/admin/payments/create` | GET | Create payment form |
| `/admin/payments` | POST | Store new payment |
| `/admin/payments/{id}` | GET | Show payment details |
| `/admin/payments/{id}/edit` | GET | Edit payment form |
| `/admin/payments/{id}` | PUT | Update payment |
| `/admin/payments/{id}` | DELETE | Delete payment |
| `/admin/payments/{id}/mark-paid` | PATCH | Mark payment as paid |
| `/admin/payments/{id}/receipt` | GET | View/print receipt |
| `/admin/reports` | GET | View comprehensive reports |

#### Student Routes (Protected by `student` middleware)

| Route | Method | Purpose |
|-------|--------|---------|
| `/student/payments` | GET | View my payments & balances |

### 3. Create Your First Payment

**Via Admin Panel:**

1. Go to `/admin/payments`
2. Click "+ Add Payment"
3. Select student
4. Enter amount (e.g., 500.00)
5. Set due date
6. Choose payment method
7. Add transaction ID (if applicable)
8. Click "Create Payment"

**Via Code:**

```php
use App\Models\Payment;

Payment::create([
    'student_id' => 1,
    'amount' => 500.00,
    'due_date' => '2026-05-31',
    'payment_date' => now(),
    'status' => 'paid',
    'payment_method' => 'online',
    'transaction_id' => 'TXN-001',
    'description' => 'Monthly hostel fee'
]);
```

### 4. Common Operations

#### Mark Payment as Paid

```php
// Option 1: Via Controller
PATCH /admin/payments/{id}/mark-paid

// Option 2: Via Code
$payment = Payment::find(1);
$payment->update([
    'status' => 'paid',
    'payment_date' => now()
]);
```

#### Generate Receipt

```php
GET /admin/payments/{id}/receipt
```
Opens receipt in new window with print dialog.

#### Get Student's Total Due

```php
use App\Models\Student;

$student = Student::find(1);
$totalDue = $student->payments()
    ->where('status', 'pending')
    ->sum('amount');
// Returns: 250.50
```

#### Get Monthly Payment Report

```php
use App\Models\Payment;

$monthlyTotal = Payment::selectRaw('MONTH(payment_date) as month, SUM(amount) as total')
    ->where('status', 'paid')
    ->whereYear('payment_date', date('Y'))
    ->groupBy('month')
    ->get();
```

### 5. View Reports

**Admin Reports Dashboard:**

Navigate to `/admin/reports` to see:
- Room occupancy statistics
- Payment summaries
- Monthly trends
- Student-wise payment breakdown
- Export options (print/CSV)

---

## Status Management

### Payment Statuses

```
pending     - Payment not yet received
paid        - Payment received
overdue     - Payment due date passed, not paid
cancelled   - Payment cancelled/voided
```

### Status Transitions

```
pending ──(admin marks paid)──> paid
pending ──(due date passed)──> overdue  [automatic in queries]
any ──(admin deletes)──> deleted
```

---

## Filtering & Querying

### Get Pending Payments

```php
use App\Models\Payment;

$pending = Payment::pending()->get();
// SELECT * FROM payments WHERE status = 'pending'
```

### Get Paid Payments

```php
$paid = Payment::paid()->get();
// SELECT * FROM payments WHERE status = 'paid'
```

### Get Overdue Payments

```php
$overdue = Payment::where('status', 'overdue')
    ->where('due_date', '<', now())
    ->get();
```

### Get Student's Payments

```php
$student = Student::with('payments')->find(1);
$payments = $student->payments;

// Or query directly
$payments = Payment::where('student_id', 1)->get();
```

---

## Validation Rules

When creating/updating payments, these rules apply:

```php
[
    'student_id' => 'required|exists:students,id',
    'amount' => 'required|numeric|min:0.01',
    'due_date' => 'required|date',
    'payment_date' => 'nullable|date',
    'status' => 'required|in:pending,paid,overdue,cancelled',
    'payment_method' => 'required|in:cash,check,transfer,online',
    'transaction_id' => 'nullable|string|unique:payments',
    'description' => 'nullable|string',
]
```

---

## Tinker Examples

Interactive testing in Laravel Tinker:

```bash
php artisan tinker
```

```php
// Create payment
>>> use App\Models\Payment;
>>> Payment::create(['student_id' => 1, 'amount' => 500, 'due_date' => '2026-05-31', 'status' => 'pending', 'payment_method' => 'cash'])

// Get all payments
>>> Payment::all()

// Get payment with student details
>>> Payment::with('student.user')->first()

// Get pending payments
>>> Payment::where('status', 'pending')->sum('amount')

// Mark as paid
>>> $p = Payment::find(1)
>>> $p->update(['status' => 'paid', 'payment_date' => now()])

// Delete
>>> Payment::find(1)->delete()

// Count by status
>>> Payment::where('status', 'paid')->count()
```

---

## API Responses

### Create Payment Response

```php
POST /admin/payments
↓
Redirect: /admin/payments
Flash: 'Payment created successfully'
```

### Update Payment Response

```php
PUT /admin/payments/{id}
↓
Redirect: /admin/payments
Flash: 'Payment updated successfully'
```

### Delete Payment Response

```php
DELETE /admin/payments/{id}
↓
Redirect: /admin/payments
Flash: 'Payment deleted successfully'
```

### Receipt Response

```php
GET /admin/payments/{id}/receipt
↓
HTML page with print dialog
```

### Reports Response

```php
GET /admin/reports
↓
View with:
- Room occupancy data
- Payment summaries
- Monthly trends
- Student payment breakdown
```

---

## Troubleshooting

### Payment not showing in list
- Check student exists: `Student::find($student_id)`
- Verify status is valid: pending/paid/overdue/cancelled
- Check dates are valid

### Receipt blank/empty
- Ensure payment has related student: `$payment->student` exists
- Check student has user relationship: `$payment->student->user` exists
- Verify payment_date isn't causing format issues

### Reports showing zero
- Ensure payments exist: `Payment::count()`
- Check status values: `Payment::distinct()->pluck('status')`
- Verify date ranges: `Payment::pluck('payment_date')`

### Database errors
- Run fresh migrations: `php artisan migrate:fresh`
- Check foreign key constraint: `Student::find($student_id)`
- Verify indexes: `php artisan db:show`

---

## Performance Tips

### For Large Payment Lists

```php
// Use pagination
$payments = Payment::with('student.user')
    ->paginate(15);

// Use chunk for processing
Payment::chunk(100, function ($payments) {
    // Process batch
});
```

### For Reports

```php
// Cache monthly data
$monthly = cache()->remember('payments:monthly', 3600, function () {
    return Payment::selectRaw('MONTH(payment_date) as month, SUM(amount) as total')
        ->where('status', 'paid')
        ->whereYear('payment_date', date('Y'))
        ->groupBy('month')
        ->get();
});
```

### For Student Balance Calculations

```php
// Eager load to avoid N+1
$students = Student::with([
    'payments' => function ($q) {
        $q->where('status', 'pending');
    }
])->get();
```

---

## Export/Import

### Export Payments to CSV

```php
// Done automatically in reports view
Click "Export CSV" button on /admin/reports
```

### Export Single Receipt to PDF

```
1. Click Receipt button on payment
2. Use browser print dialog (Ctrl+P)
3. Choose "Save as PDF"
```

### Bulk Operations

```php
use App\Models\Payment;

// Mark all pending as paid
Payment::where('status', 'pending')
    ->update([
        'status' => 'paid',
        'payment_date' => now()
    ]);

// Delete cancelled payments
Payment::where('status', 'cancelled')
    ->delete();
```

---

## Security Notes

1. ✅ All routes protected by authentication middleware
2. ✅ Admin routes protected by `admin` middleware
3. ✅ Student routes protected by `student` middleware
4. ✅ Foreign key constraints prevent orphaned records
5. ✅ Transaction IDs are unique to prevent duplicates
6. ✅ All inputs validated before storage

---

## File Structure

```
app/
├── Http/
│   └── Controllers/
│       ├── PaymentController.php
│       └── AdminController.php
├── Models/
│   └── Payment.php
database/
├── migrations/
│   └── 2026_04_28_120400_create_payments_table.php
└── seeders/
    └── [Optional: PaymentSeeder.php]
resources/
└── views/
    ├── admin/
    │   ├── payments/
    │   │   ├── index.blade.php
    │   │   ├── create.blade.php
    │   │   ├── edit.blade.php
    │   │   └── receipt.blade.php
    │   └── reports.blade.php
    └── student/
        └── payments.blade.php
routes/
└── web.php (configured with payment routes)
```

---

## Next Steps

1. ✅ Run migrations: `php artisan migrate`
2. ✅ Test payment creation in admin panel
3. ✅ Generate a receipt
4. ✅ View student payments dashboard
5. ✅ Check admin reports
6. ✅ Export CSV report

---

## Support & Documentation

- Laravel Docs: https://laravel.com/docs
- Payment Model: `app/Models/Payment.php`
- Controller: `app/Http/Controllers/PaymentController.php`
- Setup Guide: `PAYMENT_REPORTS_SETUP.md`

