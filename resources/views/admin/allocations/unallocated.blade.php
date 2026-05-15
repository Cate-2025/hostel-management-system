<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Unallocated Students') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Unallocated Students</h2>
                        <a href="{{ route('allocations.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
                            Back to Allocations
                        </a>
                    </div>

                    <!-- Students List -->
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-left">Name</th>
                                    <th class="px-4 py-2 text-left">Email</th>
                                    <th class="px-4 py-2 text-left">Enrollment #</th>
                                    <th class="px-4 py-2 text-left">Contact</th>
                                    <th class="px-4 py-2 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($students as $student)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-2 font-semibold">{{ $student->user->name }}</td>
                                        <td class="px-4 py-2">{{ $student->user->email }}</td>
                                        <td class="px-4 py-2">{{ $student->enrollment_number }}</td>
                                        <td class="px-4 py-2">{{ $student->contact_number }}</td>
                                        <td class="px-4 py-2 text-center">
                                            <a href="{{ route('allocations.allocate-student', $student) }}" class="text-blue-600 hover:text-blue-900 font-semibold">
                                                Allocate Room
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-2 text-center text-gray-500">
                                            All students are allocated! <a href="{{ route('allocations.index') }}" class="text-blue-600 hover:underline">View allocations</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $students->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
