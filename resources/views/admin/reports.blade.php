<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reports & Analytics') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Reports & Analytics</h2>

                    <!-- Room Occupancy Report -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Room Occupancy Report</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h4 class="text-lg font-semibold text-blue-800">Total Rooms</h4>
                                <p class="text-3xl font-bold text-blue-600">{{ $totalRooms }}</p>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg">
                                <h4 class="text-lg font-semibold text-green-800">Occupied</h4>
                                <p class="text-3xl font-bold text-green-600">{{ $occupiedRooms }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-lg font-semibold text-gray-800">Available</h4>
                                <p class="text-3xl font-bold text-gray-600">{{ $availableRooms }}</p>
                            </div>
                            <div class="bg-purple-50 p-4 rounded-lg">
                                <h4 class="text-lg font-semibold text-purple-800">Occupancy Rate</h4>
                                <p class="text-3xl font-bold text-purple-600">{{ $occupancyRate }}%</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Summary Report -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Payment Summary</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h4 class="text-lg font-semibold text-blue-800">Total Payments</h4>
                                <p class="text-3xl font-bold text-blue-600">${{ number_format($totalPayments, 2) }}</p>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg">
                                <h4 class="text-lg font-semibold text-green-800">Paid</h4>
                                <p class="text-3xl font-bold text-green-600">${{ number_format($paidPayments, 2) }}</p>
                            </div>
                            <div class="bg-yellow-50 p-4 rounded-lg">
                                <h4 class="text-lg font-semibold text-yellow-800">Pending</h4>
                                <p class="text-3xl font-bold text-yellow-600">${{ number_format($pendingPayments, 2) }}</p>
                            </div>
                            <div class="bg-red-50 p-4 rounded-lg">
                                <h4 class="text-lg font-semibold text-red-800">Overdue</h4>
                                <p class="text-3xl font-bold text-red-600">${{ number_format($overduePayments, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Payment Chart -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Monthly Payment Summary ({{ date('Y') }})</h3>
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                                @php
                                    $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                                @endphp
                                @foreach($months as $index => $month)
                                    @php $monthNum = $index + 1; @endphp
                                    <div class="text-center">
                                        <div class="text-sm font-medium text-gray-600">{{ $month }}</div>
                                        <div class="text-lg font-bold text-blue-600">
                                            ${{ number_format($monthlyPayments[$monthNum] ?? 0, 0) }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Student Payment Summary -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Student Payment Summary</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full table-auto">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 text-left">Student Name</th>
                                        <th class="px-4 py-2 text-left">Enrollment</th>
                                        <th class="px-4 py-2 text-right">Total Paid ($)</th>
                                        <th class="px-4 py-2 text-right">Pending ($)</th>
                                        <th class="px-4 py-2 text-right">Total Amount ($)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($studentPayments as $student)
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="px-4 py-2">{{ $student->user->name }}</td>
                                            <td class="px-4 py-2">{{ $student->enrollment_number }}</td>
                                            <td class="px-4 py-2 text-right font-semibold text-green-600">
                                                ${{ number_format($student->paid_payments ?? 0, 2) }}
                                            </td>
                                            <td class="px-4 py-2 text-right font-semibold text-yellow-600">
                                                ${{ number_format($student->pending_payments ?? 0, 2) }}
                                            </td>
                                            <td class="px-4 py-2 text-right font-semibold text-blue-600">
                                                ${{ number_format(($student->payments_sum_amount ?? 0), 2) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                                No student payment data available.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Export Options -->
                    <div class="flex justify-end space-x-4">
                        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Print Report
                        </button>
                        <button onclick="exportToCSV()" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Export CSV
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function exportToCSV() {
            // Simple CSV export functionality
            let csv = 'Student Name,Enrollment,Total Paid,Pending,Total Amount\n';
            @foreach($studentPayments as $student)
                csv += '{{ addslashes($student->user->name) }},{{ $student->enrollment_number }},{{ $student->paid_payments ?? 0 }},{{ $student->pending_payments ?? 0 }},{{ ($student->paid_payments ?? 0) + ($student->pending_payments ?? 0) }}\n';
            @endforeach

            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'student_payment_report.csv';
            a.click();
            window.URL.revokeObjectURL(url);
        }
    </script>
</x-app-layout>