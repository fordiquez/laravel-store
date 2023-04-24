const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './vendor/filament/**/*.blade.php',
        './node_modules/flowbite/**/*.js',
    ],

    darkMode: 'class',
    theme: {
        letterSpacing: {
            tightest: '-.075em',
            tighter: '-.05em',
            tight: '-.025em',
            normal: '0',
            wide: '.05em',
            wider: '.15em',
            widest: '.25em',
        },
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            rotate: {
                '-1': '-1deg',
                '-2': '-2deg',
                '-3': '-3deg',
                1: '1',
                2: '2deg',
                3: '3deg',
            },
            borderRadius: {
                xl: '0.8rem',
                xxl: '1rem',
            },
            height: {
                '1/2': '0.125rem',
                '2/3': '0.1875rem',
            },
            maxWidth: {
                '8xl': '1440px',
                '9xl': '1600px',
            },
            maxHeight: {
                16: '16rem',
                20: '20rem',
                24: '24rem',
                32: '32rem',
            },
            inset: {
                '1/2': '50%',
            },
            width: {
                96: '24rem',
                104: '26rem',
                128: '32rem',
            },
            gridTemplateRows: {
                '[auto,auto,1fr]': 'auto auto 1fr',
            },
            transitionDelay: {
                450: '450ms',
            },
            colors: {
                danger: colors.rose,
                primary: colors.purple,
                success: colors.green,
                warning: colors.yellow,
            },
        },
        screens: {
            '2xs': '360px',
            xs: '480px',
            ...defaultTheme.screens,
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('@tailwindcss/aspect-ratio'),
        require('flowbite/plugin'),
        require('tailwind-scrollbar'),
    ],
};
