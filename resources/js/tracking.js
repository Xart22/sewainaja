document.addEventListener("DOMContentLoaded", async function () {
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get("id");

    const data = await getData();
    if (!data) {
        return;
    }
    const center = [
        (parseFloat(data.data.customer.latitude) +
            parseFloat(data.data.teknisi.latitude)) /
            2,
        (parseFloat(data.data.customer.longitude) +
            parseFloat(data.data.teknisi.longitude)) /
            2,
    ];

    const route = await getRoute(data.data.customer, data.data.teknisi);

    const map = L.map("map").setView(center, 13);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 19,
    }).addTo(map);

    const routeLine = L.geoJSON(route.routes[0].geometry).addTo(map);
    routeLine.setStyle({ color: "red" });

    const customer = L.marker([
        parseFloat(data.data.customer.latitude),
        parseFloat(data.data.customer.longitude),
    ]).addTo(map);
    customer.bindPopup(data.data.customer.name);

    // Marker Teknisi
    const teknisiIcon = L.icon({
        iconUrl: "https://cdn-icons-png.flaticon.com/512/2922/2922506.png",
        iconSize: [30, 30],
        iconAnchor: [15, 15],
        popupAnchor: [0, -15],
    });

    const teknisiMarker = L.marker(
        [
            parseFloat(data.data.teknisi.latitude),
            parseFloat(data.data.teknisi.longitude),
        ],
        { icon: teknisiIcon }
    ).addTo(map);

    // Update Popup dengan Info Awal
    updateTeknisiPopup(teknisiMarker, data.data.teknisi, route.routes[0]);

    // Interval untuk Update Posisi Teknisi
    setInterval(async () => {
        const updatedData = await getData(); // Ambil data terbaru
        const teknisiLat = parseFloat(updatedData.data.teknisi.latitude);
        const teknisiLng = parseFloat(updatedData.data.teknisi.longitude);

        // Update posisi marker teknisi
        teknisiMarker.setLatLng([teknisiLat, teknisiLng]);

        // Update popup teknisi
        const updatedRoute = await getRoute(
            updatedData.data.customer,
            updatedData.data.teknisi
        );
        updateTeknisiPopup(
            teknisiMarker,
            updatedData.data.teknisi,
            updatedRoute.routes[0]
        );
    }, 5000); // Update setiap 5 detik
});

// Fungsi untuk Update Popup Teknisi
function updateTeknisiPopup(marker, teknisi, route) {
    const duration = route.duration;
    const hours = Math.floor(duration / 3600);
    const minutes = Math.floor((duration % 3600) / 60);
    const distance = (route.distance / 1000).toFixed(1);

    marker.bindPopup(
        `Teknisi: ${teknisi.name} <br> Duration: ${hours} hours ${minutes} minutes <br> Distance: ${distance} km`
    );
}

// Fungsi untuk Ambil Data
const getData = async () => {
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get("id");
    if (!id) return;
    return await fetch(`/api/tracking/${id}`).then((response) =>
        response.json()
    );
};

// Fungsi untuk Ambil Route
const getRoute = async (start, end) => {
    return await fetch(
        `https://routing.openstreetmap.de/routed-car/route/v1/driving/${start.longitude},${start.latitude};${end.longitude},${end.latitude}?overview=full&geometries=geojson`
    ).then((response) => response.json());
};
