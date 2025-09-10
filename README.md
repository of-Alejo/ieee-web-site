# 🌐 IEEE Unicauca Web Site

Este repositorio contiene el sitio web oficial de la Rama IEEE Unicauca. El proyecto está diseñado para promover las actividades, eventos y recursos de la Rama IEEE en la Universidad del Cauca.

---

## 📖 Tabla de Contenidos

1. [Descripción del Proyecto](#-descripción-del-proyecto)
2. [Características Principales](#-características-principales)
3. [Requisitos Previos](#-requisitos-previos)
4. [Instalación](#-instalación)
5. [Comandos Principales](#-comandos-principales)
6. [Configuración del Entorno](#-configuración-del-entorno)
7. [Flujo de Trabajo](#-flujo-de-trabajo)
8. [Estructura del Proyecto](#-estructura-del-proyecto)
9. [Subida a Producción](#-subida-a-producción)
10. [Seguridad y Recomendaciones](#-seguridad-y-recomendaciones)
11. [Licencia](#-licencia)
12. [Contacto](#-contacto)

---

## 📝 Descripción del Proyecto

Este sitio web está desarrollado utilizando **PHP** (compatible con PHP 5.3), **SCSS**, **JavaScript** y herramientas modernas de desarrollo frontend como **Gulp**. Su objetivo es proporcionar una plataforma para mostrar información sobre la Rama IEEE Unicauca, sus eventos, actividades y recursos institucionales.

---

## ✨ Características Principales

* 🔧 **Servidor PHP integrado** para desarrollo local
* 🎨 Compilación de SCSS a CSS
* 🧠 Minificación y ofuscación de JavaScript
* 🖼️ Optimización de imágenes y conversión a WebP
* 📈 SEO automatizado con generación de `sitemap.xml`
* 🚀 Caché busting con hashes únicos para CSS y JS
* 📆 Carpeta `project` lista para subir por SFTP

---

## ⚙️ Requisitos Previos

Asegúrate de tener instalados:

* **PHP** = 5.3
* **Composer**
* **Node.js** >= 14
* **npm**
* **Gulp CLI** (global):

  ```bash
  npm install --global gulp-cli
  ```
* Cliente SFTP como FileZilla

---

## 🚀 Instalación

Clona el repositorio y entra a la carpeta del proyecto:

```bash
git clone https://github.com/ofLitch/ieee-web-site.git
cd ieee-web-site
```

Instala las dependencias de Node.js:

```bash
npm install
```

Instala las dependencias de PHP:

```bash
composer install
```

Crea un archivo `.env` en la raíz del proyecto con el siguiente contenido:

```dotenv
ENVIRONMENT=development
DB_HOST_DEV=localhost
DB_USER_DEV=root
DB_PASS_DEV=root
DB_NAME_DEV=ieee

DB_HOST=localhost
DB_USER=ieee
DB_PASS=
DB_NAME=ieee
```

---

## 📦 Comandos Principales

### 🔧 Desarrollo

```bash
npm run dev
```

**Qué hace:**

* Inicia servidor PHP en [http://localhost:3000](http://localhost:3000)
* Observa cambios en SCSS, JS e imágenes
* Compila y copia archivos a `web/build` automáticamente

### 🏭 Producción

```bash
npm run build
```

**Qué hace:**

* Limpia carpetas `web/build` y `project`
* Compila SCSS y JS (minificado y ofuscado)
* Optimiza imágenes y convierte a WebP
* Genera `sitemap.xml` para SEO
* Copia todo lo necesario a `project/`

---

## 🛠️ Configuración del Entorno

El entorno se gestiona mediante un archivo `.env`.

### Opciones:

* `ENVIRONMENT=development`:

  * CSS no minificado
  * JS con source maps
* `ENVIRONMENT=production`:

  * CSS y JS minificados
  * Imágenes optimizadas

---

## 🔄 Flujo de Trabajo

1. **Preparar el entorno:**

   ```bash
   npm install
   composer install
   ```

2. **Configurar el entorno:**

   Edita `.env` con las variables apropiadas.

3. **Ejecutar:**

   * Desarrollo: `npm run dev`
   * Producción: `npm run build`

4. **Subida a Producción:**

   Usa la carpeta `project` generada por `npm run build`.

---

## 📂 Estructura del Proyecto

```
ieee-web-site/
├── controllers/          # Controladores PHP
├── includes/             # Configuración y funciones globales
├── models/               # Modelos ActiveRecord
├── views/                # Vistas del proyecto
│   ├── templates/        # Plantillas reutilizables
│   └── layout.php        # Layout principal
├── web/                  # Archivos públicos (CSS, JS, imágenes, etc.)
├── project/              # Carpeta generada para producción
├── .env                  # Variables de entorno
├── gulpfile.js           # Configuración de Gulp
├── composer.json         # Dependencias de PHP
├── package.json          # Dependencias de Node.js
└── README.md             # Documentación del proyecto
```

---

## 🌐 Subida a Producción

### Generar la carpeta `project`:

```bash
npm run build
```

### Conectarse al servidor:

Usa FileZilla o cualquier cliente SFTP:

* **Host:** Dirección IP o dominio del servidor
* **Usuario/Contraseña:** Proporcionados por el administrador
* **Puerto:** 22

### Subir archivos:

Sube **todo el contenido de la carpeta `project/`** al servidor.

### Configurar entorno en producción:

Asegúrate de tener en `.env`:

```dotenv
ENVIRONMENT=production
```

---

## 🔒 Seguridad y Recomendaciones

* `.htaccess` ya protege `.env`, `.json` y otros archivos sensibles.
* Verifica que `ENVIRONMENT=production` en producción.
* Siempre ejecuta `npm run build` antes de subir.
* Archivos CSS y JS tienen **hashes únicos** para evitar problemas de caché.

---

## 📜 Licencia

Este proyecto está licenciado bajo la **MIT License**. Puedes usarlo, modificarlo y distribuirlo libremente.

---

## 📧 Contacto

¿Tienes dudas o sugerencias?:

* **Correo:** [davidalejof@unicauca.edu.co](mailto:davidalejof@unicauca.edu.co)

---

🎉 ¡Gracias por visitar nuestro proyecto!
