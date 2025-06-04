/**
 * Script para el formulario de envío de noticias
 */
document.addEventListener('DOMContentLoaded', function() {
  const form = document.querySelector('form[enctype="multipart/form-data"]');
  
  if (form) {
    // Efecto de loading al enviar el formulario
    form.addEventListener('submit', function(e) {
      const submitButton = form.querySelector('button[type="submit"]');
      
      if (submitButton) {
        submitButton.classList.add('loading');
        submitButton.querySelector('span').textContent = 'Enviando...';
      }
    });
    
    // Validación de imágenes
    const fileInput = form.querySelector('input[type="file"]');
    if (fileInput) {
      fileInput.addEventListener('change', function() {
        const maxFileSize = 1024 * 1024; // 1MB
        const files = this.files;
        let valid = true;
        
        for (let i = 0; i < files.length; i++) {
          if (files[i].size > maxFileSize) {
            valid = false;
            alert(`La imagen "${files[i].name}" supera 1MB. Por favor, selecciona una imagen más pequeña.`);
            this.value = '';
            break;
          }
        }
        
        // Feedback visual si las imágenes son válidas
        if (valid && files.length > 0) {
          const uploadArea = document.querySelector('.file-upload-area');
          if (uploadArea) {
            uploadArea.style.borderColor = 'var(--color-primary)';
            uploadArea.insertAdjacentHTML('beforeend', 
            `<div class="mt-2 text-sm text-primary">${files.length} archivo(s) seleccionado(s)</div>`);
          }
        }
      });
    }
    
    // Mejora de accesibilidad para los campos requeridos
    const requiredLabels = form.querySelectorAll('.required-field');
    requiredLabels.forEach(label => {
      const input = document.getElementById(label.getAttribute('for'));
      if (input) {
        input.setAttribute('aria-required', 'true');
      }
    });
  }
});
