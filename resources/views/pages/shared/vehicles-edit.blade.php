{{-- resources\views\pages\shared\vehicles-edit.blade.php --}}
@extends('layouts.layout')

@section('content')
    <div class="max-w-6xl mx-auto px-3 sm:px-6 p-3">

        <div class="card">
            <div class="card-body">

                <h2 class="text-2xl font-bold mb-2">UPDATE VEHICLE</h2>
                <p class="text-sm text-gray-500 mb-6">
                    Fill in the vehicle information below
                </p>

                <form id="editVehicleForm" enctype="multipart/form-data" method="POST"
                    action="{{ route('vehicle.update', $vehicle->id) }}">
                    @csrf
                    @method('PUT')

                    {{-- ================= BASIC INFO ================= --}}
                    <div>
                        <h3 class="font-semibold text-lg mb-3">Basic Information</h3>

                        <div class="grid grid-cols-12 gap-4">

                            <div class="col-span-12 md:col-span-6">
                                <label class="label">Plate Number</label>
                                <input type="text" name="plate_number" class="input input-bordered w-full"
                                    placeholder="e.g. BFP-001" value="{{ old('plate_number', $vehicle->plate_number) }}">
                            </div>

                            <div class="col-span-12 md:col-span-6">
                                <label class="label">Vehicle Type</label>
                                <select name="vehicle_type" id="vehicle_type" class="select w-full">
                                    <option value="">Select Vehicle Type</option>
                                    @foreach(['fire truck', 'rescue truck', 'ambulance', 'patrol vehicle', 'water tanker', 'other'] as $type)
                                        <option value="{{ $type }}" {{ old('vehicle_type', $vehicle->vehicle_type) && strtolower(old('vehicle_type', $vehicle->vehicle_type)) === strtolower($type) ? 'selected' : '' }}>
                                            {{ Str::title($type) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-span-12 md:col-span-4">
                                <label class="label">Make</label>
                                <input type="text" name="make" class="input input-bordered w-full" placeholder="e.g. Toyota"
                                    value="{{ old('make', $vehicle->make) }}">
                            </div>

                            <div class="col-span-12 md:col-span-4">
                                <label class="label">Model</label>
                                <input type="text" name="model" class="input input-bordered w-full" placeholder="e.g. Hiace"
                                    value="{{ old('model', $vehicle->model) }}">
                            </div>

                            <div class="col-span-6 md:col-span-2">
                                <label class="label">Year</label>
                                <input type="number" name="year" min="1980" max="{{ date('Y') }}"
                                    class="input input-bordered w-full" placeholder="e.g. 1990"
                                    value="{{ old('year', $vehicle->year) }}">
                            </div>

                            <div class="col-span-6 md:col-span-2">
                                <label class="label">Color</label>
                                <input type="text" name="color" class="input input-bordered w-full" placeholder="e.g. Red"
                                    value="{{ old('color', $vehicle->color) }}">
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
                                    placeholder="Engine Serial Number"
                                    value="{{ old('engine_number', $vehicle->engine_number) }}">
                            </div>

                            <div class="col-span-12 md:col-span-6">
                                <label class="label">Chassis Number</label>
                                <input type="text" name="chassis_number" class="input input-bordered w-full"
                                    placeholder="Chassis Serial Number"
                                    value="{{ old('chassis_number', $vehicle->chassis_number) }}">
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
                                    @foreach(['Diesel', 'Gasoline', 'Hybrid', 'LPG'] as $fuel)
                                        <option value="{{ $fuel }}" {{ old('fuel_type', $vehicle->fuel_type) === $fuel ? 'selected' : '' }}>
                                            {{ $fuel }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-span-6 md:col-span-4">
                                <label class="label">Tank Capacity (Liters)</label>
                                <input type="number" step="0.01" name="fuel_tank_capacity"
                                    class="input input-bordered w-full" placeholder="e.g. 100"
                                    value="{{ old('fuel_tank_capacity', $vehicle->fuel_tank_capacity) }}">
                            </div>

                            <div class="col-span-6 md:col-span-4">
                                <label class="label">Current Fuel Level (Liters)</label>
                                <input type="number" step="0.01" name="current_fuel_level"
                                    class="input input-bordered w-full" placeholder="e.g. 50"
                                    value="{{ old('current_fuel_level', $vehicle->current_fuel_level) }}">
                            </div>

                        </div>
                    </div>

                    <div class="divider"></div>

                    {{-- ================= STATUS ================= --}}
                    <div>
                        <h3 class="font-semibold text-lg mb-3">Status</h3>

                        <select name="status" class="select select-bordered w-full md:w-72">
                            @foreach(['Available', 'Deployed', 'In Repair', 'Inactive'] as $status)
                                <option value="{{ $status }}" {{ old('status', $vehicle->status) === $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="divider"></div>

                    {{-- ================= MEDIA ================= --}}
                    <div>
                        <h3 class="font-semibold text-lg mb-3">Additional Information</h3>

                        <div class="space-y-4">

                            <div>
                                <label class="label">Vehicle Photo</label>
                                @if($vehicle->image)
                                    <img src="{{ asset('storage/' . $vehicle->image) }}"
                                        class="rounded-lg h-40 w-full object-cover mb-2">
                                @endif
                                <input type="file" name="image" class="file-input file-input-bordered w-full md:w-96">
                            </div>

                            <div>
                                <label class="label">Description</label>
                                <textarea name="description" rows="5" class="textarea textarea-bordered w-full"
                                    placeholder="Additional vehicle details...">{{ old('description', $vehicle->description) }}</textarea>
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

@push('scripts')
    <script src="{{ asset('assets/js/vehicles/manage-vehicle.js') }}"></script>
@endpush