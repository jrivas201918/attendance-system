<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Rooms') }}
            </h2>
            <a href="{{ route('rooms.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create Room
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($rooms->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($rooms as $room)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $room->name }}</h3>
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                        {{ $room->students_count }} students
                                    </span>
                                </div>
                                
                                @if($room->description)
                                    <p class="text-gray-600 text-sm mb-4">{{ $room->description }}</p>
                                @endif

                                <div class="flex flex-col space-y-2">
                                    <!-- Primary Action: Mark Attendance -->
                                    <a href="{{ route('rooms.attendance', $room) }}" 
                                       class="bg-green-500 hover:bg-green-700 text-white text-sm font-bold py-3 px-4 rounded text-center transition-colors duration-200">
                                        📝 Mark Attendance
                                    </a>
                                    
                                    <!-- Secondary Actions -->
                                    <div class="flex space-x-2">
                                        <a href="{{ route('rooms.show', $room) }}" 
                                           class="bg-blue-500 hover:bg-blue-700 text-white text-sm font-bold py-2 px-3 rounded flex-1 text-center">
                                            👁️ View
                                        </a>
                                        <a href="{{ route('rooms.edit', $room) }}" 
                                           class="bg-yellow-500 hover:bg-yellow-700 text-white text-sm font-bold py-2 px-3 rounded flex-1 text-center">
                                            ✏️ Edit
                                        </a>
                                        <form method="POST" action="{{ route('rooms.destroy', $room) }}" class="flex-1" 
                                              onsubmit="return confirm('Are you sure you want to delete this room?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full bg-red-500 hover:bg-red-700 text-white text-sm font-bold py-2 px-3 rounded">
                                                🗑️ Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-gray-500 mb-4">
                            <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No rooms yet</h3>
                        <p class="text-gray-500 mb-4">Create your first room to start organizing your students.</p>
                        <a href="{{ route('rooms.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Create Your First Room
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout> 