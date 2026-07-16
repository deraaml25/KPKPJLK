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
                    DEFAULT: '#4B3F9E',
                    light: '#7B6FD8',
                    soft: '#EEEBFB',
                },
                surface: '#FFFFFF',
                background: '#F6F5FC',
                ink: '#1E1B33',
                muted: '#6B6785',
                success: '#2E9E6B',
                warning: '#D9A02C',
                danger: '#D9534F',
                border: '#E4E1F5',
            },
            boxShadow: {
                'floating': '0 8px 24px rgba(75,63,158,0.08)',
            },
            borderRadius: {
                'card': '20px',
                'btn': '12px',
            }
        },
    },

    plugins: [forms],
};
