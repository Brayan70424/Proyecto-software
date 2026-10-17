# Proyecto Autenticación – Sprint 1 (HU-01 y HU-02)

Laravel 12 · PHP 8.2+ · MySQL (XAMPP)

Este proyecto ya trae aplicadas todas las configuraciones (.env, config/auth.php,
bootstrap/app.php, modelo, controladores, rutas y vistas). Solo falta instalar
las dependencias y crear la base de datos.

## Pasos

1. Descomprime esta carpeta dentro de `C:\xampp\htdocs\` (o donde prefieras).
2. Abre XAMPP y pulsa **Start** en **MySQL**.
3. En `http://localhost/phpmyadmin` ve a **Importar**, elige
   `database/sql/crear_bd.sql` y pulsa **Importar**.
   (Crea la base `autenticacion` y la tabla `usuario` con PK `id_usuario`.)
4. Abre una terminal dentro de la carpeta del proyecto e instala las dependencias:

       composer install

5. (Opcional) Crea el usuario de prueba ana@test.local / Abcd1234:

       php artisan db:seed

6. Levanta el servidor:

       php artisan serve

7. Abre `http://127.0.0.1:8000/registro` o `http://127.0.0.1:8000/login`.

## Si `php` o `composer` no se reconocen
Usa la ruta completa de XAMPP, por ejemplo:

    C:\xampp\php\php.exe artisan serve

o agrega `C:\xampp\php` a las variables de entorno de Windows.
Composer se descarga de https://getcomposer.org

## Comprobar la conexión con la BD

    php artisan tinker
    App\Models\Usuario::count();

Si devuelve un número (aunque sea 0), el proyecto está conectado.

## Notas
- NO ejecutes `php artisan migrate`: la base se crea con `crear_bd.sql`.
- El `.env` ya incluye una APP_KEY y usa `SESSION_DRIVER=file` y `CACHE_STORE=file`
  porque la base solo contiene la tabla `usuario`.
- Si cambias el nombre de la base o la contraseña de MySQL, edita `.env`
  y ejecuta `php artisan config:clear`.
