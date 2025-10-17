/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'ganaderasoft': {
          'celeste': '#6EC1E4',
          'verde': '#B3D335',
          'azul': '#007B92',
          'negro': '#333333',
        }
      },
      fontFamily: {
        'sans': ['Nunito', 'system-ui', 'sans-serif'],
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
