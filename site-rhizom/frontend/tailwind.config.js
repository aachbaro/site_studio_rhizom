// tailwind.config.js
module.exports = {
  content: ["./index.html", "./src/**/*.{vue,js,ts,jsx,tsx}"],
  theme: {
    extend: {
      screens: {
        landscape: { raw: "(orientation: landscape)" },
      },
      colors: {
        background: "#FFFFFF",
        foreground: "#000000",
        primary: "#00FF99",
      },
      fontFamily: {
        urw: ['"urw-form"', "ui-sans-serif", "system-ui", "sans-serif"],
        real: [
          '"ff-real-text-pro"',
          "ui-sans-serif",
          "system-ui",
          "sans-serif",
        ],
      },
      transitionDuration: {
        2000: "2000ms",
        5000: "5000ms",
      },
    },
  },
  plugins: [],
};
