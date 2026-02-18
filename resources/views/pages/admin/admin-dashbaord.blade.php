{{-- resources\views\pages\admin\admin-dashbaord.blade.php --}}
@extends('layouts.layout')

@section('content')
    {{-- DASHBOARD HEADER --}}
    <div class="p-3">
        <div class="bg-base-100 border border-gray-300 rounded-lg px-7 py-6 flex items-center justify-between">
            <div class="flex items-center gap-5">
                <div
                    class="w-14 h-14 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xl font-semibold">
                    {{ strtoupper(substr($user->profile->first_name ?? 'U', 0, 1)) }}
                    {{ strtoupper(substr($user->profile->last_name ?? '', 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight">
                        Welcome back,
                        <span class="text-primary">{{ $user->profile->first_name ?? '' }}
                            {{ $user->profile->last_name ?? '' }}</span>
                    </h1>
                    <p class="text-sm text-base-content/60 mt-1">Here's what's happening in your system today.</p>
                </div>
            </div>

            <div class="text-right space-y-2">
                <span
                    class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-md bg-base-200 border border-gray-300">
                    {{ $user->role->label() }}
                </span>
                <div class="text-xs text-base-content/50">
                    {{ now()->format('l, F d, Y') }}
                </div>
            </div>
        </div>
    </div>

    {{-- SUMMARY CARDS --}}
    <div class="p-3">
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
            @php
                $cards = [
                    ['label' => 'Total Users', 'value' => $stats['totalUsers'], 'icon' => 'users'],
                    ['label' => 'Active Drivers', 'value' => $stats['totalDrivers'], 'icon' => 'drivers'],
                    ['label' => 'Available Vehicles', 'value' => $stats['availableVehicles'], 'icon' => 'vehicles'],
                    ['label' => 'Vehicles In Repair', 'value' => $stats['repairVehicles'], 'icon' => 'repairs'],
                    ['label' => 'Pending Trip Tickets', 'value' => $stats['pendingTrips'], 'icon' => 'pending'],
                    ['label' => 'Active Trips', 'value' => $stats['activeTrips'], 'icon' => 'active'],
                ];
            @endphp

            @foreach($cards as $card)
                <div class="bg-base-100 border border-gray-300 rounded-lg p-5 hover:border-primary/40 transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-base-content/60">{{ $card['label'] }}</p>
                            <h2 class="text-3xl font-semibold mt-2 tracking-tight">{{ number_format($card['value']) }}</h2>
                        </div>
                        <div class="text-2xl opacity-70">
                            <x-icon :name="$card['icon']" class="w-7 h-7" />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Filter Form --}}
    <div class="p-3 mt-6">
        <form id="filterForm" class="flex items-end gap-4 bg-base-100 border border-gray-300 rounded-lg p-5">
            <div class="w-full">
                <label class="block text-sm font-medium mb-1">Month</label>
                <select name="month" class="select select-bordered w-full">
                    <option value="">All Months</option>
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ ($month == $m) ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-full">
                <label class="block text-sm font-medium mb-1">Year</label>
                <select name="year" class="select select-bordered w-full">
                    <option value="">All Years</option>
                    @foreach(range(now()->year, now()->year - 5) as $y)
                        <option value="{{ $y }}" {{ ($year == $y) ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary w-full">Filter</button>
            </div>
        </form>
    </div>

    {{-- Charts --}}
    <div class="p-3 mt-6 grid grid-cols-1 xl:grid-cols-2 gap-5">
        {{-- Bar Chart: Expense by Type --}}
        <div class="bg-base-100 border border-gray-300 rounded-lg p-5">
            <h2 class="text-lg font-semibold mb-4">Vehicle Expenses by Type</h2>
            <canvas id="vehicleExpenseChart" height="150"></canvas>
        </div>

        {{-- Line Chart: Monthly Expenses --}}
        <div class="bg-base-100 border border-gray-300 rounded-lg p-5">
            <h2 class="text-lg font-semibold mb-4">Monthly Vehicle Expenses</h2>
            <canvas id="monthlyExpenseChart" height="150"></canvas>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // ===== Bar Chart (Expense per Type) =====
        const barCtx = document.getElementById('vehicleExpenseChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Vehicle Expenses (₱)',
                    data: @json($chartValues),
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: { responsive: true }
        });

        // ===== Line Chart (Monthly Expenses) =====
        const lineCtx = document.getElementById('monthlyExpenseChart').getContext('2d');
        let lineChart = new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: @json($lineLabels),
                datasets: [{
                    label: 'Total Expenses (₱)',
                    data: @json($lineValues),
                    fill: false,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: { display: true, text: 'Vehicle Expenses Over Months' },
                    legend: { display: true }
                },
                scales: { y: { beginAtZero: true } }
            }
        });

        // ===== AJAX Filter =====
        $('#filterForm').submit(function (e) {
            e.preventDefault();
            const month = $(this).find('select[name="month"]').val();
            const year = $(this).find('select[name="year"]').val();

            $.ajax({
                url: '{{ route("admin.dashboard.chart") }}',
                type: 'GET',
                data: { month, year },
                success: function (res) {
                    lineChart.data.labels = res.labels;
                    lineChart.data.datasets[0].data = res.values;
                    lineChart.update();
                },
                error: function () {
                    alert('Failed to fetch chart data!');
                }
            });
        });
    </script>
@endpush