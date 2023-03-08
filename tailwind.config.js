/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.css",
    "./resources/**/*.js",
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/auth/*.blade.php",
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/layouts/*.blade.php"
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
