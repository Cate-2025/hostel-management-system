<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Payments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">My Payment History</h2>
                    </div>

                    <!-- Balance Summary -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-blue-800">Total Paid</h3>
                            <p class="text-2xl font-bold text-blue-600">
                                ${{ number_format($payments->where('status', 'paid')->sum('amount'), 2) }}
                            </p>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-yellow-800">Pending Amount</h3>
                            <p class="text-2xl font-bold text-yellow-600">${{ number_format($totalDue, 2) }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-800">Total Payments</h3>
                            <p class="text-2xl font-bold text-gray-600">{{ $payments->count() }}</p>
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
                                    <th class="px-4 py-2 text-left">Amount</th>
                                    <th class="px-4 py-2 text-left">Due Date</th>
                                    <th class="px-4 py-2 text-left">Payment Date</th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                    <th class="px-4 py-2 text-left">Payment Method</th>
                                    <th class="px-4 py-2 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($payments as $payment)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-2 font-semibold">${{ number_format($payment->amount, 2) }}</td>
                                        <td class="px-4 py-2">{{ $payment->due_date->format('M d, Y') }}</td>
                                        <td class="px-4 py-2">
                                            {{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : 'Not paid' }}
                                        </td>
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
                                            <a href="{{ route('payments.receipt', $payment) }}" target="_blank" class="text-blue-600 hover:text-blue-900 font-semibold text-sm">
                                                View Receipt
                                            </a>
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