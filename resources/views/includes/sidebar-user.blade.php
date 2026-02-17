{{-- resources\views\includes\sidebar-user.blade.php --}}
@role(['driver', 'user'])
<li>
    {{-- Dashboard Link --}}
    <a href="{{ route('driver.dashboard') }}"
        class="{{ Request::routeIs('driver.dashboard') ? 'bg-gray-200 p-3 border-l-2 border-blue-500 rounded-l-sm' : 'p-3' }}">
        <svg class="h-[1em]" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
            viewBox="0 0 24 24">
            <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
            <path
                d="m21.55 8.17-9-6c-.34-.22-.77-.22-1.11 0l-8.99 6c-.28.19-.45.5-.45.83v11c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V9c0-.33-.17-.65-.45-.83M4 20V9.54l8-5.33 8 5.33V20z">
            </path>
            <path d="M6 16h12v2H6z"></path>
        </svg>
        Dashboard
    </a>
</li>

<div class="space-y-1">
    <p>Manage Tickets</p>
    <li>
        {{-- Tickets Links --}}
        <details>
            <summary
                class="{{ Request::routeIs(['ticket.index', 'ticket.request.index']) ? 'bg-gray-200 p-3 border-l-2 border-blue-500 rounded-l-sm mb-1' : 'p-3 mb-1' }}">
                <svg class="h-[1em]" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                    viewBox="0 0 24 24">
                    <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                    <path
                        d="M21 11h-3V5c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v13c0 1.65 1.35 3 3 3h14c1.65 0 3-1.35 3-3v-6c0-.55-.45-1-1-1M5 19c-.55 0-1-.45-1-1V5h12v13a3 3 0 0 0 .17 1zm15-1c0 .55-.45 1-1 1s-1-.45-1-1v-5h2z">
                    </path>
                    <path d="M6 7h8v2H6zm0 4h8v2H6zm5 4h3v2h-3z"></path>
                </svg>
                Trip Tickets
            </summary>
            <ul>
                <li>
                    <a href="{{ route('ticket.index') }}"
                        class="{{ Request::routeIs('ticket.index') ? 'bg-gray-200 p-3 border-l-2 border-blue-500 rounded-l-sm' : 'p-3' }}">
                        <svg class="h-[1em]" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="currentColor" viewBox="0 0 24 24">
                            <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                            <path
                                d="m19.94 7.68-.03-.09a.8.8 0 0 0-.2-.29l-5-5c-.09-.09-.19-.15-.29-.2l-.09-.03a.8.8 0 0 0-.26-.05c-.02 0-.04-.01-.06-.01H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-12s-.01-.04-.01-.06c0-.09-.02-.17-.05-.26ZM6 20V4h7v4c0 .55.45 1 1 1h4v11z">
                            </path>
                            <path d="M8 11h8v2H8zm0 4h8v2H8zm0-8h3v2H8z"></path>
                        </svg>
                        Ticket Logs
                    </a>
                </li>
                <li>
                    <a href="{{ route('ticket.request.index') }}"
                        class="{{ Request::routeIs('ticket.request.index') ? 'bg-gray-200 p-3 border-l-2 border-blue-500 rounded-l-sm' : 'p-3' }}">
                        <svg class="h-[1em]" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="currentColor" viewBox="0 0 24 24">
                            <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                            <path
                                d="M21 11h-3V5c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v13c0 1.65 1.35 3 3 3h14c1.65 0 3-1.35 3-3v-6c0-.55-.45-1-1-1M5 19c-.55 0-1-.45-1-1V5h12v13a3 3 0 0 0 .17 1zm15-1c0 .55-.45 1-1 1s-1-.45-1-1v-5h2z">
                            </path>
                            <path d="M6 7h8v2H6zm0 4h8v2H6zm5 4h3v2h-3z"></path>
                        </svg>
                        Request Ticket
                    </a>
                </li>
                <li>
                    <a href="#"
                        class="{{ Request::routeIs() ? 'bg-gray-200 p-3 border-l-2 border-blue-500 rounded-l-sm' : 'p-3' }}">
                        <svg class="h-[1em]" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="currentColor" viewBox="0 0 24 24">
                            <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                            <path
                                d="M19.1 7.8c-.38-.5-.97-.8-1.6-.8H15V6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2 0 1.65 1.35 3 3 3s3-1.35 3-3h4c0 1.65 1.35 3 3 3s3-1.35 3-3c1.1 0 2-.9 2-2v-3.67c0-.43-.14-.86-.4-1.2zM17.5 9l1.5 2h-4V9zM7 19a1.003 1.003 0 0 1-.87-1.5c.37-.63 1.36-.63 1.73 0 .09.15.13.32.13.49 0 .55-.45 1-1 1Zm2.23-3s-.05-.05-.08-.07c-.06-.06-.12-.11-.17-.16-.12-.11-.25-.21-.38-.29a3 3 0 0 0-.67-.32c-.07-.02-.14-.05-.21-.07Q7.375 15 7 15c-.375 0-.49.04-.72.09-.07.02-.14.05-.21.07-.16.05-.31.11-.45.19-.07.04-.15.08-.22.13-.13.09-.26.18-.38.29-.06.05-.12.1-.18.16-.02.03-.05.04-.08.07h-.77V6h9v10H9.22ZM17 19a1.003 1.003 0 0 1-.87-1.5c.37-.63 1.36-.63 1.73 0 .09.15.13.32.13.49 0 .55-.45 1-1 1Zm3-3h-.77s-.05-.05-.08-.07c-.06-.06-.12-.11-.17-.16-.12-.11-.25-.21-.38-.29a3 3 0 0 0-.67-.32c-.07-.02-.14-.05-.21-.07Q17.375 15 17 15c-.375 0-.47.04-.7.09-.06.01-.12.03-.18.05-.18.06-.36.13-.52.22l-.12.06c-.17.1-.33.21-.48.35v-2.76h5v3Z">
                            </path>
                        </svg>
                        Active Trips
                    </a>
                </li>
            </ul>
        </details>
    </li>
