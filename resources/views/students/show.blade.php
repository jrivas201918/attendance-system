<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-200 flex items-center gap-2 tracking-tight justify-center">
            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 1115 0v.75a.75.75 0 01-.75.75H5.25a.75.75 0 01-.75-.75v-.75z" /></svg>
            Student Details
        </h2>
    </x-slot>

    <div class="py-10 min-h-screen bg-gradient-to-br from-indigo-100 via-white to-blue-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <div class="max-w-2xl mx-auto space-y-10">
            <!-- Student Info Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 flex flex-col items-center border border-gray-100 dark:border-gray-700 mx-auto">
                <div class="w-full flex flex-col items-center">
                    <a href="{{ route('students.index') }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 flex items-center mb-3 text-sm font-medium">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
                        Back to Students
                    </a>
                    <div class="text-xl font-bold mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 1115 0v.75a.75.75 0 01-.75.75H5.25a.75.75 0 01-.75-.75v-.75z" /></svg>
                        Student Information
                    </div>
                    <div class="space-y-1 text-base text-center">
                        <div><span class="font-semibold text-gray-700 dark:text-gray-300">Student ID:</span> {{ $student->student_id }}</div>
                        <div><span class="font-semibold text-gray-700 dark:text-gray-300">Full Name:</span> {{ $student->name }}</div>
                        <div><span class="font-semibold text-gray-700 dark:text-gray-300">Email:</span> {{ $student->email }}</div>
                        <div><span class="font-semibold text-gray-700 dark:text-gray-300">Course:</span> {{ $student->course }}</div>
                        <div><span class="font-semibold text-gray-700 dark:text-gray-300">Year Level:</span> {{ $student->year_level }}</div>
                    </div>
                </div>
                <div class="flex flex-row gap-3 mt-6 justify-center w-full">
                    <a href="{{ route('students.edit', $student) }}" class="inline-flex items-center px-5 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg font-semibold shadow transition gap-2 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.1 2.1 0 113.02 2.92L6.75 19.5 3 21l1.5-3.75 12.362-13.763z" /></svg>
                        Edit
                    </a>
                    <a href="{{ route('attendance.create', ['student_id' => $student->id]) }}" class="inline-flex items-center px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold shadow transition gap-2 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Mark Attendance
                    </a>
                    <form method="POST" action="{{ route('students.destroy', $student) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold shadow transition gap-2 text-sm" onclick="return confirm('Are you sure you want to delete this student?')">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            Delete
                        </button>
                    </form>
                </div>
            </div>

            <!-- Divider -->
            <div class="border-t border-gray-200 dark:border-gray-700"></div>

            <!-- Attendance Summary Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 flex flex-col items-center mx-auto w-full max-w-lg">
                <div class="text-xl font-bold mb-6 flex items-center gap-2 tracking-tight">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 17v-2a2 2 0 012-2h2a2 2 0 012 2v2m0 0v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-2zm6-2v-2a2 2 0 012-2h2a2 2 0 012 2v2m0 0v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2zm6-2v-2a2 2 0 012-2h2a2 2 0 012 2v2m0 0v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                    Attendance Summary
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 w-full justify-center">
                    <div class="bg-indigo-50 dark:bg-indigo-900 rounded-lg p-4 flex flex-col items-center shadow">
                        <div class="text-xs text-gray-500">Total Records</div>
                        <div class="text-2xl font-bold">{{ $attendanceSummary['total'] }}</div>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900 rounded-lg p-4 flex flex-col items-center shadow">
                        <div class="text-xs text-gray-500">Present</div>
                        <div class="text-2xl font-bold text-green-600">{{ $attendanceSummary['present'] }}</div>
                    </div>
                    <div class="bg-red-50 dark:bg-red-900 rounded-lg p-4 flex flex-col items-center shadow">
                        <div class="text-xs text-gray-500">Absent</div>
                        <div class="text-2xl font-bold text-red-600">{{ $attendanceSummary['absent'] }}</div>
                    </div>
                    <div class="bg-blue-50 dark:bg-blue-900 rounded-lg p-4 flex flex-col items-center shadow">
                        <div class="text-xs text-gray-500">Attendance %</div>
                        <div class="text-2xl font-bold">{{ $attendanceSummary['percentage'] }}%</div>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="border-t border-gray-200 dark:border-gray-700"></div>

            <!-- Attendance Filter, Export, and Table Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 flex flex-col items-center mx-auto">
                <div class="flex flex-col items-center gap-4 mb-8 w-full">
                    <!-- Filter Form -->
                    <form method="GET" action="" class="flex flex-col md:flex-row md:items-end gap-4 w-full max-w-xl mx-auto justify-center">
                        <div>
                            <label for="filter_date" class="block text-xs font-medium text-gray-700 dark:text-gray-300">Filter by Date</label>
                            <input type="date" id="filter_date" name="filter_date" value="{{ request('filter_date') }}" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label for="filter_month" class="block text-xs font-medium text-gray-700 dark:text-gray-300">Filter by Month</label>
                            <input type="month" id="filter_month" name="filter_month" value="{{ request('filter_month') }}" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm">
                        </div>
                        <div class="flex gap-2 mt-2 md:mt-0">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition gap-1">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707l-6.414 6.414A1 1 0 0013 13.414V19a1 1 0 01-1.447.894l-4-2A1 1 0 017 17v-3.586a1 1 0 00-.293-.707L3.293 6.707A1 1 0 013 6V4z" /></svg>
                                Filter
                            </button>
                            <a href="{{ route('students.show', $student) }}" class="inline-flex items-center px-4 py-2 bg-gray-400 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 focus:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition gap-1">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                Reset
                            </a>
                        </div>
                    </form>
                    <!-- Export Button -->
                    <a href="{{ route('attendance.export', $student) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition gap-2 mt-2 mx-auto">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                        Export CSV
                    </a>
                </div>
                <!-- Attendance Table -->
                <div class="overflow-x-auto rounded-lg shadow-inner w-full flex justify-center">
                    <table class="min-w-full max-w-2xl mx-auto divide-y divide-gray-200 dark:divide-gray-700 border border-gray-200 dark:border-gray-700 rounded-lg">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($attendances as $attendance)
                                <tr class="hover:bg-indigo-50 dark:hover:bg-gray-900 transition {{ $loop->even ? 'bg-gray-50 dark:bg-gray-900' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-medium">{{ \Carbon\Carbon::parse($attendance->date)->format('F d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($attendance->status === 'present')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 gap-1">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                Present
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 gap-1">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                                Absent
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm flex gap-2">
                                        <a href="{{ route('attendance.edit', $attendance) }}" class="inline-flex items-center px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg font-semibold shadow transition gap-1">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.1 2.1 0 113.02 2.92L6.75 19.5 3 21l1.5-3.75 12.362-13.763z" /></svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('attendance.destroy', $attendance) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold shadow transition gap-1" onclick="return confirm('Are you sure you want to delete this attendance record?')">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">No attendance records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 