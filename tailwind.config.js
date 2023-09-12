/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {},
    fontFamily: {
      'inter': ['Inter'],
      'josefin': ['JosefinSans'],
      'pacifico': ['Pacifico'],
      'lato': ['Lato'],
      'poppins': ['Poppins'],
      'circularStd': ['CircularStd'],
      'plusJakartaSans': ['Plus Jakarta Sans'],
      'ggSans': ['GG Sans'],
      'GlacialIndifference': ['Glacial Indifference'],
    }
  },
  plugins: [],
}

