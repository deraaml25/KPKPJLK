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
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
                mono: ['"IBM Plex Mono"', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                primary: {
                    DEFAULT: '#95BBEA', // Cornflower
                    light: '#AECDF4',
                    soft: '#FFFFFF', // White
                    accent: '#930500', // Sangria
                },
                surface: '#FFFFFF', // White
                background: '#FFFFFF', // White
                ink: '#1A2E4B', // Dark Blue
                muted: '#5A6F82',
                success: '#2E9E6B',
                warning: '#D9A02C',
                danger: '#D9534F',
                border: '#EFEBD3',
            },
            boxShadow: {
                'floating': '0 8px 24px rgba(15,60,101,0.08)',
            },
            borderRadius: {
                'card': '20px',
                'btn': '12px',
            }
        },
    },

    plugins: [forms],
};
