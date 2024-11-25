// Mendapatkan modal dan tombol
var modal = document.getElementById("myModal");
var btn = document.getElementById("myBtn"); // Tombol untuk membuka modal
var span = document.querySelector(".close"); // Tombol close berdasarkan kelas
var modalTitle = document.getElementById("modalTitle");

// Ketika tombol Edit Akun diklik, tampilkan modal
btn.onclick = function () {
  modal.style.display = "block";
};

// Close the modal when "X" is clicked
span.addEventListener("click", function () {
  modal.style.display = "none";
});

// Close the modal if the user clicks anywhere outside the modal content
window.addEventListener("click", function (event) {
  if (event.target === modal) {
    modal.style.display = "none";
  }
});

// Fungsi untuk menutup modal
function closeModal() {
  modal.style.display = "none"; // Menutup modal
}

// Function to open the modal for editing
window.openEditModal = function (name, email, no_hp, password) {
  modal.style.display = "block";
  modalTitle.textContent = "Form Edit Profil"; // Set modal title for edit
  // Set values in the form for editing
  document.getElementById("name").value = name;
  document.getElementById("email").value = email;
  document.getElementById("no_hp").value = no_hp;
  document.getElementById("password").value = password;
  document.getElementById("edit_email").value = email; // Set the hidden field for editing

  // Membiarkan pengguna mengedit email
  document.getElementById("email").disabled = false; // Memastikan input email bisa diedit
};

//UNTUK ALERT
// Cek apakah elemen dengan id 'success-alert' ada di halaman
window.onload = function () {
  var alert = document.getElementById("success-alert");
  if (alert) {
    // Setelah 3 detik (3000ms), sembunyikan alert
    setTimeout(function () {
      alert.style.opacity = 0; // Mengubah opacity menjadi 0 (transisi hilang)
      setTimeout(function () {
        alert.style.display = "none"; // Menghilangkan elemen setelah transisi selesai
      }, 500); // 500ms untuk transisi opacity
    }, 3000); // Menunggu 3 detik
  }
};

// Handle photo preview
document.getElementById('photo').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const fileInputText = document.querySelector('.file-input-text');
    
    if (file) {
        // Update file input text
        fileInputText.textContent = file.name;
        
        // Preview image
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('photoPreview').src = e.target.result;
        }
        reader.readAsDataURL(file);
    } else {
        // Reset if no file selected
        fileInputText.textContent = 'Pilih Foto';
    }
});

// Photo modal functions
function showPhotoModal(src) {
    const modal = document.getElementById('photoModal');
    const modalImg = document.getElementById('modalPhoto');
    modal.style.display = "block";
    modalImg.src = src;
}

function closePhotoModal() {
    document.getElementById('photoModal').style.display = "none";
}

// Auto-hide alerts
window.onload = function() {
    const successAlert = document.getElementById('success-alert');
    const errorAlert = document.getElementById('error-alert');
    
    function hideAlert(alert) {
        if (alert) {
            setTimeout(() => {
                alert.style.opacity = 0;
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 500);
            }, 3000);
        }
    }
    
    hideAlert(successAlert);
    hideAlert(errorAlert);
};

// Close modal when clicking outside
window.onclick = function(event) {
    const photoModal = document.getElementById('photoModal');
    if (event.target == photoModal) {
        photoModal.style.display = "none";
    }
}

document.addEventListener('DOMContentLoaded', function() {
  const photoInput = document.getElementById('photo');
  const photoForm = document.getElementById('photoForm');
  const fileInputText = document.querySelector('.file-input-text');
  const uploadBtn = document.querySelector('.upload-btn');

  photoInput.addEventListener('change', function(event) {
      const file = event.target.files[0];
      
      if (file) {
          // Validate file size
          if (file.size > 2 * 1024 * 1024) {
              alert('File terlalu besar. Maksimum 2MB.');
              this.value = '';
              fileInputText.textContent = 'Tidak ada file dipilih';
              uploadBtn.disabled = true;
              return;
          }

          // Validate file type
          const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
          if (!allowedTypes.includes(file.type)) {
              alert('Format file tidak diizinkan. Hanya JPG, JPEG, dan PNG yang diperbolehkan.');
              this.value = '';
              fileInputText.textContent = 'Tidak ada file dipilih';
              uploadBtn.disabled = true;
              return;
          }

          // Update file input text and preview
          fileInputText.textContent = file.name;
          uploadBtn.disabled = false;

          // Preview image
          const reader = new FileReader();
          reader.onload = function(e) {
              document.getElementById('photoPreview').src = e.target.result;
          }
          reader.readAsDataURL(file);
      } else {
          fileInputText.textContent = 'Tidak ada file dipilih';
          uploadBtn.disabled = true;
      }
  });

  // Auto-hide alerts
  const successAlert = document.getElementById('success-alert');
  const errorAlert = document.getElementById('error-alert');
  
  function hideAlert(alert) {
      if (alert) {
          setTimeout(() => {
              alert.style.display = 'none';
          }, 3000);
      }
  }
  
  hideAlert(successAlert);
  hideAlert(errorAlert);
});

// Photo modal functions
function showPhotoModal(src) {
  const modal = document.getElementById('photoModal');
  const modalImg = document.getElementById('modalPhoto');
  modal.style.display = "block";
  modalImg.src = src;
}

function closePhotoModal() {
  document.getElementById('photoModal').style.display = "none";
}

// Close modal when clicking outside
window.onclick = function(event) {
  const photoModal = document.getElementById('photoModal');
  if (event.target == photoModal) {
      photoModal.style.display = "none";
  }
}