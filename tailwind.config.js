/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          // pink colors from tailwindcss
          "50": "rgb(253 242 248)",
          "100": "rgb(252 231 243)",
          "200": "rgb(251 207 232)",
          "300": "rgb(249 168 212)",
          "400": "rgb(244 114 182)",
          "500": "rgb(236 72 153)",
          "600": "rgb(219 39 119)",
          "700": "rgb(190 24 93)",
          "800": "rgb(157 23 77)",
          "900": "rgb(131 24 67)",
          "950": "rgb(190 24 93)"
        },
        danger: {
          "50": "#fff8f8",
          "100": "#ffefef",
          "200": "#ffd7d7",
          "300": "#ffafaf",
          "400": "#ff7d7d",
          "500": "#ff4a4a",
          "600": "#ff1f1f",
          "700": "#ff0b0b",
          "800": "#e60000",
          "900": "#c50000",
          "950": "#8c0000"
        }
      }
    },
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
      'IBM': ['IBM Plex Sans'],
    }
  },
  plugins: [
      require('@tailwindcss/forms'),
  ],
}

