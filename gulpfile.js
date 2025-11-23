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

const isProduction = true;
let buildHash = crypto.randomBytes(5).toString('hex'); // Hash único por build

// Log del modo actual
console.log(`🔧 Modo: ${isProduction ? '🚀 PRODUCCIÓN' : '🛠️ DESARROLLO'}`);
if (isProduction) console.log(`📦 Build hash: ${buildHash}`);

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
  console.log('🧹 Carpeta build limpiada');
}

// Eliminar archivos sin hash en producción
async function cleanUntaggedFiles() {
  if (!isProduction) return Promise.resolve();

  const del = (await import('del')).deleteSync;
  // Borra app.css y main.js sin hash
  del([
    `${paths.build}css/app.css`,
    `${paths.build}js/main.js`
  ]);
  console.log('🧹 Archivos sin hash eliminados');
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
      autoprefixer({ overrideBrowserslist: ['last 2 versions', '> 1%', 'not dead'] }),
      isProduction && cssnano({ preset: ['default', { discardComments: { removeAll: true } }] })
    ].filter(Boolean)));

  pipeline = pipeline.pipe(concat(isProduction ? `app-${buildHash}.css` : 'app.css'));
  
  // Solo escribir sourcemaps en desarrollo
  if (!isProduction) {
    pipeline = pipeline.pipe(sourcemaps.write('.'));
  }
  
  pipeline = pipeline.pipe(dest(`${paths.build}css`));

  return pipeline.pipe(notify({ 
    message: isProduction ? '✅ CSS producción listo' : '✅ CSS desarrollo compilado', 
    onLast: true 
  }));
}

// ========================================
// JS
// ========================================

function minifyAndObfuscateJS() {
  let pipeline = src(paths.js);
  
  // Solo inicializar sourcemaps en desarrollo
  if (!isProduction) {
    pipeline = pipeline.pipe(sourcemaps.init());
  }

  // Concatenar archivos (con hash solo en producción)
  pipeline = pipeline.pipe(concat(isProduction ? `main-${buildHash}.js` : 'main.js'));

  // Minificación con Terser (diferente según entorno)
  pipeline = pipeline.pipe(terser({
    compress: {
      drop_console: isProduction,     // Elimina console.log solo en producción
      drop_debugger: isProduction,    // Elimina debugger; solo en producción
      hoist_funs: true,    // Sube declaraciones para simplificar
      reduce_vars: true, 
      collapse_vars: true, // Reduce variables repetidas
      dead_code: true,
      unused: true,
      passes: isProduction ? 2 : 3    // Más pasadas de optimización en producción
    },
    mangle: isProduction ? { toplevel: true } : false, // Ofuscar nombres de variables en producción
    output: {
      comments: false                 // Elimina comentarios siempre
    }
  }).on('error', handleError));

  // Ofuscación SOLO en producción
  if (isProduction) {
    pipeline = pipeline.pipe(javascriptObfuscator({
      compact: true,                  // Compactar código (producción)
      controlFlowFlattening: true,    // Dificulta lectura del flujo
      controlFlowFlatteningThreshold: 0.75,
      deadCodeInjection: true,        // Inyecta código muerto (confunde)
      deadCodeInjectionThreshold: 0.4,
      debugProtection: true,          // Protección contra debugging
      debugProtectionInterval: 2000,  // Refresca protección cada 2s
      disableConsoleOutput: true,     // Desactiva console en producción
      stringArray: true,              // Ofusca strings
      stringArrayThreshold: 0.75,
      transformObjectKeys: true,
      unicodeEscapeSequence: false
    }));
  }

  // Source maps (solo en desarrollo)
  if (!isProduction) {
    pipeline = pipeline.pipe(sourcemaps.write('.'));
  }

  // Guardar en destino
  pipeline = pipeline.pipe(dest(`${paths.build}js`));

  return pipeline.pipe(notify({
    message: isProduction 
      ? '✅ JavaScript producción: minificado y ofuscado' 
      : '✅ JavaScript desarrollo: concatenado',
    onLast: true
  }));
}

// ========================================
// Imágenes y SVG
// ========================================

function optimizeImages() {
  return src(paths.pngJpgImages)
    .pipe(imagemin([
      imageminMozjpeg({ quality: isProduction ? 75 : 85, progressive: true }),
      imageminOptipng({ optimizationLevel: isProduction ? 5 : 3 })
    ]))
    .pipe(dest(`${paths.build}img`))
    .pipe(notify({ message: '✅ Imágenes optimizadas!', onLast: true }));
}

function optimizeSVG() {
  return src(paths.svgImages)
    .pipe(svgmin({
      plugins: [
        { removeViewBox: false },
        { cleanupIDs: isProduction }
      ]
    }))
    .pipe(dest(`${paths.build}img`))
    .pipe(notify({ message: '✅ SVG optimizados!', onLast: true }));
}

function convertToWebp() {
  return src(paths.pngJpgImages)
    .pipe(webp({ quality: 80 }))
    .pipe(dest(`${paths.build}img`))
    .pipe(notify({ message: '✅ WebP generados', onLast: true }));
}

function copyIcons() {
  return src(paths.icoFiles)
    .pipe(dest(`${paths.build}img`));
}

// ========================================
// Cache bust (manifest común)
// ========================================

function generateManifest() {
  if (!isProduction) {
    console.log('ℹ️ Manifest omitido en desarrollo');
    return Promise.resolve();
  }

  const manifestData = {
    css: `css/app-${buildHash}.css`,
    js: `js/main-${buildHash}.js`,
    hash: buildHash,
    timestamp: new Date().toISOString()
  };

  fs.writeJsonSync(`${paths.build}manifest.json`, manifestData, { spaces: 2 });
  console.log(`✅ Manifest generado con hash: ${buildHash}`);
  return Promise.resolve();
}

function reload(done) {
  server.reload();
  done();
}

function dev() {
  watch(paths.scss, series(css, reload));
  watch(paths.js, series(minifyAndObfuscateJS, reload));
  watch(paths.php, reload);
  watch(paths.images, series(optimizeImages, optimizeSVG, copyIcons, reload));
}

// ========================================
// Production build
// ========================================

function productionBuild() {
  return series(
    clean,
    parallel(css, minifyAndObfuscateJS),
    generateManifest,
    cleanUntaggedFiles,
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
export { css, minifyAndObfuscateJS, cleanUntaggedFiles, generateManifest, optimizeImages, optimizeSVG, convertToWebp, copyIcons, dev, clean };
export default series(dev);
