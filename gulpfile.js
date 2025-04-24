import gulp from 'gulp';
import gulpSass from 'gulp-sass';
import * as sass from 'sass';
import autoprefixer from 'autoprefixer';
import cssnano from 'cssnano';
import postcss from 'gulp-postcss';
import sourcemaps from 'gulp-sourcemaps';
import imagemin from 'gulp-imagemin';
import svgmin from 'gulp-svgmin';
import imageminMozjpeg from 'imagemin-mozjpeg';
import imageminOptipng from 'imagemin-optipng';
import webp from 'gulp-webp';
import terser from 'gulp-terser-js';
import concat from 'gulp-concat';
import { deleteSync } from 'del'; 
import notify from 'gulp-notify';
import eslint from 'gulp-eslint';
import seo from 'gulp-seo';

const sassCompiler = gulpSass(sass);
const { src, dest, watch, parallel, series } = gulp;

// Rutas configuradas
const paths = {
    scss: 'src/scss/**/*.scss',
    js: 'src/js/**/*.js',
    images: 'src/img/**/*',
    php: 'views/**/*.php',
    pngJpgImages: 'src/img/**/*.{png,jpg,jpeg,JPG}',
    heicImages: 'src/img/**/*.{heic,HEIC}',
    svgImages: 'src/img/**/*.svg',
    icoFiles: 'src/img/**/*.ico',
    build: 'public/build/',
};

// Manejo de errores
function handleError(err) {
    console.error(`[Error]: ${err.toString()}`);
    this.emit('end');
}

// Limpieza de la carpeta build
function clean() {
    return deleteSync([paths.build]);
}

// Compilar y optimizar CSS
function css(isProduction = false) {
    const outputStyle = isProduction ? 'compressed' : 'expanded';
    return src(paths.scss)
        .pipe(sourcemaps.init())
        .pipe(sassCompiler({ outputStyle: outputStyle }).on('error', handleError))
        .pipe(postcss([
            autoprefixer({ overrideBrowserslist: ['last 2 versions', 'not dead'] }),
            isProduction && cssnano(), // Minificar solo en producción
        ].filter(Boolean)))
        .pipe(sourcemaps.write('.'))
        .pipe(dest(`${paths.build}css`))
        .pipe(notify({ message: 'CSS procesado correctamente!', onLast: true }));
}

// Lint y minificación de JS
function lintJS() {
    return src(`${paths.build}build/js/main.js`)
        .pipe(eslint())
        .pipe(eslint.format())
        .pipe(eslint.failAfterError());
}

function minifyJS() {
    return src(paths.js)
        .pipe(sourcemaps.init())
        .pipe(concat('main.js'))
        .pipe(terser().on('error', handleError))
        .pipe(sourcemaps.write('.'))
        .pipe(dest(`${paths.build}js`))
        .pipe(notify({ message: 'JavaScript procesado y minificado!', onLast: true }));
}

// Optimizar imágenes PNG y JPG
function optimizeImages() {
    return src(paths.pngJpgImages)
        .pipe(imagemin([
            imageminMozjpeg({ quality: 85}),
            imageminOptipng({ optimizationLevel: 5 }),
        ]))
        .pipe(dest(`${paths.build}img`))
        .pipe(notify({ message: '¡Imágenes PNG y JPG optimizadas!', onLast: true }));
}

// Optimizar archivos SVG
function optimizeSVG() {
    return src(paths.svgImages)
        .pipe(svgmin())
        .pipe(dest(`${paths.build}img`))
        .pipe(notify({ message: '¡Archivos SVG optimizados!', onLast: true }));
}

// Copiar archivos ICO
function copyIcons() {
    return src(paths.icoFiles)
        .pipe(dest(`${paths.build}img`))
        .pipe(notify({ message: '¡Archivos ICO copiados!', onLast: true }));
}

// Convertir imágenes a formato WebP
function convertToWebp() {
    return src(paths.pngJpgImages)
        .pipe(webp({ quality: 80 }))
        .pipe(dest(`${paths.build}img`))
        .pipe(notify({ message: '¡Imágenes convertidas a WebP!', onLast: true }));
}

// Convertir imágenes HEIC a formato WebP
function convertHeicToWebp() {
    return src(paths.heicImages)
        .pipe(webp({ quality: 80 })) 
        .pipe(dest(`${paths.build}img`))
        .pipe(notify({ message: '¡Imágenes HEIC convertidas a WebP!', onLast: true }));
}

// Copiar archivos HEIC sin optimizar
function copyHeic() {
    return src(paths.heicImages)
        .pipe(dest(`${paths.build}img`))
        .pipe(notify({ message: '¡Imágenes HEIC copiadas sin optimizar!', onLast: true }));
}

// Optimización SEO para archivos PHP
function seoOptimization() {
    return src(paths.php)
        .pipe(seo({
            siteName: 'IEEE Web Site',
            robots: 'index, follow',
            canonical: 'https://yourwebsite.com',
        }))
        .pipe(dest('./logs/reports/seo'))
        .pipe(notify({ message: 'SEO optimizado para archivos PHP!', onLast: true }));
}

// Automatizar tareas en desarrollo
function dev() {
    watch(paths.scss, css);
    watch(paths.js, minifyJS);
}

// Build final para producción
function productionBuild() {
    return series(
        clean,
        parallel(
            () => css(true), // CSS comprimido
            minifyJS,        // Minificación de JS
            seoOptimization  // Optimización SEO
        )
    );
}

// Exports
export const build = productionBuild();
export { css, minifyJS, optimizeImages, optimizeSVG, copyIcons, convertToWebp, dev, clean, lintJS, seoOptimization, convertHeicToWebp, copyHeic };
export default dev;