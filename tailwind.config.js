/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./*.{html,js,php}"],
  theme: {
    extend: {
      colors: {
        primary: "#0697f2",
      },
      fontFamily: {
        pixel: ["Montserrat"],
      },
    },
  },
  plugins: [],
};