</div>

<div class="space-y-1">
    <p>Manage Tickets</p>
    <li>
        {{-- Tickets Links --}}
        <details>
            <summary
                class="{{ Request::routeIs() ? 'bg-gray-200 p-3 border-l-2 border-blue-500 rounded-l-sm mb-1' : 'p-3 mb-1' }}">
                <svg class="h-[1em]" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                    viewBox="0 0 24 24">
                    <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                    <path
                        d="M21 11h-3V5c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v13c0 1.65 1.35 3 3 3h14c1.65 0 3-1.35 3-3v-6c0-.55-.45-1-1-1M5 19c-.55 0-1-.45-1-1V5h12v13a3 3 0 0 0 .17 1zm15-1c0 .55-.45 1-1 1s-1-.45-1-1v-5h2z">
                    </path>
                    <path d="M6 7h8v2H6zm0 4h8v2H6zm5 4h3v2h-3z"></path>
                </svg>
                Manage Vehicles
            </summary>
            <ul>
                <li>
                    <a href="#"
                        class="{{ Request::routeIs() ? 'bg-gray-200 p-3 border-l-2 border-blue-500 rounded-l-sm' : 'p-3' }}">
                        <svg class="h-[1em]" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="currentColor" viewBox="0 0 24 24">
                            <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                            <path
                                d="m19.94 7.68-.03-.09a.8.8 0 0 0-.2-.29l-5-5c-.09-.09-.19-.15-.29-.2l-.09-.03a.8.8 0 0 0-.26-.05c-.02 0-.04-.01-.06-.01H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-12s-.01-.04-.01-.06c0-.09-.02-.17-.05-.26ZM6 20V4h7v4c0 .55.45 1 1 1h4v11z">
                            </path>
                            <path d="M8 11h8v2H8zm0 4h8v2H8zm0-8h3v2H8z"></path>
                        </svg>
                        Manage Vehicles
                    </a>
                </li>
                <li>
                    <a href="{{ route('vehicle.create') }}"
                        class="{{ Request::routeIs('vehicle.create') ? 'bg-gray-200 p-3 border-l-2 border-blue-500 rounded-l-sm' : 'p-3' }}">
                        <svg class="h-[1em]" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="currentColor" viewBox="0 0 24 24">
                            <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                            <path d="M3 13h8v8h2v-8h8v-2h-8V3h-2v8H3z"></path>
                        </svg>
                        Create Vehicle
                    </a>
                </li>
                <li>
                    <a href="{{ route('request.vehicle.index') }}"
                        class="{{ Request::routeIs('request.vehicle.index') ? 'bg-gray-200 p-3 border-l-2 border-blue-500 rounded-l-sm' : 'p-3' }}">
                        <svg class="h-[1em]" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="currentColor" viewBox="0 0 24 24">
                            <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                            <path d="M3 13h8v8h2v-8h8v-2h-8V3h-2v8H3z"></path>
                        </svg>
                        Request Vehicle
                    </a>
                </li>
                @php
                    $activeTrip = \App\Models\TripTicket::where('driver_id', auth()->id())
                        ->where('status', 'active')
                        ->latest()
                        ->first();
                @endphp
                <li>
                    @if($activeTrip)
                        <a href="{{ route('driver.location.index', ['trip' => $activeTrip->id]) }}"
                            class="{{ Request::routeIs('driver.location.index') ? 'bg-gray-200 p-3 border-l-2 border-blue-500 rounded-l-sm' : 'p-3' }}">
                            <svg class="h-[1em]" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                fill="currentColor" viewBox="0 0 24 24">
                                <path d="M3 13h8v8h2v-8h8v-2h-8V3h-2v8H3z"></path>
                            </svg>
                            Driver Location
                        </a>
                    @else
                        <span class="p-3 opacity-50 cursor-not-allowed flex items-center gap-2">
                            <svg class="h-[1em]" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                fill="currentColor" viewBox="0 0 24 24">
                                <path d="M3 13h8v8h2v-8h8v-2h-8V3h-2v8H3z"></path>
                            </svg>
                            Driver Location (No active trip)
                        </span>
                    @endif
                </li>
            </ul>
        </details>
    </li>
</div>
@endrole