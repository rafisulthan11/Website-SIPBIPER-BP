import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    safelist: [
        'bg-indigo-600',
        'hover:bg-indigo-700',
        'bg-yellow-500', // <-- TAMBAHKAN INI
        'hover:bg-yellow-600', // <-- TAMBAHKAN INI
        'bg-gradient-to-r',
        'from-purple-50',
        'to-purple-100',
        'border-purple-600',
        'bg-purple-600',
        'text-purple-700',
        'from-emerald-50',
        'to-emerald-100',
        'border-emerald-600',
        'bg-emerald-600',
        'text-emerald-700',
        'from-amber-50',
        'to-amber-100',
        'border-amber-600',
        'bg-amber-600',
        'text-amber-700',
    ],
    
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
