# Payment & Reports Module - Setup Guide

## Overview
The Payment & Reports module provides comprehensive payment management and reporting capabilities for the hostel management system. It includes CRUD operations for payments, receipt generation, balance tracking, and detailed admin reports.

---

## Module Components

### 1. Database Model: `Payment`

**Location:** `app/Models/Payment.php`

**Fields:**
- `id`: Primary key
- `student_id`: Foreign key to students table
- `amount`: Decimal amount of payment
- `payment_date`: When payment was made
- `due_date`: Payment due date
- `status`: Enum (pending, paid, overdue, cancelled)
- `payment_method`: Enum (cash, check, transfer, online)
- `transaction_id`: Unique transaction identifier
- `description`: Payment description
- `timestamps`: Created and updated timestamps

**Relationships:**
```php
public function student()
{
    return $this->belongsTo(Student::class);
}
```

**Scopes:**
```php
public function scopePending($query)    // Get pending payments
public function scopePaid($query)       // Get paid payments
```

---

### 2. Migration

**Location:** `database/migrations/2026_04_28_120400_create_payments_table.php`

Creates the payments table with proper indexes for efficient queries:
- Index on student_id
- Index on status
- Index on due_date

---

### 3. Controllers

#### AdminController
**Location:** `app/Http/Controllers/AdminController.php`

**Key Methods:**

```php
public function reports()
```
Generates comprehensive admin reports including:
- Room occupancy statistics
- Payment summaries by status
- Monthly payment trends (current year)
- Student payment details

**Returns:**
```php
[
    'totalRooms' => int,
    'occupiedRooms' => int,
    'availableRooms' => int,
    'occupancyRate' => float,
    'totalPayments' => decimal,
    'paidPayments' => decimal,
    'pendingPayments' => decimal,
    'overduePayments' => decimal,
    'monthlyPayments' => array,
    'studentPayments' => Collection
]
```

#### PaymentController
**Location:** `app/Http/Controllers/PaymentController.php`

**CRUD Operations:**

| Method | Route | Purpose |
|--------|-------|---------|
| index | GET /admin/payments | List all payments with stats |
| create | GET /admin/payments/create | Show create form |
| store | POST /admin/payments | Save new payment |
| edit | GET /admin/payments/{id}/edit | Show edit form |
| update | PUT /admin/payments/{id} | Update payment |
| destroy | DELETE /admin/payments/{id} | Delete payment |

**Special Actions:**

| Method | Route | Purpose |
|--------|-------|---------|
| markPaid | PATCH /admin/payments/{id}/mark-paid | Mark payment as paid |
| receipt | GET /admin/payments/{id}/receipt | Generate receipt |
| studentPayments | GET /student/payments | Student payment history |

---

### 4. Views

#### Admin Views

##### `resources/views/admin/payments/index.blade.php`
Lists all payments with:
- Payment statistics cards
- Filterable payment table
- Quick actions (mark paid, edit, delete, receipt)
- Pagination support

##### `resources/views/admin/payments/create.blade.php`
Form to create new payment with fields for:
- Student selection
- Amount
- Due date
- Payment date (optional)
- Status
- Payment method
- Transaction ID
- Description

##### `resources/views/admin/payments/edit.blade.php`
Form to edit existing payment (same fields as create)

##### `resources/views/admin/payments/receipt.blade.php`
Professional receipt template with:
- Hostel header
- Student information
- Payment details
- Amount and status
- Print/export functionality
- Auto-print on page load

##### `resources/views/admin/reports.blade.php`
Comprehensive reports dashboard showing:
- **Room Occupancy Report**
  - Total rooms, occupied, available, occupancy rate
- **Payment Summary**
  - Total payments, paid, pending, overdue amounts
- **Monthly Payment Chart**
  - Payment trends for current year
- **Student Payment Summary**
  - Table with student balances
- **Export Options**
  - Print report
  - Export to CSV

