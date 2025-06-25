<!-- resources/views/students/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Students') }}
            </h2>
            <a href="{{ route('students.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 active:bg-indigo-600 disabled:opacity-25 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Create New Student
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @if(session('success'))
                    <div class="p-6 bg-green-100 dark:bg-green-900 border-b border-green-200 dark:border-green-700">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 dark:text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span class="text-green-800 dark:text-green-200">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('students.index') }}" class="mb-6">
                        <div class="flex flex-col md:flex-row md:items-end md:space-x-4 space-y-4 md:space-y-0">
                            <!-- Search -->
                            <div class="flex-1">
                                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Name or Student Number" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:text-gray-100" />
                            </div>
                            <!-- Course Filter -->
                            <div>
                                <label for="course" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Course</label>
                                <select name="course" id="course" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:text-gray-100">
                                    <option value="">All Courses</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course }}" {{ request('course') == $course ? 'selected' : '' }}>{{ $course }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Year Filter -->
                            <div>
                                <label for="year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Year</label>
                                <select name="year" id="year" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:text-gray-100">
                                    <option value="">All Years</option>
                                    @foreach ($years as $year)
                                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}{{ $year == 1 ? 'st' : ($year == 2 ? 'nd' : ($year == 3 ? 'rd' : 'th')) }} Year</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Submit -->
                            <div class="flex space-x-2">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 active:bg-indigo-600 disabled:opacity-25 transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"></path></svg>
                                    Filter
                                </button>
                                <a href="{{ route('students.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-400 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-200 active:bg-gray-600 disabled:opacity-25 transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    Clear
                                </a>
                            </div>
                        </div>
                    </form>
                    <!-- End Search and Filter Form -->

                    <div class="divide-y divide-gray-600">
                        @forelse ($students as $student)
                            <div class="py-6 flex justify-between items-center">
                                <div class="space-y-3">
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $student->name }}</h3>
                                    </div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 ml-9">{{ $student->student_id }}</p>
                                    
                                    <div class="ml-9 space-y-2 text-sm">
                                        <p class="text-gray-700 dark:text-gray-300 flex items-center space-x-2">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            <span>{{ $student->email }}</span>
                                        </p>
                                        <p class="text-gray-700 dark:text-gray-300 flex items-center space-x-2">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v11.494m-9-5.747h18"></path><path d="M12 6.253v11.494m-9-5.747h18" transform="rotate(45 12 12)"></path><path d="M12 6.253v11.494m-9-5.747h18" transform="rotate(-45 12 12)"></path></svg>
                                            <span>{{ $student->course }}</span>
                                        </p>
                                        <p class="text-gray-700 dark:text-gray-300 flex items-center space-x-2">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span>{{ $student->year_level }}{{ $student->year_level == 1 ? 'st' : ($student->year_level == 2 ? 'nd' : ($student->year_level == 3 ? 'rd' : 'th')) }} Year</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="flex flex-col space-y-2 items-end">
                                    <a href="{{ route('students.show', $student) }}" class="inline-flex items-center text-sm font-medium text-indigo-500 hover:text-indigo-700">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        View
                                    </a>
                                    <a href="{{ route('students.edit', $student) }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21H3v-3.5L16.732 5.232z"></path></svg>
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('students.destroy', $student) }}" onsubmit="return confirm('Are you sure you want to delete this student?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center text-sm font-medium text-red-500 hover:text-red-700">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-16">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-200">No students</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new student.</p>
                                <div class="mt-6">
                                    <a href="{{ route('students.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 active:bg-indigo-600 disabled:opacity-25 transition">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                        Create New Student
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    @if ($students->hasPages())
                        <div class="mt-8">
                            {{ $students->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>