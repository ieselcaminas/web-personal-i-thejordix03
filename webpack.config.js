const Encore = require('@symfony/webpack-encore');

Encore
    // directorio donde se guardarán los archivos compilados
    .setOutputPath('public/build/')
    // ruta pública usada por el servidor web para acceder a ellos
    .setPublicPath('/build')
    // entrada principal del proyecto (app.js y app.css)
    .addEntry('app', './assets/app.js')
    // limpia la carpeta antes de cada compilación
    .cleanupOutputBeforeBuild()
    // muestra notificaciones y mejora la depuración
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    // permite usar Sass/SCSS si lo necesitas
    .enableSassLoader()
    // compatibilidad con ES6 y navegadores antiguos
    .configureBabel(() => {}, {
        useBuiltIns: 'usage',
        corejs: 3
    })
    // ✅ ESTA LÍNEA ARREGLA EL ERROR
    .enableSingleRuntimeChunk()
;

module.exports = Encore.getWebpackConfig();
