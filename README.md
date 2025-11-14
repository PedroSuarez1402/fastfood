# Aplicación de Comidas Rápidas

Esta es una aplicación web desarrollada con **Laravel 12**, **Livewire** y el kit starter **Flux**. Está diseñada como una herramienta para empresas de comida rápida, permitiendo preparar menús, gestionar mesas y pedidos realizados por los usuarios. Su objetivo es agilizar el servicio y mantener un control preciso de las ventas.

## Características

- **Gestión de Menús**: Crea y administra menús de comida rápida de manera sencilla.
- **Gestión de Mesas**: Organiza y asigna mesas para pedidos.
- **Pedidos**: Registra y procesa pedidos de usuarios en tiempo real.
- **Control de Ventas**: Monitorea y analiza las ventas para optimizar el negocio.
- **Interfaz Interactiva**: Utiliza Livewire para una experiencia de usuario fluida y reactiva.
- **Diseño Moderno**: Basado en el kit Flux para una UI atractiva y responsiva.

## Requisitos del Sistema

Antes de comenzar, asegúrate de tener instalados los siguientes componentes:

- **PHP**: Versión 8.1 o superior.
- **Composer**: Para gestionar dependencias de PHP.
- **Node.js**: Versión 16 o superior (incluye npm).
- **Git**: Para clonar el repositorio.
- **Base de Datos**: MySQL, PostgreSQL o SQLite (configurada en el archivo `.env`).
- **Entorno de Desarrollo**: Recomendado Laragon o XAMPP para Windows, o un servidor local similar.

## Instalación

Sigue estos pasos para configurar y ejecutar la aplicación en tu entorno local.

### 1. Clonar el Repositorio

Extrae el proyecto desde Git:

```bash
git clone https://github.com/PedroSuarez1402/fastfood.git
cd fastfood
```

### 2. Instalar Dependencias de Composer

Instala las dependencias del proyecto:

```bash
composer install
```

### 3. Instalar dependencias NPM

Instala las dependencias de NPM:

```bash
npm install
```

### 4. Configurar la Base de Datos

Crea la base de datos y configura las credenciales en el archivo `.env`:
Puede copiar o usar de ejemplo el `.env.example` editando las credenciales. de esta forma:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1 o la ip de la base de datos
DB_PORT=3306
DB_DATABASE=fastfood
DB_USERNAME=root
DB_PASSWORD=cualquier contraseña asignada
```

### 5. Generar la clave de la aplicacion

Genera la clave de la aplicacion:

```bash
php artisan key:generate
```

### 6. Ejecutar las Migraciones

Antes de ejecutar las migraciones asegurate de crear con el nombre de la base de datos en el archivo `.env` y luego si ejecutar las migraciones

```bash
php artisan migrate
```
tambien se debe enlazar la carpeta storage con la carpeta public

```bash
php artisan storage:link
```
y ejecutar las seeds

```bash
php artisan db:seed
```

### 7. Iniciar el Servidor

- **Si usa laragon**:
1. Coloca el proyecto dentro de laragon/www/
2. Reinicia los servicios de laragon
3. Accede al dominio http://localhost/fastfood por ejemplo o el dominio que tengas configurado en laragon segun el .env
```bash
APP_URL=http://fastfood.test
```
este es un ejemplo

- **Si usa XAMPP**:
1. Coloca el proyecto dentro de xampp/htdocs/
2. Reinicia los servicios de XAMPP
3. Ejecutar
```bash
php artisan serve
```
4. Accede al dominio http://localhost/fastfood 

## Estructura princial del proyecto

El proyecto se estructura de la siguiente manera:

```bash
fastfood/
├── app/ "Logica de negocio (livewire + laravel)"
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── tests/
└── vendor/
```