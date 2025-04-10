import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        '!./app/Filament/**/{User,Web}/**/*.php',
        './resources/views/**/*.blade.php',
        '!./resources/views/**/{user,web}/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
}
