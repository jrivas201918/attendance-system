<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Card -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg shadow-lg mb-8">
                <div class="p-8 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold mb-2">👋 Welcome back, {{ Auth::user()->name }}!</h1>
                            <p class="text-indigo-100 text-lg">{{ now()->timezone('Asia/Manila')->format('l, F j, Y') }} | {{ now()->timezone('Asia/Manila')->format('g:i A') }}</p>
                            <p class="text-indigo-100 mt-2">Here's your system overview for today...</p>
                            
                    <!-- Last Logout info -->
                        @php
                            $lastLogout = auth()->user()->last_logout_at;
                        @endphp
                            @if($lastLogout)
                                <p class="text-indigo-100 mt-2 text-sm">
                                    📅 Last Logout: {{ \Carbon\Carbon::parse($lastLogout)->timezone('Asia/Manila')->format('F j, Y g:i A') }}
                                </p>
                            @endif
                        </div>
                        <div class="hidden md:block">
                            <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                <span class="text-3xl font-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                @if(auth()->user()->isTeacher())
                    <a href="{{ route('students.index') }}" class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-200">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">My Students</p>
                                <p class="text-xs text-gray-500">Manage students</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('rooms.index') }}" class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-200">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">My Rooms</p>
                                <p class="text-xs text-gray-500">Mark attendance</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('teacher.analytics') }}" class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-200">
                        <div class="flex items-center">
                            <div class="p-2 bg-purple-100 rounded-lg">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">My Analytics</p>
                                <p class="text-xs text-gray-500">View reports</p>
                            </div>
                        </div>
                    </a>
                @endif

                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.users') }}" class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-200">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Manage Teachers</p>
                                <p class="text-xs text-gray-500">User management</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('admin.statistics') }}" class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-200">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">View Statistics</p>
                                <p class="text-xs text-gray-500">System reports</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('admin.dashboard') }}" class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-200">
                        <div class="flex items-center">
                            <div class="p-2 bg-yellow-100 rounded-lg">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Admin Dashboard</p>
                                <p class="text-xs text-gray-500">Full overview</p>
                            </div>
                        </div>
                    </a>
                @endif

                <a href="{{ route('profile.edit') }}" class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-200">
                    <div class="flex items-center">
                        <div class="p-2 bg-gray-100 rounded-lg">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Profile</p>
                            <p class="text-xs text-gray-500">Account settings</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Room Quick Actions (Teachers Only) -->
            @if(auth()->user()->isTeacher())
                @php
                    $rooms = \App\Models\Room::where('user_id', auth()->id())->withCount('students')->latest()->take(3)->get();
                @endphp
                @if($rooms->count() > 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">🏠 Quick Room Access</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach($rooms as $room)
                                    <a href="{{ route('rooms.attendance', $room) }}" 
                                       class="block p-4 border border-gray-200 rounded-lg hover:border-green-300 hover:bg-green-50 transition-colors duration-200">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="font-medium text-gray-900">{{ $room->name }}</h4>
                                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                                {{ $room->students_count }} students
                    </span>
                                        </div>
                                        <p class="text-sm text-gray-600 mb-3">
                                            📝 Click to mark attendance
                                        </p>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('rooms.show', $room) }}" 
                                               class="text-xs text-blue-600 hover:text-blue-800">View</a>
                                            <a href="{{ route('rooms.edit', $room) }}" 
                                               class="text-xs text-yellow-600 hover:text-yellow-800">Edit</a>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                            @if($rooms->count() >= 3)
                                <div class="mt-4 text-center">
                                    <a href="{{ route('rooms.index') }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        View all rooms →
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @endif

            <!-- Weather and System Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Weather Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">🌤️ Weather Information</h3>
                        @if(isset($weather))
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-lg font-semibold text-gray-900">{{ $weather['name'] }}</p>
                                    <p class="text-sm text-gray-600">{{ $weather['weather'][0]['main'] }} ({{ $weather['weather'][0]['description'] }})</p>
                                    <p class="text-2xl font-bold text-blue-600">{{ round($weather['main']['temp']) }}°C</p>
                                </div>
                        </div>
                    @else
                            <div class="text-center py-4">
                                <p class="text-gray-500">Unable to fetch weather data</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- System Status -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">⚙️ System Status</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">System Status</span>
                                <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">Online</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Database</span>
                                <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">Connected</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Last Activity</span>
                                <span class="text-xs text-gray-500">{{ now()->format('g:i A') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</x-app-layout>
