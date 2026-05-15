<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Room') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Room</h2>

                    <form method="POST" action="{{ route('rooms.update', $room) }}">
                        @csrf
                        @method('PUT')

                        <!-- Room Number -->
                        <div class="mb-4">
                            <label for="room_number" class="block text-gray-700 text-sm font-bold mb-2">
                                Room Number *
                            </label>
                            <input type="text" name="room_number" id="room_number" value="{{ old('room_number', $room->room_number) }}" 
                                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error('room_number') border-red-500 @enderror"
                                required>
                            @error('room_number')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Room Type -->
                        <div class="mb-4">
                            <label for="type" class="block text-gray-700 text-sm font-bold mb-2">
                                Room Type *
                            </label>
                            <select name="type" id="type" 
                                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error('type') border-red-500 @enderror"
                                required>
                                <option value="">Select Type</option>
                                <option value="single" @selected(old('type', $room->type) === 'single')>Single</option>
                                <option value="double" @selected(old('type', $room->type) === 'double')>Double</option>
                                <option value="triple" @selected(old('type', $room->type) === 'triple')>Triple</option>
                                <option value="quad" @selected(old('type', $room->type) === 'quad')>Quad</option>
                            </select>
                            @error('type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Capacity -->
                        <div class="mb-4">
                            <label for="capacity" class="block text-gray-700 text-sm font-bold mb-2">
                                Capacity (Number of Beds) *
                            </label>
                            <input type="number" name="capacity" id="capacity" value="{{ old('capacity', $room->capacity) }}" 
                                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error('capacity') border-red-500 @enderror"
                                min="1" max="10" required>
                            @error('capacity')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Floor -->
                        <div class="mb-4">
                            <label for="floor" class="block text-gray-700 text-sm font-bold mb-2">
                                Floor Number *
                            </label>
                            <input type="number" name="floor" id="floor" value="{{ old('floor', $room->floor) }}" 
                                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error('floor') border-red-500 @enderror"
                                min="1" required>
                            @error('floor')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label for="status" class="block text-gray-700 text-sm font-bold mb-2">
                                Status *
                            </label>
                            <select name="status" id="status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error('status') border-red-500 @enderror"
                                required>
                                <option value="">Select Status</option>
                                <option value="available" @selected(old('status', $room->status) === 'available')>Available</option>
                                <option value="occupied" @selected(old('status', $room->status) === 'occupied')>Occupied</option>
                                <option value="maintenance" @selected(old('status', $room->status) === 'maintenance')>Maintenance</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Maintenance Notes -->
                        <div class="mb-6">
                            <label for="maintenance_notes" class="block text-gray-700 text-sm font-bold mb-2">
                                Maintenance Notes
                            </label>
                            <textarea name="maintenance_notes" id="maintenance_notes" rows="3" 
                                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">{{ old('maintenance_notes', $room->maintenance_notes) }}</textarea>
                        </div>

                        <!-- Info -->
                        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded">
                            <p class="text-sm text-gray-700">
                                <strong>Current Available Beds:</strong> {{ $room->available_beds }} / {{ $room->capacity }}
                            </p>
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-3">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                                Update Room
                            </button>
                            <a href="{{ route('rooms.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-6 rounded">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
