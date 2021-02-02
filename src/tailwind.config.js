const colors = require('tailwindcss/colors')

module.exports = {
  purge: [],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {},
    colors: {
      // Build your palette here
      transparent: 'transparent',
      current: 'currentColor',
      base: {
        light: '#6EE7B7',
        default: '#34D399',
        dark: '#10B981',
      },
      gray: colors.trueGray,
      red: colors.red,
      blue: colors.lightBlue,
      yellow: colors.amber,
      white: colors.white,
    },
  },
  variants: {
    extend: {},
  },
  plugins: [],
}
