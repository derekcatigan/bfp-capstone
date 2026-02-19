{{-- resources\views\pages\shared\manage-repair.blade.php --}}
@extends('layouts.layout')

@section('content')
    <div class="max-w-4xl mx-auto p-6 space-y-6">

        {{-- HEADER --}}
        <div class="bg-base-100 border border-gray-300 rounded-lg p-6">
            <h1 class="text-2xl font-semibold">Vehicle Repair Form</h1>
            <p class="text-sm text-base-content/60 mt-1">
                Record maintenance expenses and automatically mark vehicle as under repair.
            </p>
        </div>

        <form id="repairForm" class="space-y-6">
            @csrf

            {{-- VEHICLE SELECTION --}}
            <div class="bg-base-100 border border-gray-300 rounded-lg p-6 space-y-4">
                <h2 class="text-lg font-semibold">Vehicle Information</h2>

                <div>
                    <label class="text-sm font-medium block mb-1">Select Vehicle</label>
                    <select name="vehicle_id"
                        class="select w-full border-gray-300 focus:border-primary focus:outline-none rounded-md" required>
                        <option value="">Choose Vehicle</option>
                        @foreach($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}">
                                {{ $vehicle->plate_number }} - {{ $vehicle->make }} {{ $vehicle->model }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- FLUID EXPENSES --}}
            <div class="bg-base-100 border border-gray-300 rounded-lg p-6 space-y-5">
                <h2 class="text-lg font-semibold">Fluid Expenses</h2>

                <div class="grid sm:grid-cols-2 gap-5">

                    <div class="space-y-1">
                        <label class="text-sm font-medium">Fuel</label>
                        <input type="number" step="0.01" name="fuel_total"
                            class="input w-full border-gray-300 rounded-md focus:border-primary" placeholder="0.00">
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium">Gear Oil</label>
                        <input type="number" step="0.01" name="gear_oil_total"
                            class="input w-full border-gray-300 rounded-md focus:border-primary" placeholder="0.00">
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium">Lubricant Oil</label>
                        <input type="number" step="0.01" name="lub_oil_total"
                            class="input w-full border-gray-300 rounded-md focus:border-primary" placeholder="0.00">
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium">Grease</label>
                        <input type="number" step="0.01" name="grease_total"
                            class="input w-full border-gray-300 rounded-md focus:border-primary" placeholder="0.00">
                    </div>

                </div>
            </div>

            {{-- REPAIR COST --}}
            <div class="bg-base-100 border border-gray-300 rounded-lg p-6 space-y-4">
                <h2 class="text-lg font-semibold">Repair Expense</h2>

                <div>
                    <label class="text-sm font-medium block mb-1">Repair Total Cost</label>
                    <input type="number" step="0.01" name="repair_total"
                        class="input w-full border-gray-300 rounded-md focus:border-primary" placeholder="0.00">
                </div>
            </div>

            {{-- REPAIR NOTES --}}
            <div class="bg-base-100 border border-gray-300 rounded-lg p-6 space-y-4">
                <h2 class="text-lg font-semibold">Repair Notes</h2>

                <div>
                    <label class="text-sm font-medium block mb-1">Description / Findings</label>
                    <textarea name="description" rows="4"
                        placeholder="Example: Replaced brake pads, cleaned carburetor, changed engine oil..."
                        class="textarea w-full border-gray-300 rounded-md focus:border-primary"></textarea>

                    <p class="text-xs text-base-content/60 mt-1">
                        Describe what was repaired, replaced, or serviced.
                    </p>
                </div>
            </div>

            {{-- ACTION FOOTER --}}
            <div class="bg-base-100 border border-gray-300 rounded-lg p-4 flex justify-end gap-3">
                <button type="reset" class="btn border-gray-300 rounded-md">
                    Clear
                </button>

                <button type="submit" class="btn btn-primary rounded-md px-8">
                    Submit Repair
                </button>
            </div>

        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/repair/repairForm.js') }}"></script>
@endpush