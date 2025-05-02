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
    container: {
      center: true,          // siempre centrado
      screens: {
        DEFAULT: '100%',     // full‑width en ≤ sm
        lg: '1024px',        // a partir de 1024 px
        xl: '1280px',        // y/o 1440‑1600 según diseño
      },
    },
    extend: {
      colors: {
        bg: '#0D0D0D',
        surface: '#1E2428',
        primary: '#E56600',
        'primary-h': '#FF781A',
        text: '#CCCCCC',
        'text-sub': '#8B8B8B',
        border: '#2B2B2B',
        maxWidth: {
          site: 'var(--site-width)',
        },
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

