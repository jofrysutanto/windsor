module.exports = {
  purge: [
    'src/**/*.vue',
    'src/**/*.js',
  ],
  important: '#windsor',
  theme: {
    extend: {
      colors: {
        'wp-blue': {
          'medium': '#00A0D2',
          'default': '#0073AA',
        },
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
