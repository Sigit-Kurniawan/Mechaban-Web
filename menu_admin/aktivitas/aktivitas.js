$(document).ready(function () {
    // Initialize DataTable without paging, info, or search
    $('#bookingTable').DataTable({
        "paging": false,
        "info": false,
        "searching": false
    });
});

// At the top of aktivitas.js
const filterForm = document.getElementById('filterForm');
const dateFromInput = document.getElementById('date_from');
const dateToInput = document.getElementById('date_to');


// View booking details in a modal
function displayLocation(location) {
    const mapContainer = document.getElementById('map');
    
    if (!mapContainer) {
        console.error('Location container not found');
        return;
    }

    // Clear previous content
    mapContainer.innerHTML = '';

    // Display coordinates as text
    const locationInfo = document.createElement('div');
    locationInfo.innerHTML = `
        <p><strong>Latitude:</strong> ${location.lat.toFixed(6)}</p>
        <p><strong>Longitude:</strong> ${location.lng.toFixed(6)}</p>
        <a href="https://www.google.com/maps?q=${location.lat},${location.lng}" 
           target="_blank" 
           class="maps-link">
            Buka di Google Maps
        </a>
    `;

    mapContainer.appendChild(locationInfo);
}

// Modify the viewDetails function in aktivitas.js
function viewDetails(bookingId) {
    $.ajax({
        url: 'get_booking_details.php',
        type: 'GET',
        data: { id_booking: bookingId },
        success: function (response) {
            const data = JSON.parse(response);
            
            // Decode coordinates for better precision
            const decodedLat = parseFloat(data.latitude);
            const decodedLng = parseFloat(data.longitude);

            // Update modal fields including decoded coordinates
            $('#modal-id-booking').text(data.id_booking);
            $('#modal-tgl-booking').text(new Date(data.tgl_booking).toLocaleString());
            $('#modal-email-customer').text(data.email_customer);
            $('#modal-nopol').text(data.nopol);
            $('#modal-servis').text(data.servis);
            $('#modal-harga-servis').text(data.harga_servis);
            $('#modal-total-biaya').text(data.total_biaya);
            $('#modal-status').text(data.status);
            $('#modal-ketua-montir').text(data.ketua_montir);
            $('#modal-anggota-montir').text(data.anggota_montir);
            $('#modal-latitude').text(decodedLat.toFixed(6));
            $('#modal-longitude').text(decodedLng.toFixed(6));

            // Ensure map container exists
            let mapContainer = document.getElementById('map');
            if (!mapContainer) {
                mapContainer = document.createElement('div');
                mapContainer.id = 'map';
                
                const bookingDetails = document.getElementById('bookingDetails');
                if (bookingDetails) {
                    bookingDetails.appendChild(mapContainer);
                }
            }

            // Display location if coordinates are valid
            if (!isNaN(decodedLat) && !isNaN(decodedLng)) {
                displayLocation({
                    lat: decodedLat,
                    lng: decodedLng
                });
            } else {
                // Handle invalid coordinates
                mapContainer.innerHTML = '<p>Lokasi tidak tersedia</p>';
            }
            
            $('#viewDetailsModal').show();
        },
        error: function(_xhr, _status, error) {
            console.error('Ajax error:', error);
            showAlert('Error loading booking details', 'error');
        }
    });
}
// Update booking status in a modal
function updateStatus(bookingId) {
    $('#bookingId').val(bookingId);
    $('#updateStatusModal').show();
}

// Close modal when clicking the close button or outside the modal
document.querySelectorAll('.close').forEach(closeBtn => {
    closeBtn.onclick = function () {
        this.closest('.modal').style.display = "none";
    };
});

window.onclick = function (event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = "none";
    }
};

// Callback for when Google Maps API is loaded
function initMap() {
    console.log('Google Maps API loaded');
}

// Add this function at the top of your file
function showAlert(message, type = 'success') {
    const alertContainer = document.getElementById('alert-container');
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    
    alertDiv.innerHTML = `
        ${message}
        <span style="margin-left: 10px; cursor: pointer;" onclick="this.parentElement.remove()">×</span>
    `;
    
    alertContainer.appendChild(alertDiv);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Then replace all alert() calls with showAlert()
// For example, in your form submit handler:
document.getElementById('updateStatusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('update_status.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Status updated successfully', 'success');
            location.reload(); // Refresh page to show updated status
        } else {
            showAlert('Error updating status: ' + data.error, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error updating status', 'error');
    });
});

filterForm.addEventListener('submit', function(e) {
    const dateFrom = new Date(dateFromInput.value);
    const dateTo = new Date(dateToInput.value);

    if (dateFrom > dateTo) {
        e.preventDefault();
        showAlert('Tanggal awal tidak boleh lebih besar dari tanggal akhir', 'error');
        return false;
    }
});

filterForm.addEventListener('submit', function(e) {
    const dateFrom = new Date(dateFromInput.value);
    const dateTo = new Date(dateToInput.value);

    if (dateFrom > dateTo) {
        e.preventDefault();
        showAlert('Tanggal awal tidak boleh lebih besar dari tanggal akhir', 'error');
        return false;
    }
});
