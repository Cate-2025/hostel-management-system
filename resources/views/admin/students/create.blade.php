<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Student') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Add New Student</h2>

                    <form method="POST" action="{{ route('students.store') }}">
                        @csrf

                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">
                                Name *
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error('name') border-red-500 @enderror"
                                required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">
                                Email *
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" 
                                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error('email') border-red-500 @enderror"
                                required>
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Enrollment Number -->
                        <div class="mb-4">
                            <label for="enrollment_number" class="block text-gray-700 text-sm font-bold mb-2">
                                Enrollment Number *
                            </label>
                            <input type="text" name="enrollment_number" id="enrollment_number" value="{{ old('enrollment_number') }}" 
                                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error('enrollment_number') border-red-500 @enderror"
                                required>
                            @error('enrollment_number')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contact Number -->
                        <div class="mb-4">
                            <label for="contact_number" class="block text-gray-700 text-sm font-bold mb-2">
                                Contact Number *
                            </label>
                            <input type="tel" name="contact_number" id="contact_number" value="{{ old('contact_number') }}" 
                                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error('contact_number') border-red-500 @enderror"
                                required>
                            @error('contact_number')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Parent Name -->
                        <div class="mb-4">
                            <label for="parent_name" class="block text-gray-700 text-sm font-bold mb-2">
                                Parent/Guardian Name
                            </label>
                            <input type="text" name="parent_name" id="parent_name" value="{{ old('parent_name') }}" 
                                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
                        </div>

                        <!-- Parent Contact -->
                        <div class="mb-4">
                            <label for="parent_contact" class="block text-gray-700 text-sm font-bold mb-2">
                                Parent/Guardian Contact
                            </label>
                            <input type="tel" name="parent_contact" id="parent_contact" value="{{ old('parent_contact') }}" 
                                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
                        </div>

                        <!-- Address -->
                        <div class="mb-6">
                            <label for="address" class="block text-gray-700 text-sm font-bold mb-2">
                                Address
                            </label>
                            <textarea name="address" id="address" rows="3" 
                                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">{{ old('address') }}</textarea>
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-3">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                                Create Student
                            </button>
                            <a href="{{ route('students.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-6 rounded">
                                Cancel
                            </a>
                        </div>

                        <p class="text-gray-600 text-sm mt-4">
                            Note: Default password is "password123". Student can change it after first login.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
