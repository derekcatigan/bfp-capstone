{{-- resources\views\pages\admin\active-trip.blade.php --}}
@extends('layouts.layout')

@section('content')
    <div class="min-h-[calc(100vh-100px)] flex flex-col lg:flex-row gap-4">

        {{-- MAP PANEL --}}
        <div class="flex-1 bg-base-100 border border-gray-300 shadow-xl overflow-hidden flex flex-col min-h-105 lg:min-h-0">

            {{-- Header --}}
            <div class="bg-base-200 px-4 py-3 border-b border-gray-300">
                <h2 class="text-lg font-semibold flex items-center gap-3">
                    Active Deployment Map
                </h2>
            </div>

            {{-- Route Info --}}
            <div id="routeInfo"
                class="px-4 py-2 bg-base-100 border-b border-gray-300 flex flex-wrap items-center gap-x-6 gap-y-1 text-sm"
                style="display:none;">

                <div>
                    <span class="font-semibold">ETA:</span>
                    <span id="infoEta"></span>
                </div>

                <div>
                    <span class="font-semibold">Distance:</span>
                    <span id="infoDistance"></span>
                </div>
            </div>

            {{-- Map --}}
            <div id="activeTripsMap" class="w-full flex-1 min-h-75 lg:min-h-0"></div>

        </div>

        {{-- SIDEBAR --}}
        <div class="w-full lg:w-95 bg-base-100 shadow-xl rounded-xl flex flex-col max-h-[60vh] lg:max-h-none">

            {{-- Sidebar Header --}}
            <div class="px-4 py-3 border-b bg-base-200">
                <h2 class="text-lg font-semibold">
                    📋 Active Trips
                </h2>
                <p class="text-xs opacity-60">
                    {{ $activeTrips->count() }} currently deployed
                </p>
            </div>

            {{-- Scrollable List --}}
            <div class="flex-1 overflow-y-auto p-3 space-y-3 min-h-0">

                @forelse($activeTrips as $trip)
                    <div class="trip-card p-4 rounded-xl border border-gray-300 cursor-pointer transition-all duration-200 hover:shadow-md hover:border-green-500 active:scale-[0.98]"
                        data-trip-id="{{ $trip->id }}" data-destination-name="{{ $trip->place }}"
                        data-destination-lat="{{ $trip->latitude }}" data-destination-lng="{{ $trip->longitude }}"
                        data-current-lat="{{ $trip->latitude ?? 10.132646794843092 }}"
                        data-current-lng="{{ $trip->longitude ?? 124.83489696799799 }}" data-control="{{ $trip->control_no }}">

                        <div class="flex justify-between items-center">
                            <h3 class="font-bold text-sm">{{ $trip->control_no }}</h3>
                            <span class="badge badge-soft badge-success badge-sm border border-green-400">Active</span>
                        </div>

                        <div class="mt-2 text-sm space-y-1">
                            <p>{{ $trip->driver?->profile?->first_name }} {{ $trip->driver?->profile?->last_name }}</p>
                            <p>{{ $trip->vehicle?->plate_number }}</p>
                            <p class="text-xs">{{ Str::limit($trip->place, 60) }}</p>
                        </div>
                    </div>
                @empty
                    <div class="flex items-center justify-center h-full text-sm opacity-50">
                        No active trips right now
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/map/active-trips.js') }}"></script>
@endpush