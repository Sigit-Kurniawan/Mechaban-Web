document.addEventListener("DOMContentLoaded", function () {
  // Get modal elements
  const modal = document.getElementById("myModal");
  const span = modal.getElementsByClassName("close")[0];
  const modalTitle = document.getElementById("modalTitle");
  const formCustomer = document.getElementById("formCustomer");

  // Get button
  const tambahCustomerBtn = document.getElementById("myBtn");

  // Open the modal when "Tambah Pelanggan" button is clicked
  tambahCustomerBtn.addEventListener("click", function () {
      modal.style.display = "block";
      modalTitle.textContent = "Form Tambah Pelanggan";
      formCustomer.reset();
      document.getElementById("edit_email").value = "";
      document.getElementById("password").required = true;
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
  window.openEditModal = function (email, name, no_hp) {
      modal.style.display = "block";
      modalTitle.textContent = "Form Edit Pelanggan";

      // Set form values for editing
      document.getElementById("email").value = email;
      document.getElementById("name").value = name;
      document.getElementById("no_hp").value = no_hp;
      document.getElementById("edit_email").value = email;
      document.getElementById("password").required = false;
  };
});

 // Photo preview functionality
 document.getElementById('photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        if (file.size > 2 * 1024 * 1024) {
            alert('File terlalu besar. Maksimum 2MB.');
            this.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(event) {
            document.getElementById('photoPreview').src = event.target.result;
        };
        reader.readAsDataURL(file);
    }
});

// Photo modal functionality
function showPhotoModal(photoUrl) {
    const modal = document.getElementById('photoModal');
    const modalImg = document.getElementById('modalPhoto');
    modal.style.display = "flex";
    modalImg.src = photoUrl;
}

function closePhotoModal() {
    document.getElementById('photoModal').style.display = "none";
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('photoModal');
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

// Update existing openEditModal function to include photo preview
function openEditModal(email, name, no_hp, photo) {
    // Existing code...
    document.getElementById('photoPreview').src = photo ? 
        '<?php echo UPLOAD_DIR ?>' + photo : 
        '../../assets/img/default-profile.png';
}

document.querySelector('.search ion-icon').addEventListener('click', function() {
    this.closest('form').submit();
});
