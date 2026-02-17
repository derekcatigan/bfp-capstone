{{-- resources\views\pages\admin\active-trip.blade.php --}}
@extends('layouts.layout')

@section('content')
    <div class="h-[calc(100vh-100px)] flex gap-4">

        <div class="flex-1 bg-base-100 border border-gray-300 shadow-xl overflow-hidden">
            <div class="bg-base-200 px-4 py-3 border-b border-gray-300">
                <h2 class="text-lg font-semibold flex items-center gap-3">
                    <svg class="text-red-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                        viewBox="0 0 24 24">
                        <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                        <path
                            d="m21.51 6.14-5-3a.99.99 0 0 0-.87-.08L8.09 5.89 3.51 3.14a.99.99 0 0 0-1.01-.01c-.31.18-.51.51-.51.87v13c0 .35.18.68.49.86l5 3c.26.16.58.19.87.08l7.55-2.83 4.59 2.75c.16.1.34.14.51.14s.34-.04.49-.13c.31-.18.51-.51.51-.87V7a.99.99 0 0 0-.49-.86M7 18.23l-3-1.8V5.77l3 1.8v10.67Zm8-1.93-6 2.25V7.69l6-2.25zm5 1.93-3-1.8V5.77l3 1.8v10.67Z">
                        </path>
                    </svg>
                    Active Deployment Map
                </h2>
            </div>

            <div class="px-4 py-2 bg-base-100 border-b border-gray-300 flex items-center gap-6 text-sm" id="routeInfo"
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

            <div id="activeTripsMap" class="w-full h-[calc(100%-60px)]"></div>
        </div>

        <div class="w-95 bg-base-100 shadow-xl rounded-xl flex flex-col">

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
            <div class="flex-1 overflow-y-auto p-3 space-y-3">

                @forelse($activeTrips as $trip)
                    <div class="trip-card p-4 rounded-lg border border-gray-300 cursor-pointer transition-all duration-200 hover:shadow-md hover:border-green-500"
                        data-destination-name="{{ $trip->place }}" data-destination-lat="{{ $trip->latitude }}"
                        data-destination-lng="{{ $trip->longitude }}" data-control="{{ $trip->control_no }}">
                        <div class="flex justify-between items-center">
                            <h3 class="font-bold text-sm">
                                {{ $trip->control_no }}
                            </h3>
                            <span class="badge badge-soft badge-success badge-sm border border-green-400">
                                Active
                            </span>
                        </div>

                        <div class="mt-2 text-sm space-y-1">
                            <p class="flex items-center gap-2">
                                <svg class="h-[1em]" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    fill="currentColor" viewBox="0 0 24 24">
                                    <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                                    <path
                                        d="M12 12c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5m0-8c1.65 0 3 1.35 3 3s-1.35 3-3 3-3-1.35-3-3 1.35-3 3-3M4 22h16c.55 0 1-.45 1-1v-1c0-3.86-3.14-7-7-7h-4c-3.86 0-7 3.14-7 7v1c0 .55.45 1 1 1m6-7h4c2.76 0 5 2.24 5 5H5c0-2.76 2.24-5 5-5">
                                    </path>
                                </svg>
                                {{ $trip->driver?->profile?->first_name }}
                                {{ $trip->driver?->profile?->last_name }}
                            </p>

                            <p class="flex items-center gap-2">
                                <svg class="h-[1em]" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    fill="currentColor" viewBox="0 0 24 24">
                                    <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                                    <path
                                        d="M19.1 7.8c-.38-.5-.97-.8-1.6-.8H15V6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2 0 1.65 1.35 3 3 3s3-1.35 3-3h4c0 1.65 1.35 3 3 3s3-1.35 3-3c1.1 0 2-.9 2-2v-3.67c0-.43-.14-.86-.4-1.2zM17.5 9l1.5 2h-4V9zM7 19a1.003 1.003 0 0 1-.87-1.5c.37-.63 1.36-.63 1.73 0 .09.15.13.32.13.49 0 .55-.45 1-1 1Zm2.23-3s-.05-.05-.08-.07c-.06-.06-.12-.11-.17-.16-.12-.11-.25-.21-.38-.29a3 3 0 0 0-.67-.32c-.07-.02-.14-.05-.21-.07Q7.375 15 7 15c-.375 0-.49.04-.72.09-.07.02-.14.05-.21.07-.16.05-.31.11-.45.19-.07.04-.15.08-.22.13-.13.09-.26.18-.38.29-.06.05-.12.1-.18.16-.02.03-.05.04-.08.07h-.77V6h9v10H9.22ZM17 19a1.003 1.003 0 0 1-.87-1.5c.37-.63 1.36-.63 1.73 0 .09.15.13.32.13.49 0 .55-.45 1-1 1Zm3-3h-.77s-.05-.05-.08-.07c-.06-.06-.12-.11-.17-.16-.12-.11-.25-.21-.38-.29a3 3 0 0 0-.67-.32c-.07-.02-.14-.05-.21-.07Q17.375 15 17 15c-.375 0-.47.04-.7.09-.06.01-.12.03-.18.05-.18.06-.36.13-.52.22l-.12.06c-.17.1-.33.21-.48.35v-2.76h5v3Z">
                                    </path>
                                </svg>
                                {{ $trip->vehicle?->plate_number }}
                                <span class="opacity-60">
                                    ({{ $trip->vehicle?->vehicle_type }})
                                </span>
                            </p>

                            <p class="flex items-center gap-2 text-xs">
                                <svg class="h-[1.2em]" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    fill="currentColor" viewBox="0 0 24 24">
                                    <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                                    <path
                                        d="M11 9.86V16l1 2 1-2V9.86c1.72-.45 3-2 3-3.86 0-2.21-1.79-4-4-4S8 3.79 8 6c0 1.86 1.28 3.41 3 3.86M12 4c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2">
                                    </path>
                                    <path
                                        d="M15 14.17v2.01c3.29.41 5 1.41 5 1.82 0 .51-2.75 2-8 2s-8-1.49-8-2c0-.4 1.71-1.41 5-1.82v-2.01c-3.75.42-7 1.66-7 3.83 0 2.75 5.18 4 10 4s10-1.25 10-4c0-2.18-3.25-3.41-7-3.83">
                                    </path>
                                </svg>
                                {{ Str::limit($trip->place, 60) }}
                            </p>
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