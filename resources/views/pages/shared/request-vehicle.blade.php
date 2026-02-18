{{-- resources\views\pages\shared\request-vehicle.blade.php --}}
@extends('layouts.layout')

@section('content')

    <div class="flex items-center justify-center min-h-[80vh]">
        <div class="bg-white shadow-md border border-gray-200 rounded-lg p-8 w-full max-w-lg text-center space-y-6">

            <h1 class="text-2xl font-semibold text-gray-800">
                REQUEST VEHICLE REPAIR
            </h1>

            <p class="text-gray-600 text-sm"> Submit a repair request for a damaged or malfunctioning vehicle. Admin will
                review and
                schedule maintenance.
            </p>

            <form method="POST" action="{{ route('request.vehicle.store') }}">
                @csrf
                <button type="button" class="btn btn-warning w-full" onclick="openVehicleModal()">
                    Request Vehicle Repair
                </button>
            </form>
        </div>
    </div>

    {{-- View Modal --}}
    <dialog id="vehicle_modal" class="modal">
        <div class="modal-box">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>

            <h3 class="text-lg font-bold mb-4">Select Vehicle</h3>

            <form id="vehicleRepairForm">
                @csrf

                <select name="vehicle_id" id="vehicleSelect" class="select select-bordered w-full mb-4">
                    <option value="">Loading vehicles...</option>
                </select>

                <textarea name="notes" id="repairNotes" class="textarea textarea-bordered w-full mb-4"
                    placeholder="Describe the issue (e.g., brake noise, engine overheating, broken headlights...)" rows="4"
                    required></textarea>

                <button type="submit" class="btn btn-warning w-full">
                    Submit Repair Request
                </button>
            </form>
        </div>
    </dialog>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/vehicles/request-vehicle.js') }}"></script>
@endpush