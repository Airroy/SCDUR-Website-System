import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    yellow: '#FFD87F',
                    blue: '#1e3c72',
                    pink: '#ff9f8e',
                    red: '#af1a00',
                    orange: '#ff6d54',
                    gold: '#cba351',
                },
            },
        },
    },

    plugins: [forms],
};
