// JS UNTUK SLIDE
let slideIndex = 0;

function showSlides() {
  let slides = document.getElementsByClassName("slide");
  for (let i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  slideIndex++;
  if (slideIndex > slides.length) {
    slideIndex = 1;
  }
  slides[slideIndex - 1].style.display = "block";
  setTimeout(showSlides, 4000); // Ubah slide setiap 4 detik
}

function changeSlide(n) {
  let slides = document.getElementsByClassName("slide");
  slideIndex += n;
  if (slideIndex < 1) {
    slideIndex = slides.length;
  } else if (slideIndex > slides.length) {
    slideIndex = 1;
  }
  for (let i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  slides[slideIndex - 1].style.display = "block";
}

// JS UNTUK ACCORDION
document.addEventListener("DOMContentLoaded", () => {
  const accordionHeaders = document.querySelectorAll(".accordion-header");

  accordionHeaders.forEach((header) => {
    header.addEventListener("click", () => {
      // Toggle active class on clicked accordion item
      const parentItem = header.parentElement;
      const content = parentItem.querySelector(".accordion-content");

      // Close other open items
      document.querySelectorAll(".accordion-item").forEach((item) => {
        if (item !== parentItem) {
          item.classList.remove("active");
          item.querySelector(".accordion-content").style.maxHeight = null;
        }
      });

      // Toggle the active state of the clicked item
      parentItem.classList.toggle("active");
      if (parentItem.classList.contains("active")) {
        content.style.maxHeight = content.scrollHeight + "px";
      } else {
        content.style.maxHeight = null;
      }
    });
  });
});
