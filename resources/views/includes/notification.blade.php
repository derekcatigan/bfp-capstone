{{-- resources\views\includes\notification.blade.php --}}

{{-- Notification --}}
<div class="dropdown hidden lg:block lg:dropdown-end">
    <div tabindex="0" role="button">
        <div class="indicator">
            {{-- Notification Indicator --}}
            <span class="indicator-item badge badge-sm badge-primary">
                {{ $unreadCount }}
            </span>

            {{-- Notification Icon --}}
            <svg class="h-[1.5em]" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                viewBox="0 0 24 24">
                <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                <path
                    d="M19 12.59V10c0-3.22-2.18-5.93-5.14-6.74C13.57 2.52 12.85 2 12 2s-1.56.52-1.86 1.26C7.18 4.08 5 6.79 5 10v2.59L3.29 14.3a1 1 0 0 0-.29.71v2c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-2c0-.27-.11-.52-.29-.71zM19 16H5v-.59l1.71-1.71a1 1 0 0 0 .29-.71v-3c0-2.76 2.24-5 5-5s5 2.24 5 5v3c0 .27.11.52.29.71L19 15.41zm-4.18 4H9.18c.41 1.17 1.51 2 2.82 2s2.41-.83 2.82-2">
                </path>
            </svg>
        </div>
    </div>
    <ul tabindex="-1"
        class="dropdown-content menu bg-white border border-gray-300 text-black rounded z-1 w-80 p-2 shadow-sm">

        <div class="max-h-80 overflow-y-auto space-y-1">

            @forelse($notifications as $note)
                <li>
                    <div class="flex flex-col p-2 rounded {{ $note->status ? 'bg-blue-50 font-semibold' : '' }}">

                        <span class="text-sm text-center">{{ $note->title }}</span>
                        <span class="text-xs text-center text-gray-500">
                            {{ $note->message }}
                        </span>
                        <span class="text-[10px] text-gray-400 mb-2">
                            {{ $note->created_at->diffForHumans() }}
                        </span>

                        {{-- APPROVE BUTTON (Admin Only + Trip Ticket Type Only) --}}
                        @if(
                                auth()->user()->role === \App\Enum\RoleEnum::AdminRole
                                && $note->type === 'trip_ticket_request'
                            )

                            <div class="flex gap-1 mt-1">

                                {{-- Approve --}}
                                <form method="POST" action="{{ route('ticket.request.approve', $note->id) }}" class="w-1/2">
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-success w-full">
                                        Approve
                                    </button>
                                </form>

                                {{-- Reject --}}
                                <form method="POST" action="{{ route('ticket.request.reject', $note->id) }}" class="w-1/2">
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-error w-full">
                                        Reject
                                    </button>
                                </form>

                            </div>
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
                </li>

            @empty
                <li class="text-center text-gray-400 text-sm">No notifications</li>
            @endforelse

        </div>
    </ul>
</div>