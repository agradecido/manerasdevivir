/** @type {import('tailwindcss').Config} */
const config = {
  content: ['./app/**/*.php', './resources/**/*.{php,vue,js}'],
  theme: {
    extend: {
      colors: {
        'text': '#cccccc',
        'bg': '#0D0D0D',
        'links': '#8AB0BF',
        'hs': '#ff6600',
      },
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
    // Añadir FontAwesome como un plugin
    function({ addComponents }) {
      addComponents({
        '.fa-user': {
          content: '"\\f007"',
          fontFamily: 'FontAwesome',
        },
        // Aquí puedes seguir añadiendo más íconos según sea necesario
      });
    }
  ],
};

export default config;
