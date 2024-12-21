// Script to handle modal for adding and editing montir data
document.addEventListener("DOMContentLoaded", function() {
    // Get modal elements
    const modal = document.getElementById("myModal");
    const span = modal.getElementsByClassName("close")[0];
    const modalTitle = document.getElementById("modalTitle");
    const formMontir = document.getElementById("formMontir");
    const photoInput = document.getElementById('photo');
    const photoPreview = document.getElementById('photoPreview');

    // Get buttons
    const tambahMontirBtn = document.getElementById("myBtn");

    // Open the modal when "Tambah Montir" button is clicked
    tambahMontirBtn.addEventListener("click", function() {
        modal.style.display = "block";
        modalTitle.textContent = "Form Tambah Montir";
        formMontir.reset();
        document.getElementById("edit_email").value = "";
    });

    photoInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                photoPreview.src = e.target.result;
                photoPreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
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
    
    // Make this function global so it can be called from HTML onclick
    window.editMontir = function(email, name, noHp, password) {
        document.getElementById('email').value = email;
        document.getElementById('name').value = name;
        document.getElementById('no_hp').value = noHp;
        document.getElementById('password').value = password;
        document.getElementById('edit_email').value = email;
        document.getElementById('modalTitle').innerHTML = 'Edit Data Montir';
    
        // Show modal
        modal.style.display = "block";
    };
    

   // Update password validation
const passwordInput = document.getElementById("password");
passwordInput.addEventListener("input", function() {
    const password = this.value;
    const editEmailField = document.getElementById('edit_email');
    
    // If this is an edit (edit_email has value) and password field is empty, skip validation
    if (editEmailField.value && !password) {
        this.setCustomValidity("");
        return;
    }
    
    // Only validate if password is provided
    if (password) {
        const hasMinLength = password.length >= 8;
        const hasUpperCase = /[A-Z]/.test(password);
        const hasNumber = /[0-9]/.test(password);
        const hasSpecial = /[@$!%*?&]/.test(password);
        
        const isValid = hasMinLength && hasUpperCase && hasNumber && hasSpecial;
        
        this.setCustomValidity(
            isValid ? "" : "Password must be at least 8 characters and contain uppercase, number, and special character"
        );
    } else if (!editEmailField.value) {
        // If this is a new entry (no edit_email), require password
        this.setCustomValidity("Password is required for new entries");
    }
});

// Update editMontir function to not set password field
window.editMontir = function(email, name, noHp) {
    document.getElementById('email').value = email;
    document.getElementById('name').value = name;
    document.getElementById('no_hp').value = noHp;
    document.getElementById('password').value = ''; // Clear password field
    document.getElementById('edit_email').value = email;
    document.getElementById('modalTitle').innerHTML = 'Edit Data Montir';

    // Show modal
    const modal = document.getElementById("myModal");
    modal.style.display = "block";
};
    // Optional: Toggle password visibility
    window.togglePassword = function() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.querySelector('.toggle-password ion-icon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.setAttribute('name', 'eye-off-outline');
        } else {
            passwordInput.type = 'password';
            eyeIcon.setAttribute('name', 'eye-outline');
        }
    };
});

// Update the delete functionality
function confirmDelete(email, name) {
    const modal = document.getElementById('deleteModal');
    const confirmBtn = document.getElementById('confirmDelete');
    const montirName = document.getElementById('montirName');
    
    montirName.textContent = name;
    modal.style.display = 'block';
    
    confirmBtn.onclick = function() {
        window.location.href = `?delete_email=${email}`;
    };
}

// Close delete modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('deleteModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
}

// Close delete modal when clicking X
document.querySelector('.delete-modal .close').onclick = function() {
    document.getElementById('deleteModal').style.display = 'none';
}



// Auto-hide alerts after 3 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.success-alert, .error-alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.display = 'none';
        }, 3000);
    });
});
