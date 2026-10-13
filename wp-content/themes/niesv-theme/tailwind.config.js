/**
 * Tailwind build for the NIESV theme. Run `npm run build` after changing classes
 * in any template, and commit the generated assets/css/tailwind.css.
 * (Replaces the cdn.tailwindcss.com script, which compiled CSS in every visitor's browser.)
 */
module.exports = {
  content: [
    './*.php',
    './template-parts/**/*.php',
    './includes/**/*.php',
    './admin/**/*.php',
    './assets/js/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        'niesv-primary': 'rgba(0, 0, 40, 1)',
        'niesv-secondary': '#e7edf4',
        'niesv-text-primary': '#0d141c',
        'niesv-text-secondary': '#49739c',
        'niesv-background': '#f8fafc',
        'niesv-accent-gold': '#d4af37',
      },
      fontFamily: {
        newsreader: ['Newsreader', 'serif'],
        'noto-sans': ['Noto Sans', 'sans-serif'],
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
    require('@tailwindcss/container-queries'),
  ],
};
