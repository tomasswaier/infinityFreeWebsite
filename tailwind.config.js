const defaultTheme = require('tailwindcss/defaultTheme');
const forms = require('@tailwindcss/forms');

module.exports = {
  content :
          [
            './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
            './storage/framework/views/*.php',
            './resources/views/**/*.blade.php',
            './public/js/*.js',
            './public/css/*.css',
            './**/*.blade.php',
            './**/*.html',
            './**/*.js',
            './**/*.vue',

          ],
  theme : {
    extend : {
      fontFamily : {
        sans : [ 'Figtree', ...defaultTheme.fontFamily.sans ],
      },
      colors : {
        'project-white' : '#EFEFEF',
        'project-light-blue' : '#B1E6F3',
        'project-blue' : '#72DDF7',
        'project-dark-blue' : '#79B8F4',
        'project-super-blue' : '#8093F1',
      },
    },
  },
  plugins : [ forms ],
};
