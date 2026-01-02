/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                // Palette TerangaMbay
                'mbay': {
                    50: '#E8F5E9',
                    100: '#C8E6C9',
                    200: '#A5D6A7',
                    300: '#81C784',
                    400: '#66BB6A',
                    500: '#2E7D32', // Vert Mbay principal
                    600: '#27692A',
                    700: '#1B5E20',
                    800: '#145218',
                    900: '#0D3B10',
                },
                'teranga': {
                    50: '#FFF8E1',
                    100: '#FFECB3',
                    200: '#FFE082',
                    300: '#FFD54F',
                    400: '#FFCA28',
                    500: '#F9A825', // Or Teranga principal
                    600: '#F57F17',
                    700: '#EF6C00',
                    800: '#E65100',
                    900: '#BF360C',
                },
                'sillon': {
                    50: '#EFEBE9',
                    100: '#D7CCC8',
                    200: '#BCAAA4',
                    300: '#A1887F',
                    400: '#8D6E63',
                    500: '#4E342E', // Brun Sillon principal
                    600: '#3E2723',
                    700: '#321911',
                    800: '#261309',
                    900: '#1A0D06',
                },
                'nuage': '#F1F8E9', // Blanc Nuage
            },
            fontFamily: {
                sans: ['Inter', 'system-ui', 'sans-serif'],
            },
        },
    },
    plugins: [],
}