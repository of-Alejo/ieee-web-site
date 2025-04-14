import gulp from 'gulp';
import gulpSass from 'gulp-sass';
import * as sass from 'sass';
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
import { deleteSync } from 'del'; 
import notify from 'gulp-notify';
import eslint from 'gulp-eslint';
import purgecss from '@fullhuman/postcss-purgecss';
import seo from 'gulp-seo';

const sassCompiler = gulpSass(sass);
const { src, dest, watch, parallel, series } = gulp;

const paths = {
    scss: 'src/scss/**/*.scss',
    js: 'src/js/**/*.js',
    php: 'views/**/*.php',
    images: 'src/img/**/*',
    build: 'public/build/',
};

// Manejo de errores
function handleError(err) {
    console.error(err.toString());
    this.emit('end');
}

// Limpieza de la carpeta build
function clean() {
    return deleteSync([paths.build]);
}

// Compilar y minificar CSS
function css(isProduction = false) {
    const outputStyle = isProduction ? 'compressed' : 'expanded';
    return src(paths.scss)
        .pipe(sourcemaps.init())
        .pipe(sassCompiler({ outputStyle }).on('error', handleError))
        .pipe(postcss([
            autoprefixer({ overrideBrowserslist: ['last 2 versions', 'not dead'] }),
            cssnano(),
            isProduction && purgecss({
                content: [paths.php, paths.js],
            })
        ].filter(Boolean)))
        .pipe(sourcemaps.write('.'))
        .pipe(dest(`${paths.build}css`))
        .pipe(notify({ message: 'CSS procesado correctamente!', onLast: true }));
}

// Minificar y concatenar JS
function javascript() {
    return src(paths.js)
        .pipe(sourcemaps.init())
        .pipe(concat('main.js'))
        .pipe(terser().on('error', handleError))
        .pipe(sourcemaps.write('.'))
        .pipe(dest(`${paths.build}js`))
        .pipe(notify({ message: 'JavaScript minificado!', onLast: true }));
}

// Optimizar imágenes
function images() {
    return src(paths.images)
        .pipe(cache(imagemin({ optimizationLevel: 3 })))
        .pipe(dest(`${paths.build}img`))
        .pipe(notify({ message: 'Imágenes optimizadas!', onLast: true }));
}

// Convertir imágenes a formato WebP
function versionWebp() {
    return src('src/img/**/*.{png,jpg}')
        .pipe(webp({ quality: 70 }))
        .pipe(dest(`${paths.build}img`))
        .pipe(notify({ message: 'Imágenes convertidas a WebP!', onLast: true }));
}

// Convertir imágenes a formato AVIF
function versionAvif() {
    return src('src/img/**/*.{png,jpg}')
        .pipe(avif({ quality: 70 }))
        .pipe(dest(`${paths.build}img`))
        .pipe(notify({ message: 'Imágenes convertidas a AVIF!', onLast: true }));
}

// Verificar calidad del código JS (ESLint)
function lintJS() {
    return src(paths.js)
        .pipe(eslint())
        .pipe(eslint.format())
        .pipe(eslint.failAfterError());
}

// Optimizar SEO para archivos PHP en views
function seoOptimization() {
    return src(paths.php) // Ahora apunta a la carpeta views
        .pipe(seo({
            siteName: 'IEEE Web Site',
            robots: 'index, follow',
            canonical: 'http://localhost:3000',
        }))
        .pipe(dest('./reports/seo'))
        .pipe(notify({ message: 'SEO optimizado para archivos PHP!', onLast: true }));
}

// Automatizar tareas en desarrollo
function dev() {
    watch(paths.scss, css);
    watch(paths.js, javascript);
    watch(paths.images, series(images, versionWebp, versionAvif));
    watch(paths.php, seoOptimization); // Vigila cambios en PHP dentro de views
}

// Build final para producción
function productionBuild() {
    return series(
        clean,
        parallel(
            () => css(true), // CSS comprimido con PurgeCSS
            javascript,
            images,
            versionWebp,
            versionAvif,
            seoOptimization
        )
    );
}

// Exports
export const build = productionBuild();
export { css, javascript as js, images, versionWebp, versionAvif, dev, clean, lintJS, seoOptimization };
export default dev;