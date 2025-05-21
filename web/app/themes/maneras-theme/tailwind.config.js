/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
      './resources/views/**/*.twig',
      './assets/src/**/*.{js,css}'
    ],
  theme: {
    extend: {
      colors: {
        primary: 'var(--color-primary)',
        accent: 'var(--color-accent)',
        bg: 'var(--color-bg)',
      },
      container: {
        center: true,
        padding: '1rem',
      },
    },
  },
  plugins: [],
}