#### Student Views

##### `resources/views/student/payments.blade.php`
Student payment dashboard showing:
- Total paid amount
- Pending amount (amount due)
- Total payment records
- Payment history table
- Receipt view option

---

### 5. Routes

**Location:** `routes/web.php`

```php
// Admin Payment Routes
Route::resource('admin/payments', PaymentController::class);
Route::patch('/admin/payments/{payment}/mark-paid', [PaymentController::class, 'markPaid'])
    ->name('payments.mark-paid');
Route::get('/admin/payments/{payment}/receipt', [PaymentController::class, 'receipt'])
    ->name('payments.receipt');

// Admin Reports
Route::get('/admin/reports', [AdminController::class, 'reports'])
    ->name('admin.reports');

// Student Payment Routes
Route::get('/student/payments', [PaymentController::class, 'studentPayments'])
    ->name('student.payments');
```

---

## Feature Descriptions

### 1. Payment CRUD Operations

**Create Payment:**
- Admin can manually add payments
- Select student from dropdown
- Enter amount and dates
- Choose payment method
- Add optional transaction ID
- Include payment description

**Read Payments:**
- View all payments with pagination
- Filter by status via table
- Quick statistics overview
- See payment details inline

**Update Payment:**
- Edit any payment details
- Adjust amount or status
- Update payment method/transaction ID
- Modify descriptions

**Delete Payment:**
- Remove erroneous payments
- Confirmation required

### 2. Receipt Generation

**Features:**
- Professional PDF-ready format
- Student information display
- Payment details
- Transaction confirmation
- Automatic print dialog
- Print/save as PDF capability

**Accessed via:**
- Admin: Click "Receipt" button on payment
- Student: Click "View Receipt" on payment history

### 3. Balance Display

**Student Dashboard:**
- Shows total amount paid
- Displays pending/due amount
- Counts total payment transactions
- Lists payment history

**Calculation:**
```
Pending Amount = Sum of all payments with status='pending'
Total Paid = Sum of all payments with status='paid'
```

### 4. Admin Reports

#### Room Occupancy Report
- Total rooms in system
- Currently occupied rooms
- Available rooms
- Occupancy percentage

#### Payment Summary
- Total payments received
- Paid amount
- Pending amount (not yet received)
- Overdue amount

#### Monthly Trends
- Current year breakdown
- Payment totals by month
- Visual grid layout
- Helps identify peak payment periods

#### Student Payment Details
- Per-student breakdown
- Enrollment numbers
- Amount paid per student
- Amount pending per student
- Total amount per student

#### Export Functions
- **Print:** Browser print dialog
- **CSV Export:** Download student payment summary

---

## Usage Examples

### Admin: Create a Payment

```php
// Route: POST /admin/payments
// Form data:
[
    'student_id' => 5,
    'amount' => 500.00,
    'due_date' => '2026-05-31',
    'payment_date' => '2026-05-15',
    'status' => 'paid',
    'payment_method' => 'online',
    'transaction_id' => 'TXN-2026-05-001',
    'description' => 'Monthly hostel fee for May 2026'
]
```

### Admin: View Payment Reports

```
Route: GET /admin/reports
Returns: Admin dashboard with:
- 45 total rooms, 38 occupied (84% occupancy)
- $18,500 total payments, $16,200 paid, $2,300 pending
- Monthly breakdown for 2026
- Student-wise payment summary
```

### Student: Check Payment Status

```
Route: GET /student/payments
Shows:
- Total paid: $3,500
- Amount due: $500
- Payment history: 7 records
- Can download receipts
```

---

## Database Indexes

The payment table includes strategic indexes for performance:

```sql
INDEX student_id          -- Fast student lookup
INDEX status              -- Quick status filtering
INDEX due_date           -- Date range queries
UNIQUE transaction_id    -- Prevent duplicate transactions
```

---

## Status Workflow

