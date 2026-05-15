<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - {{ $payment->student->user->name }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .receipt-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #333;
            margin: 0;
            font-size: 28px;
        }
        .header p {
            color: #666;
            margin: 5px 0;
        }
        .receipt-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .info-section {
            flex: 1;
        }
        .info-section h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 16px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .info-section p {
            margin: 5px 0;
            color: #555;
        }
        .payment-details {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 30px;
        }
        .amount {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
            text-align: center;
            margin: 20px 0;
        }
        .status {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
        }
        .status.paid {
            background: #d4edda;
            color: #155724;
        }
        .status.pending {
            background: #fff3cd;
            color: #856404;
        }
        .footer {
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 20px;
            color: #666;
            font-size: 12px;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .receipt-container {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="header">
            <h1>Hostel Management System</h1>
            <p>Payment Receipt</p>
            <p>Receipt #{{ $payment->id }}</p>
        </div>

        <div class="receipt-info">
            <div class="info-section">
                <h3>Student Information</h3>
                <p><strong>Name:</strong> {{ $payment->student->user->name }}</p>
                <p><strong>Enrollment:</strong> {{ $payment->student->enrollment_number }}</p>
                <p><strong>Email:</strong> {{ $payment->student->user->email }}</p>
                <p><strong>Contact:</strong> {{ $payment->student->contact_number }}</p>
            </div>
            <div class="info-section">
                <h3>Payment Information</h3>
                <p><strong>Payment Date:</strong> {{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : 'N/A' }}</p>
                <p><strong>Due Date:</strong> {{ $payment->due_date->format('M d, Y') }}</p>
                <p><strong>Payment Method:</strong> {{ $payment->payment_method ? ucfirst($payment->payment_method) : 'N/A' }}</p>
                <p><strong>Transaction ID:</strong> {{ $payment->transaction_id ?: 'N/A' }}</p>
            </div>
        </div>

        <div class="payment-details">
            <h3 style="text-align: center; margin-bottom: 20px;">Payment Details</h3>
            @if($payment->description)
                <p><strong>Description:</strong> {{ $payment->description }}</p>
            @endif
            <div class="amount">
                Amount Paid: ${{ number_format($payment->amount, 2) }}
            </div>
            <div style="text-align: center;">
                <span class="status {{ $payment->status }}">
                    {{ ucfirst($payment->status) }}
                </span>
            </div>
        </div>

        <div class="footer">
            <p>This is an official receipt from Hostel Management System</p>
            <p>Generated on {{ now()->format('M d, Y \a\t H:i') }}</p>
            <p>Thank you for your payment!</p>
        </div>
    </div>

    <script>
        // Auto-print when loaded
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>