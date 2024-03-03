/** @type {import('tailwindcss').Config} config */
const config = {
  content: ['./app/**/*.php', './resources/**/*.{php,vue,js}'],
  theme: {
    extend: {
      colors: {
        primary: {
          100: '#aa6600',
          200: '#bb6600',
          300: '#cc6600',
          400: '#dd6600',
          500: '#ee6600',
          600: '#ff6600',
        }
      }, // Extend Tailwind's default colors
    },
  },
  plugins: [],
};

export default config;
