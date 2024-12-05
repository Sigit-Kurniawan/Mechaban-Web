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
// Fungsi untuk memformat nopol
function formatNopol(nopol) {
  const regex = /^([A-Za-z]{1,2})(\d{3,4})([A-Za-z]{1,2})$/; // Regex untuk memisahkan bagian nopol
  const matches = nopol.match(regex);

  if (matches) {
    return `${matches[1]} ${matches[2]} ${matches[3]}`; // Format ulang dengan spasi
  }
  return nopol; // Jika tidak cocok, tampilkan apa adanya
}

// Event listener pada input nopol
document.getElementById("nopol").addEventListener("input", function (event) {
  const nopolInput = event.target;
  let value = nopolInput.value.toUpperCase(); // Ubah menjadi huruf besar

  // Hanya izinkan karakter yang valid (huruf, angka)
  value = value.replace(/[^A-Za-z0-9]/g, "");

  // Terapkan format nopol
  const formattedValue = formatNopol(value);

  // Tampilkan hasil format ulang
  nopolInput.value = formattedValue;
});

//UNTUK SEARCH
