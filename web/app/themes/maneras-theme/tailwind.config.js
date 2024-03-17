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
        'bg': '#0D0D0D',
        'bg-secondary': '#1E2428',
        'hs': '#cc6600',
        'links': '#ff6600',
        'text': '#cccccc',

      },
    },
  },
  plugins: [
    forms,
    require('@tailwindcss/typography'),
  ],
};

export default config;
