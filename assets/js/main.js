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
