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
            fontSize: {
                'nav': '15px',    // nav menu items  (text-[15px])
                'badge': '10px',  // small badges/tags (text-[10px])
                'micro': '11px',  // tiny labels      (text-[11px])
            },
            spacing: {
                'nav-y': '18px',  // nav link vertical padding (py-[18px])
                '15pct': '15%',   // banner element offset     (left/right-[15%])
            },
            lineHeight: {
                'content': '1.8', // article body text (leading-[1.8])
            },
            width: {
                'dropdown': '250px', // nav dropdown menu width (w-[250px])
            },
            boxShadow: {
                'dropdown': '0 4px 15px rgba(0,0,0,0.3)',  // nav dropdown
                'card': '0 8px 25px rgba(0,0,0,0.25)',     // card hover
                'soft': '0 4px 15px rgba(0,0,0,0.15)',     // subtle elevation
                'nav': '0 2px 10px rgba(0,0,0,0.2)',       // sticky nav bar
            },
            zIndex: {
                'modal': '200',
                'dropdown': '60',
                'overlay': '55',
            },
            colors: {
                brand: {
                    yellow: '#FFD87F',
                    blue: '#1e3c72',
                    pink: '#ff9f8e',
                    red: '#af1a00',
                    'red-medium': '#a82200',
                    'red-dark': '#8a1500',
                    'red-darker': '#6b1000',
                    'red-light': '#fecaca',
                    'red-lightest': '#fef2f2',
                    orange: '#ff6d54',
                    gold: '#cba351',
                    dark: '#2d2921',
                },
            },
        },
    },

    plugins: [forms],
};
