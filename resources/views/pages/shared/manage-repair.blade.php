{{-- resources\views\pages\shared\manage-repair.blade.php --}}
@extends('layouts.layout')

@section('content')
    <div class="p-6 max-w-2xl mx-auto">

        <h1 class="text-2xl font-bold mb-6">VEHICLE REPAIR FORM</h1>

        <form id="repairForm">

            @csrf

            {{-- Vehicle Select --}}
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Select Vehicle</label>
                <select name="vehicle_id" class="select select-bordered w-full" required>
                    <option value="">-- Choose Vehicle --</option>
                    @foreach($vehicles as $vehicle)
                        <option value="{{ $vehicle->id }}">
                            {{ $vehicle->plate_number }} - {{ $vehicle->make }} {{ $vehicle->model }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Expenses --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-medium mb-1">Fuel Expenses</label>
                    <input type="number" step="0.01" name="fuel_total" class="input input-bordered w-full"
                        placeholder="0.00">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Gear Oil Expenses</label>
                    <input type="number" step="0.01" name="gear_oil_total" class="input input-bordered w-full"
                        placeholder="0.00">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Lub Oil Expenses</label>
                    <input type="number" step="0.01" name="lub_oil_total" class="input input-bordered w-full"
                        placeholder="0.00">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Grease Expenses</label>
                    <input type="number" step="0.01" name="grease_total" class="input input-bordered w-full"
                        placeholder="0.00">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium mb-1">Repair Expenses</label>
                    <input type="number" step="0.01" name="repair_total" class="input input-bordered w-full"
                        placeholder="0.00">
                </div>

            </div>

            <div class="mt-6">
                <button type="submit" class="btn btn-primary w-full">Submit Repair</button>
            </div>

        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/repair/repairForm.js') }}"></script>
@endpush