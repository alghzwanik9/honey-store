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
            colors: {
                'brand': {
                    'navy': '#0f172a',      // الكحلي الملكي
                    'navy-light': '#1e293b',
                    'gold': '#c29b40',      // الذهبي الأساسي
                    'gold-light': '#e5c16b', // ذهبي فاتح للهوفر
                    'gold-dark': '#a47e2a',
                }
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                cairo: ['Cairo', 'sans-serif'],
            },
        },
    },

    plugins: [forms],
};
