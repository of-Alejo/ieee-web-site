// ========================================
// Importaciones
// ========================================
import gulp from 'gulp';
import gulpSass from 'gulp-sass';
import * as sass from 'sass';
import autoprefixer from 'autoprefixer';
import cssnano from 'cssnano';
import postcss from 'gulp-postcss';
import sourcemaps from 'gulp-sourcemaps';
import imagemin from 'gulp-imagemin';
import imageminMozjpeg from 'imagemin-mozjpeg';
import imageminOptipng from 'imagemin-optipng';
import svgmin from 'gulp-svgmin';
import webp from 'gulp-webp';
import terser from 'gulp-terser-js';
import concat from 'gulp-concat';
import notify from 'gulp-notify';
import eslint from 'gulp-eslint';
import javascriptObfuscator from 'gulp-javascript-obfuscator';
import browserSync from 'browser-sync';
import rev from 'gulp-rev';
import revDel from 'gulp-rev-delete-original';
import sitemap from 'gulp-sitemap';
import fs from 'fs-extra';
import path from 'path';
import crypto from 'crypto';

const sassCompiler = gulpSass(sass);
const { src, dest, watch, series, parallel } = gulp;
const server = browserSync.create();

// ========================================
// Configuración general
// ========================================

const paths = {
  scss: 'src/scss/**/*.scss',
  js: 'src/js/**/*.js',
  images: 'src/img/**/*',
  php: 'views/**/*.php',
  pngJpgImages: 'src/img/**/*.{png,jpg,jpeg}',
  heicImages: 'src/img/**/*.{heic,HEIC}',
  svgImages: 'src/img/**/*.svg',
  icoFiles: 'src/img/**/*.ico',
  build: 'web/build/'
};

const filesToCopy = [
  './controllers/',
  './includes/',
  './models/',
  './views/',
  './web/',
  './vendor/',
  './.env',
  './web/.htaccess',
  './composer.json',
  './composer.lock',
  './package.json',
  './Router.php'
];

// ========================================
// Modos de ejecución
// ========================================

const isProduction = false;
let buildHash = crypto.randomBytes(5).toString('hex'); // Hash único por build

// ========================================
// Manejo de errores
// ========================================

function handleError(err) {
  notify.onError({
    title: 'Error en Gulp',
    message: `Error: ${err.message}`,
    sound: 'Beep'
  })(err);
  console.error(`[Error]: ${err.toString()}`);
  this.emit('end');
}

// ========================================
// Limpieza
// ========================================

async function clean() {
  const del = (await import('del')).deleteSync;
  del([paths.build]);
}

// Eliminar archivos sin hash en producción
function cleanUntaggedFiles() {
  if (!isProduction) return Promise.resolve();

  const del = require('del');
  // Borra app.css y main.js sin hash
  return del([
    `${paths.build}css/app.css`,
    `${paths.build}js/main.js`
  ]);
}

// ========================================
// CSS
// ========================================

function css() {
  const outputStyle = isProduction ? 'compressed' : 'expanded';

  let pipeline = src(paths.scss)
    .pipe(sourcemaps.init())
    .pipe(sassCompiler({ outputStyle }).on('error', handleError))
    .pipe(postcss([
      autoprefixer({ overrideBrowserslist: ['last 2 versions', 'not dead'] }),
      isProduction && cssnano()
    ].filter(Boolean)));

  pipeline = pipeline.pipe(concat(isProduction ? `app-${buildHash}.css` : 'app.css'));
  
  pipeline = pipeline.pipe(sourcemaps.write('.')).pipe(dest(`${paths.build}css`));

  return pipeline.pipe(notify({ message: 'CSS procesado correctamente!', onLast: true }));
}

// ========================================
// JS
// ========================================

function minifyAndObfuscateJS() {
  let pipeline = src(paths.js).pipe(sourcemaps.init());

  pipeline = pipeline.pipe(concat(isProduction ? `main-${buildHash}.js` : 'main.js'));

  pipeline = pipeline
    .pipe(terser({
      compress: { drop_console: true },
      output: { comments: false }
    }).on('error', handleError))
    .pipe(javascriptObfuscator({
      compact: true,
      controlFlowFlattening: true,
      deadCodeInjection: true,
      debugProtection: true,
      disableConsoleOutput: true
    }))
    .pipe(sourcemaps.write('.'))
    .pipe(dest(`${paths.build}js`));

  return pipeline.pipe(notify({ message: 'JavaScript procesado, minificado y ofuscado!', onLast: true }));
}

// ========================================
// Imágenes y SVG
// ========================================

function optimizeImages() {
  return src(paths.pngJpgImages)
    .pipe(imagemin([
      imageminMozjpeg({ quality: 75, progressive: true }),
      imageminOptipng({ optimizationLevel: 5 })
    ]))
    .pipe(dest(`${paths.build}img`))
    .pipe(notify({ message: '¡Imágenes optimizadas!', onLast: true }));
}

function optimizeSVG() {
  return src(paths.svgImages)
    .pipe(svgmin())
    .pipe(dest(`${paths.build}img`))
    .pipe(notify({ message: 'SVG optimizados!', onLast: true }));
}

function convertToWebp() {
  return src(paths.pngJpgImages)
    .pipe(webp({ quality: 80 }))
    .pipe(dest(`${paths.build}img`));
}

function copyIcons() {
  return src(paths.icoFiles).pipe(dest(`${paths.build}img`));
}

// ========================================
// Cache bust (manifest común)
// ========================================

function generateManifest() {
  if (!isProduction) return Promise.resolve();

  const manifestData = {
    css: `css/app-${buildHash}.css`,
    js: `js/main-${buildHash}.js`,
    hash: buildHash
  };

  fs.writeJsonSync(`${paths.build}manifest.json`, manifestData, { spaces: 2 });
  console.log(`✔ Manifest generado con hash: ${buildHash}`);
  return Promise.resolve();
}

// ========================================
// Servidor y watch
// ========================================

function serve(done) {
  server.init({
    server: { baseDir: './web' },
    port: 3000,
  });
  done();
}

function reload(done) {
  server.reload();
  done();
}

function dev() {
  watch(paths.scss, series(css, reload));
  watch(paths.js, series(minifyAndObfuscateJS, reload));
  watch(paths.php, reload);
}

// ========================================
// Production build
// ========================================

function productionBuild() {
  return series(
    clean,
    parallel(css, minifyAndObfuscateJS),
    generateManifest,
    optimizeImages,
    optimizeSVG,
    convertToWebp,
    copyIcons
  );
}

// ========================================
// Exportaciones
// ========================================

export const build = productionBuild();
export { css, minifyAndObfuscateJS, cleanUntaggedFiles, generateManifest, optimizeImages, optimizeSVG, convertToWebp, copyIcons, dev, clean, serve };
export default dev;
