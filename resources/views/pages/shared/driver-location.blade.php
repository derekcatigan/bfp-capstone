{{-- resources\views\pages\shared\driver-location.blade.php --}}
@extends('layouts.layout')

@section('content')
    <div class="min-h-screen bg-linear-to-br from-base-200 to-base-300 flex items-center justify-center p-6">

        <div class="w-full max-w-lg space-y-6">

            {{-- HEADER --}}
            <div class="bg-base-100 rounded shadow p-6 text-center border border-gray-300">
                <h1 class="text-3xl font-extrabold mb-2">BFP Driver Tracking</h1>
                <p class="text-gray-500 text-sm">Live GPS transmission every 10 seconds</p>
            </div>

            {{-- STATUS CARD --}}
            <div class="bg-base-100 rounded shadow p-5 border border-gray-300">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-lg font-semibold">Status</span>
                    <span id="gpsStatus" class="badge badge-success gap-2 text-sm">
                        <span class="animate-pulse">●</span> LIVE
                    </span>
                </div>

                <div class="divider my-2"></div>

                <div class="grid grid-cols-2 gap-6 text-sm">
                    <div class="flex flex-col">
                        <span class="text-gray-400">Accuracy</span>
                        <span id="gpsAccuracy" class="font-bold text-lg">-- m</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-gray-400">Last Update</span>
                        <span id="lastUpdate" class="font-bold text-lg">Waiting...</span>
                    </div>
                </div>
            </div>

            {{-- COORDINATES CARD --}}
            <div class="bg-base-100 rounded shadow p-5 border border-gray-300 space-y-4">
                <div class="flex flex-col">
                    <span class="text-gray-400 text-sm">Latitude</span>
                    <span id="latDisplay" class="font-mono font-bold text-xl">--</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-gray-400 text-sm">Longitude</span>
                    <span id="lngDisplay" class="font-mono font-bold text-xl">--</span>
                </div>
            </div>

            {{-- CONTROL BUTTON --}}
            <div class="bg-base-100 rounded shadow p-5 border border-gray-300 text-center">
                <button id="stopBtn" class="btn btn-error btn-lg w-full font-semibold">
                    Stop Sharing Location
                </button>
            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        const tripId = "{{ $trip->id }}";
        let watchId = null;
        let isTracking = {{ $tracking->is_tracking ? 'true' : 'false' }};
        let sending = false;

        function updateLastSent(label = null) {
            document.getElementById('lastUpdate').innerText =
                label ?? new Date().toLocaleTimeString();
        }

        function setStatus(active) {
            const badge = document.getElementById('gpsStatus');
            if (active) {
                badge.className = "badge badge-success gap-2";
                badge.innerHTML = '<span class="animate-pulse">●</span> LIVE';
            } else {
                badge.className = "badge badge-error gap-2";
                badge.innerHTML = '● OFFLINE';
            }
        }

        function setButton() {
            const btn = document.getElementById('stopBtn');
            if (isTracking) {
                btn.className = "btn btn-error w-full";
                btn.innerText = "Stop Sharing Location";
            } else {
                btn.className = "btn btn-success w-full";
                btn.innerText = "Start Sharing Location";
            }
        }

        /* Start GPS tracking */
        function startTracking() {
            if (watchId || !isTracking) return;

            if (!navigator.geolocation) return alert("GPS not supported");

            watchId = navigator.geolocation.watchPosition(async position => {

                if (!isTracking || sending) return;
                sending = true;

                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                const acc = Math.round(position.coords.accuracy);

                document.getElementById('latDisplay').innerText = lat.toFixed(6);
                document.getElementById('lngDisplay').innerText = lng.toFixed(6);
                document.getElementById('gpsAccuracy').innerText = acc + " m";

                try {
                    await fetch(`/driver/${tripId}/update-location`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ latitude: lat, longitude: lng })
                    });

                    if (isTracking) updateLastSent();

                } catch (e) {
                    setStatus(false);
                }

                sending = false;

            }, err => {
                console.log(err);
                setStatus(false);
            }, { enableHighAccuracy: true, maximumAge: 0, timeout: 15000 });
        }

        /* Stop GPS tracking */
        function stopTracking() {
            if (watchId) {
                navigator.geolocation.clearWatch(watchId);
                watchId = null;
            }
        }

        /* Toggle sharing */
        document.getElementById('stopBtn').onclick = async () => {
            try {
                const res = await fetch(`/driver/${tripId}/toggle-tracking`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const data = await res.json();
                isTracking = data.is_tracking;

                setStatus(isTracking);
                setButton();

                if (isTracking) startTracking();
                else stopTracking();
                updateLastSent(isTracking ? null : 'Stopped');

            } catch (e) {
                console.error(e);
                alert("Failed to toggle tracking");
            }
        };

        /* Start automatically if enabled */
        if (isTracking) startTracking();
        else setStatus(false);
        setButton();
    </script>
@endpush