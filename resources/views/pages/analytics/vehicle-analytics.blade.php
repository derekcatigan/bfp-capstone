{{-- resources\views\pages\analytics\vehicle-analytics.blade.php --}}
@extends('layouts.layout')

@section('content')
    <div class="p-3">
        <h1 class="text-2xl font-bold mb-5">Vehicle Expense Analytics</h1>

        {{-- SUMMARY CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-5 mb-6">
            @php
                $types = ['fuel', 'gear_oil', 'lub_oil', 'grease', 'repair'];
            @endphp

            @foreach($types as $type)
                <div class="bg-base-100 border border-gray-300 rounded-lg p-5 hover:border-primary/40 transition">
                    <p class="text-sm text-base-content/60 capitalize">{{ str_replace('_', ' ', $type) }} Expenses</p>
                    <h2 class="text-2xl font-semibold mt-2">
                        ₱{{ number_format($expenseTotals[$type] ?? 0, 2) }}
                    </h2>
                </div>
            @endforeach
        </div>

        {{-- RECENT EXPENSES TABLE --}}
        <div class="bg-base-100 border border-gray-300 rounded-lg p-5">
            <h2 class="text-lg font-semibold mb-3">Recent Expenses</h2>

            <table class="table w-full">
                <thead>
                    <tr>
                        <th>Vehicle</th>
                        <th>Type</th>
                        <th>Total Cost</th>
                        <th>Date</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentExpenses as $expense)
                        <tr>
                            <td>{{ $expense->vehicle->plate_number }}</td>
                            <td class="capitalize">{{ str_replace('_', ' ', $expense->type) }}</td>
                            <td>₱{{ number_format($expense->total_cost, 2) }}</td>
                            <td>{{ $expense->expense_date->format('M d, Y') }}</td>
                            <td>{{ $expense->description ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No expenses recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination Links --}}
            <div class="mt-4">
                {{ $recentExpenses->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')

@endpush