/**
 * Archivo principal de JavaScript para el tema
 */

// Importar los módulos
import './image-handler.js';

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
  // Código de inicialización global
  console.log('Tema Maneras de Vivir cargado');
  
  // Inicializar detección de tema oscuro/claro
  initDarkModeDetection();
  
  // Inicializar menú móvil
  initMobileMenu();
});

/**
 * Inicializar la detección de modo oscuro/claro
 */
function initDarkModeDetection() {
  // Detectar preferencia del sistema
  const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
  
  // Comprobar si ya hay una preferencia guardada
  const currentTheme = localStorage.getItem('theme');
  
  // Aplicar tema oscuro si está guardado o si el sistema lo prefiere
  if (currentTheme === 'dark' || (!currentTheme && prefersDarkScheme.matches)) {
    document.documentElement.classList.remove('light');
    document.documentElement.classList.add('dark');
    document.body.classList.add('dark');
  } else {
    document.documentElement.classList.add('light');
    document.documentElement.classList.remove('dark');
    document.body.classList.remove('dark');
  }
  
  // Escuchar cambios en la preferencia del sistema
  prefersDarkScheme.addEventListener('change', (e) => {
    if (!localStorage.getItem('theme')) {
      if (e.matches) {
        document.documentElement.classList.remove('light');
        document.documentElement.classList.add('dark');
        document.body.classList.add('dark');
      } else {
        document.documentElement.classList.add('light');
        document.documentElement.classList.remove('dark');
        document.body.classList.remove('dark');
      }
    }
  });
  
  // Añadir toggle de tema oscuro/claro si existe el botón
  const themeToggleBtn = document.getElementById('theme-toggle');
  if (themeToggleBtn) {
    // Actualizar el ícono según el tema actual
    updateThemeToggleIcon(document.body.classList.contains('dark'));
    
    themeToggleBtn.addEventListener('click', () => {
      const isDark = document.body.classList.contains('dark');
      if (isDark) {
        document.documentElement.classList.add('light');
        document.documentElement.classList.remove('dark');
        document.body.classList.remove('dark');
        localStorage.setItem('theme', 'light');
      } else {
        document.documentElement.classList.remove('light');
        document.documentElement.classList.add('dark');
        document.body.classList.add('dark');
        localStorage.setItem('theme', 'dark');
      }
      
      // Actualizar el ícono
      updateThemeToggleIcon(!isDark);
    });
  }
}

/**
 * Actualiza el icono del botón de cambio de tema
 * @param {boolean} isDark - Si el tema actual es oscuro
 */
function updateThemeToggleIcon(isDark) {
  const themeToggleBtn = document.getElementById('theme-toggle');
  if (!themeToggleBtn) return;
  
  // Cambiar el icono según el tema actual
  if (isDark) {
    themeToggleBtn.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
      </svg>
    `;
  } else {
    themeToggleBtn.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
      </svg>
    `;
  }
}

/**
 * Inicializar el menú móvil
 */
function initMobileMenu() {
  const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
  const mobileMenu = document.querySelector('.mobile-menu');
  
  if (mobileMenuToggle && mobileMenu) {
    mobileMenuToggle.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
    });
  }
}
