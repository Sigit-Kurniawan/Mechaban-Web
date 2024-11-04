document.addEventListener("DOMContentLoaded", function () {
  var modal = document.getElementById("myModal");
  var btn = document.getElementById("myBtn");
  var span = document.getElementsByClassName("close")[0];

  // Ketika tombol 'Tambah Mobil' ditekan, buka modal
  btn.onclick = function () {
    // Reset form input
    document.getElementById("formMobil").reset();
    document.getElementById("modalTitle").innerText = "Form Tambah Mobil"; // Set judul untuk tambah mobil
    modal.style.display = "block"; // Menampilkan modal
  };

  // Ketika pengguna mengklik tanda x, tutup modal
  span.onclick = function () {
    closeModal();
  };

  // Ketika pengguna mengklik di luar modal, tutup modal
  window.onclick = function (event) {
    if (event.target === modal) {
      closeModal();
    }
  };
});

// Fungsi untuk membuka modal edit
function openEditModal(nopol, merk, type, transmition, year) {
  document.getElementById("nopol").value = nopol; // Mengisi input nopol
  document.getElementById("merk").value = merk; // Mengisi input merk
  document.getElementById("type").value = type; // Mengisi input tipe
  document.getElementById("transmition").value = transmition; // Mengisi input transmisi
  document.getElementById("year").value = year; // Mengisi input tahun
  document.getElementById("edit_nopol").value = nopol; // Mengisi input tahun
  document.getElementById("modalTitle").innerText = "Form Edit Mobil"; // Mengubah judul modal

  // Tampilkan modal
  var modal = document.getElementById("myModal");
  modal.style.display = "block";
}

// Fungsi untuk menutup modal
function closeModal() {
  var modal = document.getElementById("myModal");
  modal.style.display = "none"; // Menutup modal
}
