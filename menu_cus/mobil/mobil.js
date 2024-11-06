// Script to handle modal for adding and editing vehicles
document.addEventListener("DOMContentLoaded", function () {
  // Get modal elements
  const modal = document.getElementById("myModal");
  const span = modal.getElementsByClassName("close")[0];
  const modalTitle = document.getElementById("modalTitle");
  const formMobil = document.getElementById("formMobil");

  // Get buttons
  const tambahMobilBtn = document.getElementById("myBtn");

  // Open the modal when "Tambah Mobil" button is clicked
  tambahMobilBtn.addEventListener("click", function () {
    modal.style.display = "block";
    modalTitle.textContent = "Form Tambah Mobil"; // Set modal title
    formMobil.reset(); // Reset the form for adding a new vehicle
    document.getElementById("edit_nopol").value = ""; // Clear hidden field for editing
  });

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

  // Function to open the modal for editing
  window.openEditModal = function (nopol, merk, type, transmition, year) {
    modal.style.display = "block";
    modalTitle.textContent = "Form Edit Mobil"; // Set modal title for edit
    // Set values in the form for editing
    document.getElementById("nopol").value = nopol;
    document.getElementById("merk").value = merk;
    document.getElementById("type").value = type;
    document.getElementById("transmition").value = transmition;
    document.getElementById("year").value = year;
    document.getElementById("edit_nopol").value = nopol; // Set the hidden field for editing
  };
});

//JS UNTUK NOPOL
// Fungsi untuk memformat nopol sesuai dengan aturan yang diinginkan
function formatNopol(input) {
  let value = input.value.replace(/\s+/g, "").toUpperCase(); // Menghapus spasi dan memastikan huruf kapital

  // Pola regex untuk format Nopol: Kode wilayah (huruf), nomor (angka), huruf terakhir
  let regex = /^([A-Z]{1,2})(\d{1,4})([A-Z]{1})$/;
  let match = value.match(regex);

  if (match) {
    input.value = match[1] + " " + match[2] + " " + match[3]; // Format menjadi 'AB 1234 C'
  } else {
    input.value = value; // Jika tidak sesuai format, tampilkan apa adanya
  }
}

// Menambahkan event listener untuk input Nopol
document.getElementById("nopol").addEventListener("input", function () {
  formatNopol(this);
});
