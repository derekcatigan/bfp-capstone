{{-- resources\views\pages\admin\manage-tickets.blade.php --}}
@php 
    $role = auth()->user()->role; 
@endphp
@extends('layouts.layout')

@section('content')
    <div class="p-3">
        <h1 class="text-2xl font-bold">MANAGE TICKETS</h1>
    </div>

    {{-- ================= DASHBOARD COUNTS ================= --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 p-3">

        {{-- TOTAL --}}
        <a href="?status=" class="rounded shadow border border-gray-300">
            <div class="flex flex-col p-3">
                <div class="flex justify-end">
                    <div class="bg-white border border-gray-300 p-1 rounded shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            viewBox="0 0 24 24">
                            <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                            <path
                                d="M21 11h-3V5c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v13c0 1.65 1.35 3 3 3h14c1.65 0 3-1.35 3-3v-6c0-.55-.45-1-1-1M5 19c-.55 0-1-.45-1-1V5h12v13a3 3 0 0 0 .17 1zm15-1c0 .55-.45 1-1 1s-1-.45-1-1v-5h2z">
                            </path>
                            <path d="M6 7h8v2H6zm0 4h8v2H6zm5 4h3v2h-3z"></path>
                        </svg>
                    </div>
                </div>

                <div class="text-center">
                    <p class="text-sm text-gray-500">TOTAL</p>
                    <p class="text-3xl font-bold">{{ $counts['total'] }}</p>
                </div>
            </div>
        </a>

        {{-- PENDING --}}
        <a href="?status=Pending" class="rounded shadow border border-gray-300">
            <div class="flex flex-col p-3">
                <div class="flex justify-end">
                    <div class="bg-yellow-200 border border-gray-300 p-1 rounded shadow">
                        <svg class="text-yellow-700" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="currentColor" viewBox="0 0 24 24">
                            <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                            <path
                                d="M20 4h-8.59L10 2.59C9.62 2.21 9.12 2 8.59 2H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2m0 14H4V6h16z">
                            </path>
                        </svg>
                    </div>
                </div>

                <div class="text-center">
                    <p class="text-sm text-yellow-600">PENDING</p>
                    <p class="text-3xl font-bold text-yellow-700">{{ $counts['pending'] }}</p>
                </div>
            </div>
        </a>

        {{-- ACTIVE --}}
        <a href="?status=Active" class="rounded shadow border border-gray-300">
            <div class="flex flex-col p-3">
                <div class="flex justify-end">
                    <div class="bg-blue-200 border border-gray-300 p-1 rounded shadow">
                        <svg class="text-blue-700" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="currentColor" viewBox="0 0 24 24">
                            <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                            <path d="M6.5 12a1.5 1.5 0 1 0 0 3 1.5 1.5 0 1 0 0-3m11 0a1.5 1.5 0 1 0 0 3 1.5 1.5 0 1 0 0-3">
                            </path>
                            <path
                                d="m20.77 9.16-1.37-4.1a2.99 2.99 0 0 0-2.85-2.05H7.44a3 3 0 0 0-2.85 2.05l-1.37 4.1c-.72.3-1.23 1.02-1.23 1.84v5c0 .74.41 1.38 1 1.72V20c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-2h12v2c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-2.28a2 2 0 0 0 1-1.72v-5c0-.83-.51-1.54-1.23-1.84ZM7.44 5h9.12a1 1 0 0 1 .95.68L18.62 9H5.39L6.5 5.68A1 1 0 0 1 7.45 5ZM4 16v-5h16v5z">
                            </path>
                        </svg>
                    </div>
                </div>

                <div class="text-center">
                    <p class="text-sm text-blue-600">ACTIVE</p>
                    <p class="text-3xl font-bold text-blue-700">{{ $counts['active'] }}</p>
                </div>
            </div>
        </a>

        {{-- SUBMITTED --}}
        <a href="?status=Submitted" class="rounded shadow border border-gray-300">
            <div class="flex flex-col p-3">
                <div class="flex justify-end">
                    <div class="bg-green-200 border border-gray-300 p-1 rounded shadow">
                        <svg class="text-green-700" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="currentColor" viewBox="0 0 24 24">
                            <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                            <path
                                d="M20 3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2M4 19V5h16v14z">
                            </path>
                            <path
                                d="M13 8h5v2h-5zm-5 .59L6.96 7.54 5.54 8.96 8 11.41l3.46-3.45-1.42-1.42zM13 14h5v2h-5zm-5 .59-1.04-1.05-1.42 1.42L8 17.41l3.46-3.45-1.42-1.42z">
                            </path>
                        </svg>
                    </div>
                </div>

                <div class="text-center">
                    <p class="text-sm text-green-600">SUBMITTED</p>
                    <p class="text-3xl font-bold text-green-700">{{ $counts['submitted'] }}</p>
                </div>
            </div>
        </a>

    </div>

    {{-- ================= FILTER BAR ================= --}}
    <form method="GET" class="card bg-base-100 shadow p-4">
        <div class="flex flex-col md:flex-row gap-2">

            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Control Number..."
                class="input input-bordered w-full">

            {{-- MONTH --}}
            <select name="month" class="select select-bordered w-full">
                <option value="">All Months</option>
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                    </option>
                @endforeach
            </select>

            {{-- YEAR --}}
            <select name="year" class="select select-bordered w-full">
                <option value="">All Years</option>

                @foreach(array_reverse(range(now()->year - 5, now()->year)) as $y)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endforeach
            </select>

            <button class="btn btn-primary">Filter</button>

            <a href="{{ route('ticket.index') }}" class="btn btn-warning">
                Reset
            </a>
        </div>
    </form>

    {{-- ================= TICKET LIST ================= --}}
    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-4 p-3">
        @forelse ($tickets as $ticket)
            <div class="rounded shadow border border-gray-300">
                <div class="card-body">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="font-bold text-lg"> {{ $ticket->control_no }} </h2>
                            <p class="text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($ticket->ticket_date)->format('M d, Y') }}
                            </p>
                        </div>
                        <span>{{ Str::title($ticket->status) }} </span>
                    </div>
                    <div class="divider my-1"></div>
                    <p>
                        <strong>Driver:</strong>
                        {{ $ticket->driver?->profile?->first_name }}
                        {{ $ticket->driver?->profile?->last_name }}
                    </p>
                    <p><strong>Destination:</strong> {{ $ticket->place }}</p>
                    <p><strong>Purpose:</strong> {{ $ticket->purpose }}</p>
                    <div class="card-actions justify-end mt-2">
                        {{-- DRIVER SUBMIT --}}
                        @if($role === \App\Enum\RoleEnum::DriverRole && $ticket->status === 'active')
                            <button class="btn btn-sm btn-success btn-submit-ticket" data-id="{{ $ticket->id }}"
                                data-url="{{ route('ticket.submit', $ticket->id) }}">
                                Submit
                            </button>
                        @endif

                        {{-- ADMIN ACTIVATE --}}
                        @if($role === \App\Enum\RoleEnum::AdminRole && $ticket->status === 'pending')
                            <button class="btn btn-sm btn-success btn-activate-ticket" data-id="{{ $ticket->id }}"
                                data-url="{{ route('ticket.activate', $ticket->id) }}">
                                Activate
                            </button>
                        @endif

                        <a href="{{ route('ticket.edit', $ticket->id) }}" class="btn btn-sm btn-warning">
                            Edit
                        </a>
                        <button class="btn btn-sm btn-primary btn-view-ticket" data-id="{{ $ticket->id }}">
                            View Details
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full flex items-center justify-center py-20">
                <div class="text-center space-y-2">
                    <div class="flex justify-center opacity-30">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                            <path
                                d="m19.94 7.68-.03-.09a.8.8 0 0 0-.2-.29l-5-5c-.09-.09-.19-.15-.29-.2l-.09-.03a.8.8 0 0 0-.26-.05c-.02 0-.04-.01-.06-.01H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-12s-.01-.04-.01-.06c0-.09-.02-.17-.05-.26ZM6 20V4h7v4c0 .55.45 1 1 1h4v11z">
                            </path>
                        </svg>
                    </div>
                    <p class="text-lg font-semibold text-gray-500">No tickets found</p>
                    <p class="text-sm text-gray-400">Try adjusting the filters or create a new trip ticket</p>
                </div>
            </div>
        @endforelse
    </div>

    {{-- ================= PAGINATION ================= --}}
    <div class="mt-4">
        {{ $tickets->links() }}
    </div>

    {{-- VIEW MODAL --}}
    <dialog id="ticketPreviewModal" class="modal">
        <div class="modal-box w-11/12 max-w-5xl">
            {{-- Your paper preview --}}
            @include('print.document-ticket')

            <div class="modal-action">
                <!-- Print Button -->
                <button type="button" class="btn btn-primary" id="btnPrintTicket">
                    Print
                </button>

                <form method="dialog">
                    <!-- if there is a button, it will close the modal -->
                    <button class="btn">Close</button>
                </form>
            </div>
        </div>
    </dialog>

    {{-- CONFIRMATION MODAL --}}
    <dialog id="confirmModal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg" id="confirmTitle">Confirm Action</h3>
            <p class="py-4" id="confirmMessage"></p>

            <div class="modal-action">
                <button class="btn" onclick="confirmModal.close()">Cancel</button>
                <button class="btn btn-primary" id="confirmYes">Yes</button>
            </div>
        </div>
    </dialog>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/ticket/manageTicket.js') }}"></script>
    <script src="{{ asset('assets/js/ticket/status-actions.js') }}"></script>
@endpush