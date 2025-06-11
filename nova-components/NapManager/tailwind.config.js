/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/**/*.{js,vue,blade.php}',
    './src/**/*.{php,blade.php}',
  ],
  theme: {
    extend: {},
  },
  prefix: 'nap-',
  corePlugins: {
    preflight: false,
  },
  plugins: [],
  // Ensure Tailwind doesn't purge classes that might be used dynamically
  safelist: [
    {
      pattern: /nap-bg-(red|green|blue|yellow|gray|purple)-(100|200|300|400|500|600|700|800|900)/,
      variants: ['hover', 'focus'],
    },
    {
      pattern: /nap-text-(red|green|blue|yellow|gray|purple)-(100|200|300|400|500|600|700|800|900)/,
      variants: ['hover', 'focus'],
    },
    {
      pattern: /nap-border-(red|green|blue|yellow|gray|purple)-(100|200|300|400|500|600|700|800|900)/,
      variants: ['hover', 'focus'],
    },
  ],
}
