{{-- resources\views\pages\shared\vehicle-repair.blade.php --}}
@extends('layouts.layout')

@section('content')

    @php
        $pendingCount = $requests->where('type', 'vehicle_repair_request')->count();
    @endphp

    <div class="max-w-6xl mx-auto space-y-10 p-3">

        {{-- HERO HEADER --}}
        <div class="bg-linear-to-r from-warning/10 to-base-200 rounded p-8 shadow-sm border border-base-300">

            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-6">

                <div>
                    <h1 class="text-4xl font-bold tracking-tight">
                        VEHICLE REPAIR REQUEST
                    </h1>
                    <p class="opacity-70 mt-2">
                        Review, approve, or reject submitted maintenance issues.
                    </p>
                </div>

                <div class="bg-base-100 border border-gray-300 shadow-md px-6 py-4 rounded text-center min-w-40">
                    <p class="text-sm opacity-60">Pending Requests</p>
                    <p class="text-3xl font-bold text-warning">
                        {{ $pendingCount }}
                    </p>
                </div>

            </div>
        </div>

        {{-- REQUEST LIST --}}
        <div class="space-y-6">

            @forelse($requests as $note)

                @php
                    $state = match ($note->type) {
                        'vehicle_repair_request' => ['Pending', 'badge-warning'],
                        'vehicle_repair_approved' => ['Approved', 'badge-success'],
                        'vehicle_repair_rejected' => ['Rejected', 'badge-error'],
                        default => ['Unknown', 'badge-ghost']
                    };
                @endphp

                <div class="bg-base-100 border border-gray-300 rounded shadow-sm hover:shadow-md transition duration-300">

                    <div class="p-5">
                        {{-- TOP ROW --}}
                        <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4">

                            <div>
                                <h2 class="text-xl font-semibold">
                                    {{ $note->requester?->profile?->first_name ?? 'Unknown' }}
                                    {{ $note->requester?->profile?->last_name }}
                                </h2>

                                <p class="text-xs opacity-60">
                                    Submitted {{ $note->created_at->diffForHumans() }}
                                </p>
                            </div>

                            <div class="badge {{ $state[1] }} badge-outline px-4 py-3 text-sm">
                                {{ $state[0] }}
                            </div>

                        </div>

                        {{-- ISSUE --}}
                        <div class="mt-6 bg-base-200/60 border border-base-300 rounded-xl p-5">
                            <p class="text-sm opacity-80 leading-relaxed">
                                {{ $note->message }}
                            </p>
                        </div>

                        {{-- ACTION --}}
                        <div class="mt-8 flex justify-end">

                            @if($note->type === 'vehicle_repair_request')

                                <div class="flex gap-4">

                                    <form method="POST" action="{{ route('vehicle.request.approve', $note->id) }}">
                                        @csrf
                                        <button class="btn btn-success px-8">
                                            Approve
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('vehicle.request.reject', $note->id) }}">
                                        @csrf
                                        <button class="btn btn-outline btn-error px-8">
                                            Reject
                                        </button>
                                    </form>

                                </div>

                            @else

                                <span class="text-sm opacity-60">
                                    This request has been
                                    <strong>{{ str_replace('vehicle_repair_', '', $note->type) }}</strong>.
                                </span>

                            @endif

                        </div>

                    </div>
                </div>

            @empty

                <div class="bg-base-100 border border-dashed border-base-300 rounded-2xl p-16 text-center opacity-60">
                    <h3 class="text-xl font-semibold">No Repair Requests</h3>
                    <p class="text-sm mt-2">Everything is up to date.</p>
                </div>

            @endforelse

        </div>

        {{-- PAGINATION --}}
        <div class="flex justify-center pt-4">
            {{ $requests->links() }}
        </div>

    </div>

@endsection