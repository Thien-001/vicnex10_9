const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    // Quét toàn bộ file Blade để tree-shaking class Tailwind không dùng
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './resources/**/*.ts',
        './resources/**/*.jsx',
        './resources/**/*.tsx',
    ],
    
    darkMode: 'class', // hoặc 'media' nếu muốn dựa theo hệ thống OS

    theme: {
        extend: {
            fontFamily: {
                // Bạn có thể thêm font tuỳ chỉnh tại đây
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Ví dụ mở rộng thêm màu custom
                primary: {
                    DEFAULT: '#2563eb', // blue-600
                    light: '#3b82f6',   // blue-500
                    dark: '#1e40af',    // blue-800
                },
                danger: {
                    DEFAULT: '#dc2626', // red-600
                },
            },
        },
    },

    plugins: [
        // Nếu bạn cần forms hoặc typography thì thêm:
        // require('@tailwindcss/forms'),
        // require('@tailwindcss/typography'),
        // require('@tailwindcss/aspect-ratio'),
    ],
};
