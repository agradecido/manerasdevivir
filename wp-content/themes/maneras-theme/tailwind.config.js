/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
      './templates/**/*.twig',
      './assets/src/**/*.{js,css}'
    ],
  theme: {
    extend: {
      colors: {
        primary: 'var(--color-primary)',
        'primary-h': 'var(--color-primary-h)',
        accent: 'var(--color-accent)',
        bg: 'var(--color-bg)',
        text: 'var(--color-text)',
        'text-subtle': 'var(--color-text-subtle)',
        surface: 'var(--color-surface)',
        border: 'var(--color-border)',
      },
      fontFamily: {
        title: ['"Bebas Neue"', 'sans-serif'],
        body: ['Inter', 'sans-serif'],
        alt: ['"Space Grotesk"', 'sans-serif'],
      },
      maxWidth: {
        'content-width': 'var(--content-width)',
        'site-width': 'var(--site-width)',
      },
      container: {
        center: true,
        padding: '1rem',
      },     
    },
  },
  plugins: [],
}
