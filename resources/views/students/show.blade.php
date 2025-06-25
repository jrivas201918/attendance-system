<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('students.index') }}" class="flex items-center gap-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15.75 19.5L8.25 12l7.5-7.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                <span>Back to Students</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Student Information Section -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Student Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 text-sm">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 1115 0v.75a.75.75 0 01-.75.75H5.25a.75.75 0 01-.75-.75v-.75z" /></svg>
                                <span class="text-gray-500 dark:text-gray-400">Name:</span>
                                <span class="text-gray-900 dark:text-gray-100 font-medium">{{ $student->name }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 1115 0v.75a.75.75 0 01-.75.75H5.25a.75.75 0 01-.75-.75v-.75z" /></svg>
                                <span class="text-gray-500 dark:text-gray-400">Student ID:</span>
                                <span class="text-gray-900 dark:text-gray-100 font-medium">{{ $student->student_id }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                                <span class="text-gray-500 dark:text-gray-400">Email:</span>
                                <span class="text-gray-900 dark:text-gray-100 font-medium">{{ $student->email }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                                <span class="text-gray-500 dark:text-gray-400">Course:</span>
                                <span class="text-gray-900 dark:text-gray-100 font-medium">{{ $student->course }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0l-2.072-1.036A49.92 49.92 0 0112 2.25c4.142 0 8.046.89 11.698 2.457l-2.072 1.036M6.19 18.75h11.62" /></svg>
                                <span class="text-gray-500 dark:text-gray-400">Year Level:</span>
                                <span class="text-gray-900 dark:text-gray-100 font-medium">{{ $student->year_level }} Year</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('students.edit', $student) }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 19.5H7.5v-3.082l9.362-9.363zM16.862 4.487L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                            Edit Student
                        </a>
                        <form method="POST" action="{{ route('students.destroy', $student) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg shadow-sm transition gap-2" onclick="return confirm('Are you sure? This action cannot be undone.')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.134-2.033-2.134H8.033c-1.12 0-2.033.954-2.033 2.134v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                Delete Student
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Attendance Section -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="flex flex-col md:flex-row justify-between md:items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 md:mb-0">Attendance Records</h3>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('attendance.export', $student) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow-sm transition gap-2">
                            Export CSV
                        </a>
                        <a href="{{ route('attendance.create', ['student_id' => $student->id]) }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg shadow-sm transition gap-2">
                            Mark Attendance
                        </a>
                    </div>
                </div>
                
                <!-- Filter Form -->
                <form method="GET" action="" class="mb-4 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg border dark:border-gray-700">
                    <div class="flex flex-col md:flex-row md:items-end gap-4">
                        <div class="flex-1">
                            <label for="filter_date" class="block text-xs font-medium text-gray-700 dark:text-gray-300">Filter by Date</label>
                            <input type="date" id="filter_date" name="filter_date" value="{{ request('filter_date') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm text-sm">
                        </div>
                        <div class="flex-1">
                            <label for="filter_month" class="block text-xs font-medium text-gray-700 dark:text-gray-300">Filter by Month</label>
                            <input type="month" id="filter_month" name="filter_month" value="{{ request('filter_month') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm text-sm">
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                Filter
                            </button>
                            <a href="{{ route('students.show', $student) }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>

                <!-- Attendance Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($attendances as $attendance)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($attendance->date)->format('F d, Y') }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        @if($attendance->status === 'present')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                                Present
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                                Absent
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium flex justify-end gap-2">
                                        <a href="{{ route('attendance.edit', $attendance) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Edit</a>
                                        <form action="{{ route('attendance.destroy', $attendance) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">No attendance records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                 @if ($attendances->hasPages())
                    <div class="mt-4 p-2 bg-gray-50 dark:bg-gray-800/50 rounded-lg border dark:border-gray-700">
                        {{ $attendances->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout> 