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
