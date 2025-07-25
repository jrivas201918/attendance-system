<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- You're logged in message -->
                    <div class="mb-4">
                        You're logged in!
                    </div>
                    <!-- Last Logout info -->
                    <span class="font-semibold">Last Logout:</span>
                    <span>
                        @php
                            $lastLogout = auth()->user()->last_logout_at;
                        @endphp
                        {{ $lastLogout 
                            ? \Carbon\Carbon::parse($lastLogout)->timezone('Asia/Manila')->format('F j, Y g:i A') 
                            : 'No logout record' }}
                    </span>
                    <!-- Weather Info -->
                    @if(isset($weather))
                        <div class="mb-4">
                            <span class="font-semibold">Weather in {{ $weather['name'] }}:</span>
                            <span>
                                {{ $weather['weather'][0]['main'] }} ({{ $weather['weather'][0]['description'] }}),
                                {{ round($weather['main']['temp']) }}°C
                            </span>
                        </div>
                    @else
                        <div class="mb-4">
                            <span class="font-semibold">Weather:</span>
                            <span>Unable to fetch weather data.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
