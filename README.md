# Barbería · Sistema de citas

Sistema web para agendar citas en un negocio local. Equipo: Augusto Pale Hernández, Alexis Isai López Quiroz y Alexis Daniel Perez Becerra.

## Instalación
1. Copiar la carpeta en htdocs (Windows) o /var/www/html (Ubuntu).
2. En PostgreSQL crear la base `citas` y el usuario `citas_app`.
3. Ejecutar `crear_base.sql` en la base `citas`.
4. Copiar `config/db.ejemplo.php` como `config/db.php` y poner la contraseña.
5. Registrarse y dar rol de administrador con UPDATE usuarios SET rol = 'admin' ...

## Arquitectura cliente-servidor

    Navegador (HTML, CSS, JS)
         |  petición HTTP / fetch           ^ HTML o JSON
         v                                   |
    Apache + PHP (index.php -> controlador -> modelo -> vista)
         |  consultas SQL con PDO            ^ resultados
         v                                   |
    PostgreSQL (usuarios, servicios, citas)

## Mapa del sitio


## ¿Web 1.0 o Web 2.0?
[un párrafo: qué tiene su sistema de Web 2.0 y cómo sería en Web 1.0]

## Prueba con usuarios
[quiénes lo probaron, qué se les dificultó y qué cambiaron]

## ¿Qué nos habría ahorrado Laravel?
Primero que nada la estructura, luego las vistas ya que estas se generan en base a comandos y la facilidad de ver los errores.
