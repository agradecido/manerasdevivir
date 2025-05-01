module.exports = {
  content: [
    "./views/**/*.blade.php",
    "./resources/views/**/*.blade.php",
    "./resources/**/*.js",
    "./*.php",   
    "./src/**/*.php"
  ],
  theme: {
    extend: {
      colors: {
        'bg': '#0D0D0D',
        'bg-secondary': '#1E2428',
        'hs': '#cc6600',
        'links': '#ff6600',
        'text': '#cccccc',
      },
    },
  },
  plugins: [
  ],
}
