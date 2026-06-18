import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js', // Added so Tailwind scans your extracted JS files
    ],

    theme: {
        extend: {
            fontFamily: {
                // Merges Inter with Tailwind's default sans fallback fonts
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    // Defined as a raw RGB string so Tailwind can append /opacity
                    // This is the approximate equivalent to your lab() color
                    DEFAULT: 'rgb(42 58 236)',
                }
            },
            aspectRatio: {
                '4/3': '4 / 3',
            },
            boxShadow: {
                'soft': '0 4px 20px -2px rgba(0, 0, 0, 0.05)',
                'soft-lg': '0 10px 30px -3px rgba(0, 0, 0, 0.08)',
            }
        },
    },

    plugins: [forms,require('@tailwindcss/typography')],
};
