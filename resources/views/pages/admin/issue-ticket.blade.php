{{-- resources\views\pages\admin\issue-ticket.blade.php --}}
@extends('layouts.layout')

@section('head')
    <link rel="stylesheet" href="{{ asset('assets/css/preview-section.css') }}">
@endsection

@section('content')


    <div class="h-full flex flex-wrap items-stretch gap-1 p-3">

        {{-- Form Section --}}
        <div class="flex-1 min-w-50 bg-white border border-gray-300 rounded shadow p-3 h-250 overflow-scroll">
            <form id="ticketForm">
                @csrf

                {{-- Section A --}}
                <div class="p-3 bg-gray-800 rounded border-l-4 border-blue-500 mb-3">
                    <h2 class="font-bold text-md text-neutral-300">A. ADMINISTRATIVE SECTION</h2>
                </div>

                <div class="space-y-1 mb-3">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-1">
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Control Number:</legend>
                            <input type="text" name="control_no" id="control_no" class="input w-full"
                                placeholder="Control No.">
                        </fieldset>
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Date:</legend>
                            <input type="date" name="ticket_date" id="ticket_date" class="input w-full">
                        </fieldset>
                    </div>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Driver Name:</legend>
                        <select name="driver_id" id="driver_id" class="select w-full">
                            <option disabled selected>Select Driver Name</option>
                            @foreach ($drivers as $driver)
                                <option value="{{ $driver->id }}">
                                    {{ $driver->profile->first_name }} {{ $driver->profile->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </fieldset>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Plate Number:</legend>
                        <select name="vehicle_id" id="vehicle_id" class="select w-full">
                            <option disabled selected>Select Plate No</option>

                            @foreach ($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" data-status="{{ $vehicle->status }}">
                                    {{ $vehicle->plate_number }}
                                </option>
                            @endforeach

                        </select>
                    </fieldset>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Authorized Passenger:</legend>
                        <input type="text" name="authorized_passenger" id="authorized_passenger" class="input w-full"
                            placeholder="Authorized Passenger">
                    </fieldset>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Places to Visit</legend>
                        <div class="flex items-center gap-1">
                            <input type="text" name="places_visit" id="places_visit" class="input w-full"
                                placeholder="Select Places to Visit">
                            <button type="button" id="openMapBtn" class="btn btn-primary">Map</button>
                        </div>
                    </fieldset>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Purpose:</legend>
                        <textarea name="purpose" id="purpose" class="textarea w-full" placeholder="Purpose"></textarea>
                    </fieldset>
                </div>

                {{-- Section B --}}
                <div class="p-3 bg-gray-800 rounded border-l-4 border-blue-500 mb-3">
                    <h2 class="font-bold text-md text-neutral-300">B. DRIVER SECTION</h2>
                </div>

                <div class="space-y-1 mb-3">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-1">
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Time Departed (Garage):</legend>
                            <input type="time" name="time_departed" id="time_departed" class="input w-full"
                                placeholder="Time Departed (Garage)">
                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Time Arrival at Destination:</legend>
                            <input type="time" name="time_arrival_destination" id="time_arrival_destination"
                                class="input w-full" placeholder="Time Arrival at Destination">
                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Time Departure from Destination:</legend>
                            <input type="time" name="time_departure_destination" id="time_departure_destination"
                                class="input w-full" placeholder="Time Departure from Destination">
                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Time Arrival back at Garage:</legend>
                            <input type="time" name="time_arrival_garage" id="time_arrival_garage" class="input w-full"
                                placeholder="Time Arrival back at Garage">
                        </fieldset>
                    </div>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Approx. Distance (kms):</legend>
                        <input type="text" name="distance" id="distance" class="input w-full"
                            placeholder="Approx. Distance (kms)">
                    </fieldset>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Balance in Tank:</legend>
                        <input type="text" name="balance_tank" id="balance_tank" class="input w-full"
                            placeholder="Balance in Tank">
                    </fieldset>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Issued from Stock:</legend>
                        <input type="text" name="issued_stock" id="issued_stock" class="input w-full"
                            placeholder="Issued from Stock">
                    </fieldset>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-1">
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Add Purchased during Trip:</legend>
                            <input type="text" name="purchased_trip" id="purchased_trip" class="input w-full"
                                placeholder="Add Purchased during Trip">
                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Deduct Used during Trip:</legend>
                            <input type="text" name="deduct_trip" id="deduct_trip" class="input w-full"
                                placeholder="Deduct Used during Trip">
                        </fieldset>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-1">
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Gear Oil Issued:</legend>
                            <input type="text" name="gear_oil" id="gear_oil" class="input w-full"
                                placeholder="Gear Oil Issued">
                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Lub. Oil Issued:</legend>
                            <input type="text" name="lub_oil" id="lub_oil" class="input w-full"
                                placeholder="Lub. Oil Issued">
                        </fieldset>

                        <fieldset class="fieldset col-span-2">
                            <legend class="fieldset-legend">Grease Issued:</legend>
                            <input type="text" name="grease_issued" id="grease_issued" class="input w-full"
                                placeholder="Grease Issued">
                        </fieldset>
                    </div>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Speedometer at Beginning of Trip:</legend>
                        <input type="text" name="speedometer_start" id="speedometer_start" class="input w-full"
                            placeholder="Speedometer at Beginning of Trip">
                    </fieldset>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Speedometer at End of Trip:</legend>
                        <input type="text" name="speedometer_end" id="speedometer_end" class="input w-full"
                            placeholder="Speedometer at End of Trip">
                    </fieldset>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Remarks:</legend>
                        <textarea class="textarea w-full" name="remarks" id="remarks" placeholder="Remarks"></textarea>
                    </fieldset>
                </div>

                {{-- Section C --}}
                <div class="p-3 bg-gray-800 rounded border-l-4 border-blue-500 mb-3">
                    <h2 class="font-bold text-md text-neutral-300">C. PASSENGER CERTIFICATION SECTION</h2>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-1">
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Name:</legend>
                        <input type="text" name="passenger_name1" id="passenger_name1" class="input w-full"
                            placeholder="Name">
                    </fieldset>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Date:</legend>
                        <input type="date" name="passenger_date1" id="passenger_date1" class="input w-full"
                            placeholder="Speedometer at End of Trip">
                    </fieldset>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-1">
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Name:</legend>
                        <input type="text" name="passenger_name2" id="passenger_name2" class="input w-full"
                            placeholder="Name">
                    </fieldset>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Date:</legend>
                        <input type="date" name="passenger_date2" id="passenger_date2" class="input w-full"
                            placeholder="Speedometer at End of Trip">
                    </fieldset>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-1">
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Name:</legend>
                        <input type="text" name="passenger_name3" id="passenger_name3" class="input w-full"
                            placeholder="Name">
                    </fieldset>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Date:</legend>
                        <input type="date" name="passenger_date3" id="passenger_date3" class="input w-full"
                            placeholder="Speedometer at End of Trip">
                    </fieldset>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-block btn-primary">Submit</button>
                </div>
            </form>
        </div>

        {{-- resources\views\includes\preview-paper.blade.php --}}
        @include('includes.preview-paper')


        {{-- Map Modal --}}
        <dialog id="mapModal" class="modal">
            <div class="modal-box w-11/12 max-w-5xl">

                <!-- Header -->
                <div class="flex justify-between items-center p-4 border-b">
                    <h3 class="font-bold text-lg">Select Location</h3>
                </div>

                <!-- Search -->
                <div class="p-3 border-b flex gap-2">
                    <input type="text" id="locationSearch" class="input input-bordered w-full"
                        placeholder="Search location (ex. Maasin City Hall)">
                    <button type="button" id="searchBtn" class="btn btn-primary">Search</button>
                </div>

                <!-- Map -->
                <div id="map" class="w-full h-105"></div>

                <!-- Footer -->
                <div class="p-3 flex justify-end gap-2 border-t">
                    <button id="selectLocationBtn" class="btn btn-success">Use This Location</button>
                </div>

                <div class="modal-action">
                    <form method="dialog">
                        <!-- if there is a button, it will close the modal -->
                        <button class="btn">Close</button>
                    </form>
                </div>
            </div>
        </dialog>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('assets/js/ticket/issueTicket.js') }}"></script>
    <script src="{{ asset('assets/js/map/map.js') }}"></script>
@endsection