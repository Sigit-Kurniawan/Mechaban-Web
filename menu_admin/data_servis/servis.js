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
    const numbers = String(Math.floor(Math.random() * 90 + 10)).padStart(2, '0'); // 10-99
    const letters = Array(3).fill()
        .map(() => String.fromCharCode(Math.floor(Math.random() * 26) + 65))
        .join('');
    return `DK${numbers}${letters}`;
}

// Function to generate random ID for Servis (DS + 2 numbers + 3 letters)
function generateServisId() {
    const numbers = String(Math.floor(Math.random() * 90 + 10)).padStart(2, '0'); // 10-99
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

function openEditKomponenModal(id, nama) {
    console.log('Attempting to open Komponen edit modal');
    console.log('Edit Komponen - ID:', id, 'Nama:', nama);
    
    const titleElement = document.getElementById('komponenModalTitle');
    const idElement = document.getElementById('id_data_komponen');
    const namaElement = document.getElementById('nama_komponen');
    const editIdElement = document.getElementById('edit_id_data_komponen');
    
    if (titleElement) titleElement.textContent = 'Edit Komponen';
    if (idElement) idElement.value = id;
    if (namaElement) namaElement.value = nama;
    if (editIdElement) editIdElement.value = id;
    
    if (komponenModal) {
        komponenModal.style.display = 'block';
    } else {
        console.error('Komponen modal not found');
    }
}

function openEditServisModal(id, nama, harga, komponen) {
    console.log('Attempting to open Servis edit modal');
    console.log('Edit Servis - ID:', id, 'Nama:', nama, 'Harga:', harga, 'Komponen:', komponen);
    
    const titleElement = document.getElementById('servisModalTitle');
    const idElement = document.getElementById('id_data_servis');
    const namaElement = document.getElementById('nama_servis');
    const hargaElement = document.getElementById('harga');
    const komponenElement = document.getElementById('komponen');
    const editIdElement = document.getElementById('edit_id_data_servis');
    
    if (titleElement) titleElement.textContent = 'Edit Servis';
    if (idElement) idElement.value = id;
    if (namaElement) namaElement.value = nama;
    if (hargaElement) hargaElement.value = harga;
    if (komponenElement) komponenElement.value = komponen;
    if (editIdElement) editIdElement.value = id;
    
    if (servisModal) {
        servisModal.style.display = 'block';
    } else {
        console.error('Servis modal not found');
    }
}

// Ensure the functions are globally accessible
window.openEditKomponenModal = openEditKomponenModal;
window.openEditServisModal = openEditServisModal;

function showDeleteModal(deleteUrl, itemName = '') {
    const modal = document.querySelector('.delete-modal');
    const confirmButton = modal.querySelector('.confirm-delete');
    const cancelButton = modal.querySelector('.cancel-delete');
    const message = modal.querySelector('.delete-modal-message');
    
    // Update message if item name is provided
    if (itemName) {
        message.textContent = `Apakah Anda yakin ingin menghapus "${itemName}"? Tindakan ini tidak dapat dibatalkan.`;
    }

    // Show modal with animation
    modal.style.display = 'block';
    setTimeout(() => modal.classList.add('active'), 10);

    // Set the correct delete URL
    confirmButton.href = deleteUrl;

    // Handle cancel button click
    const hideModal = () => {
        modal.classList.remove('active');
        setTimeout(() => modal.style.display = 'none', 200);
    };

    cancelButton.onclick = hideModal;

    // Close modal when clicking outside
    modal.onclick = (e) => {
        if (e.target === modal) {
            hideModal();
        }
    };

    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideModal();
        }
    });
}

// Update delete buttons to use modal
document.querySelectorAll('.btn-hapus').forEach(button => {
    button.addEventListener('click', (e) => {
        e.preventDefault();
        const deleteUrl = button.getAttribute('href');
        const row = button.closest('tr');
        const itemName = row.cells[1].textContent.trim(); // Get name from second column
        showDeleteModal(deleteUrl, itemName);
    });
});

class Alert {
    static DURATION = 5000; // Duration in milliseconds before auto-dismiss

    static create(type, message, title = '') {
        const container = document.getElementById('alertContainer');
        const alert = document.createElement('div');
        alert.className = `alert alert-${type}`;
        
        let icon = '';
        if (type === 'success') {
            icon = 'checkmark-circle-outline';
        } else if (type === 'danger') {
            icon = 'alert-circle-outline';
        }

        alert.innerHTML = `
            <div class="alert-icon">
                <ion-icon name="${icon}"></ion-icon>
            </div>
            <div class="alert-content">
                ${title ? `<div class="alert-title">${title}</div>` : ''}
                <p class="alert-message">${message}</p>
            </div>
            <button class="alert-dismiss" aria-label="Dismiss">
                <ion-icon name="close-outline"></ion-icon>
            </button>
        `;

        container.appendChild(alert);

        // Add dismiss button functionality
        const dismissBtn = alert.querySelector('.alert-dismiss');
        dismissBtn.addEventListener('click', () => this.dismiss(alert));

        // Auto dismiss after duration
        setTimeout(() => this.dismiss(alert), this.DURATION);

        return alert;
    }

    static dismiss(alert) {
        if (!alert.classList.contains('fade-out')) {
            alert.classList.add('fade-out');
            setTimeout(() => {
                alert.remove();
            }, 300);
        }
    }
}

// Function to show alert from PHP success/error parameters
function showAlertFromParams() {
    const urlParams = new URLSearchParams(window.location.search);
    
    if (urlParams.has('success')) {
        const successType = urlParams.get('success');
        let message = '';
        let title = 'Berhasil!';
        
        switch(successType) {
            case 'delete_servis':
                message = 'Data servis berhasil dihapus.';
                break;
            case 'delete_komponen':
                message = 'Data komponen berhasil dihapus.';
                break;
            case 'save_servis':
                message = 'Data servis berhasil disimpan.';
                break;
            case 'save_komponen':
                message = 'Data komponen berhasil disimpan.';
                break;
            case 'edit_servis':
                message = 'Data servis berhasil diperbarui.';
                break;
            case 'edit_komponen':
                message = 'Data komponen berhasil diperbarui.';
                break;
        }
        
        if (message) {
            Alert.create('success', message, title);
        }
    }
    
    if (urlParams.has('error')) {
        const errorType = urlParams.get('error');
        let message = '';
        let title = 'Gagal!';
        
        switch(errorType) {
            case 'delete_servis':
                message = 'Gagal menghapus servis: Data masih digunakan dalam transaksi lain.';
                break;
            case 'delete_komponen':
                message = 'Gagal menghapus komponen: Komponen masih digunakan dalam data servis.';
                break;
        }
        
        if (message) {
            Alert.create('danger', message, title);
        }
    }

    // Clear URL parameters without refreshing
    window.history.replaceState({}, document.title, window.location.pathname);
}

// Show alerts when page loads
document.addEventListener('DOMContentLoaded', showAlertFromParams);