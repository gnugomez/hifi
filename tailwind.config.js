module.exports = {
  mode: "jit",
  purge: [
    "./apps/frontend/templates/*.twig",
    "./apps/frontend/views/**/*.twig",
    "./apps/frontend/views/**/*.php",
  ],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {},
  },
  variants: {
    extend: {},
  },
  plugins: [],
};
