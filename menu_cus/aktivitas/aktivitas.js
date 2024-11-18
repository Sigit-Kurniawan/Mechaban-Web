function openModal(id_booking) {
  document.getElementById("modal-detail").style.display = "block";

  var xhr = new XMLHttpRequest();
  xhr.open("GET", "get_booking_details.php?id_booking=" + id_booking, true);
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      document.getElementById("modal-body").innerHTML = xhr.responseText;
    }
  };
  xhr.send();
}

function closeModal() {
  document.getElementById("modal-detail").style.display = "none";
}

window.onclick = function (event) {
  if (event.target == document.getElementById("modal-detail")) {
    closeModal();
  }
};
