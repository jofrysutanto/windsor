module.exports = {
  purge: [
    'src/**/*.vue',
    'src/**/*.js',
  ],
  important: '#windsor',
  theme: {
    extend: {
      height: {
        'acf-header-height': '47px',
      },
      colors: {
        'wp-blue': {
          'medium': '#00A0D2',
          'default': '#0073AA',
        },
        'acf-border': {
          'default': '#ccd0d4',
        }
      },
      minHeight: {
        'wp-body': 'calc(100vh - 32px)',
      },
    },
  },
  variants: {},
  plugins: [
    require('@tailwindcss/ui'),
  ]
}
