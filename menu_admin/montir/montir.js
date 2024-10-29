// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on the button, open the modal
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
} 

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