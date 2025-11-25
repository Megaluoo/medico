# Medico

Sistema mínimo en PHP con autenticación basada en sesiones, middleware y vistas estilizadas con Tailwind.

## Puesta en marcha

```bash
php -S localhost:8000 -t public public/index.php
```

Abre http://localhost:8000 y autentícate con alguno de los usuarios de prueba:

- ana@example.com / password123 (admin)
- carlos@example.com / password123 (medico)

## Características
- Formulario de login con diseño moderno en Tailwind.
- Controladores y middleware para login, logout, sesiones seguras y control de roles.
- Layout que muestra el usuario autenticado y permite cerrar sesión.
- Redirección a `/login` para usuarios no autenticados y bloqueo de rutas por rol.
