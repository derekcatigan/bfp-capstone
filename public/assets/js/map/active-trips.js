let map;
let driverMarker;
let destinationMarker;
let routeLine;
let pollTimer;
let currentAnimation = null;

const BASE_COORDS = [10.132646794843092, 124.83489696799799];
const POLL_INTERVAL = 10000; // 10 seconds
const ANIMATION_INTERVAL = 50; // ms per animation frame

$(window).on("load", function () {
    setTimeout(() => {
        initMap();
        map.invalidateSize();
    }, 300);
});

/* ---------------- MAP INIT ---------------- */
function initMap() {
    map = L.map("activeTripsMap").setView(BASE_COORDS, 15);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "&copy; OpenStreetMap contributors",
    }).addTo(map);

    // Station marker
    L.marker(BASE_COORDS)
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

    if (!destLat || !destLon)
        return toastr.error("Destination coordinates not found");

    // Fetch latest driver location
    $.get(`/admin/active-trip/${tripId}/location`, function (data) {
        const startLat = parseFloat(data.latitude) || BASE_COORDS[0];
        const startLon = parseFloat(data.longitude) || BASE_COORDS[1];

        drawRoute(
            [startLat, startLon],
            [destLat, destLon],
            controlNo,
            destinationName,
            tripId,
        );
    });
});

/* ---------------- DRAW ROUTE ---------------- */
function drawRoute(
    startCoords,
    destCoords,
    controlNo,
    destinationName,
    tripId,
) {
    const [startLat, startLon] = startCoords;
    const [destLat, destLon] = destCoords;

    // Remove previous markers & route
    if (driverMarker) map.removeLayer(driverMarker);
    if (destinationMarker) map.removeLayer(destinationMarker);
    if (routeLine) map.removeLayer(routeLine);

    // Driver marker
    driverMarker = L.marker([startLat, startLon], { icon: driverIcon }).addTo(
        map,
    );

    // Destination marker
    destinationMarker = L.marker([destLat, destLon])
        .addTo(map)
        .bindPopup(`<b>${controlNo}</b><br>${destinationName}`)
        .openPopup();

    // Fetch and draw route
    const osrmUrl = `https://router.project-osrm.org/route/v1/driving/${startLon},${startLat};${destLon},${destLat}?overview=full&geometries=geojson`;

    fetch(osrmUrl)
        .then((res) => res.json())
        .then((data) => {
            if (!data.routes || !data.routes.length)
                return toastr.error("No route found");

            const routeCoords = data.routes[0].geometry.coordinates.map((c) => [
                c[1],
                c[0],
            ]);

            const durationMs = data.routes[0].duration * 1000;

            // Draw route line
            routeLine = L.polyline(routeCoords, {
                color: "red",
                weight: 5,
            }).addTo(map);
            map.fitBounds(routeLine.getBounds(), { padding: [50, 50] });

            // Show route info
            $("#routeInfo").show();
            $("#infoEta").text(formatDuration(data.routes[0].duration));
            $("#infoDistance").text(formatDistance(data.routes[0].distance));

            // Animate marker along route
            animateMarkerSmooth(driverMarker, routeCoords, durationMs);

            // Start polling
            startPolling(tripId, [destLat, destLon]);
        })
        .catch((err) => console.error("OSRM fetch error:", err));
}

/* ---------------- POLLING LIVE GPS (optimized) ---------------- */
function startPolling(tripId, destCoords) {
    if (pollTimer) clearInterval(pollTimer);

    pollTimer = setInterval(() => {
        $.get(`/admin/active-trip/${tripId}/location`, function (data) {
            if (!data || !data.latitude || !data.longitude) return;

            const newLatLng = L.latLng(data.latitude, data.longitude);

            // Move marker smoothly
            moveMarkerSmooth(driverMarker, newLatLng);

            // Only redraw route if driver deviates >50m
            if (routeLine) {
                const lastRoutePoint =
                    routeLine.getLatLngs()[routeLine.getLatLngs().length - 1];
                const distanceToEnd = map.distance(newLatLng, lastRoutePoint);

                if (distanceToEnd > 50) {
                    // Redraw route from current driver location
                    drawRoute(
                        [data.latitude, data.longitude], // start
                        destCoords, // destination
                        destinationMarker
                            .getPopup()
                            .getContent()
                            .split("<br>")[0]
                            .replace("<b>", "")
                            .replace("</b>", ""), // controlNo
                        destinationMarker
                            .getPopup()
                            .getContent()
                            .split("<br>")[1], // destinationName
                        tripId,
                    );
                }
            }
        });
    }, POLL_INTERVAL);
}

/* ---------------- SMOOTH ANIMATION ALONG ROUTE ---------------- */
function animateMarkerSmooth(marker, route, durationMs) {
    if (!marker || !route.length) return;
    if (currentAnimation) cancelAnimationFrame(currentAnimation);

    const totalSteps = Math.max(Math.floor(durationMs / ANIMATION_INTERVAL), 1);
    let latLngs = [];

    for (let i = 0; i < route.length - 1; i++) {
        const start = route[i];
        const end = route[i + 1];
        const stepCount = Math.floor(totalSteps / (route.length - 1));

        for (let s = 0; s < stepCount; s++) {
            const lat = start[0] + ((end[0] - start[0]) * s) / stepCount;
            const lng = start[1] + ((end[1] - start[1]) * s) / stepCount;
            latLngs.push([lat, lng]);
        }
    }

    let currentStep = 0;
    function animate() {
        if (currentStep >= latLngs.length) return;
        marker.setLatLng(latLngs[currentStep]);
        currentStep++;
        currentAnimation = requestAnimationFrame(animate);
    }
    animate();
}

/* ---------------- SMOOTH MOVE TO NEW GPS ---------------- */
function moveMarkerSmooth(marker, newLatLng, duration = 1000) {
    if (!marker) return;

    const startLatLng = marker.getLatLng();
    const startTime = performance.now();

    function animate(time) {
        const elapsed = time - startTime;
        const t = Math.min(elapsed / duration, 1);
        const lat = startLatLng.lat + (newLatLng.lat - startLatLng.lat) * t;
        const lng = startLatLng.lng + (newLatLng.lng - startLatLng.lng) * t;
        marker.setLatLng([lat, lng]);
        if (t < 1) requestAnimationFrame(animate);
    }

    requestAnimationFrame(animate);
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
