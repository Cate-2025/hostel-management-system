<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Student Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Student Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Your Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Name</p>
                            <p class="text-gray-900">{{ auth()->user()->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Email</p>
                            <p class="text-gray-900">{{ auth()->user()->email }}</p>
                        </div>
                        @if($student)
                            <div>
                                <p class="text-sm font-medium text-gray-500">Roll Number</p>
                                <p class="text-gray-900">{{ $student->roll_number ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Program</p>
                                <p class="text-gray-900">{{ $student->program ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Semester</p>
                                <p class="text-gray-900">{{ $student->semester ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Contact</p>
                                <p class="text-gray-900">{{ $student->contact_number ?? 'N/A' }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Room Allocation -->
            @if($room)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Room Allocation</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Room Number</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $room->room_number }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Capacity</p>
                                <p class="text-gray-900">{{ $room->capacity }} beds</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Floor</p>
                                <p class="text-gray-900">{{ $room->floor }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Room Type</p>
                                <p class="text-gray-900">{{ ucfirst($room->type) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-yellow-50 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="px-4 py-5 sm:p-6">
                        <p class="text-yellow-800">No room allocated yet. Please contact the administration.</p>
                    </div>
                </div>
            @endif

            <!-- Payment History -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Payment History</h3>
                    @if($payments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">₹{{ number_format($payment->amount, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment->due_date->format('d M Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($payment->payment_date)
                                                    {{ $payment->payment_date->format('d M Y') }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($payment->status === 'paid')
                                                        bg-green-100 text-green-800
                                                    @elseif($payment->status === 'pending')
                                                        bg-yellow-100 text-yellow-800
                                                    @else
                                                        bg-red-100 text-red-800
                                                    @endif
                                                ">
                                                    {{ ucfirst($payment->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $payments->links() }}
                        </div>
                    @else
                        <p class="text-gray-500">No payment records found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
