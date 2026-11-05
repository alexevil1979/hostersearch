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
                brand: {
                    50: '#eff6ff',
                    100: '#dbeafe',
                    200: '#bfdbfe',
                    300: '#93c5fd',
                    400: '#60a5fa',
                    500: '#3b82f6',
                    600: '#2563eb',
                    700: '#1d4ed8',
                    800: '#1e3a8a',
                    900: '#0f274f',
                    950: '#0a1a33',
                },
                accent: {
                    DEFAULT: '#0ea5e9',
                    dark: '#0284c7',
                },
            },
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
                display: ['"Fraunces"', ...defaultTheme.fontFamily.serif],
            },
            boxShadow: {
                soft: '0 10px 40px -12px rgba(15, 39, 79, 0.18)',
            },
        },
    },

    plugins: [forms],
};
