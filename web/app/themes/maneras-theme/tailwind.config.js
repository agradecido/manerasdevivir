/** @type {import('tailwindcss').Config} */
import forms from '@tailwindcss/forms';

const config = {
  content: [
    './app/**/*.php',
    './resources/**/*.{php,vue,js}',
    './vendor/brandymedia/turbine-ui-core/**/*.php',
  ],
  theme: {
    extend: {
      colors: {
        'text': '#cccccc',
        'bg': '#0D0D0D',
        'bg-secondary': '#1E2428',
        'links': '#8AB0BF',
        'hs': '#ff6600',
      },
    },
  },
  plugins: [
    forms,
    require('@tailwindcss/typography'),
    // Añadir FontAwesome como un plugin
    function({ addComponents }) {
      addComponents({
        '.fa-user': {
          content: '"\\f007"',
          fontFamily: 'FontAwesome',
          marginRight: '0.5rem',
          fontSize: '.75rem',
        },
        // Aquí puedes seguir añadiendo más íconos según sea necesario
      });
    }
  ],
};

export default config;
