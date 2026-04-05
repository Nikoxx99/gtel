# Guía de migración de este WordPress a Coolify

Esta guía está adaptada a tu proyecto actual (`/Users/brayancardozo/Documents/NICOLAS/gtel`).

## 1) Datos detectados en tu backup actual

- WordPress con tema activo `silicon-child` (padre `silicon`).
- Plugins activos detectados: ACF, Elementor, One Click Demo Import, Safe SVG, Silicon Elementor, Silicon Extensions, WPForms Lite.
- Base de datos origen: `wp_gtel`.
- Prefijo de tablas: `mod990_`.
- URL actual en la DB: `https://gteltelecomunicaciones.com`.
- En tu carpeta existe `alldbs_backup.sql` (no encontré `all_databases.sql`).

Si vas a usar el nombre pedido por ti (`all_databases.sql`), renómbralo:

```bash
mv alldbs_backup.sql all_databases.sql
```

## 2) Estrategia recomendada en Coolify

Usar **Docker Compose** en Coolify con 2 servicios:

1. `wordpress`: contenedor de app
2. `db`: MySQL 8

Y 2 volúmenes persistentes:

1. `wp_data` para `/var/www/html`
2. `db_data` para `/var/lib/mysql`

## 3) Archivos sugeridos

### `Dockerfile.coolify`

```dockerfile
FROM wordpress:php8.2-apache

# Copia tu WordPress (core + themes + plugins + uploads) al seed path
COPY . /usr/src/wordpress/

# Evita usar wp-config hardcodeado del proyecto antiguo
RUN rm -f /usr/src/wordpress/wp-config.php \
 && chown -R www-data:www-data /usr/src/wordpress
```

### `docker-compose.coolify.yml`

```yaml
services:
  wordpress:
    build:
      context: .
      dockerfile: Dockerfile.coolify
    depends_on:
      - db
    environment:
      WORDPRESS_DB_HOST: db:3306
      WORDPRESS_DB_NAME: wp_gtel
      WORDPRESS_DB_USER: wp_gtel_user
      WORDPRESS_DB_PASSWORD: ${MYSQL_PASSWORD}
      WORDPRESS_TABLE_PREFIX: mod990_
      WORDPRESS_CONFIG_EXTRA: |
        define('CONCATENATE_SCRIPTS', false);
        define('WP_MEMORY_LIMIT', '256M');
    volumes:
      - wp_data:/var/www/html
    restart: unless-stopped

  db:
    image: mysql:8.0
    command: --default-authentication-plugin=mysql_native_password
    environment:
      MYSQL_DATABASE: wp_gtel
      MYSQL_USER: wp_gtel_user
      MYSQL_PASSWORD: ${MYSQL_PASSWORD}
      MYSQL_ROOT_PASSWORD: ${MYSQL_ROOT_PASSWORD}
    volumes:
      - db_data:/var/lib/mysql
      - ./all_databases.sql:/docker-entrypoint-initdb.d/01-all_databases.sql:ro
    restart: unless-stopped

volumes:
  wp_data:
  db_data:
```

## 4) Despliegue en Coolify (paso a paso)

1. Sube este proyecto a un repositorio Git (GitHub/GitLab).
2. En Coolify: `New Resource` -> `Application` -> `Docker Compose`.
3. Conecta el repo y selecciona `docker-compose.coolify.yml`.
4. Define variables en Coolify (Secrets):
   - `MYSQL_PASSWORD`
   - `MYSQL_ROOT_PASSWORD`
5. En Domains, agrega tu dominio final (ej. `gteltelecomunicaciones.com`).
6. Activa `HTTPS` / Let's Encrypt en Coolify.
7. Deploy.

Nota importante: el SQL en `/docker-entrypoint-initdb.d/` se ejecuta solo cuando el volumen de MySQL está vacío (primer arranque).

## 5) Si la base **no** se importó automáticamente

Entrar al terminal del servicio `db` en Coolify y ejecutar:

```bash
mysql -u root -p"$MYSQL_ROOT_PASSWORD" wp_gtel < /docker-entrypoint-initdb.d/01-all_databases.sql
```

## 6) Si cambias de dominio

Si el dominio nuevo es diferente a `https://gteltelecomunicaciones.com`, ejecuta al menos:

```sql
UPDATE mod990_options SET option_value='https://NUEVO_DOMINIO' WHERE option_name IN ('siteurl','home');
```

Para reemplazo completo en contenido serializado, usa WP-CLI (`search-replace`) o un plugin de reemplazo seguro.

## 7) Checklist post-migración

1. Abre `/wp-admin` y verifica login.
2. Ve a `Ajustes > Enlaces permanentes` y guarda una vez.
3. Revisa formularios (WPForms), páginas Elementor y archivos PDF/imagenes.
4. Verifica correo saliente (si usas SMTP, reconfigura credenciales).
5. Revisa cron/jobs del sitio.
6. Activa backup automático de volumen `db_data` y `wp_data`.

## 8) Recomendaciones de seguridad (muy importante)

1. No reutilices las credenciales antiguas de `wp-config.php`.
2. Usa nuevas contraseñas DB en Coolify Secrets.
3. Rota claves/salts de WordPress (regenerar en config).
4. Mantén WordPress, tema y plugins actualizados.

## 9) Rollback rápido

Si algo falla:

1. Restaurar snapshot de `db_data`.
2. Restaurar snapshot de `wp_data`.
3. Re-deploy de la versión anterior en Coolify.

---

Si quieres, en el siguiente paso te puedo dejar estos dos archivos (`Dockerfile.coolify` y `docker-compose.coolify.yml`) ya creados en el repo para que solo conectes Coolify y hagas deploy.
