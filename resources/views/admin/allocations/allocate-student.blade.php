<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Allocate Room: ' . $student->user->name) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Allocate Room for {{ $student->user->name }}</h2>

                    <!-- Student Info -->
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Student Name</p>
                                <p class="font-semibold text-gray-800">{{ $student->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Email</p>
                                <p class="font-semibold text-gray-800">{{ $student->user->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Enrollment #</p>
                                <p class="font-semibold text-gray-800">{{ $student->enrollment_number }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Contact</p>
                                <p class="font-semibold text-gray-800">{{ $student->contact_number }}</p>
                            </div>
                        </div>
                    </div>

                    @if ($rooms->isEmpty())
                        <div class="p-4 bg-red-50 border border-red-200 rounded mb-6">
                            <p class="text-sm text-red-700">
                                <strong>No rooms available!</strong> Please check room availability or <a href="{{ route('rooms.create') }}" class="underline font-semibold">create new rooms</a>.
                            </p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('allocations.store-student', $student) }}">
                        @csrf

                        <!-- Room Selection -->
                        <div class="mb-6">
                            <label for="room_id" class="block text-gray-700 text-sm font-bold mb-2">
                                Select Room *
                            </label>
                            <select name="room_id" id="room_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error('room_id') border-red-500 @enderror"
                                required @disabled($rooms->isEmpty())>
                                <option value="">Choose a room</option>
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->id }}" @selected(old('room_id') == $room->id)>
                                        Room {{ $room->room_number }} ({{ ucfirst($room->type) }}) - Floor {{ $room->floor }} - {{ $room->available_beds }} beds available
                                    </option>
                                @endforeach
                            </select>
                            @error('room_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Allocation Date -->
                        <div class="mb-6">
                            <label for="allocation_date" class="block text-gray-700 text-sm font-bold mb-2">
                                Allocation Date *
                            </label>
                            <input type="date" name="allocation_date" id="allocation_date" value="{{ old('allocation_date', now()->toDateString()) }}" 
                                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error('allocation_date') border-red-500 @enderror"
                                required>
                            @error('allocation_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-3">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded" @disabled($rooms->isEmpty())>
                                Allocate Room
                            </button>
                            <a href="{{ route('allocations.unallocated') }}" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-6 rounded">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
