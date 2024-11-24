$(document).ready(function() {
    $('#bookingTable').DataTable({
        "paging": false,
        "info": false,
        "searching": false
    });
});

function exportToExcel() {
    window.location.href = 'export_booking.php' + window.location.search;
}

function viewDetails(bookingId) {
    window.location.href = 'booking_details.php?id=' + bookingId;
}

function updateStatus(bookingId) {
    // Implement status update modal or redirect to update page
    window.location.href = 'update_status.php?id=' + bookingId;
}