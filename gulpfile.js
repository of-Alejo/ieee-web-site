import gulp from 'gulp';
import gulpSass from 'gulp-sass';
import * as sass from 'sass'; // Sass como compilador
import autoprefixer from 'autoprefixer';
import cssnano from 'cssnano';
import postcss from 'gulp-postcss';
import sourcemaps from 'gulp-sourcemaps';
import cache from 'gulp-cache';
import imagemin from 'gulp-imagemin';
import webp from 'gulp-webp';
import avif from 'gulp-avif';
import terser from 'gulp-terser-js';
import concat from 'gulp-concat';
import del from 'del'; // Para limpiar el caché y directorios
import notify from 'gulp-notify';
import eslint from 'gulp-eslint';

const sassCompiler = gulpSass(sass);
const { src, dest, watch, parallel, series } = gulp;

const paths = {
    scss: 'src/scss/**/*.scss',
    js: 'src/js/**/*.js',
    imagenes: 'src/img/**/*',
    build: 'public/build/',
};

// Manejo de errores
function handleError(err) {
    console.error(err.toString());
    this.emit('end');
}

// Limpieza de la carpeta de build
function clean() {
    return del([paths.build]);
}

// Compilar y minificar CSS
function css() {
    return src(paths.scss)
        .pipe(sourcemaps.init())
        .pipe(sassCompiler({ outputStyle: 'compressed' }).on('error', handleError))
        .pipe(postcss([autoprefixer({ overrideBrowserslist: ['last 2 versions', 'not dead'] }), cssnano()]))
        .pipe(sourcemaps.write('.'))
        .pipe(dest(`${paths.build}css`))
        .pipe(notify({ message: 'CSS compilado y minificado!', onLast: true }));
}

// Minificar y concatenar JavaScript
function javascript() {
    return src(paths.js)
        .pipe(sourcemaps.init())
        .pipe(concat('main.js'))
        .pipe(terser().on('error', handleError))
        .pipe(sourcemaps.write('.'))
        .pipe(dest(`${paths.build}js`))
        .pipe(notify({ message: 'JavaScript minificado!', onLast: true }));
}

// Optimización de imágenes
function imagenes() {
    return src(paths.imagenes)
        .pipe(cache(imagemin({ optimizationLevel: 3 })))
        .pipe(dest(`${paths.build}img`))
        .pipe(notify({ message: 'Imágenes optimizadas!', onLast: true }));
}

// Convertir imágenes a WebP
function versionWebp() {
    return src('src/img/**/*.{png,jpg}')
        .pipe(webp({ quality: 70 }))
        .pipe(dest(`${paths.build}img`))
        .pipe(notify({ message: 'Imágenes convertidas a WebP!', onLast: true }));
}

// Convertir imágenes a AVIF
function versionAvif() {
    return src('src/img/**/*.{png,jpg}')
        .pipe(avif({ quality: 70 }))
        .pipe(dest(`${paths.build}img`))
        .pipe(notify({ message: 'Imágenes convertidas a AVIF!', onLast: true }));
}

// Linting para JS
function lintJS() {
    return src(paths.js)
        .pipe(eslint())
        .pipe(eslint.format())
        .pipe(eslint.failAfterError());
}

// Clear Cache
function clearCache() {
    return cache.clearAll();
}

// Automatización
function dev() {
    watch(paths.scss, css);
    watch(paths.js, javascript);
    watch(paths.imagenes, series(imagenes, versionWebp, versionAvif));
}

// Build final para producción
export const finalBuild = series(
    clean,
    parallel(css, javascript, imagenes, versionWebp, versionAvif),
);

export { css, javascript as js, imagenes, versionWebp, versionAvif, dev, clearCache, lintJS };

// Desarrollo: tarea por defecto
export default dev;