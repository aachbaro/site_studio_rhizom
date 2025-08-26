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
        poppins: ["ff-real-text-pro", "sans-serif"],
        halogen: ["urw-form", "sans-serif"],
        real: ['"ff-real-text-pro"', "sans-serif"],
        urw: ['"urw-form"', "sans-serif"],
      },
      transitionDuration: {
        2000: "2000ms",
        5000: "5000ms",
      },
    },
  },
  plugins: [],
};
