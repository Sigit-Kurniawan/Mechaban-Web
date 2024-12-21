//UNTUK DROPDOWN
document.addEventListener("DOMContentLoaded", () => {
  const komponenSelect = document.querySelector(".komponen-combobox");
  const servisContainers = document.querySelectorAll(".servis-container");

  // Event listener untuk perubahan dropdown komponen
  komponenSelect.addEventListener("change", (event) => {
    const selectedKomponen = event.target.value;

    // Sembunyikan semua container servis
    servisContainers.forEach((container) => {
      container.style.display = "none";
    });

    // Tampilkan container yang sesuai dengan komponen terpilih
    const selectedContainer = document.querySelector(
      `.servis-container[data-komponen="${selectedKomponen}"]`
    );
    if (selectedContainer) {
      selectedContainer.style.display = "block";
    }
  });
});

// Alert class for managing notifications
class Alert {
  static DURATION = 5000;

  static create(type, message, title = '') {
      let alertContainer = document.getElementById('alertContainer');
      if (!alertContainer) {
          alertContainer = document.createElement('div');
          alertContainer.id = 'alertContainer';
          alertContainer.className = 'alert-container';
          document.body.appendChild(alertContainer);
      }

      const alert = document.createElement('div');
      alert.className = `alert alert-${type}`;
      
      let icon = type === 'success' ? 'checkmark-circle-outline' : 'alert-circle-outline';

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

      alertContainer.appendChild(alert);

      const dismissBtn = alert.querySelector('.alert-dismiss');
      dismissBtn.addEventListener('click', () => this.dismiss(alert));

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
  try {
      const urlParams = new URLSearchParams(window.location.search);
      const successType = urlParams.get('success');
      const errorType = urlParams.get('error');
      
      const messageMap = {
          success: {
              booking: 'Booking servis berhasil dilakukan.',
              location: 'Lokasi berhasil ditentukan.'
          },
          error: {
              booking: 'Gagal melakukan booking servis. Silakan coba lagi.',
              location: 'Gagal menentukan lokasi. Silakan coba lagi.',
              validation: 'Mohon lengkapi semua field yang diperlukan.'
          }
      };

      if (successType && messageMap.success[successType]) {
          Alert.create('success', messageMap.success[successType], 'Berhasil!');
      }
      
      if (errorType && messageMap.error[errorType]) {
          Alert.create('danger', messageMap.error[errorType], 'Gagal!');
      }

      window.history.replaceState({}, document.title, window.location.pathname);
  } catch (error) {
      console.error('Error showing alert:', error);
  }

  
  if (urlParams.has('error')) {
      const errorType = urlParams.get('error');
      let message = '';
      let title = 'Gagal!';
      
      switch(errorType) {
          case 'booking':
              message = 'Gagal melakukan booking servis. Silakan coba lagi.';
              break;
          case 'location':
              message = 'Gagal menentukan lokasi. Silakan coba lagi.';
              break;
          case 'validation':
              message = 'Mohon lengkapi semua field yang diperlukan.';
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

