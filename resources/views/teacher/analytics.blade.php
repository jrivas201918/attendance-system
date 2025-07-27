<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Analytics') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Student Distribution by Course (Pie Chart) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">My Students by Course</h3>
                        <div class="h-64">
                            <canvas id="courseDistributionChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Daily Attendance (Bar Chart) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">My Students' Daily Attendance (This Month)</h3>
                        <div class="h-64">
                            <canvas id="dailyAttendanceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Scripts -->
    <script>
        // Course Distribution Pie Chart
        const courseCtx = document.getElementById('courseDistributionChart').getContext('2d');
        const courseData = @json($chartData['courseDistribution']);
        
        if (courseData.length > 0) {
            new Chart(courseCtx, {
                type: 'pie',
                data: {
                    labels: courseData.map(item => item.course),
                    datasets: [{
                        data: courseData.map(item => item.student_count),
                        backgroundColor: [
                            '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#06B6D4', '#F97316', '#EC4899'
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.parsed / total) * 100).toFixed(1);
                                    return `${context.label}: ${context.parsed} students (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        } else {
            courseCtx.font = '16px Arial';
            courseCtx.fillStyle = '#9CA3AF';
            courseCtx.textAlign = 'center';
            courseCtx.fillText('No course data available', courseCtx.canvas.width / 2, courseCtx.canvas.height / 2);
        }

        // Daily Attendance Bar Chart
        const attendanceCtx = document.getElementById('dailyAttendanceChart').getContext('2d');
        const attendanceData = @json($chartData['dailyAttendance']);
        
        if (attendanceData.dates.length > 0) {
            new Chart(attendanceCtx, {
                type: 'bar',
                data: {
                    labels: attendanceData.dates,
                    datasets: [
                        {
                            label: 'Present',
                            data: attendanceData.present,
                            backgroundColor: '#10B981',
                            borderColor: '#059669',
                            borderWidth: 1
                        },
                        {
                            label: 'Absent',
                            data: attendanceData.absent,
                            backgroundColor: '#EF4444',
                            borderColor: '#DC2626',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Students'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            callbacks: {
                                afterBody: function(context) {
                                    const present = context[0].dataset.data[context[0].dataIndex];
                                    const absent = context[1].dataset.data[context[1].dataIndex];
                                    const total = present + absent;
                                    return `Total: ${total} students`;
                                }
                            }
                        }
                    }
                }
            });
        } else {
            attendanceCtx.font = '16px Arial';
            attendanceCtx.fillStyle = '#9CA3AF';
            attendanceCtx.textAlign = 'center';
            attendanceCtx.fillText('No attendance data available', attendanceCtx.canvas.width / 2, attendanceCtx.canvas.height / 2);
        }
    </script>
</x-app-layout> 