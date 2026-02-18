{{-- resources\views\pages\shared\notifications.blade.php --}}
@extends('layouts.layout')

@section('content')

    <div class="max-w-5xl mx-auto py-8 px-4">
        <h1 class="text-2xl font-bold mb-6">Notifications</h1>

        <div class="space-y-4">

            @forelse($notifications as $note)

                <div
                    class="bg-white border border-gray-200 rounded-xl shadow-sm p-5 
                                                                                        {{ $note->status ? 'border-l-4 border-blue-500 bg-blue-50' : '' }}">

                    <div class="flex justify-between items-start gap-4">

                        <div class="space-y-1 w-full">
                            <h2 class="font-semibold text-gray-800">
                                {{ $note->title }}
                            </h2>

                            <p class="text-sm text-gray-600">
                                {{ $note->message }}
                            </p>

                            <span class="text-xs text-gray-400">
                                {{ $note->created_at->diffForHumans() }}
                            </span>
                        </div>

                        {{-- ACTIONS --}}
                        <div class="flex flex-col gap-2 min-w-35">

                            {{-- ADMIN ACTIONS --}}
                            @if(
                                    auth()->user()->role === \App\Enum\RoleEnum::AdminRole
                                    && $note->type === 'trip_ticket_request'
                                )

                                <form method="POST" action="{{ route('ticket.request.approve', $note->id) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-success w-full">
                                        Approve
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('ticket.request.reject', $note->id) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-error w-full">
                                        Reject
                                    </button>
                                </form>

                            @else

                                {{-- USER ACTION --}}
                                @if($note->status)
                                    <a href="{{ route('notifications.read', $note->id) }}"
                                        class="btn btn-sm btn-outline btn-primary w-full">
                                        Mark as read
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400 text-center">
                                        Already read
                                    </span>
                                @endif

                            @endif

                        </div>
                    </div>

                </div>

            @empty
                <div class="text-center text-gray-400 py-12">
                    No notifications found
                </div>
            @endforelse

        </div>

        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    </div>
@endsection