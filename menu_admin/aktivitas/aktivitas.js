$(document).ready(function () {
    // Initialize DataTable without paging, info, or search
    $('#bookingTable').DataTable({
        "paging": false,
        "info": false,
        "searching": false
    });
});

// View booking details in a modal
function viewDetails(bookingId) {
    $.ajax({
        url: 'get_booking_details.php',
        type: 'GET',
        data: { id_booking: bookingId },
        success: function (response) {
            const data = JSON.parse(response);

            // Populate modal fields with booking details
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
            $('#modal-latitude').text(data.latitude);
            $('#modal-longitude').text(data.longitude);

            // Initialize map if coordinates exist
            if (data.latitude && data.longitude) {
                const location = {
                    lat: parseFloat(data.latitude),
                    lng: parseFloat(data.longitude)
                };
                
                // Check for Google Maps API and Advanced Marker
                if (window.google && window.google.maps && window.google.maps.Map && window.google.maps.marker) {
                    const mapElement = document.getElementById('map');
                    
                    if (mapElement) {
                        // Clear previous map
                        mapElement.innerHTML = '';
                        
                        const map = new google.maps.Map(mapElement, {
                            zoom: 15,
                            center: location,
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        });
                        
                        // Use AdvancedMarkerElement
                        new google.maps.marker.AdvancedMarkerElement({
                            map: map,
                            position: location,
                            title: 'Booking Location'
                        });
                    }
                } else {
                    console.error('Google Maps API or Advanced Marker not fully loaded');
                }
            }
            
            $('#viewDetailsModal').show();
        },
        error: function(xhr, status, error) {
            console.error('Ajax error:', error);
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
