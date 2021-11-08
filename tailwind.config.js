module.exports = {
  mode: "jit",
  purge: [
    "./apps/frontend/templates/*.twig",
    "./apps/frontend/pages/**/*.twig",
    "./apps/frontend/pages/**/*.php",
  ],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {
      colors: {
        main: {
          dark: "#1B1907",
        },
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
