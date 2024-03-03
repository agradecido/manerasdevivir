/** @type {import('tailwindcss').Config} config */
const config = {
  content: ['./app/**/*.php', './resources/**/*.{php,vue,js}'],
  theme: {
    extend: {
      colors: {
        'text': '#F2EADF',
        'bg': '#0D0D0D',
        'links': '#8AB0BF',
        'hs': '#F25C05',
      },
    },
  },
  plugins: [],
  corePlugins: {
    // Extender la base de estilos para <p>
    typography: ({theme}) => ({
      DEFAULT: {
        css: {
          'p': {
            color: theme('colors.text'), // Usa el color definido en la sección extend
          },
          'a': {
            color: theme('colors.links'),
            '&:hover': {
              color: theme('colors.hs'),
            }
          },
          'h1': {
            color: theme('colors.hs'),
          },
        },
      },
    }),
  },
};
export default config;
