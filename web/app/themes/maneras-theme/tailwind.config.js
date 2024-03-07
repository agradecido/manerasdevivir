/** @type {import('tailwindcss').Config} */
import forms from '@tailwindcss/forms';

const config = {
    content: [
      './app/**/*.php',
      './resources/**/*.{php,vue,js}',
    ],
    theme: {
      extend: {
        colors: {
          'gray': {
            '50': '#f7f7f7',
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
          'black': {
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
          'blue': {
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
          'orange': {
            '50': '#fff8ec',
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
      function ({addComponents}) {
        addComponents({
          // User
          '.fa-user': {
            content: '"\\f007"',
            fontFamily: 'FontAwesome',
            marginRight: '0.5rem',
            fontSize: '.75rem',
          },
          // RRSS
          '.fa-facebook': {
            content: '"\\f39e"',
            fontFamily: 'FontAwesome',
            marginRight: '0.5rem',
            fontSize: '.75rem',
          },
          '.fa-twitter': {
            content: '"\\f099"',
            fontFamily: 'FontAwesome',
            marginRight: '0.5rem',
            fontSize: '.75rem',
          },
          '.fa-instagram': {
            content: '"\\f16d"',
            fontFamily: 'FontAwesome',
            marginRight: '0.5rem',
            fontSize: '.75rem',
          },
          '.fa-linkedin': {
            content: '"\\f08c"',
            fontFamily: 'FontAwesome',
            marginRight: '0.5rem',
            fontSize: '.75rem',
          },
          // Date Time
          '.fa-calendar': {
            content: '"\\f133"',
            fontFamily: 'FontAwesome',
            marginRight: '0.5rem',
            fontSize: '.75rem',
          },
          '.fa-calendar-alt': {
            content: '"\\f073"',
            fontFamily: 'FontAwesome',
            marginRight: '0.5rem',
            fontSize: '.75rem',
          },
          '.fa-clock': {
            content: '"\\f017"',
            fontFamily: 'FontAwesome',
            marginRight: '0.5rem',
            fontSize: '.75rem',
          },
          // More icons
          '.fa-users': { content: '"\\f0c0"' },
          '.fa-skull': { content: '"\\f54c"' },
          '.fa-house': { content: '"\\f015"' },
          '.fa-magnifying-glass': { content: '"\\f002"' },
          '.fa-image': { content: '"\\f03e"' },
          '.fa-download': { content: '"\\f019"' },
          '.fa-phone': { content: '"\\f095"' },
          '.fa-music': { content: '"\\f001"' },
          '.fa-envelope': { content: '"\\f0e0"' },
          '.fa-location-dot': { content: '"\\f3c5"' },
          '.fa-star': { content: '"\\f005"' },
          '.fa-heart': { content: '"\\f004"' },
          '.fa-arrow-right': { content: '"\\f061"' },
          '.fa-arrow-left': { content: '"\\f060"' },
          '.fa-arrow-up': { content: '"\\f062"' },
          '.fa-arrow-down': { content: '"\\f063"' },
          '.fa-camera-retro': { content: '"\\f083"' },
          '.fa-calendar-days': { content: '"\\f073"' },
          '.fa-clipboard': { content: '"\\f328"' },
          '.fa-bolt': { content: '"\\f0e7"' },
          '.fa-circle-up': { content: '"\\f35b"' },
          '.fa-circle-down': { content: '"\\f358"' },
          '.fa-circle-right': { content: '"\\f35a"' },
          '.fa-circle-left': { content: '"\\f359"' },
          '.fa-rotate-right': { content: '"\\f2f9"' }, // o fa-redo
          '.fa-rotate-left': { content: '"\\f2ea"' }, // o fa-undo
          '.fa-video': { content: '"\\f03d"' },
          '.fa-camera': { content: '"\\f030"' },
          '.fa-pen-to-square': { content: '"\\f044"' },
          '.fa-share': { content: '"\\f064"' },
          '.fa-plane': { content: '"\\f072"' },
          '.fa-hand': { content: '"\\f256"' }, // Verifica este, podría ser diferente
          '.fa-folder': { content: '"\\f07b"' },
          '.fa-thumbs-up': { content: '"\\f164"' },
          '.fa-wifi': { content: '"\\f1eb"' },
          '.fa-sliders': { content: '"\\f1de"' },
          '.fa-address-book': { content: '"\\f2b9"' },
          '.fa-layer-group': { content: '"\\f5fd"' },
        });
      }
    ],
  }
;

export default config;
