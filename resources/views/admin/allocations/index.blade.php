<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Room Allocations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-500">Total Students</p>
                                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $totalStudents }}</p>
                            </div>
                            <div class="inline-flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-2a6 6 0 0112 0v2zm0 0h6v-2a6 6 0 00-9-5.197M19 13a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-500">Allocated</p>
                                <p class="mt-2 text-3xl font-bold text-green-600">{{ $totalStudents - $unallocatedCount }}</p>
                            </div>
                            <div class="inline-flex items-center justify-center h-12 w-12 rounded-md bg-green-500 text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-500">Unallocated</p>
                                <p class="mt-2 text-3xl font-bold text-orange-600">{{ $unallocatedCount }}</p>
                            </div>
                            <div class="inline-flex items-center justify-center h-12 w-12 rounded-md bg-orange-500 text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Room Allocations</h2>
                        <div class="flex gap-3">
                            <a href="{{ route('allocations.unallocated') }}" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-6 rounded">
                                View Unallocated
                            </a>
                            <a href="{{ route('allocations.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                                + Allocate Student
                            </a>
                        </div>
                    </div>

                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Allocations Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-left">Student Name</th>
                                    <th class="px-4 py-2 text-left">Email</th>
                                    <th class="px-4 py-2 text-left">Room #</th>
                                    <th class="px-4 py-2 text-left">Allocation Date</th>
                                    <th class="px-4 py-2 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($allocations as $allocation)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-2 font-semibold">{{ $allocation->user->name }}</td>
                                        <td class="px-4 py-2">{{ $allocation->user->email }}</td>
                                        <td class="px-4 py-2">
                                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $allocation->room()->first()->room_number ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2">{{ $allocation->room()->first()->pivot->allocation_date ?? 'N/A' }}</td>
                                        <td class="px-4 py-2 text-center">
                                            <form method="POST" action="{{ route('allocations.release', $allocation) }}" class="inline" onsubmit="return confirm('Are you sure you want to release this student?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-semibold">
                                                    Release
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-2 text-center text-gray-500">
                                            No allocations found. <a href="{{ route('allocations.create') }}" class="text-blue-600 hover:underline">Create one</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $allocations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
