{{-- resources\views\pages\driver\driver-dashboard.blade.php --}}
@extends('layouts.layout')

@section('content')
    {{-- DASHBOARD HEADER --}}
    <div class="p-3">
        <div class="bg-base-100 border border-gray-300 rounded-lg px-7 py-6 flex items-center justify-between">

            <div class="flex items-center gap-5">

                {{-- AVATAR --}}
                <div
                    class="w-14 h-14 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xl font-semibold">
                    {{ strtoupper(substr($user->profile->first_name ?? 'U', 0, 1)) }}
                    {{ strtoupper(substr($user->profile->last_name ?? '', 0, 1)) }}
                </div>

                {{-- TEXT --}}
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight">
                        Welcome back,
                        <span class="text-primary">
                            {{ $user->profile->first_name ?? '' }}
                            {{ $user->profile->last_name ?? '' }}
                        </span>
                    </h1>

                    <p class="text-sm text-base-content/60 mt-1">
                        Here's what's happening in your system today.
                    </p>
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

            {{-- CARD --}}
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

                            <h2 class="text-3xl font-semibold mt-2 tracking-tight">
                                {{ number_format($card['value']) }}
                            </h2>
                        </div>

                        <div class="text-2xl opacity-70">
                            <x-icon :name="$card['icon']" class="w-7 h-7" />
                        </div>

                    </div>

                </div>
            @endforeach

        </div>
    </div>
@endsection