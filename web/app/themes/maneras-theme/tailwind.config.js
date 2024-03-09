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
  ],
};

export default config;
