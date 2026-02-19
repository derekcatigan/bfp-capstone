{{-- resources\views\pages\shared\manage-storage.blade.php --}}
@extends('layouts.layout')

@section('content')
    <div class="max-w-7xl mx-auto p-6 space-y-6">

        {{-- HEADER --}}
        <div
            class="bg-base-100 border border-gray-300 p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold">Fuel Storage Management</h1>
                <p class="text-sm text-base-content/60">
                    Monitor fuel inventory and record stock movements.
                </p>
            </div>

            <div class="flex gap-3">
                <button class="btn btn-success px-6" onclick="addStockModal.showModal()">
                    + Add Stock
                </button>

                <button class="btn btn-error px-6" onclick="removeStockModal.showModal()">
                    − Remove Stock
                </button>
            </div>
        </div>

        {{-- FUEL LEVEL VISUAL --}}
        <div
            class="bg-base-100 border border-gray-300 p-6 rounded-md flex flex-col md:flex-row items-center justify-center gap-8 md:gap-12">

            {{-- Vertical Fuel Tank --}}
            <div class="flex flex-col items-center">
                <div
                    class="w-24 h-64 border border-gray-300 rounded-md relative bg-gray-100 flex flex-col-reverse overflow-hidden shadow-sm">
                    <div id="fuelLevelBar" class="bg-success w-full transition-all duration-500" style="height: 0%;"></div>
                    <span
                        class="absolute bottom-0 left-1/2 -translate-x-1/2 text-sm text-base-content/70 font-medium">Fuel</span>
                </div>
                <span class="mt-2 text-sm text-base-content/60">Tank Visualization</span>
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 w-full md:w-auto">
                <div class="bg-base-200 p-4 rounded-md shadow-sm flex flex-col">
                    <span class="text-sm text-gray-500">Current Level</span>
                    <span id="currentLevel" class="text-lg font-semibold text-base-content">0 L</span>
                </div>
                <div class="bg-base-200 p-4 rounded-md shadow-sm flex flex-col">
                    <span class="text-sm text-gray-500">Tank Capacity</span>
                    <span id="tankCapacity" class="text-lg font-semibold text-base-content">1000 L</span>
                </div>
                <div class="bg-base-200 p-4 rounded-md shadow-sm flex flex-col">
                    <span class="text-sm text-gray-500">Available Space</span>
                    <span id="availableSpace" class="text-lg font-semibold text-base-content">1000 L</span>
                </div>
                <div class="bg-base-200 p-4 rounded-md shadow-sm flex flex-col">
                    <span class="text-sm text-gray-500">Fill Percentage</span>
                    <span id="fillPercentage" class="text-lg font-semibold text-base-content">0%</span>
                </div>
            </div>

        </div>

        {{-- RECENT UPDATES --}}
        <div class="bg-base-100 border border-gray-300 p-6 space-y-4">
            <h2 class="text-lg font-semibold">Recent Stock Updates</h2>

            <div class="overflow-x-auto">
                <table class="table table-sm">
                    <thead class="bg-base-200 text-sm">
                        <tr>
                            <th>Date & Time</th>
                            <th>Container</th>
                            <th>Transaction</th>
                            <th>Amount (L)</th>
                            <th>Balance</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody id="recentUpdatesTable">
                        {{-- AJAX DATA --}}
                    </tbody>
                </table>
            </div>
        </div>

        {{-- COMPLETE HISTORY --}}
        <div class="bg-base-100 border border-gray-300 p-6 space-y-4">
            <h2 class="text-lg font-semibold">Complete Stock History</h2>

            <div class="overflow-x-auto">
                <table class="table table-md">
                    <thead class="bg-base-200 text-sm">
                        <tr>
                            <th>Date & Time</th>
                            <th>Container</th>
                            <th>Transaction</th>
                            <th>Amount (L)</th>
                            <th>Running Balance</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody id="historyTable">
                        {{-- AJAX DATA --}}
                    </tbody>
                </table>
            </div>
        </div>

    </div>


    {{-- ADD STOCK MODAL --}}
    <dialog id="addStockModal" class="modal">
        <div class="modal-box max-w-lg border border-gray-300">
            <h3 class="font-semibold text-lg mb-4">Add Fuel Stock</h3>

            <form id="addStockForm" class="space-y-4">

                @csrf

                <div>
                    <label class="text-sm">Container Type</label>
                    <select name="container_type" class="select w-full" required>
                        <option value="">Select container</option>
                        <option value="Fuel Can">Fuel Can</option>
                        <option value="Fuel Drum">Fuel Drum</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm">Amount (Liters)</label>
                    <input type="number" step="0.01" name="amount" placeholder="Enter amount in liters"
                        class="input input-bordered w-full border-gray-300">
                </div>

                <div>
                    <label class="text-sm">Note</label>
                    <textarea name="note" placeholder="Optional note about this transaction"
                        class="textarea textarea-bordered w-full border-gray-300"></textarea>
                </div>

                <input type="hidden" name="transaction_type" value="added">

                <div class="modal-action">
                    <button type="submit" class="btn btn-success px-6">Save</button>
                    <button type="button" class="btn" onclick="addStockModal.close()">Cancel</button>
                </div>
            </form>
        </div>
    </dialog>


    {{-- REMOVE STOCK MODAL --}}
    <dialog id="removeStockModal" class="modal">
        <div class="modal-box max-w-lg border border-gray-300">
            <h3 class="font-semibold text-lg mb-4">Remove Fuel Stock</h3>

            <form id="removeStockForm" class="space-y-4">

                @csrf

                <div>
                    <label class="text-sm">Container Type</label>
                    <select name="container_type" class="select w-full" required>
                        <option value="">Select container</option>
                        <option value="Fuel Can">Fuel Can</option>
                        <option value="Fuel Drum">Fuel Drum</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm">Amount (Liters)</label>
                    <input type="number" step="0.01" name="amount" placeholder="Enter amount in liters"
                        class="input input-bordered w-full border-gray-300">
                </div>

                <div>
                    <label class="text-sm">Note</label>
                    <textarea name="note" placeholder="Optional note about this transaction"
                        class="textarea textarea-bordered w-full border-gray-300"></textarea>
                </div>

                <input type="hidden" name="transaction_type" value="removed">

                <div class="modal-action">
                    <button type="submit" class="btn btn-error px-6">Save</button>
                    <button type="button" class="btn" onclick="removeStockModal.close()">Cancel</button>
                </div>
            </form>
        </div>
    </dialog>

@endsection


@push('scripts')
    <script src="{{ asset('assets/js/storage/storage.js') }}"></script>
@endpush