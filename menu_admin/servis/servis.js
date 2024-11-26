// Script to handle modal for adding and editing servis data
document.addEventListener("DOMContentLoaded", function() {
    // Get modal elements
    const modal = document.getElementById("myModal");
    const span = modal.getElementsByClassName("close")[0];
    const modalTitle = document.getElementById("modalTitle");
    const formServis = document.getElementById("formServis");

    // Get buttons
    const tambahServisBtn = document.getElementById("myBtn");

    // Open the modal when "Tambah Servis" button is clicked
    tambahServisBtn.addEventListener("click", function() {
        modal.style.display = "block";
        modalTitle.textContent = "Form Tambah Servis";
        formServis.reset();
        document.getElementById("edit_id").value = "";
    });

    // Close the modal when "X" is clicked
    span.addEventListener("click", function() {
        modal.style.display = "none";
    });

    // Close the modal if user clicks outside of it
    window.addEventListener("click", function(event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

    // Function to open the modal for editing
    window.openEditModal = function(id, nama_servis, harga_servis, id_komponen) {
        modal.style.display = "block";
        modalTitle.textContent = "Form Edit Servis";
        document.getElementById("nama_servis").value = nama_servis;
        document.getElementById("harga_servis").value = harga_servis;
        document.getElementById("id_komponen").value = id_komponen;
        document.getElementById("edit_id").value = id;
    };
});

function showPhotoModal(photoSrc) {
    document.getElementById('modalPhoto').src = photoSrc;
    document.getElementById('photoModal').style.display = 'block';
}

function closePhotoModal() {
    document.getElementById('photoModal').style.display = 'none';
}

$(document).ready(function() {
    $('#servisTable').DataTable({
        "paging": false,
        "info": false,
        "searching": false
    });
});