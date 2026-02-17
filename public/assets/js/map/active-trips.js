// public\assets\js\map\active-trips.js
let map;
let baseMarker;
let routeLine;

const BASE_LOCATION =
    "BFP Maasin City, Captain Iyano Street, Combado, Maasin City, Southern Leyte, Philippines";

const BASE_COORDS = [10.132646794843092, 124.83489696799799];

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

    if (!destLat || !destLon)
        return toastr.error("Destination coordinates not found");

    drawRoute([destLat, destLon], controlNo, destinationName);
});
/* ---------------- GEOCODE ---------------- */

function geocodeAddress(address) {
    return $.get(
        `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(
            address,
        )}`,
    );
}

// HELPER FUNCTION
function formatDuration(seconds) {
    const minutes = Math.round(seconds / 60);

    if (minutes < 60) return `${minutes} min`;

    const hours = Math.floor(minutes / 60);
    const remaining = minutes % 60;
    return `${hours}h ${remaining}m`;
}

function formatDistance(meters) {
    const km = meters / 1000;
    return `${km.toFixed(2)} km`;
}

/* ---------------- DRAW ROUTE ---------------- */

function drawRoute(destCoords, controlNo, destinationName) {
    const [destLat, destLon] = destCoords;

    // OSRM requires lng,lat order
    const osrmUrl = `https://router.project-osrm.org/route/v1/driving/${BASE_COORDS[1]},${BASE_COORDS[0]};${destLon},${destLat}?overview=full&geometries=geojson`;

    fetch(osrmUrl)
        .then((response) => response.json())
        .then((routeData) => {
            if (!routeData.routes || !routeData.routes.length) {
                toastr.error("No route found");
                return;
            }

            const routeCoords = routeData.routes[0].geometry.coordinates;
            const duration = routeData.routes[0].duration;
            const distance = routeData.routes[0].distance;

            // Convert [lng, lat] → [lat, lng] for Leaflet
            const latLngs = routeCoords.map((coord) => [coord[1], coord[0]]);

            // Display route info
            $("#routeInfo").show();
            $("#infoDestination").text(destinationName);
            $("#infoEta").text(formatDuration(duration));
            $("#infoDistance").text(formatDistance(distance));

            // Remove previous route
            if (routeLine) map.removeLayer(routeLine);

            // Draw polyline
            routeLine = L.polyline(latLngs, { color: "red", weight: 5 }).addTo(
                map,
            );

            // Add destination marker
            L.marker([destLat, destLon])
                .addTo(map)
                .bindPopup(`<b>${controlNo}</b><br>${destinationName}`)
                .openPopup();

            // Fit map to route
            map.fitBounds(routeLine.getBounds(), { padding: [50, 50] });
        })
        .catch((err) => {
            console.error(err);
            toastr.error("Map routing failed");
        });
}
