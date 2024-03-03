/** @type {import('tailwindcss').Config} config */
const config = {
  content: ['./app/**/*.php', './resources/**/*.{php,vue,js}'],
  theme: {
    extend: {
      colors: {
        // 'text': '#cccccc',
        // 'bg': '#0D0D0D',
        // 'links': '#8AB0BF',
        // 'hs': '#ff6600',
        'text': '#0d0d0d',
        'bg': '#fff',
        'links': '#8AB0BF',
        'hs': '#ff6600',
      },
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
  ],
};
export default config;
