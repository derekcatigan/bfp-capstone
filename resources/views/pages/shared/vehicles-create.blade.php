{{-- resources\views\pages\shared\vehicles-create.blade.php --}}
@extends('layouts.layout')

@section('content')
    <div class="max-w-6xl mx-auto px-3 sm:px-6 p-3">

        <div class="card">
            <div class="card-body">

                <h2 class="text-2xl font-bold mb-2">REGISTER NEW VEHICLE</h2>
                <p class="text-sm text-gray-500 mb-6">
                    Fill in the vehicle information below
                </p>

                <form id="vehicleForm" enctype="multipart/form-data" class="space-y-8">
                    @csrf

                    {{-- ================= BASIC INFO ================= --}}
                    <div>
                        <h3 class="font-semibold text-lg mb-3">Basic Information</h3>

                        <div class="grid grid-cols-12 gap-4">

                            <div class="col-span-12 md:col-span-6">
                                <label class="label">Plate Number</label>
                                <input type="text" name="plate_number" class="input input-bordered w-full"
                                    placeholder="e.g. BFP-001">
                            </div>

                            <div class="col-span-12 md:col-span-6">
                                <label class="label">Vehicle Type</label>
                                <select name="vehicle_type" id="vehicle_type" class="select w-full">
                                    <option value="">Select Vehicle Type</option>
                                    <option value="fire truck">Fire Truck</option>
                                    <option value="rescue truck">Rescue Truck</option>
                                    <option value="ambulance">Ambulance</option>
                                    <option value="patrol vehicle">Patrol Vehicle</option>
                                    <option value="water tanker">Water Tanker</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <div class="col-span-12 md:col-span-4">
                                <label class="label">Make</label>
                                <input type="text" name="make" class="input input-bordered w-full"
                                    placeholder="e.g. Toyota">
                            </div>

                            <div class="col-span-12 md:col-span-4">
                                <label class="label">Model</label>
                                <input type="text" name="model" class="input input-bordered w-full"
                                    placeholder="e.g. Hiace">
                            </div>

                            <div class="col-span-6 md:col-span-2">
                                <label class="label">Year</label>
                                <input type="number" name="year" min="1980" max="{{ date('Y') }}"
                                    class="input input-bordered w-full" placeholder="e.g. 1990">
                            </div>

                            <div class="col-span-6 md:col-span-2">
                                <label class="label">Color</label>
                                <input type="text" name="color" class="input input-bordered w-full" placeholder="e.g. Red">
                            </div>

                        </div>
                    </div>

                    <div class="divider"></div>

                    {{-- ================= IDENTIFIERS ================= --}}
                    <div>
                        <h3 class="font-semibold text-lg mb-3">Vehicle Identifiers</h3>

                        <div class="grid grid-cols-12 gap-4">

                            <div class="col-span-12 md:col-span-6">
                                <label class="label">Engine Number</label>
                                <input type="text" name="engine_number" class="input input-bordered w-full"
                                    placeholder="Engine Serial Number">
                            </div>

                            <div class="col-span-12 md:col-span-6">
                                <label class="label">Chassis Number</label>
                                <input type="text" name="chassis_number" class="input input-bordered w-full"
                                    placeholder="Chassis Serial Number">
                            </div>

                        </div>
                    </div>

                    <div class="divider"></div>

                    {{-- ================= FUEL ================= --}}
                    <div>
                        <h3 class="font-semibold text-lg mb-3">Fuel Information</h3>

                        <div class="grid grid-cols-12 gap-4">

                            <div class="col-span-12 md:col-span-4">
                                <label class="label">Fuel Type</label>
                                <select name="fuel_type" class="select select-bordered w-full">
                                    <option value="">Select Fuel Type</option>
                                    <option>Diesel</option>
                                    <option>Gasoline</option>
                                    <option>Hybrid</option>
                                    <option>LPG</option>
                                </select>
                            </div>

                            <div class="col-span-6 md:col-span-4">
                                <label class="label">Tank Capacity (Liters)</label>
                                <input type="number" step="0.01" name="fuel_tank_capacity"
                                    class="input input-bordered w-full" placeholder="e.g. 100">
                            </div>

                            <div class="col-span-6 md:col-span-4">
                                <label class="label">Current Fuel Level (Liters)</label>
                                <input type="number" step="0.01" name="current_fuel_level"
                                    class="input input-bordered w-full" placeholder="e.g. 50">
                            </div>

                        </div>
                    </div>

                    <div class="divider"></div>

                    {{-- ================= STATUS ================= --}}
                    <div>
                        <h3 class="font-semibold text-lg mb-3">Status</h3>

                        <select name="status" class="select select-bordered w-full md:w-72">
                            <option>Available</option>
                            <option>Deployed</option>
                            <option>In Repair</option>
                            <option>Inactive</option>
                        </select>
                    </div>

                    <div class="divider"></div>

                    {{-- ================= MEDIA ================= --}}
                    <div>
                        <h3 class="font-semibold text-lg mb-3">Additional Information</h3>

                        <div class="space-y-4">

                            <div>
                                <label class="label">Vehicle Photo</label>
                                <input type="file" name="image" class="file-input file-input-bordered w-full md:w-96">
                            </div>

                            <div>
                                <label class="label">Description</label>
                                <textarea name="description" rows="5" class="textarea textarea-bordered w-full"
                                    placeholder="Additional vehicle details..."></textarea>
                            </div>

                        </div>
                    </div>

                    {{-- SUBMIT --}}
                    <div class="flex justify-end pt-4">
                        <button type="submit" class="btn btn-primary px-12">
                            Save Vehicle
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/vehicles/vehicleCreate.js') }}"></script>
@endsection