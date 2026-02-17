{{-- resources\views\pages\shared\manage-vehicle.blade.php --}}
@extends('layouts.layout')

@section('content')
    <div class="p-3">
        <h1 class="text-2xl font-bold">MANAGE VEHICLES</h1>
    </div>

    {{-- DASHBOARD COUNTS --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 p-3">

        {{-- TOTAL --}}
        <a href="?status=" class="rounded shadow border border-gray-300">
            <div class="flex flex-col p-3">
                <div class="flex justify-end">
                    <div class="bg-white border border-gray-300 p-1 rounded shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            viewBox="0 0 24 24">
                            <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                            <path
                                d="M19.1 7.8c-.38-.5-.97-.8-1.6-.8H15V6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2 0 1.65 1.35 3 3 3s3-1.35 3-3h4c0 1.65 1.35 3 3 3s3-1.35 3-3c1.1 0 2-.9 2-2v-3.67c0-.43-.14-.86-.4-1.2zM17.5 9l1.5 2h-4V9zM7 19a1.003 1.003 0 0 1-.87-1.5c.37-.63 1.36-.63 1.73 0 .09.15.13.32.13.49 0 .55-.45 1-1 1Zm2.23-3s-.05-.05-.08-.07c-.06-.06-.12-.11-.17-.16-.12-.11-.25-.21-.38-.29a3 3 0 0 0-.67-.32c-.07-.02-.14-.05-.21-.07Q7.375 15 7 15c-.375 0-.49.04-.72.09-.07.02-.14.05-.21.07-.16.05-.31.11-.45.19-.07.04-.15.08-.22.13-.13.09-.26.18-.38.29-.06.05-.12.1-.18.16-.02.03-.05.04-.08.07h-.77V6h9v10H9.22ZM17 19a1.003 1.003 0 0 1-.87-1.5c.37-.63 1.36-.63 1.73 0 .09.15.13.32.13.49 0 .55-.45 1-1 1Zm3-3h-.77s-.05-.05-.08-.07c-.06-.06-.12-.11-.17-.16-.12-.11-.25-.21-.38-.29a3 3 0 0 0-.67-.32c-.07-.02-.14-.05-.21-.07Q17.375 15 17 15c-.375 0-.47.04-.7.09-.06.01-.12.03-.18.05-.18.06-.36.13-.52.22l-.12.06c-.17.1-.33.21-.48.35v-2.76h5v3Z">
                            </path>
                        </svg>
                    </div>
                </div>

                <div class="text-center">
                    <p class="text-sm text-gray-500">TOTAL</p>
                    <p class="text-3xl font-bold">{{ $counts['total'] }}</p>
                </div>
            </div>
        </a>

        {{-- AVAILABLE --}}
        <a href="?status=available" class="rounded shadow border border-gray-300">
            <div class="flex flex-col p-3">
                <div class="flex justify-end">
                    <div class="bg-white border border-gray-300 p-1 rounded shadow">
                        <svg class="text-green-700" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="currentColor" viewBox="0 0 24 24">
                            <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                            <path
                                d="M12 22C6.49 22 2 17.51 2 12S6.49 2 12 2s10 4.49 10 10-4.49 10-10 10m0-18c-4.41 0-8 3.59-8 8s3.59 8 8 8 8-3.59 8-8-3.59-8-8-8">
                            </path>
                            <path
                                d="M10 16c-.26 0-.51-.1-.71-.29l-3-3L7.7 11.3l2.29 2.29 5.29-5.29 1.41 1.41-6 6c-.2.2-.45.29-.71.29Z">
                            </path>
                        </svg>
                    </div>
                </div>

                <div class="text-center">
                    <p class="text-sm text-green-600">AVAILABLE</p>
                    <p class="text-3xl font-bold text-green-700">{{ $counts['available'] }}</p>
                </div>
            </div>
        </a>

        {{-- DEPLOYED --}}
        <a href="?status=deployed" class="rounded shadow border border-gray-300">
            <div class="flex flex-col p-3">
                <div class="flex justify-end">
                    <div class="bg-white border border-gray-300 p-1 rounded shadow">
                        <svg class="text-blue-700" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="currentColor" viewBox="0 0 24 24">
                            <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                            <path
                                d="M17 5H6.5C4.02 5 2 7.02 2 9.5S4.02 14 6.5 14H17c1.1 0 2 .9 2 2s-.9 2-2 2H2v2h15c2.21 0 4-1.79 4-4s-1.79-4-4-4H6.5a2.5 2.5 0 0 1 0-5H17v3l5-4-5-4z">
                            </path>
                        </svg>
                    </div>
                </div>

                <div class="text-center">
                    <p class="text-sm text-blue-600">DEPLOYED</p>
                    <p class="text-3xl font-bold text-blue-700">{{ $counts['deployed'] }}</p>
                </div>
            </div>
        </a>

        {{-- IN REPAIR --}}
        <a href="?status=in repair" class="rounded shadow border border-gray-300">
            <div class="flex flex-col p-3">
                <div class="flex justify-end">
                    <div class="bg-white border border-gray-300 p-1 rounded shadow">
                        <svg class="text-yellow-700" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="currentColor" viewBox="0 0 24 24">
                            <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                            <path
                                d="M20.71 6.04a.99.99 0 0 0-.9.27l-3.18 3.18-2.12-2.12 3.18-3.18a.98.98 0 0 0 .27-.9c-.07-.33-.29-.6-.6-.73A7.47 7.47 0 0 0 9.2 4.19a7.49 7.49 0 0 0-1.86 7.52L2.3 16.75c-.19.19-.29.44-.29.71s.11.52.29.71l3.54 3.54c.19.19.44.29.71.29s.52-.11.71-.29l5.04-5.04c2.64.82 5.53.12 7.52-1.86a7.47 7.47 0 0 0 1.63-8.16c-.13-.31-.4-.53-.73-.6Zm-2.32 7.34a5.51 5.51 0 0 1-5.98 1.2c-.37-.15-.8-.07-1.09.22l-4.78 4.78-2.12-2.12 4.78-4.78c.29-.29.37-.71.22-1.09a5.47 5.47 0 0 1 1.2-5.98 5.5 5.5 0 0 1 4.41-1.59l-2.65 2.65a.996.996 0 0 0 0 1.41l3.54 3.54c.19.19.44.29.71.29s.52-.11.71-.29l2.65-2.65c.16 1.61-.4 3.23-1.59 4.42Z">
                            </path>
                        </svg>
                    </div>
                </div>

                <div class="text-center">
                    <p class="text-sm text-yellow-600">IN REPAIR</p>
                    <p class="text-3xl font-bold text-yellow-700">{{ $counts['repair'] }}</p>
                </div>
            </div>
        </a>

        {{-- INACTIVE --}}
        <a href="?status=inactive" class="rounded shadow border border-gray-300">
            <div class="flex flex-col p-3">
                <div class="flex justify-end">
                    <div class="bg-white border border-gray-300 p-1 rounded shadow">
                        <svg class="text-red-700" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="currentColor" viewBox="0 0 24 24">
                            <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                            <path
                                d="M12 2C6.49 2 2 6.49 2 12s4.49 10 10 10 10-4.49 10-10S17.51 2 12 2m0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8">
                            </path>
                            <path d="m12 12.59-3.29-3.3-1.42 1.42 4.71 4.7 4.71-4.7-1.42-1.42z"></path>
                        </svg>
                    </div>
                </div>

                <div class="text-center">
                    <p class="text-sm text-red-600">INACTIVE</p>
                    <p class="text-3xl font-bold text-red-800">{{ $counts['inactive'] }}</p>
                </div>
            </div>
        </a>

    </div>

    {{-- FILTER BAR --}}
    <form method="GET" class="card bg-base-100 shadow p-4 mx-3">
        <div class="flex flex-col md:flex-row gap-2">

            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Plate Number..."
                class="input input-bordered w-full">

            <select name="month" class="select select-bordered w-full">
                <option value="">All Months</option>
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                    </option>
                @endforeach
            </select>

            <select name="year" class="select select-bordered w-full">
                <option value="">All Years</option>
                @foreach(array_reverse(range(now()->year - 5, now()->year)) as $y)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endforeach
            </select>

            <button class="btn btn-primary">Filter</button>

            <a href="{{ route('vehicle.index') }}" class="btn btn-warning">Reset</a>
        </div>
    </form>

    {{-- VEHICLE LIST --}}
    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-4 p-3">
        @forelse ($vehicles as $vehicle)

            <div class="rounded shadow border border-gray-300">
                <div class="card-body" data-id="{{ $vehicle->id }}">

                    {{-- TITLE --}}
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="font-bold text-lg">{{ $vehicle->plate_number }}</h2>
                            <p class="text-sm text-gray-500">
                                {{ $vehicle->make }} {{ $vehicle->model }} ({{ $vehicle->year }})
                            </p>
                        </div>

                        <span class="badge badge-outline">
                            {{ Str::title($vehicle->status) }}
                        </span>
                    </div>

                    <div class="divider my-1"></div>

                    {{-- IMAGE --}}
                    @if($vehicle->image)
                        <img src="{{ asset('storage/' . $vehicle->image) }}" class="rounded-lg h-40 w-full object-cover mb-2">
                    @endif

                    {{-- DETAILS --}}
                    <p><strong>Type:</strong> {{ $vehicle->vehicle_type }}</p>
                    <p><strong>Fuel:</strong> {{ number_format($vehicle->fuelPercentage(), 1) }}%</p>
                    <p><strong>Color:</strong> {{ $vehicle->color }}</p>

                    <p class="text-sm text-gray-500 mt-2">
                        {{ $vehicle->description }}
                    </p>

                    {{-- ACTIONS --}}
                    <div class="card-actions justify-end mt-3">
                        <a href="{{ route('vehicle.edit', $vehicle->id) }}" class="btn btn-sm btn-warning btn-edit">
                            Edit
                        </a>
                        <button class="btn btn-sm btn-primary btn-view">
                            View Details
                        </button>
                    </div>

                </div>
            </div>

        @empty
            <div class="col-span-full flex items-center justify-center py-20">
                <div class="text-center space-y-2">
                    <p class="text-lg font-semibold text-gray-500">No vehicles found</p>
                    <p class="text-sm text-gray-400">Try adjusting filters</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4 px-3">
        {{ $vehicles->links() }}
    </div>

    {{-- VIEW DETAILS MODAL --}}
    <dialog id="vehicleModal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box max-w-3xl">

            <div class="flex justify-between items-start mb-3">
                <h3 id="modalPlate" class="text-xl font-bold"></h3>
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost">✕</button>
                </form>
            </div>

            <p id="modalMakeModel" class="text-gray-500 mb-3"></p>

            <div class="grid md:grid-cols-2 gap-6 items-start">

                {{-- IMAGE --}}
                <div class="space-y-3">

                    <!-- IMAGE -->
                    <div class="relative w-full h-56">

                        <img id="modalImage" class="rounded-2xl w-full h-56 object-cover shadow-md hidden">

                        <!-- FALLBACK CARD -->
                        <div id="imageFallback"
                            class="absolute inset-0 rounded-2xl bg-base-200 border border-dashed border-base-300 flex flex-col items-center justify-center text-center p-4">

                            <div class="text-5xl mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                                    <path
                                        d="M6.5 12a1.5 1.5 0 1 0 0 3 1.5 1.5 0 1 0 0-3m11 0a1.5 1.5 0 1 0 0 3 1.5 1.5 0 1 0 0-3">
                                    </path>
                                    <path
                                        d="m20.77 9.16-1.37-4.1a2.99 2.99 0 0 0-2.85-2.05H7.44a3 3 0 0 0-2.85 2.05l-1.37 4.1c-.72.3-1.23 1.02-1.23 1.84v5c0 .74.41 1.38 1 1.72V20c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-2h12v2c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-2.28a2 2 0 0 0 1-1.72v-5c0-.83-.51-1.54-1.23-1.84ZM7.44 5h9.12a1 1 0 0 1 .95.68L18.62 9H5.39L6.5 5.68A1 1 0 0 1 7.45 5ZM4 16v-5h16v5z">
                                    </path>
                                </svg>
                            </div>

                            <p class="font-bold text-lg" id="fallbackPlate"></p>
                            <p class="text-sm text-gray-500" id="fallbackMake"></p>

                            <span id="fallbackStatus" class="badge badge-outline mt-2"></span>
                        </div>

                    </div>

                    <!-- BADGES -->
                    <div class="flex gap-2 flex-wrap">
                        <span id="modalStatus" class="badge badge-outline"></span>
                        <span id="modalType" class="badge badge-primary badge-outline"></span>
                        <span id="modalColor" class="badge badge-secondary badge-outline"></span>
                    </div>

                </div>


                {{-- DETAILS --}}
                <div class="space-y-5">

                    {{-- IDENTIFIERS --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-base-200 border border-gray-300 rounded-xl p-3">
                            <p class="text-xs text-gray-500">Engine Number</p>
                            <p id="modalEngine" class="font-mono text-sm font-semibold"></p>
                        </div>

                        <div class="bg-base-200 border border-gray-300 rounded-xl p-3">
                            <p class="text-xs text-gray-500">Chassis Number</p>
                            <p id="modalChassis" class="font-mono text-sm font-semibold"></p>
                        </div>
                    </div>

                    {{-- FUEL CARD --}}
                    <div class="bg-base-200 border border-gray-300 rounded-2xl p-4 space-y-2">
                        <div class="flex justify-between items-center">
                            <p class="font-semibold">Fuel Level</p>
                            <span id="modalFuel" class="text-sm font-bold"></span>
                        </div>

                        <progress id="fuelGauge" class="progress progress-success w-full" value="0" max="100"></progress>

                        <p class="text-xs text-gray-500">
                            Estimated remaining fuel capacity
                        </p>
                    </div>

                    {{-- DESCRIPTION --}}
                    <div class="bg-base-100 border border-gray-500 rounded p-4">
                        <p class="text-sm text-gray-400 mb-1">Notes</p>
                        <p id="modalDescription" class="text-sm leading-relaxed"></p>
                    </div>

                </div>
            </div>

            <div class="modal-action">
                <form method="dialog">
                    <button class="btn">Close</button>
                </form>
            </div>

        </div>
    </dialog>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/vehicles/manage-vehicle.js') }}"></script>
@endpush