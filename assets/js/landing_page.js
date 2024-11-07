//JS UNTUK SLIDE
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

document.addEventListener("DOMContentLoaded", function () {
  showSlides();
});

function toggleAccordion(faqId) {
  const content = document.getElementById(faqId);
  const isExpanded = content.getAttribute("aria-hidden") === "false";

  // Toggle the visibility
  content.setAttribute("aria-hidden", isExpanded);
  content.style.display = isExpanded ? "none" : "block";

  // Toggle the button's expanded state
  const button = content.previousElementSibling;
  button.setAttribute("aria-expanded", !isExpanded);
}

// JS UNTUK ACCORDION
const accordionHeaders = document.querySelectorAll(".accordion-header");

accordionHeaders.forEach((header) => {
  header.addEventListener("click", function () {
    const accordionBody = header.nextElementSibling;
    accordionBody.classList.toggle("show");
    header.classList.toggle("active");
  });
});
{
  /* <script>
  // Ambil semua elemen dengan class 'accordion-header' const accordionHeaders =
  document.querySelectorAll('.accordion-header');
  accordionHeaders.forEach(header ={" "}
  {header.addEventListener("click", () => {
    // Ambil konten terkait dengan mencari elemen sibling berikutnya
    const content = header.nextElementSibling;

    // Toggle antara menampilkan dan menyembunyikan konten
    if (content.style.display === "block") {
      content.style.display = "none";
    } else {
      content.style.display = "block";
    }
  })}
  );
</script>; */
}
