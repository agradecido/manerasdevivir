import '../css/app.css';
import feather from 'feather-icons'


const btnOpen  = document.getElementById('mobile-menu-button');
const btnClose = document.getElementById('mobile-menu-close');
const menu     = document.getElementById('mobile-menu');

if (btnOpen && btnClose && menu) {
  // Abrir menu.
  btnOpen.addEventListener('click', () => {
    menu.classList.remove('translate-x-full');
    menu.classList.add('translate-x-0');
    btnOpen.classList.add('hidden');
  });

  // Cerrar menu.
  btnClose.addEventListener('click', () => {
    menu.classList.remove('translate-x-0');
    menu.classList.add('translate-x-full');
    btnOpen.classList.remove('hidden');
  });
} else {
  console.warn('Mobile menu elements not found:', { btnOpen, btnClose, menu });
}

// once DOM is ready, replace all <i data-feather> with inline SVG
document.addEventListener('DOMContentLoaded', () => {
  feather.replace({ 'stroke-width': '1' })
})
