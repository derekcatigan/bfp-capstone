let map;
let baseMarker;
let driverMarker;
let routeLine;
let pollTimer;
let routeCoords = []; // Store route coordinates globally

const BASE_COORDS = [10.132646794843092, 124.83489696799799];
const POLL_INTERVAL = 10000;

$(window).on("load", function () {
    setTimeout(() => {
        initActiveTripsMap();
        map.invalidateSize();
    }, 300);
});

/* ---------------- MAP INIT ---------------- */
function initActiveTripsMap() {
    map = L.map("activeTripsMap").setView(BASE_COORDS, 15);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "&copy; OpenStreetMap contributors",
    }).addTo(map);

    baseMarker = L.marker(BASE_COORDS)
        .addTo(map)
        .bindPopup("<b>BFP Maasin City Station</b>")
        .openPopup();
}

/* ---------------- CLICK TRIP ---------------- */
$(document).on("click", ".trip-card", function () {
    const destLat = parseFloat($(this).data("destination-lat"));
    const destLon = parseFloat($(this).data("destination-lng"));
    const controlNo = $(this).data("control");
    const destinationName = $(this).data("destination-name");
    const tripId = $(this).data("trip-id");

    if (!destLat || !destLon) return alert("Destination coordinates not found");

    drawRoute([destLat, destLon], controlNo, destinationName, tripId);
});

/* ---------------- DRAW ROUTE ---------------- */
function drawRoute(destCoords, controlNo, destinationName, tripId) {
    const [destLat, destLon] = destCoords;

    const osrmUrl = `https://router.project-osrm.org/route/v1/driving/${BASE_COORDS[1]},${BASE_COORDS[0]};${destLon},${destLat}?overview=full&geometries=geojson`;

    fetch(osrmUrl)
        .then((res) => res.json())
        .then((data) => {
            if (!data.routes || !data.routes.length) {
                alert("No route found");
                return;
            }

            // Save route coordinates globally
            routeCoords = data.routes[0].geometry.coordinates.map((c) => [
                c[1],
                c[0],
            ]);

            $("#routeInfo").show();
            $("#infoEta").text(formatDuration(data.routes[0].duration));
            $("#infoDistance").text(formatDistance(data.routes[0].distance));

            if (routeLine) map.removeLayer(routeLine);

            // Draw route polyline
            routeLine = L.polyline(routeCoords, {
                color: "red",
                weight: 5,
            }).addTo(map);

            // Destination marker
            L.marker([destLat, destLon])
                .addTo(map)
                .bindPopup(`<b>${controlNo}</b><br>${destinationName}`)
                .openPopup();

            map.fitBounds(routeLine.getBounds(), { padding: [50, 50] });

            // Driver marker at base
            if (driverMarker) map.removeLayer(driverMarker);
            driverMarker = L.marker(BASE_COORDS, { icon: driverIcon }).addTo(
                map,
            );

            // Start polling for driver location
            startPolling(tripId, driverMarker);
        });
}

/* ---------------- POLLING ---------------- */
function startPolling(tripId, marker) {
    if (pollTimer) clearInterval(pollTimer);

    pollTimer = setInterval(() => {
        $.get(`/admin/active-trip/${tripId}/location`, function (data) {
            if (!data || !data.latitude || !data.longitude) return;

            const currentLatLng = marker.getLatLng();
            const newLatLng = [data.latitude, data.longitude];

            // Find closest point on route to current marker
            let nearestIndex = 0;
            let minDist = Infinity;
            routeCoords.forEach((c, i) => {
                const dist = map.distance(currentLatLng, L.latLng(c));
                if (dist < minDist) {
                    minDist = dist;
                    nearestIndex = i;
                }
            });

            // Animate marker along remaining route to new GPS location
            const remainingRoute = routeCoords.slice(nearestIndex);
            animateMarkerSmooth(marker, remainingRoute, POLL_INTERVAL);
        });
    }, POLL_INTERVAL);
}

/* ---------------- ANIMATE MARKER ALONG ROUTE ---------------- */
function animateMarkerSmooth(marker, route, duration) {
    if (!marker || !route.length) return;

    let stepIndex = 0;
    const interval = 50;
    const totalSteps = Math.max(Math.floor(duration / interval), 1);

    let latLngs = [];
    // Flatten the route into very small steps
    for (let i = 0; i < route.length - 1; i++) {
        const start = route[i];
        const end = route[i + 1];

        const dx = end[0] - start[0];
        const dy = end[1] - start[1];

        for (let s = 0; s < totalSteps / route.length; s++) {
            const lat = start[0] + (dx * s) / (totalSteps / route.length);
            const lng = start[1] + (dy * s) / (totalSteps / route.length);
            latLngs.push([lat, lng]);
        }
    }

    let currentStep = 0;
    const animate = setInterval(() => {
        if (currentStep >= latLngs.length) {
            clearInterval(animate);
            return;
        }
        marker.setLatLng(latLngs[currentStep]);
        currentStep++;
    }, interval);
}

/* ---------------- HELPERS ---------------- */
function formatDuration(seconds) {
    const minutes = Math.round(seconds / 60);
    if (minutes < 60) return `${minutes} min`;
    const hours = Math.floor(minutes / 60);
    return `${hours}h ${minutes % 60}m`;
}

function formatDistance(meters) {
    return `${(meters / 1000).toFixed(2)} km`;
}

/* ---------------- DRIVER ICON ---------------- */
const driverIcon = L.icon({
    iconUrl: "/assets/icons/truck.png",
    iconSize: [32, 32],
    iconAnchor: [16, 16],
});
