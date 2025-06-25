<x-app-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <div class="w-full sm:max-w-lg mt-6 px-6 py-8 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">

            <div class="flex flex-col items-center">
                <svg class="w-20 h-20 text-indigo-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"></path>
                  </svg>

                <h2 class="mt-6 text-2xl font-bold text-center text-gray-900 dark:text-gray-100">
                    Create Student
                </h2>
            </div>


            <form method="POST" action="{{ route('students.store') }}" class="mt-8 space-y-6">
                @csrf

                <!-- Student ID -->
                <div>
                    <x-input-label for="student_id" :value="__('Student ID')" />
                    <x-text-input id="student_id" class="block mt-1 w-full" type="text" name="student_id" :value="old('student_id')" required autofocus autocomplete="student_id" />
                    <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                </div>

                <!-- Name -->
                <div class="mt-4">
                    <x-input-label for="name" :value="__('Full Name')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                 <!-- Course -->
                 <div class="mt-4">
                    <x-input-label for="course" :value="__('Course')" />
                    <select id="course" name="course" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" required>
                        <option value="">Select a Course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course }}" {{ old('course') == $course ? 'selected' : '' }}>
                                {{ $course }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('course')" class="mt-2" />
                </div>


                <!-- Year Level -->
                <div class="mt-4">
                    <x-input-label for="year_level" :value="__('Year Level')" />
                    <select id="year_level" name="year_level" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" required>
                        <option value="">Select Year Level</option>
                        @for ($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ old('year_level') == $i ? 'selected' : '' }}>
                                {{ $i }}{{ $i == 1 ? 'st' : ($i == 2 ? 'nd' : ($i == 3 ? 'rd' : 'th')) }} Year
                            </option>
                        @endfor
                    </select>
                    <x-input-error :messages="$errors->get('year_level')" class="mt-2" />
                </div>


                <div class="flex items-center justify-end mt-6 space-x-4">
                    <a href="{{ route('students.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                        Cancel
                    </a>

                    <x-primary-button>
                        {{ __('Create Student') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout> 