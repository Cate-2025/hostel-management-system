<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payment Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Payment Management</h2>
                        <a href="{{ route('payments.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                            + Add Payment
                        </a>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-blue-800">Total Payments</h3>
                            <p class="text-2xl font-bold text-blue-600">${{ number_format($stats['total_payments'], 2) }}</p>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-yellow-800">Pending</h3>
                            <p class="text-2xl font-bold text-yellow-600">${{ number_format($stats['pending_payments'], 2) }}</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-green-800">Completed</h3>
                            <p class="text-2xl font-bold text-green-600">${{ number_format($stats['completed_payments'], 2) }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-800">Total Records</h3>
                            <p class="text-2xl font-bold text-gray-600">{{ $stats['total_records'] }}</p>
                        </div>
                    </div>

                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Payments Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-left">Student</th>
                                    <th class="px-4 py-2 text-left">Amount</th>
                                    <th class="px-4 py-2 text-left">Due Date</th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                    <th class="px-4 py-2 text-left">Payment Method</th>
                                    <th class="px-4 py-2 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($payments as $payment)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-2">
                                            {{ $payment->student->user->name }}
                                            <br><small class="text-gray-500">{{ $payment->student->enrollment_number }}</small>
                                        </td>
                                        <td class="px-4 py-2 font-semibold">${{ number_format($payment->amount, 2) }}</td>
                                        <td class="px-4 py-2">{{ $payment->due_date->format('M d, Y') }}</td>
                                        <td class="px-4 py-2">
                                            @if($payment->status == 'paid')
                                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-semibold">Paid</span>
                                            @elseif($payment->status == 'pending')
                                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-semibold">Pending</span>
                                            @elseif($payment->status == 'overdue')
                                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-semibold">Overdue</span>
                                            @else
                                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs font-semibold">{{ ucfirst($payment->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2">{{ $payment->payment_method ? ucfirst($payment->payment_method) : 'N/A' }}</td>
                                        <td class="px-4 py-2 text-center">
                                            @if($payment->status != 'paid')
                                                <form method="POST" action="{{ route('payments.mark-paid', $payment) }}" class="inline mr-2">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-green-600 hover:text-green-900 font-semibold text-sm">
                                                        Mark Paid
                                                    </button>
                                                </form>
                                            @endif
                                            <a href="{{ route('payments.receipt', $payment) }}" target="_blank" class="text-blue-600 hover:text-blue-900 font-semibold mr-3 text-sm">
                                                Receipt
                                            </a>
                                            <a href="{{ route('payments.edit', $payment) }}" class="text-blue-600 hover:text-blue-900 font-semibold mr-3 text-sm">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('payments.destroy', $payment) }}" class="inline" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-semibold text-sm">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                            No payments found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($payments->hasPages())
                        <div class="mt-6">
                            {{ $payments->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>