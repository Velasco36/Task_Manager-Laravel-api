# Task Manager Laravel API

Este repositorio contiene una API construida con Laravel para gestionar tareas. La API proporciona endpoints para crear, leer, actualizar y eliminar tareas.

## Instalación

Sigue estos pasos para configurar y ejecutar el proyecto localmente:

1. Clona este repositorio:

git clone https://github.com/Velasco36/Task_Manager-Laravel-api.git


2. Instala las dependencias utilizando Composer:

-composer install 

3. Crea una base de datos MySQL llamada `api`.

4. Inicia sesión en [Mailtrap](https://mailtrap.io/) para obtener las credenciales SMTP y luego agrega estas credenciales al archivo `.env`. Asegúrate de cambiar las variables `DB_USERNAME`, `DB_PASSWORD`, `MAIL_USERNAME`, y `MAIL_PASSWORD` con tus propias credenciales.

5. Agrega las siguientes variables de entorno al archivo `.env`:

APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:AVg9o8G4ZBt5t9Vq6tRfLWLzI01DpW3VWeOZ6oBXxKc=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=api
DB_USERNAME=root
DB_PASSWORD=sunny

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME="tu_usuario"
MAIL_PASSWORD="tu_contraseña"
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

# testing
APP_ENV=testing
DB_CONNECTION=mysql
DB_DATABASE=api

MAIL_MAILER=smtp
QUEUE_CONNECTION=sync
SESSION_DRIVER=array
TELESCOPE_ENABLED=false



AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false



VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

JWT_SECRET=jIeeOa6G8UuzPhWHpiaa4ouxraeA6bmrCMKIAgA8NdVttd2NX070sczzafqLoclz

6. Ejecuta las migraciones para crear las tablas de la base de datos:
   php artisan migrate


7. Inicia el servidor:
php artisan serve

   


8. La documentación de la API estará disponible en la ruta `/api/documentation`, donde podrás ver todas las rutas de la API.

## Frontend (Notas)

Para ejecutar el frontend, sigue estos pasos:

1. Navega al directorio `Notas`:


cd Notas


2. Instala las dependencias utilizando `pnpm`:


3. Ejecuta el proyecto:
pnpm install

3. Ejecuta el proyecto:

npm run dev




