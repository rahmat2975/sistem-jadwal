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
        brand: {
          50: '#F4F2FF',
          100: '#EEF2FF',
          200: '#E0E7FF',
          300: '#C7D2FE',
          400: '#818CF8',
          500: '#6366F1',
          600: '#5B50E5',
          700: '#4F46E5',
          800: '#4338CA',
          900: '#3730A3',
          dark: '#161A30',
        },
        canvas: '#F4F5F9',
        card: '#FFFFFF',
        'card-subtle': '#F9FAFC',
        muted: '#8A94A6',
        subtle: '#E5E7EB',
      },
      fontFamily: {
        sans: ['"Poppins"', 'ui-sans-serif', 'system-ui', '-apple-system', 'sans-serif'],
      },
      boxShadow: {
        'card': '0px 4px 20px -2px rgba(91, 80, 229, 0.05), 0px 2px 6px -1px rgba(27, 33, 61, 0.03)',
        'card-hover': '0px 10px 28px -4px rgba(91, 80, 229, 0.12), 0px 4px 10px -2px rgba(27, 33, 61, 0.04)',
        'hero': '0 20px 25px -5px rgba(91, 80, 229, 0.25), 0 8px 10px -6px rgba(91, 80, 229, 0.2)',
      },
      borderRadius: {
        '2xl': '1rem',
        '3xl': '1.5rem',
      }
    },
  },
  plugins: [],
}
