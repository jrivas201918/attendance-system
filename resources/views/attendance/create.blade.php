<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mark Today\'s Attendance') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

                    @if ($errors->any())
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Header -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">📅 Attendance for {{ \Carbon\Carbon::parse($today)->format('l, F j, Y') }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Mark all your students as present or absent for today.</p>
                    </div>

                    @if($students->count() > 0)
                    <form method="POST" action="{{ route('attendance.store') }}" class="space-y-6">
                        @csrf
                            <input type="hidden" name="date" value="{{ $today }}">

                            <!-- Attendance Table -->
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                #
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Student Name
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Course
                                            </th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Present
                                            </th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Absent
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($students as $index => $student)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $index + 1 }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mr-3">
                                                            <span class="text-sm font-medium text-blue-600 dark:text-blue-300">
                                                                {{ strtoupper(substr($student->name, 0, 1)) }}
                                                            </span>
                                                        </div>
                        <div>
                                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                                {{ $student->name }}
                                                            </div>
                                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                                ID: {{ $student->id }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $student->course }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="radio" 
                                                           name="attendance[{{ $student->id }}]" 
                                                           value="present" 
                                                           id="present_{{ $student->id }}"
                                                           {{ isset($existingAttendance[$student->id]) && $existingAttendance[$student->id] == 'present' ? 'checked' : '' }}
                                                           class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 focus:ring-green-500 dark:focus:ring-green-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="radio" 
                                                           name="attendance[{{ $student->id }}]" 
                                                           value="absent" 
                                                           id="absent_{{ $student->id }}"
                                                           {{ isset($existingAttendance[$student->id]) && $existingAttendance[$student->id] == 'absent' ? 'checked' : '' }}
                                                           class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                        </div>

                            <!-- Summary -->
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <div class="flex items-center justify-between">
                        <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            Total Students: <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $students->count() }}</span>
                                        </p>
                                    </div>
                                    <div class="flex space-x-4">
                                        <button type="button" onclick="markAllPresent()" class="px-4 py-2 bg-green-100 text-green-700 rounded-md hover:bg-green-200 text-sm font-medium">
                                            Mark All Present
                                        </button>
                                        <button type="button" onclick="markAllAbsent()" class="px-4 py-2 bg-red-100 text-red-700 rounded-md hover:bg-red-200 text-sm font-medium">
                                            Mark All Absent
                                        </button>
                                    </div>
                                </div>
                        </div>

                            <!-- Submit Button -->
                            <div class="flex items-center justify-end space-x-4">
                                <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 text-sm font-medium">
                                    Cancel
                                </a>
                                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm font-medium">
                                    📝 Save Attendance
                                </button>
                        </div>
                        </form>
                    @else
                        <div class="text-center py-8">
                            <div class="text-4xl mb-4">👥</div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">No Students Found</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">You don't have any students assigned to you yet.</p>
                            <a href="{{ route('students.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm font-medium">
                                Add Your First Student
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function markAllPresent() {
            document.querySelectorAll('input[value="present"]').forEach(radio => {
                radio.checked = true;
            });
        }

        function markAllAbsent() {
            document.querySelectorAll('input[value="absent"]').forEach(radio => {
                radio.checked = true;
            });
        }
    </script>
</x-app-layout>
