/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,jsx}"
  ],
  safelist: [
    'flex-col',
    'flex-row',
    'flex-row-reverse',
    'sm:flex-col',
    'md:flex-row',
    'md:flex-row-reverse',
    'sm:grid-cols-3',
    'md:grid-cols-4',
    'lg:grid-cols-5',
    'sm:w-full',
    'md:w-[65%]',
    'sm:px-4',
    'md:px-12',
    'sm:text-4xl',
    'md:text-[52px]',
    'sm:pt-[5rem]',
    'md:pt-[7rem]',
    'sm:flex-1',
    'sm:max-w-full',
    'md:max-w-[420px]',
    'md:max-w-[380px]',
    'sm:p-4',
    'md:p-12',
  ],
  theme: {
    extend: {
      fontFamily: {
        playfair: ['"Playfair Display"', 'serif'],
        raleway: ['Raleway', 'sans-serif'],
        inter: ['Inter', 'sans-serif'],
        mono: ['"DM Mono"', 'monospace'],
      },
    },
  },
  plugins: [],
}