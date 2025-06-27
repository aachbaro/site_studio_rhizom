/** @type {import('tailwindcss').Config} */
export default {
  content: ['./index.html', './src/**/*.{vue,js,ts,jsx,tsx}'],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Manrope', 'sans-serif']
      },
      colors: {
        primary: '#00FF99',
        background: '#0A0A0A',
        foreground: '#FFFFFF',
        muted: '#999999'
      }
    }
  },
  plugins: []
}
