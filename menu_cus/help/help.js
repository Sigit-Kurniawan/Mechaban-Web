// Menangkap semua elemen dengan class 'pertanyaan'
const itemHeaders = document.querySelectorAll(".pertanyaan");

itemHeaders.forEach((itemHeader) => {
  itemHeader.addEventListener("click", () => {
    // Menambah atau menghapus class 'active'
    itemHeader.classList.toggle("active");

    const itemBody = itemHeader.nextElementSibling;

    if (itemBody) {
      if (itemHeader.classList.contains("active")) {
        itemBody.style.maxHeight = itemBody.scrollHeight + "px";
      } else {
        itemBody.style.maxHeight = 0;
      }
    }
  });
});
