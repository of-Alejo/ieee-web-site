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
import notify from 'gulp-notify';
import eslint from 'gulp-eslint';
import seo from 'gulp-seo';
import fs from 'fs-extra';
import path from 'path';
import javascriptObfuscator from 'gulp-javascript-obfuscator';
import browserSync from 'browser-sync';
import rev from 'gulp-rev';
import revDel from 'gulp-rev-delete-original';
import sitemap from 'gulp-sitemap';
import debug from 'gulp-debug';

const sassCompiler = gulpSass(sass);
const { src, dest, watch, parallel, series } = gulp;
const server = browserSync.create();

// Rutas configuradas para los archivos
const paths = {
    scss: 'src/scss/**/*.scss',
    js: 'src/js/**/*.js',
    images: 'src/img/**/*',
    php: 'views/**/*.php',
    pngJpgImages: 'src/img/**/*.{png,jpg,jpeg}',
    heicImages: 'src/img/**/*.{heic,HEIC}',
    svgImages: 'src/img/**/*.svg',
    icoFiles: 'src/img/**/*.ico',
    build: 'web/build/',
};

// Archivos y carpetas a copiar a la carpeta project
const filesToCopy = [
    './controllers/',
    './includes/',
    './models/',
    './views/',
    './web/',
    './vendor/',
    './.env',
    './.htaccess',
    './composer.json',
    './composer.lock',
    './package.json',
    './Router.php',
];

// Manejo de errores
function handleError(err) {
    notify.onError({
        title: "Error en Gulp",
        message: `Error: ${err.message}`,
        sound: "Beep",
    })(err);
    console.error(`[Error]: ${err.toString()}`);
    this.emit('end');
}

// Limpieza de la carpeta build
async function clean() {
    const del = (await import('del')).deleteSync; // Importación dinámica
    del([paths.build]);
}

// Limpieza de la carpeta project
async function cleanProduction() {
    const del = (await import('del')).deleteSync; // Importación dinámica
    del(['project']);
}

// Limpieza de todas las carpetas
function cleanAll() {
    return del([paths.build, 'project']);
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

// Minificar y ofuscar JavaScript
function minifyAndObfuscateJS() {
    return src(paths.js)
        .pipe(sourcemaps.init())
        .pipe(concat('main.js')) // Combinar todos los archivos JS en uno
        .pipe(terser({
            compress: {
                drop_console: true, // Eliminar console.log
            },
            output: {
                comments: false, // Eliminar comentarios
            },
        }).on('error', handleError)) // Minificar el archivo
        .pipe(javascriptObfuscator({ // Ofuscar el archivo
            compact: true,
            controlFlowFlattening: true,
            deadCodeInjection: true,
            debugProtection: true, // Protección contra depuración
            disableConsoleOutput: true,
        }))
        .pipe(sourcemaps.write('.'))
        .pipe(dest(`${paths.build}js`))
        .pipe(notify({ message: 'JavaScript procesado, minificado y ofuscado!', onLast: true }));
}

// Optimizar imágenes
function optimizeImages() {
    return src(paths.pngJpgImages)
        .pipe(imagemin([
            imageminMozjpeg({ quality: 75, progressive: true }),
            imageminOptipng({ optimizationLevel: 5 }),
        ]))
        .pipe(dest(`${paths.build}img`))
        .pipe(notify({ message: '¡Imágenes optimizadas!', onLast: true }));
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
            canonical: 'http://ieee.unicauca.edu.co/',
        }))
        .pipe(dest('./logs/reports/seo'))
        .pipe(notify({ message: 'SEO optimizado para archivos PHP!', onLast: true }));
}

// Nueva función para copiar archivos y carpetas a la carpeta project
function copyToProduction() {
    return Promise.all(
        filesToCopy.map((file) => {
            const resolvedPath = path.resolve(file); // Resolver la ruta completa
            if (!fs.existsSync(resolvedPath)) {
                console.error(`La ruta no existe: ${resolvedPath}`);
                return Promise.resolve(); // Ignorar rutas inexistentes
            }
            return fs.copy(resolvedPath, `project/${file.replace('./', '')}`);
        })
    ).then(() => {
        console.log('¡Archivos copiados a la carpeta project y listo para subir por SFTP!');
    }).catch((err) => {
        console.error(`Error al copiar archivos: ${err.message}`);
    });
}

// Generar sitemap
function generateSitemap() {
    return src('web/**/*.php', { read: false })
        .pipe(sitemap({
            siteUrl: 'http://ieee.unicauca.edu.co/',
        }))
        .pipe(dest('./web'));
}

// Nueva función para depuración
function debugTask() {
    return src(paths.js)
        .pipe(debug({ title: 'Procesando archivo:' }))
        .pipe(dest(`${paths.build}js`));
}

// Servidor de desarrollo con BrowserSync
function serve(done) {
    server.init({
        server: {
            baseDir: './web',
        },
        port: 3000,
    });
    done();
}

function reload(done) {
    server.reload();
    done();
}

// Automatizar tareas en desarrollo
function dev() {
    watch(paths.scss, series(css, reload));
    watch(paths.js, series(minifyAndObfuscateJS, reload));
    watch(paths.php, reload);
}

// Build final para producción
function productionBuild() {
    return series(
        clean,
        () => css(true), // CSS comprimido
        minifyAndObfuscateJS, // Minificación y ofuscación de JS
        seoOptimization, // Optimización SEO
        optimizeImages, //Hacerlomanualmente con los scripts
        optimizeSVG, // Optimización de SVG
        convertToWebp, // Convertir a WebP
        copyHeic, // Copiar HEIC sin optimizar
        copyIcons, // Copiar archivos ICO
        //generateSitemap, // Generar sitemap
        cleanProduction, // Limpiar carpeta project
        () => {
            return src(paths.php) // Copiar archivos PHP a la carpeta project
                .pipe(dest('project'));
        },
        () => {
            return src(paths.build) // Copiar archivos de build a la carpeta project
                .pipe(dest('project'));
        },
        () => {
            return src('web/**/*') // Copiar archivos de web a la carpeta project
                .pipe(dest('project'));
        },
        cacheBust, // Agregar hash a los archivos
        copyToProduction // Copiar archivos a la carpeta project
    );
}

// Cache Busting
function cacheBust() {
    return src(`${paths.build}/**/*.{css,js}`)
        .pipe(rev())
        .pipe(dest(paths.build))
        .pipe(revDel())
        .pipe(rev.manifest())
        .pipe(dest(paths.build));
}

// Exports
export const build = productionBuild();
export { css, minifyAndObfuscateJS, optimizeImages, optimizeSVG, copyIcons, convertToWebp, dev, clean, lintJS, seoOptimization, convertHeicToWebp, copyHeic, cleanAll, serve, cacheBust, generateSitemap, debugTask };
export default dev;