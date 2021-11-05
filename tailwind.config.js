const defaultTheme = require("tailwindcss/defaultTheme");

module.exports = {
  mode: "jit",
  purge: [
    "./apps/frontend/templates/*.twig",
    "./apps/frontend/views/**/*.twig",
    "./apps/frontend/views/**/*.php",
  ],
  darkMode: false, // or 'media' or 'class'
  theme: {
    screens: {
      m2xl: { max: "1535px" },
      // => @media (max-width: 1535px) { ... }

      mxl: { max: "1279px" },
      // => @media (max-width: 1279px) { ... }

      mlg: { max: "1023px" },
      // => @media (max-width: 1023px) { ... }

      mmd: { max: "767px" },
      // => @media (max-width: 767px) { ... }

      msm: { max: "639px" },
      ...defaultTheme.screens,
    },
    fontFamily: {
      roboto: ["Roboto Slab", ...defaultTheme.fontFamily.serif],
      zen: ["Zen Maru Gothic", ...defaultTheme.fontFamily.sans],
    },
    extend: {
      colors: {
        main: {
          dark: "#1B1907",
        },
        primary: "#F38118",
        secondary: "#FF5C70",
      },
      boxShadow: {
        primary: "0px 15px 50px -12px rgba(242,169,96,1)",
      },
    },
  },
  variants: {
    extend: {},
  },
  plugins: [
    require("@tailwindcss/typography"),
    require("@tailwindcss/forms"),
    require("@tailwindcss/line-clamp"),
    require("@tailwindcss/aspect-ratio"),
  ],
};
