const sidebarButton = document.querySelector('.sidebar-button');
const sidebar = document.querySelector('.sidebar');
const mainContent = document.querySelector('.main-content');

sidebarButton.addEventListener('click', () => {
    sidebar.classList.toggle('open');
    mainContent.classList.toggle('open');
});