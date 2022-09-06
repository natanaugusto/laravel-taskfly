const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/rappasoft/laravel-livewire-tables/resources/views/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Nunito", ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [require("@tailwindcss/forms")],
    safelist: [
        "bg-green-600",
        "bg-green-700",
        "ring-green-500",
        "bg-red-600",
        "bg-red-700",
        "ring-red-500",
        "bg-gray-200",
        "text-gray-700",
        "bg-gray-50",
        "border-gray-300"
    ],
};
