// tailwind.config.js
module.exports = {
  content: ["./index.html", "./src/**/*.{vue,js,ts,jsx,tsx}"],
  theme: {
    extend: {
      colors: {
        background: "#FFFFFF",
        foreground: "#000000",
        primary: "#00FF99",
      },
      fontFamily: {
        poppins: ["Arial", "sans-serif"],
        halogen: ["Halogen", "sans-serif"],
      },
    },
  },
  plugins: [],
};
