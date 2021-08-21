module.exports = {
    purge: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.twig',
    ],
    darkMode: false, // or 'media' or 'class'
    theme: {
        extend: {
            colors: {
                'green-ultra-light': '#D1FAE5',
                'green-light': '#3cdb9a',
                'green-mid': '#399470',
                'green-dark': '#2F7056',

                'gold-500': "#FFDC73",
                'gold-600': "#FFCF40",
                'gold-700': "#FFBF00",
                'gold-800': "#BF9B30",
                'gold-900': "#A67C00",
            }
        },
    },
    variants: {
        extend: {},
    },

}
