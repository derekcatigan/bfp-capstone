{{-- resources\views\pages\shared\edit-ticket.blade.php --}}
@extends('layouts.layout')

@section('content')
       <div class="p-3">
                <form id="editTicketForm" data-update-url="{{ route('ticket.update', $ticket->id) }}"
                    data-index-url="{{ route('ticket.index') }}">
                    @csrf
                    @method('PUT')

                    {{-- ====== Section A ====== --}}
                    <div class="p-3 bg-gray-800 rounded border-l-4 border-blue-500 mb-3">
                        <h2 class="font-bold text-md text-neutral-300">A. ADMINISTRATIVE SECTION</h2>
                    </div>

                    <div class="space-y-1 mb-3">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-1">
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">Control Number:</legend>
                                <input type="text" name="control_no" id="control_no" class="input w-full"
                                    value="{{ old('control_no', $ticket->control_no) }}" placeholder="Control No.">
                            </fieldset>
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">Date:</legend>
                                <input type="date" name="ticket_date" id="ticket_date"
                                    value="{{ old('ticket_date', $ticket->ticket_date) }}" class="input w-full">
                            </fieldset>
                        </div>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Driver Name:</legend>
                            <select name="driver_id" id="driver_id" class="select w-full">
                                <option disabled {{ old('driver_id', $ticket->driver_id) ? '' : 'selected' }}>Select Driver Name
                                </option>
                                @foreach ($drivers as $driver)
                                    <option value="{{ $driver->id }}" {{ old('driver_id', $ticket->driver_id) == $driver->id ? 'selected' : '' }}>
                                        {{ $driver->profile->first_name }} {{ $driver->profile->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Plate Number:</legend>
                            <select name="vehicle_id" id="vehicle_id" class="select w-full">
                                <option disabled {{ old('vehicle_id', $ticket->vehicle_id) ? '' : 'selected' }}>Select Plate No
                                </option>
                                @foreach ($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}" {{ old('vehicle_id', $ticket->vehicle_id) == $vehicle->id ? 'selected' : '' }}>
                                        {{ $vehicle->plate_number }}
                                    </option>
                                @endforeach
                            </select>
                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Authorized Passenger:</legend>
                            <input type="text" name="authorized_passenger" id="authorized_passenger" class="input w-full"
                                value="{{ old('authorized_passenger', $ticket->authorized_passenger) }}"
                                placeholder="Authorized Passenger">
                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Places to Visit:</legend>
                            <div class="flex items-center gap-1">
                                <input type="text" name="places_visit" id="places_visit" class="input w-full"
                                    value="{{ old('places_visit', $ticket->place) }}" placeholder="Select Places to Visit">
                                <button type="button" id="openMapBtn" class="btn btn-primary">Map</button>
                            </div>
                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Purpose:</legend>
                            <textarea name="purpose" id="purpose" class="textarea w-full"
                                placeholder="Purpose">{{ old('purpose', $ticket->purpose) }}</textarea>
                        </fieldset>
                    </div>

                    {{-- ====== Section B ====== --}}
                    <div class="p-3 bg-gray-800 rounded border-l-4 border-blue-500 mb-3">
                        <h2 class="font-bold text-md text-neutral-300">B. DRIVER SECTION</h2>
                    </div>

                    <div class="space-y-1 mb-3">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-1">
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">Time Departed (Garage):</legend>
                                <input type="time" name="time_departed" id="time_departed" class="input w-full"
                                    value="{{ old('time_departed', $ticket->time_departed_garage) }}">
                            </fieldset>

                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">Time Arrival at Destination:</legend>
                                <input type="time" name="time_arrival_destination" id="time_arrival_destination"
                                    value="{{ old('time_arrival_destination', $ticket->time_arrival_destination) }}"
                                    class="input w-full">
                            </fieldset>

                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">Time Departure from Destination:</legend>
                                <input type="time" name="time_departure_destination" id="time_departure_destination"
                                    value="{{ old('time_departure_destination', $ticket->time_departure_destination) }}"
                                    class="input w-full">
                            </fieldset>

                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">Time Arrival back at Garage:</legend>
                                <input type="time" name="time_arrival_garage" id="time_arrival_garage" class="input w-full"
                                    value="{{ old('time_arrival_garage', $ticket->time_arrival_garage) }}">
                            </fieldset>
                        </div>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Approx. Distance (kms):</legend>
                            <input type="text" name="distance" id="distance" class="input w-full"
                                value="{{ old('distance', $ticket->approx_distance) }}">
                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Balance in Tank:</legend>
                            <input type="text" name="balance_tank" id="balance_tank" class="input w-full"
                                value="{{ old('balance_tank', $ticket->balance_tank) }}">
                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Issued from Stock:</legend>
                            <input type="text" name="issued_stock" id="issued_stock" class="input w-full"
                                value="{{ old('issued_stock', $ticket->issued_stock) }}">
                        </fieldset>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-1">
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">Add Purchased during Trip:</legend>
                                <input type="text" name="purchased_trip" id="purchased_trip" class="input w-full"
                                    value="{{ old('purchased_trip', $ticket->purchased_trip) }}">
                            </fieldset>

                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">Deduct Used during Trip:</legend>
                                <input type="text" name="deduct_trip" id="deduct_trip" class="input w-full"
                                    value="{{ old('deduct_trip', $ticket->deduct_trip) }}">
                            </fieldset>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-1">
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">Gear Oil Issued:</legend>
                                <input type="text" name="gear_oil" id="gear_oil" class="input w-full"
                                    value="{{ old('gear_oil', $ticket->gear_oil_issued) }}">
                            </fieldset>

                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">Lub. Oil Issued:</legend>
                                <input type="text" name="lub_oil" id="lub_oil" class="input w-full"
                                    value="{{ old('lub_oil', $ticket->lub_oil_issued) }}">
                            </fieldset>

                            <fieldset class="fieldset col-span-2">
                                <legend class="fieldset-legend">Grease Issued:</legend>
                                <input type="text" name="grease_issued" id="grease_issued" class="input w-full"
                                    value="{{ old('grease_issued', $ticket->grease_issued) }}">
                            </fieldset>
                        </div>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Speedometer at Beginning of Trip:</legend>
                            <input type="text" name="speedometer_start" id="speedometer_start" class="input w-full"
                                value="{{ old('speedometer_start', $ticket->speedometer_start) }}">
                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Speedometer at End of Trip:</legend>
                            <input type="text" name="speedometer_end" id="speedometer_end" class="input w-full"
                                value="{{ old('speedometer_end', $ticket->speedometer_end) }}">
                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Remarks:</legend>
                            <textarea class="textarea w-full" name="remarks"
                                id="remarks">{{ old('remarks', $ticket->remarks) }}</textarea>
                        </fieldset>
                    </div>

                    {{-- ====== Section C ====== --}}
                    <div class="p-3 bg-gray-800 rounded border-l-4 border-blue-500 mb-3">
                        <h2 class="font-bold text-md text-neutral-300">C. PASSENGER CERTIFICATION SECTION</h2>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-1">
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Name:</legend>
                            <input type="text" name="passenger_name1" id="passenger_name1" class="input w-full"
                                value="{{ old('passenger_name1', $ticket->passenger_name1) }}">
                        </fieldset>
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Date:</legend>
                            <input type="date" name="passenger_date1" id="passenger_date1" class="input w-full"
                                value="{{ old('passenger_date1', $ticket->passenger_date1) }}">
                        </fieldset>
                    </div>

                    {{-- Repeat for passenger 2 and 3 --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-1">
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Name:</legend>
                            <input type="text" name="passenger_name2" id="passenger_name2" class="input w-full"
                                value="{{ old('passenger_name2', $ticket->passenger_name2) }}">
                        </fieldset>
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Date:</legend>
                            <input type="date" name="passenger_date2" id="passenger_date2" class="input w-full"
                                value="{{ old('passenger_date2', $ticket->passenger_date2) }}">
                        </fieldset>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-1">
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Name:</legend>
                            <input type="text" name="passenger_name3" id="passenger_name3" class="input w-full"
                                value="{{ old('passenger_name3', $ticket->passenger_name3) }}">
                        </fieldset>
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Date:</legend>
                            <input type="date" name="passenger_date3" id="passenger_date3" class="input w-full"
                                value="{{ old('passenger_date3', $ticket->passenger_date3) }}">
                        </fieldset>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-block btn-primary">Update Trip Ticket</button>
                    </div>
                </form>
            </div>


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

@endsection

@section('scripts')
    <script src="{{ asset('assets/js/ticket/editTicket.js') }}"></script>
    <script src="{{ asset('assets/js/map/map.js') }}"></script>
@endsection