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
          'text': {
            '50':  '#f7f7f7',
            '100': '#ededed',
            '200': '#dfdfdf',
            '300': '#cccccc',
            '400': '#adadad',
            '500': '#999999',
            '600': '#888888',
            '700': '#7b7b7b',
            '800': '#676767',
            '900': '#545454',
            '950': '#363636',
          },
          'background': {
            '50': '#f6f6f6',
            '100': '#e7e7e7',
            '200': '#d1d1d1',
            '300': '#b0b0b0',
            '400': '#888888',
            '500': '#6d6d6d',
            '600': '#5d5d5d',
            '700': '#4f4f4f',
            '800': '#454545',
            '900': '#0d0d0d',
            '950': '#000000',
          },
          'links': {
            '50': '#edf0ff',
            '100': '#dfe2ff',
            '200': '#c5caff',
            '300': '#a1a6ff',
            '400': '#807cfd',
            '500': '#6e5df7',
            '600': '#5f3fec',
            '700': '#5d3fd3',
            '800': '#432ba8',
            '900': '#392b84',
            '950': '#23194d',
          },
          'headings': {
            '50':  '#fff8ec',
            '100': '#fff0d3',
            '200': '#ffdca5',
            '300': '#ffc26d',
            '400': '#ff9d32',
            '500': '#ff7f0a',
            '600': '#ff6600',
            '700': '#cc4902',
            '800': '#a1390b',
            '900': '#82310c',
            '950': '#461604',
          },
      },
    },
  },
  plugins: [
    forms,
    require
('@tailwindcss/typography'),
  // FontAwesome as plugin.
  function ({addComponents}) {
    addComponents({
      '.fa-user': {
        content: '"\\f007"',
        fontFamily: 'FontAwesome',
        marginRight: '0.5rem',
        fontSize: '.75rem',
      },
    });
  }
],
}
;

export default config;
