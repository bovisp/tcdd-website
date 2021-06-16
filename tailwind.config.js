module.exports = {
  theme: {
    screens: {
      'md': '640px',
      'lg': '768px',
      'xl': '1024px'
    },
    extend: {},
    zIndex: {
      '0': 0,
      '10': 10,
      '20': 20,
      '30': 30,
      '40': 40,
      '50': 50,
      '100': 100,
      'auto': 'auto',
    }
  },
  variants: {
    borderStyle: ['responsive', 'hover', 'focus']
  },
  plugins: [
    require('@tailwindcss/custom-forms')
  ],
}