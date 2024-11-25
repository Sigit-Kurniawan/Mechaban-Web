<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mechaban</title>
    <link rel="icon" type="image/png" href="../../assets/img/favicon.png" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        #map {
            flex: 1;
            height: 100%;
            width: 100%;
        }

        .controls {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            background: rgba(255, 255, 255, 0.9);
            padding: 10px 20px;
            border-radius: 5px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            display: flex;
            gap: 10px;
            align-items: center;
        }

        button {
            padding: 8px 15px;
            font-size: 14px;
            border: none;
            border-radius: 4px;
            background-color: #007BFF;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        #locate-btn {
            background-color: #343a40;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="controls">
        <button id="locate-btn">Deteksi Lokasi</button>
        <button id="submit-btn" disabled>Pilih Lokasi</button>
    </div>
    <div id="map"></div>

    <!-- Menampilkan Alamat -->
    <p id="location-display">Lokasi belum dipilih.</p>

    <script>
        // Inisialisasi peta
        const map = L.map('map').setView([0, 0], 13); // Default: Latitude 0, Longitude 0

        // Tambahkan tile dari OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        const marker = L.marker([0, 0]).addTo(map).bindPopup('Lokasi Anda').openPopup();
        let latitude = null;
        let longitude = null;
        let address = null; // Menyimpan alamat lengkap

        // Fungsi untuk melacak lokasi pengguna
        function locateUser() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        latitude = position.coords.latitude;
                        longitude = position.coords.longitude;

                        // Pusatkan peta ke lokasi pengguna
                        map.setView([latitude, longitude], 15);

                        // Update posisi marker
                        marker.setLatLng([latitude, longitude])
                            .bindPopup(`Anda berada di sini!<br>Latitude: ${latitude.toFixed(6)}<br>Longitude: ${longitude.toFixed(6)}`)
                            .openPopup();

                        // Aktifkan tombol submit
                        document.getElementById('submit-btn').disabled = false;

                        // Mengonversi koordinat menjadi alamat
                        getAddressFromCoordinates(latitude, longitude);
                    },
                    (error) => {
                        alert('Gagal mendeteksi lokasi: ' + error.message);
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            } else {
                alert('Geolokasi tidak didukung oleh browser Anda.');
            }
        }

        // Fungsi untuk mengonversi koordinat menjadi alamat menggunakan API Nominatim
        function getAddressFromCoordinates(lat, lon) {
            const url = `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    address = data.display_name; // Menyimpan alamat lengkap
                    // Menampilkan alamat di halaman
                    document.getElementById('location-display').textContent = address;
                })
                .catch(error => {
                    console.error('Kesalahan saat mengambil alamat:', error);
                });
        }

        // Event listener tombol "Deteksi Lokasi"
        document.getElementById('locate-btn').addEventListener('click', locateUser);

        // Kirim data lokasi (latitude, longitude, alamat) ke booking.php
        document.getElementById('submit-btn').addEventListener('click', () => {
            if (latitude && longitude && address) {
                const params = new URLSearchParams();
                params.append('latitude', latitude);
                params.append('longitude', longitude);
                params.append('address', address); // Mengirimkan alamat

                // Kirim data ke booking.php
                window.location.href = `booking.php?${params.toString()}`;
            }
        });
    </script>
</body>

</html>