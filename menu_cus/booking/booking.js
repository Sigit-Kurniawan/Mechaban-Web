//UNTUK DROPDOWN
document.addEventListener("DOMContentLoaded", () => {
  const komponenSelect = document.querySelector(".komponen-combobox");
  const servisContainers = document.querySelectorAll(".servis-container");

  // Event listener untuk perubahan dropdown komponen
  komponenSelect.addEventListener("change", (event) => {
    const selectedKomponen = event.target.value;

    // Sembunyikan semua container servis
    servisContainers.forEach((container) => {
      container.style.display = "none";
    });

    // Tampilkan container yang sesuai dengan komponen terpilih
    const selectedContainer = document.querySelector(
      `.servis-container[data-komponen="${selectedKomponen}"]`
    );
    if (selectedContainer) {
      selectedContainer.style.display = "block";
    }
  });
});
