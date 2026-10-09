import defaultTheme from 'tailwindcss/defaultTheme'
import forms from '@tailwindcss/forms'

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        navy: { DEFAULT: '#0B2C5C', 700: '#0E3A77', 900: '#071D3D' },
        orange: { DEFAULT: '#FF8C00', 600: '#E67E00' },
        ink: '#0F172A',
        canvas: '#F8FAFC',
        muted: '#64748B',
        line: '#E2E8F0',
        success: '#10B981',
        warning: '#F59E0B',
        danger: '#EF4444',
      },
      fontFamily: {
        sans: ['Inter', ...defaultTheme.fontFamily.sans],
        display: ['"Instrument Serif"', 'Georgia', 'serif'],
      },
      borderRadius: { DEFAULT: '12px', xl: '12px', '2xl': '16px' },
      boxShadow: { glass: '0 8px 32px rgba(11,44,92,.08)' },
      transitionTimingFunction: { rise: 'cubic-bezier(.16,1,.3,1)' },
      keyframes: {
        rise: { '0%': { opacity: '0', transform: 'translateY(16px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
        float: { '0%, 100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-10px)' } },
        'float-left': { '0%, 100%': { transform: 'translate(0, 0)' }, '50%': { transform: 'translate(4px, -8px)' } },
        'float-right': { '0%, 100%': { transform: 'translate(0, 0)' }, '50%': { transform: 'translate(-4px, -8px)' } },
        'pulse-slow': { '0%, 100%': { opacity: '1' }, '50%': { opacity: '.5' } },
      },
      animation: {
        rise: 'rise .7s cubic-bezier(.16,1,.3,1) both',
        float: 'float 4s ease-in-out infinite',
        'float-left': 'float-left 5s ease-in-out infinite',
        'float-right': 'float-right 5s ease-in-out 1s infinite',
        'pulse-slow': 'pulse-slow 6s ease-in-out infinite',
      },
    },
  },
  plugins: [forms],
}