```
pending ──(payment received)──> paid
   ↓
(due date passed)
   ↓
overdue ──(payment received)──> paid

paid/pending/overdue ──(admin action)──> cancelled
```

---

## Payment Methods Supported

- **Cash**: Physical money payment
- **Check**: Check payment
- **Transfer**: Bank transfer
- **Online**: Digital/online payment

Each method can have an optional transaction ID for reconciliation.

---

## Security Considerations

1. **Authentication:** All routes require authenticated user
2. **Authorization:** Admin-only routes protected by `admin` middleware
3. **Validation:** All inputs validated with Laravel validation rules
4. **Database:** Foreign key constraints prevent orphaned payments
5. **Soft Delete Option:** Payments can be marked cancelled instead of deleted

---

## Performance Optimizations

1. **Pagination:** Large payment lists paginated (15 per page)
2. **Eager Loading:** Relationships loaded with `with('student.user')`
3. **Database Indexes:** Strategic indexes on frequently queried fields
4. **Caching:** Monthly data can be cached for reports
5. **Query Optimization:** Count queries use efficient SQL aggregations

---

## Troubleshooting

### Issue: No payments displaying in admin view
**Solution:** 
- Check if students exist in database
- Verify student_id foreign key constraint
- Run: `php artisan migrate` to ensure table exists

### Issue: Receipt not printing correctly
**Solution:**
- Use Chrome or Firefox for best PDF results
- Check browser print settings
- Ensure JavaScript is enabled

### Issue: Reports showing incorrect totals
**Solution:**
- Verify payment status values are exactly: pending, paid, overdue, cancelled
- Check date format in database (should be YYYY-MM-DD)
- Run: `php artisan tinker` and verify `Payment::count()`

---

## Future Enhancements

1. **Automated Email Receipts:** Auto-send receipts to student email
2. **Payment Reminders:** Automated reminder emails for overdue payments
3. **Online Payment Gateway Integration:** Stripe/PayPal integration
4. **Recurring Payments:** Setup automatic monthly payments
5. **Advanced Reporting:** Charts, graphs, and financial summaries
6. **Payment Plans:** Allow installment payment setup
7. **Audit Trail:** Track all payment modifications
8. **Multi-currency Support:** Support different currencies

---

## File Checklist

✅ `app/Models/Payment.php` - Model with relationships
✅ `database/migrations/2026_04_28_120400_create_payments_table.php` - Schema
✅ `app/Http/Controllers/PaymentController.php` - CRUD controller
✅ `app/Http/Controllers/AdminController.php` - Reports controller
✅ `resources/views/admin/payments/index.blade.php` - Payment list
✅ `resources/views/admin/payments/create.blade.php` - Create form
✅ `resources/views/admin/payments/edit.blade.php` - Edit form
✅ `resources/views/admin/payments/receipt.blade.php` - Receipt template
✅ `resources/views/admin/reports.blade.php` - Reports dashboard
✅ `resources/views/student/payments.blade.php` - Student payments
✅ `routes/web.php` - Routes configured

---

## API Endpoints Summary

| Endpoint | Method | Auth | Purpose |
|----------|--------|------|---------|
| /admin/payments | GET | Admin | List payments |
| /admin/payments | POST | Admin | Create payment |
| /admin/payments/{id} | GET | Admin | Show payment |
| /admin/payments/{id} | PUT | Admin | Update payment |
| /admin/payments/{id} | DELETE | Admin | Delete payment |
| /admin/payments/{id}/mark-paid | PATCH | Admin | Mark as paid |
| /admin/payments/{id}/receipt | GET | Admin | View receipt |
| /admin/reports | GET | Admin | View reports |
| /student/payments | GET | Student | View my payments |

---

## Conclusion

The Payment & Reports module provides a complete solution for managing hostel payments with comprehensive reporting capabilities. The modular design allows for easy extensions and integrations with other systems.

For support or questions, refer to the Laravel documentation at https://laravel.com/docs
