<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Hostel Management') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-gray-900">Hostel Management System</h1>
                    </div>
                    <div class="flex items-center gap-4">
                        @auth
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                Dashboard
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">
                                Login
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-gray-700 hover:text-gray-900 px-4 py-2 rounded-md text-sm font-medium border border-gray-300 hover:border-gray-400">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="flex items-center justify-center min-h-[calc(100vh-4rem)]">
            <div class="text-center">
                <h2 class="text-5xl font-bold text-gray-900 mb-4">Welcome to Hostel Management</h2>
                <p class="text-xl text-gray-600 mb-8 max-w-2xl">
                    A comprehensive system for managing hostel operations, student accommodations, and payments.
                </p>

                @guest
                    <div class="flex gap-4 justify-center">
                        <a href="{{ route('login') }}" class="bg-blue-600 text-white px-8 py-3 rounded-lg text-lg font-medium hover:bg-blue-700 transition">
                            Login
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="border-2 border-blue-600 text-blue-600 px-8 py-3 rounded-lg text-lg font-medium hover:bg-blue-50 transition">
                                Create Account
                            </a>
                        @endif
                    </div>

                    <!-- Features -->
                    <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">
                        <div class="bg-white p-6 rounded-lg shadow">
                            <div class="text-3xl mb-2">👨‍💼</div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Admin Dashboard</h3>
                            <p class="text-gray-600">Manage students, rooms, allocations, and payments efficiently.</p>
                        </div>
                        <div class="bg-white p-6 rounded-lg shadow">
                            <div class="text-3xl mb-2">👤</div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Student Portal</h3>
                            <p class="text-gray-600">View room allocation, payment history, and personal information.</p>
                        </div>
                        <div class="bg-white p-6 rounded-lg shadow">
                            <div class="text-3xl mb-2">🏢</div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Easy Management</h3>
                            <p class="text-gray-600">Simple and intuitive interface for all hostel operations.</p>
                        </div>
                    </div>
                @endguest

                @auth
                    <p class="text-gray-600 mb-6">Welcome back! Click the button below to access your dashboard.</p>
                    <a href="{{ route('admin.dashboard') }}" class="bg-blue-600 text-white px-8 py-3 rounded-lg text-lg font-medium hover:bg-blue-700 transition inline-block">
                        Go to Dashboard
                    </a>
                @endauth
            </div>
        </div>
    </div>
</body>
</html>
