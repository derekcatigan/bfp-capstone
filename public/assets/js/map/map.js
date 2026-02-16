// public\assets\js\map\map.js
let map;
let marker;
let selectedAddress = "";
let selectedLatLng = null;
const mapModal = document.getElementById("mapModal");

/* OPEN MODAL INIT MAP */
$("#openMapBtn").on("click", function () {
    mapModal.showModal();

    setTimeout(() => {
        initMap();
        map.invalidateSize();
    }, 200);
});

function initMap() {
    if (map) return;

    map = L.map("map").setView([10.131465634681566, 124.83868259357023], 16);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "&copy; OpenStreetMap contributors",
    }).addTo(map);

    map.on("click", function (e) {
        placeMarker(e.latlng);
        reverseGeocode(e.latlng.lat, e.latlng.lng);
    });
}

/* PLACE MARKER */
function placeMarker(latlng) {
    selectedLatLng = latlng;

    if (marker) {
        marker.setLatLng(latlng);
    } else {
        marker = L.marker(latlng).addTo(map);
    }
}

/* REVERSE GEOCODING (FREE OSM) */
function reverseGeocode(lat, lon) {
    $.get(
        `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`,
        function (data) {
            selectedAddress = data.display_name;
        },
    );
}

/* SEARCH LOCATION */
$("#searchBtn").click(function () {
    let query = $("#locationSearch").val();

    if (!query) return;

    $.get(
        `https://nominatim.openstreetmap.org/search?format=json&q=${query}`,
        function (data) {
            if (!data.length) return toastr.error("Location not found");

            let result = data[0];

            let latlng = L.latLng(result.lat, result.lon);

            map.setView(latlng, 16);
            placeMarker(latlng);
            selectedAddress = result.display_name;
        },
    );
});

/* USE LOCATION */
$("#selectLocationBtn").click(function () {
    if (!selectedAddress) return toastr.error("Please select a location");

    $("#places_visit").val(selectedAddress).trigger("input");

    mapModal.close();
});
