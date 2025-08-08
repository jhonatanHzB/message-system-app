# Sistema de Mensajería API (Prueba Técnica)

Esta es la implementación del backend para un sistema de mensajería tipo "inbox". La API está construida con Laravel 12 y sigue los principios RESTful para gestionar usuarios, conversaciones y mensajes.

# ✨ Características

* Autenticación segura: Basada en tokens con Laravel Sanctum.
* Gestión de conversaciones: Creación de hilos de conversación entre dos o más usuarios.
* Mensajería en tiempo real (simulada): Envío y recepción de mensajes dentro de los hilos.
* Sistema de notificaciones: Recuento de mensajes no leídos y funcionalidad para marcarlos como leídos.
* API documentada: Endpoints claros y predecibles para una fácil integración con un cliente frontend.

# 🚀 Instalación y Configuración

Sigue estos pasos para levantar el proyecto en un entorno local.

1. Clonar el repositorio
```
git clone https://github.com/jhonatanHzB/message-system-app.git
cd message-system-app
```

2. Instalar dependencias de PHP
```
composer install
```

3. Copia el archivo de ejemplo ```.env.example``` y crea tu propio archivo de configuración ```.env```.

```
cp .env.example .env
```

Abre el archivo ```.env``` y configura las credenciales de tu base de datos (MySQL):

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=message_board
DB_USERNAME=message_app_user
DB_PASSWORD=password
```

4. Generar la clave de la aplicación

```
php artisan key:generate
```

5. Ejecutar las migraciones y los seeders

Este comando creará la estructura de la base de datos y la poblará con datos de prueba (usuarios, conversaciones, etc.).

```
php artisan migrate --seed
```

6. Iniciar el servidor local

```
php artisan serve
```

La API estará disponible en ```http://127.0.0.1:8000```.

# 📚 Documentación de la API

Todas las peticiones a endpoints protegidos deben incluir las siguientes cabeceras:

* ```Authorization: Bearer <token>```
* ```Accept: application/json```

<hr>

## Autenticación

### Login de Usuario

Inicia sesión para obtener un token de acceso.

* Endpoint: ```POST /api/login```
* Autorización: Pública
* Body (raw/json):
```
{
    "email": "johndoe@message-app.com",
    "password": "password"
}
```
* Respuesta Exitosa (200 OK):
```
{
    "access_token": "1|AbcDefGhi...",
    "token_type": "Bearer"
}  
```

### Obtener Datos del Usuario Autenticado

Devuelve la información del usuario correspondiente al token.

* Endpoint: ```GET /api/user```
* Autorización: Bearer Token
* Respuesta Exitosa (200 OK):
```
{
    "id": 1,
    "name": "John Doe",
    "email": "johndoe@message-app.com",
    "email_verified_at": "2025-08-07T23:33:37.000000Z",
    "created_at": "2025-08-07T23:33:38.000000Z",
    "updated_at": "2025-08-07T23:33:38.000000Z"
}
```

<hr>

## Conversaciones (Threads)

## Listar Conversaciones

Obtiene una lista paginada de todas las conversaciones en las que participa el usuario. Permite búsqueda por asunto.

* Endpoint: ```GET /api/threads```
* Autorización: Bearer Token
* Parámetros de Query (Opcionales):
  * ```page``` (integer): Número de página a solicitar. Ej: ```?page=2```
  * ```search``` (string): Término de búsqueda por asunto. Ej: ```?search=Proyecto```
* Respuesta Exitosa (200 OK):
```
{
    "current_page": 1,
    "data": [
        {
            "id": 2,
            "subject": "Avance del proyecto",
            "created_at": "2025-08-07T23:33:38.000000Z",
            "updated_at": "2025-08-07T23:33:38.000000Z",
            "participants": [ /* ... */],
            "latest_message": { /* ... */ },
            "pivot": {
                "user_id": 1,
                "thread_id": 2
            }
        }
    ],
    "first_page_url": "http://127.0.0.1:8000/api/threads?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http://127.0.0.1:8000/api/threads?page=1",
    // .. datos de paginación
}
```

### Crear Nueva Conversación

Crea un nuevo hilo con un primer mensaje y una lista de participantes.

* Endpoint: POST /api/threads
* Autorización: Bearer Token
* Body (raw/json):
```
{
    "subject": "Revisión de la API",
    "body": "Hola equipo, ¿podemos revisar algunos puntos?",
    "participants": [2, 3] 
}
```
* Respuesta Exitosa (201 Created):
```
{
    "subject": "Revisión de la API",
    "updated_at": "2025-08-07T23:48:23.000000Z",
    "created_at": "2025-08-07T23:48:23.000000Z",
    "id": 3,
    "participants": [ /* ... */ ],
    "latest_message": { /* */ }
}
```

### Ver una Conversación Específica

Obtiene los detalles de un hilo, incluyendo todos sus mensajes y participantes.

* Endpoint: ```GET /api/threads/{id}```
* Autorización: Bearer Token
* Respuesta Exitosa (200 OK):
```
{
    "id": 3,
    "subject": "Revisión de la API",
    "created_at": "2025-08-07T23:48:23.000000Z",
    "updated_at": "2025-08-07T23:48:23.000000Z",
    "messages": [ /* ... */],
    "participants": [ /* ... */ ]
}
```

<hr>

## Mensajes

### Enviar Respuesta en una Conversación

Añade un nuevo mensaje a un hilo existente.

* Endpoint: ```POST /api/threads/{id}/messages```
* Autorización: Bearer Token
* Body (raw/json):
```
{
    "body": "¡Claro! Agendo una reunion para revisarlos."
}
```
* Respuesta Exitosa (201 Created):
```
{
    "body": "¡Claro! Agendo una reunion para revisarlos.",
    "user_id": 1,
    "thread_id": 3,
    "updated_at": "2025-08-07T23:56:14.000000Z",
    "created_at": "2025-08-07T23:56:14.000000Z",
    "id": 7,
    "thread": {
        "id": 3,
        "subject": "Revisión de la API",
        "created_at": "2025-08-07T23:48:23.000000Z",
        "updated_at": "2025-08-07T23:56:14.000000Z"
    },
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "johndoe@message-app.com",
        "email_verified_at": "2025-08-07T23:33:37.000000Z",
        "created_at": "2025-08-07T23:33:38.000000Z",
        "updated_at": "2025-08-07T23:33:38.000000Z"
    }
}
```

<hr>

## Notificaciones
### Obtener Notificaciones

Devuelve el número total de mensajes no leídos por el usuario.

* Endpoint: ```GET /api/notifications```
* Autorización: Bearer Token
* Respuesta Exitosa (200 OK):
```
{
    "unread_messages_count": 2
}
```

### Marcar Mensajes como Leídos

Marca todos los mensajes no leídos de un hilo específico como leídos para el usuario. Es ideal llamar a este endpoint cuando el usuario entra a una conversación.

* Endpoint: ```POST /api/threads/{id}/read```
* Autorización: Bearer Token
* Respuesta Exitosa (200 OK):
```
{
    "message": "Mensajes marcados como leídos exitosamente."
}
```
