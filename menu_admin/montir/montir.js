document.addEventListener("DOMContentLoaded", function () {
    // Get modal elements
    const modal = document.getElementById("myModal");
    const span = modal.getElementsByClassName("close")[0];
    const modalTitle = document.getElementById("modalTitle");
    const formMontir = document.getElementById("formMontir");
    const passwordField = document.getElementById("password");
    const typeSelect = document.getElementById("type");

    // Get buttons
    const tambahMontirBtn = document.getElementById("myBtn");

    // Function to enable or disable the password field based on type
    function togglePasswordField() {
        if (typeSelect.value === 'ketua') {
            passwordField.disabled = false;
            passwordField.required = true;
            passwordField.closest('.formLabel').style.display = 'block';
        } else if (typeSelect.value === 'anggota') {
            passwordField.disabled = true;
            passwordField.required = false;
            passwordField.value = ''; // Clear the field when disabled
            passwordField.closest('.formLabel').style.display = 'none';
        }
    }

    // Add event listener to type select
    typeSelect.addEventListener("change", togglePasswordField);

    // Open the modal when "Tambah Montir" button is clicked
    tambahMontirBtn.addEventListener("click", function () {
        modal.style.display = "block";
        modalTitle.textContent = "Form Tambah Montir";
        formMontir.reset();
        document.getElementById("edit_email").value = "";
        togglePasswordField(); // Set the initial state of the password field
    });

    // Close the modal when "X" is clicked
    span.addEventListener("click", function () {
        modal.style.display = "none";
    });

    // Close the modal if user clicks outside of it
    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

    // Function to open the modal for editing
    window.openEditModal = function (type, email, nama, no_hp) {
        modal.style.display = "block";
        modalTitle.textContent = "Form Edit Montir";

        // Set form values for editing
        document.getElementById("email").value = email;
        document.getElementById("nama_montir").value = nama;
        document.getElementById("no_hp").value = no_hp;
        document.getElementById("type").value = type;
        document.getElementById("edit_email").value = email;

        // Update the password field based on the type
        togglePasswordField();
    };

    // Initial setup of password field
    togglePasswordField();
});