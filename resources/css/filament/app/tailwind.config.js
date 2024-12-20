import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    theme: {
        extend: {
          colors: {
            'green': 'var(--green)',
            'green': 'var(--green)',
            'blue': 'var(--blue)',
            'dark-blue': 'var(--dark-blue)',
            'orange': 'var(--orange)',
            'dark-orange': 'var(--dark-orange)',
            'light-grey': 'var(--light-grey)',
            'grey': 'var(--grey)',
            'lightgreen': 'var(--lightgreen)',
            'hyellow': 'var(--hyellow)',
            'light-orange': 'var(--light-orange)'
          },
          fontFamily: {
            montserrat: ['"Montserrat"', "sans-serif"],
          },
        },
      },
}
