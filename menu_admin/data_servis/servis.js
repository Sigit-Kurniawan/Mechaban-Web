 // Modal for Komponen
 var komponenModal = document.getElementById("komponenModal");
 var komponenBtn = document.getElementById("komponenBtn");
 var komponenSpan = document.getElementsByClassName("close")[0];

 komponenBtn.onclick = function() {
     komponenModal.style.display = "block";
     document.getElementById("komponenModalTitle").innerText = "Form Tambah Komponen";
     document.getElementById("formKomponen").reset();
 }

 komponenSpan.onclick = function() {
     komponenModal.style.display = "none";
 }

 function openEditKomponenModal(id_data_komponen, nama_komponen) {
     komponenModal.style.display = "block";
     document.getElementById("komponenModalTitle").innerText = "Edit Komponen";
     document.getElementById("nama_komponen").value = nama_komponen;
     document.getElementById("id_data_komponen").value = id_data_komponen;
 }

// Modal for Servis
var servisModal = document.getElementById("servisModal");
var servisBtn = document.getElementById("servisBtn");
var servisSpan = document.getElementsByClassName("closeServis")[0];

servisBtn.onclick = function() {
    servisModal.style.display = "block";
    document.getElementById("servisModalTitle").innerText = "Form Tambah Servis";
    document.getElementById("formServis").reset();
}

servisSpan.onclick = function() {
    servisModal.style.display = "none";
}

function openEditServisModal(id, nama, harga) {
    servisModal.style.display = "block";
    document.getElementById("servisModalTitle").innerText = "Edit Servis";
    document.getElementById("nama_servis").value = nama;
    document.getElementById("harga_servis").value = harga;
    document.getElementById("servis_edit_id").value = id;
}
