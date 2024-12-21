// JS UNTUK NAVBAR
const hamburger = document.getElementById("hamburger");
const drawer = document.getElementById("drawer");
const closeDrawer = document.getElementById("close-drawer");

hamburger.addEventListener("click", () => {
  drawer.classList.remove("translate-x-full");
});

closeDrawer.addEventListener("click", () => {
  drawer.classList.add("translate-x-full");
});

const drawerLinks = drawer.querySelectorAll("a");
drawerLinks.forEach((link) => {
  link.addEventListener("click", () => {
    drawer.classList.add("translate-x-full");
  });
});

const navbar = document.getElementById("navbar");
const logo = navbar.querySelector("img");
const links = navbar.querySelectorAll("a");
const title = document.getElementById("title");
const login = document.getElementById("login");
const filteredLinks = Array.from(links).filter((link) => link.id !== "login");

window.addEventListener("scroll", () => {
  const scrollY = window.scrollY;
  const changePoint = 700; // Jarak scroll di mana warna berubah

  if (scrollY > changePoint) {
    navbar.classList.remove("bg-opacity-20");
    navbar.classList.remove("backdrop-blur-md");
    navbar.classList.add("bg-white");
    logo.srcset = "assets/img/logo2.png";
    title.classList.remove("text-white");
    title.classList.add("text-primary");
    filteredLinks.forEach((link) => link.classList.add("text-primary"));
    login.classList.remove("text-primary");
    login.classList.add("text-white");
    login.classList.remove("bg-white");
    login.classList.add("bg-primary");
    hamburger.classList.add("text-primary");
    hamburger.classList.remove("text-white");
  } else {
    navbar.classList.add("bg-opacity-20");
    navbar.classList.add("backdrop-blur-md");
    navbar.classList.remove("bg-white");
    logo.srcset = "assets/img/logo.png";
    title.classList.add("text-white");
    title.classList.remove("text-primary");
    filteredLinks.forEach((link) => link.classList.remove("text-primary"));
    login.classList.add("text-primary");
    login.classList.remove("text-white");
    login.classList.add("bg-white");
    login.classList.remove("bg-primary");
    hamburger.classList.remove("text-primary");
    hamburger.classList.add("text-white");
  }
});

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

// JS UNTUK SWIPER
var swiper = new Swiper(".mySwiper", {
  slidesPerView: 1,
  spaceBetween: 16,
  loop: true,
  centeredSlides: true,
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
  breakpoints: {
    640: {
      // Tablet portrait
      slidesPerView: 2,
      spaceBetween: 20,
    },
    768: {
      // Tablet landscape
      slidesPerView: 3,
      spaceBetween: 24,
    },
    1024: {
      // Desktop
      slidesPerView: 5,
      spaceBetween: 12,
      centeredSlides: true,
    },
  },
});

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
