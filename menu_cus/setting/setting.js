// Modal functionality
const modal = document.getElementById("myModal");
const photoModal = document.getElementById("photoModal");
const editBtn = document.getElementById("myBtn");
const closeBtn = document.querySelector(".close");
const modalTitle = document.getElementById("modalTitle");
const photoForm = document.getElementById("photoForm");
const photoInput = document.getElementById("photo");
const uploadBtn = document.querySelector(".upload-btn");
const fileInputText = document.querySelector(".file-input-text");

// Photo preview functionality
photoInput.addEventListener("change", function() {
    const file = this.files[0];
    if (file) {
        // Update file name display
        fileInputText.textContent = file.name;
        
        // Enable upload button
        uploadBtn.disabled = false;
        
        // Preview image
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById("photoPreview").src = e.target.result;
        };
        reader.readAsDataURL(file);
    } else {
        fileInputText.textContent = "Tidak ada file dipilih";
        uploadBtn.disabled = true;
    }
});

// Photo modal functionality
function showPhotoModal(src) {
    const modalPhoto = document.getElementById("modalPhoto");
    modalPhoto.src = src;
    photoModal.style.display = "block";
}

function closePhotoModal() {
    photoModal.style.display = "none";
}

// Edit profile modal functionality
editBtn.onclick = function() {
    modal.style.display = "block";
};

closeBtn.onclick = function() {
    modal.style.display = "none";
};

// Close modals when clicking outside
window.onclick = function(event) {
    if (event.target === modal) {
        modal.style.display = "none";
    }
    if (event.target === photoModal) {
        photoModal.style.display = "none";
    }
};

// Form validation
const formAkun = document.getElementById("formAkun");
formAkun.addEventListener("submit", function(e) {
    const email = document.getElementById("email").value;
    const phone = document.getElementById("no_hp").value;
    
    // Basic email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        e.preventDefault();
        alert("Please enter a valid email address");
        return;
    }
    
    // Basic phone validation (adjust regex according to your needs)
    const phoneRegex = /^[0-9]{10,13}$/;
    if (!phoneRegex.test(phone)) {
        e.preventDefault();
        alert("Please enter a valid phone number (10-13 digits)");
        return;
    }
});

// Alert auto-hide functionality
window.onload = function() {
    const successAlert = document.querySelector(".success-alert");
    const errorAlert = document.querySelector(".error-alert");
    
    function hideAlert(alert) {
        if (alert) {
            setTimeout(() => {
                alert.style.opacity = "0";
                setTimeout(() => {
                    alert.style.display = "none";
                }, 500);
            }, 3000);
        }
    }
    
    hideAlert(successAlert);
    hideAlert(errorAlert);
};

// Function to open edit modal with existing data
window.openEditModal = function(name, email, no_hp) {
    modal.style.display = "block";
    modalTitle.textContent = "Form Edit Profil";
    
    document.getElementById("name").value = name;
    document.getElementById("email").value = email;
    document.getElementById("no_hp").value = no_hp;
    document.getElementById("edit_email").value = email;
    
    // Enable email editing
    document.getElementById("email").disabled = false;
};

// Submit form confirmation
document.querySelector("form[action='delete_account.php']").onsubmit = function(e) {
    if (!confirm("Apakah Anda yakin ingin menghapus akun?")) {
        e.preventDefault();
    }
};