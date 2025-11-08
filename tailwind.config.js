/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'spotify-black': '#121212',
        'spotify-dark': '#181818',
        'spotify-gray': '#1a1a1a',
        'spotify-green': '#1db954',
        'spotify-text': '#b3b3b3',
        'soundcloud-orange': '#ff5500',
      },
      fontFamily: {
        sans: ['Inter', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}

