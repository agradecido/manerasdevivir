// resources/tailwind.config.js
import { fontFamily } from 'tailwindcss/defaultTheme'

export default {
  content: [
    './resources/views/**/*.blade.php',
    './resources/**/*.js',
    './*.php',
    './src/**/*.php',
  ],
  darkMode: 'class',   // por si algún día añadimos modo claro
  theme: {
    extend: {
      colors: {
        bg: '#0D0D0D',
        surface: '#1E2428',
        primary: '#E56600',
        'primary-h': '#FF781A',
        text: '#CCCCCC',
        'text-sub': '#8B8B8B',
        border: '#2B2B2B',
      },
      fontFamily: {
        sans: ['"Barlow Condensed"', ...fontFamily.sans],  // headline look & feel
        body: ['Inter', ...fontFamily.sans],
      },
      spacing: {
        'nav-h': '4.5rem',   // altura fija en mobile / desktop
      },
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
  ],
}
