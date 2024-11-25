// Menu Toggle
let toggle = document.querySelector('.toggle');
let navigation = document.querySelector('.navigation');
let main = document.querySelector('.main');

toggle.onclick = function(){
    navigation.classList.toggle('active');
    main.classList.toggle('active');
}
//

let list = document.querySelectorAll('.navigation li');

function activeLink() {
    // Hapus kelas 'hovered' dari semua elemen <li>
    list.forEach(item => item.classList.remove('hovered'));
    // Tambahkan kelas 'hovered' pada elemen yang sedang di-hover
    this.classList.add('hovered');
}

// Menambahkan event listener untuk mouseover
list.forEach(item => {
    item.addEventListener('mouseover', activeLink);
    item.addEventListener('mouseout', function() {
        this.classList.remove('hovered');
    });
});

// document.querySelector('.toggle').addEventListener('click', () => {
//     document.querySelector('.navigation').classList.toggle('active');
//     document.querySelector('.main').classList.toggle('active');
// });

// Photo modal functions
function showPhotoModal(src) {
    const modal = document.getElementById('photoModal');
    const modalImg = document.getElementById('modalPhoto');
    modal.style.display = "block";
    modalImg.src = src;
}

function closePhotoModal() {
    document.getElementById('photoModal').style.display = "none";
}

// Close modal when clicking outside
window.addEventListener('click', function(event) {
    const photoModal = document.getElementById('photoModal');
    if (event.target == photoModal) {
        photoModal.style.display = "none";
    }
});