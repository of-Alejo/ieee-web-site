# ieee-web-site
Unicauca's IEEE Branch Web Site

# Guía del Proyecto

Este documento explica cómo iniciar el entorno de desarrollo, construir la versión de producción, instalar dependencias y configurar el entorno usando el archivo .env.

---

## Comandos principales

### Desarrollo
Para iniciar el entorno de desarrollo, utiliza este comando:
<npm run dev>

**Qué hace:**
- Levanta un servidor PHP en el puerto 3000, sirviendo los archivos desde la carpeta `public`.
- Monitorea los cambios en archivos del proyecto:
  - **SCSS**: Compila y genera archivos CSS en `public/build/css`.
  - **JavaScript**: Minifica y concatena en `public/build/js`.
  - **Imágenes**: Optimiza las imágenes y genera versiones en formatos modernos (WebP y AVIF) en `public/build/img`.
- Actualiza automáticamente los archivos generados en tiempo real para facilitar el desarrollo.

---

### Producción
Para preparar el proyecto para producción, ejecuta:
<npm run production>

**Qué hace:**
- Limpia los archivos antiguos en la carpeta `public/build`.
- Compila y optimiza todos los recursos:
  - **CSS**: Genera CSS comprimido y elimina los estilos no utilizados mediante PurgeCSS.
  - **JavaScript**: Minifica y concatena en un único archivo.
  - **Imágenes**: Optimiza y convierte las imágenes en formatos modernos.
- Genera reportes SEO para optimizar las etiquetas `meta` en los archivos `.php` de la carpeta `views`.

---

### Iniciar solo el servidor PHP
Si solo necesitas levantar el servidor PHP:
<npm run server>

Esto ejecuta el servidor PHP en `http://0.0.0.0:3000`, apuntando a la carpeta `public`.

---

## Instalación de dependencias

### Dependencias de Node.js
Para instalar todas las herramientas necesarias para compilar y procesar los recursos del frontend, ejecuta:
<npm install>

---

### Dependencias de PHP
Las dependencias PHP del proyecto se manejan con Composer. Para instalarlas:
composer install

Esto incluye paquetes como:
- `vlucas/phpdotenv` para manejar las variables de entorno mediante un archivo `.env`.
- `phpmailer/phpmailer` para la administración de correos masivos.

---

## Configuración del entorno

El entorno del proyecto (desarrollo o producción) se configura mediante un archivo `.env`. Este archivo debe estar ubicado en la raíz del proyecto y tiene el siguiente formato de ejemplo:
ENVIRONMENT=development

### Opciones de entorno
- **Desarrollo**: Configura `ENVIRONMENT` como `development` para habilitar mapas de origen (`sourcemaps`) y no minificar los archivos generados. Ejemplo:
  ENVIRONMENT=development
- **Producción**: Configura `ENVIRONMENT` como `production` para habilitar optimizaciones avanzadas, como PurgeCSS y compresión de archivos. Ejemplo:
  ENVIRONMENT=production

---

### Cómo funciona
La variable `ENVIRONMENT` se carga mediante el paquete `phpdotenv` y se utiliza en PHP para determinar el comportamiento del proyecto. Por ejemplo:

function isProduction(): bool {
    return $_ENV['ENVIRONMENT'] === 'production';
}

**Dependiendo del entorno:**
- **En desarrollo (`development`)**:
  - CSS se compila pero no se minifica.
  - JavaScript mantiene los mapas de origen.
- **En producción (`production`)**:
  - CSS comprimido y minificado.
  - Se eliminan estilos no utilizados.
  - JavaScript comprimido y concatenado.

---

## Flujo de trabajo

1. Preparar el entorno:
   - Instala dependencias con:
     <npm install>
     <composer install>

2. Configurar el entorno:
   - Crea un archivo `.env` y ajusta el entorno deseado:
     - Desarrollo: `ENVIRONMENT=development`
     - Producción: `ENVIRONMENT=production`

3. Ejecutar el proyecto:
   - Iniciar en desarrollo:
     <npm run dev>
   - Preparar para producción:
     <npm run production>

---

Con esta guía, puedes configurar, desarrollar y desplegar tu proyecto fácilmente.