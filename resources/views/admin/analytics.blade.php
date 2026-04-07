@extends('layouts.admin-navigation')

@section('title', 'Analytics - Admin')

@section('page-title', 'Analytics')

@section('content')
    <div class="p-6 space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-xl font-semibold text-gray-900">Platform Analytics</h2>
            <form method="GET" action="{{ route('admin.analytics') }}" class="flex items-center gap-2">
                <label for="months" class="text-sm text-gray-600">Window</label>
                <select id="months" name="months" onchange="this.form.submit()" class="text-sm border border-gray-300 rounded-lg px-2 py-1">
                    <option value="3" @selected($months === 3)>Last 3 months</option>
                    <option value="6" @selected($months === 6)>Last 6 months</option>
                    <option value="12" @selected($months === 12)>Last 12 months</option>
                    <option value="24" @selected($months === 24)>Last 24 months</option>
                </select>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">
            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <p class="text-sm text-gray-500">Total Views</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($analytics['total_views']) }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <p class="text-sm text-gray-500">Total Favorites</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($analytics['total_favorites']) }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <p class="text-sm text-gray-500">Total Enquiries</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($analytics['total_enquiries']) }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <p class="text-sm text-gray-500">Avg Views / Property</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($analytics['avg_views_per_property'], 1) }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <p class="text-sm text-gray-500">Approval Rate</p>
                <p class="text-2xl font-bold text-green-700 mt-1">{{ number_format($analytics['approval_rate'], 1) }}%</p>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 bg-white rounded-xl border border-gray-200 p-5">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Listings vs New Users</h3>
                <div class="h-80">
                    <canvas id="growthChart" class="w-full h-full"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Property Status Split</h3>
                <div class="h-80">
                    <canvas id="statusChart" class="w-full h-full"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labels = @json($labels);
        const listingSeries = @json($monthlyListings);
        const userSeries = @json($monthlyUsers);
        const statusSeries = @json($statusSeries);

        const growthCanvas = document.getElementById('growthChart');
        if (growthCanvas) {
            new Chart(growthCanvas, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                            label: 'Listings',
                            data: listingSeries,
                            borderColor: '#b5813a',
                            backgroundColor: 'rgba(181, 129, 58, 0.18)',
                            fill: true,
                            tension: 0.35
                        },
                        {
                            label: 'New Users',
                            data: userSeries,
                            borderColor: '#1a1a2e',
                            backgroundColor: 'rgba(26, 26, 46, 0.08)',
                            fill: true,
                            tension: 0.35
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }

        const statusCanvas = document.getElementById('statusChart');
        if (statusCanvas) {
            new Chart(statusCanvas, {
                type: 'doughnut',
                data: {
                    labels: ['Approved', 'Pending', 'Rejected'],
                    datasets: [{
                        data: statusSeries,
                        backgroundColor: ['#16a34a', '#f59e0b', '#dc2626'],
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    </script>
@endsection
