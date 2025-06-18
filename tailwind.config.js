/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './assets/css/**/*.{css,scss}',
    './templates/**/*.{php,js}',
    './admin/js/**/*.js',
    './public/js/**/*.js',
    './includes/**/*.php',
    './core/**/*.php',
  ],
  plugins: [require('daisyui')],
  daisyui: {
    themes: ["dim"],
  },
  important: '#wpbody-content',
};
