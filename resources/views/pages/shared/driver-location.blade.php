{{-- resources\views\pages\shared\driver-location.blade.php --}}
@extends('layouts.layout')

@section('content')
    <div class="p-4">
        <h2 class="text-lg font-bold">Driver Live GPS Tracking</h2>
        <p class="text-sm opacity-70">Your location will be sent every 10 seconds</p>
    </div>
@endsection

@push('scripts')
    <script>
        const tripId = "{{ $trip->id }}"; // Pass trip ID from controller
        const POLL_INTERVAL = 10000; // 10 seconds

        if (navigator.geolocation) {
            navigator.geolocation.watchPosition(position => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                fetch(`/driver/${tripId}/update-location`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ latitude: lat, longitude: lng })
                }).then(res => res.json())
                    .then(data => console.log('Location sent:', data))
                    .catch(err => console.error(err));

            }, err => {
                console.error('Error getting GPS:', err);
            }, {
                enableHighAccuracy: true,
                maximumAge: 0
            });
        } else {
            alert("Geolocation is not supported by this browser");
        }
    </script>
@endpush