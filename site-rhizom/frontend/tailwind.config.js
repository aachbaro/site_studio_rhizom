// tailwind.config.js
module.exports = {
  content: [
    './index.html',
    './src/**/*.{vue,js,ts,jsx,tsx}',
  ],
  theme: {
    extend: {
      colors: {
        background: '#FFFFFF',
        foreground: '#000000',
        primary: '#00FF99',
      },
      fontFamily: {
        halogen: ['Halogen', 'sans-serif'],
        sans: ['Manrope', 'sans-serif'],
      },
    },
  },
  plugins: [],
}
