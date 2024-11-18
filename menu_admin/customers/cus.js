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
