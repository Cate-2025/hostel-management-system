<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Hostel Management System') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <i class="fas fa-hotel text-3xl text-blue-600 mr-3"></i>
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                            Hostel Management System
                        </h1>
                    </div>
                    <div class="flex items-center gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                                Dashboard
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-600 hover:text-gray-900 px-4 py-2 rounded-lg text-sm font-medium transition">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition shadow-md">
                                Login
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="border-2 border-blue-600 text-blue-600 px-5 py-2 rounded-lg text-sm font-medium hover:bg-blue-50 transition">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="flex items-center justify-center min-h-[calc(100vh-4rem)] py-12">
            <div class="text-center px-4">
                <div class="mb-6">
                    <i class="fas fa-building text-6xl text-blue-600"></i>
                </div>
                <h2 class="text-5xl font-bold text-gray-900 mb-4">
                    Welcome to Hostel Management
                </h2>
                <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                    A comprehensive system for managing hostel operations, student accommodations, and payments.
                </p>

                @guest
                    <div class="flex gap-4 justify-center flex-wrap">
                        <a href="{{ route('login') }}" class="bg-blue-600 text-white px-8 py-3 rounded-lg text-lg font-medium hover:bg-blue-700 transition shadow-lg">
                            <i class="fas fa-sign-in-alt mr-2"></i> Login
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="border-2 border-blue-600 text-blue-600 px-8 py-3 rounded-lg text-lg font-medium hover:bg-blue-50 transition">
                                <i class="fas fa-user-plus mr-2"></i> Create Account
                            </a>
                        @endif
                    </div>

                    <!-- Statistics -->
                    <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">
                        <div class="bg-white p-6 rounded-xl shadow-lg feature-card">
                            <i class="fas fa-bed text-4xl text-blue-600 mb-3"></i>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Room Management</h3>
                            <p class="text-gray-600">Manage hostel rooms, track capacity, and monitor availability in real-time.</p>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-lg feature-card">
                            <i class="fas fa-users text-4xl text-green-600 mb-3"></i>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Student Allocation</h3>
                            <p class="text-gray-600">Smart allocation system with automated room assignment and capacity management.</p>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-lg feature-card">
                            <i class="fas fa-credit-card text-4xl text-purple-600 mb-3"></i>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Payment Tracking</h3>
                            <p class="text-gray-600">Track payments, generate invoices, and manage financial reports seamlessly.</p>
                        </div>
                    </div>

                    <!-- Additional Features -->
                    <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-6 max-w-3xl mx-auto">
                        <div class="flex items-center justify-center gap-2 text-gray-600">
                            <i class="fas fa-chart-line text-blue-500"></i>
                            <span>Real-time Analytics</span>
                        </div>
                        <div class="flex items-center justify-center gap-2 text-gray-600">
                            <i class="fas fa-file-pdf text-red-500"></i>
                            <span>PDF Reports</span>
                        </div>
                        <div class="flex items-center justify-center gap-2 text-gray-600">
                            <i class="fas fa-bell text-yellow-500"></i>
                            <span>Instant Notifications</span>
                        </div>
                        <div class="flex items-center justify-center gap-2 text-gray-600">
                            <i class="fas fa-shield-alt text-green-500"></i>
                            <span>Secure System</span>
                        </div>
                    </div>
                @endguest

                @auth
                    <div class="bg-white p-8 rounded-xl shadow-lg max-w-md mx-auto">
                        <i class="fas fa-smile-wink text-5xl text-green-500 mb-4"></i>
                        <p class="text-gray-600 mb-6">Welcome back! Click the button below to access your dashboard.</p>
                        <a href="{{ route('dashboard') }}" class="bg-blue-600 text-white px-8 py-3 rounded-lg text-lg font-medium hover:bg-blue-700 transition inline-block">
                            <i class="fas fa-tachometer-alt mr-2"></i> Go to Dashboard
                        </a>
                    </div>
                @endauth
            </div>
        </div>
        
        <!-- Footer -->
        <footer class="bg-white border-t mt-12">
            <div class="max-w-7xl mx-auto px-4 py-6">
                <div class="text-center text-gray-500 text-sm">
                    <p>&copy; {{ date('Y') }} Hostel Management System. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>