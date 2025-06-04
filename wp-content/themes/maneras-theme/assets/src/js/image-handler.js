/**
 * Manejador de imágenes responsivas
 * 
 * - Carga perezosa (lazy loading) de imágenes
 * - Detección de soporte para WebP
 * - Manejo de fallbacks para navegadores antiguos
 */

document.addEventListener('DOMContentLoaded', function() {
  // Detectar soporte para WebP
  detectWebP();
  
  // Inicializar carga perezosa
  initLazyLoading();

  // Inicializar lightbox para galerías si existe
  if (typeof lightGallery !== 'undefined') {
    initLightbox();
  }
});

/**
 * Detecta si el navegador soporta imágenes WebP
 */
function detectWebP() {
  const webP = new Image();
  webP.src = 'data:image/webp;base64,UklGRjoAAABXRUJQVlA4IC4AAACyAgCdASoCAAIALmk0mk0iIiIiIgBoSygABc6WWgAA/veff/0PP8bA//LwYAAA';
  
  webP.onload = webP.onerror = function() {
    const isWebPSupported = (webP.height === 2);
    document.body.classList.add(isWebPSupported ? 'webp' : 'no-webp');
  };
}

/**
 * Inicializa la carga perezosa (lazy loading) de imágenes
 */
function initLazyLoading() {
  // Si el navegador ya soporta lazy loading nativo, no hacemos nada
  if ('loading' in HTMLImageElement.prototype) {
    // Configurar todas las imágenes lazy como nativas
    document.querySelectorAll('img[data-src]').forEach(img => {
      img.src = img.dataset.src;
      if (img.dataset.srcset) {
        img.srcset = img.dataset.srcset;
      }
      img.classList.add('loaded');
    });
    return;
  }

  // Para navegadores sin soporte nativo, usamos IntersectionObserver
  if ('IntersectionObserver' in window) {
    const lazyImageObserver = new IntersectionObserver(function(entries, observer) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          const lazyImage = entry.target;
          
          if (lazyImage.dataset.src) {
            lazyImage.src = lazyImage.dataset.src;
          }
          
          if (lazyImage.dataset.srcset) {
            lazyImage.srcset = lazyImage.dataset.srcset;
          }
          
          lazyImage.classList.add('loaded');
          lazyImageObserver.unobserve(lazyImage);
        }
      });
    });

    document.querySelectorAll('img[data-src]').forEach(function(lazyImage) {
      lazyImageObserver.observe(lazyImage);
    });
  } else {
    // Fallback para navegadores sin soporte para IntersectionObserver
    let lazyImages = [].slice.call(document.querySelectorAll('img[data-src]'));
    let active = false;

    const lazyLoad = function() {
      if (active === false) {
        active = true;

        setTimeout(function() {
          lazyImages.forEach(function(lazyImage) {
            if ((lazyImage.getBoundingClientRect().top <= window.innerHeight && lazyImage.getBoundingClientRect().bottom >= 0) && getComputedStyle(lazyImage).display !== 'none') {
              if (lazyImage.dataset.src) {
                lazyImage.src = lazyImage.dataset.src;
              }
              
              if (lazyImage.dataset.srcset) {
                lazyImage.srcset = lazyImage.dataset.srcset;
              }
              
              lazyImage.classList.add('loaded');
              
              lazyImages = lazyImages.filter(function(image) {
                return image !== lazyImage;
              });

              if (lazyImages.length === 0) {
                document.removeEventListener('scroll', lazyLoad);
                window.removeEventListener('resize', lazyLoad);
                window.removeEventListener('orientationchange', lazyLoad);
              }
            }
          });

          active = false;
        }, 200);
      }
    };

    document.addEventListener('scroll', lazyLoad);
    window.addEventListener('resize', lazyLoad);
    window.addEventListener('orientationchange', lazyLoad);
    lazyLoad();
  }
}

/**
 * Inicializa lightbox para galerías de imágenes
 */
function initLightbox() {
  const galleries = document.querySelectorAll('.image-gallery');
  
  if (galleries.length) {
    galleries.forEach(gallery => {
      lightGallery(gallery, {
        selector: '.image-gallery-item a',
        download: false,
        counter: true,
        zoom: true
      });
    });
  }
}
