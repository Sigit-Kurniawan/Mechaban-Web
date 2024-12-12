// Get modal elements
const komponenModal = document.getElementById('komponenModal');
const servisModal = document.getElementById('servisModal');

// Get buttons
const komponenBtn = document.getElementById('komponenBtn');
const servisBtn = document.getElementById('servisBtn');

// Add click event listeners
komponenBtn.addEventListener('click', () => {
    komponenModal.style.display = 'block';
});

servisBtn.addEventListener('click', () => {
    servisModal.style.display = 'block';
});

// Get close buttons
const closeButtons = document.getElementsByClassName('close');

// Add close functionality
Array.from(closeButtons).forEach(button => {
    button.addEventListener('click', () => {
        komponenModal.style.display = 'none';
        servisModal.style.display = 'none';
    });
});

// Close modal when clicking outside
window.addEventListener('click', (e) => {
    if (e.target == komponenModal) komponenModal.style.display = 'none';
    if (e.target == servisModal) servisModal.style.display = 'none';
});

// Function to generate random ID for Komponen (DK + 2 numbers + 3 letters)
function generateKomponenId() {
    const numbers = Math.floor(Math.random() * 90 + 10); // 10-99
    const letters = Array(3).fill()
        .map(() => String.fromCharCode(Math.floor(Math.random() * 26) + 65))
        .join('');
    return `DK${numbers}${letters}`;
}

// Function to generate random ID for Servis (DS + 2 numbers + 3 letters)
function generateServisId() {
    const numbers = Math.floor(Math.random() * 90 + 10); // 10-99
    const letters = Array(3).fill()
        .map(() => String.fromCharCode(Math.floor(Math.random() * 26) + 65))
        .join('');
    return `DS${numbers}${letters}`;
}

// Auto-fill IDs when opening modals
komponenBtn.addEventListener('click', () => {
    document.getElementById('id_data_komponen').value = generateKomponenId();
    komponenModal.style.display = 'block';
});

servisBtn.addEventListener('click', () => {
    document.getElementById('id_data_servis').value = generateServisId();
    servisModal.style.display = 'block';
});
