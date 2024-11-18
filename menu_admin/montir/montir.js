// Script to handle modal for adding and editing montir data
document.addEventListener("DOMContentLoaded", function() {
    // Get modal elements
    const modal = document.getElementById("myModal");
    const span = modal.getElementsByClassName("close")[0];
    const modalTitle = document.getElementById("modalTitle");
    const formMontir = document.getElementById("formMontir");

    // Get buttons
    const tambahMontirBtn = document.getElementById("myBtn");

    // Open the modal when "Tambah Montir" button is clicked
    tambahMontirBtn.addEventListener("click", function() {
        modal.style.display = "block";
        modalTitle.textContent = "Form Tambah Montir";
        formMontir.reset();
        document.getElementById("edit_email").value = "";
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
    window.openEditModal = function(email, name, no_hp, password) {
        modal.style.display = "block";
        modalTitle.textContent = "Form Edit Montir";
        document.getElementById("email").value = email;
        document.getElementById("name").value = name;
        document.getElementById("no_hp").value = no_hp;
        document.getElementById("password").value = password;
        document.getElementById("edit_email").value = email;
    };

    // Add password validation
    const passwordInput = document.getElementById("password");
    passwordInput.addEventListener("input", function() {
        const password = this.value;
        const isValid = 
            password.length >= 8 && 
            /[A-Z]/.test(password) && 
            /[0-9]/.test(password) && 
            /[@$!%*?&]/.test(password);
        
        this.setCustomValidity(
            isValid ? "" : "Password must meet all requirements"
        );
    });
});