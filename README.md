# Examen 4V Pizza
Este es un proyecto desarrollado con Symfony.


## Instalación

```bash
git clone https://github.com/usuario/nombre-del-proyecto.git
cd nombre-del-proyecto
composer install
cp .env .env.local
# Configura tus variables de entorno
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### 3. Crear el archivo `.env.local`

Crea un archivo `.env.local` en la raíz del proyecto y configura la conexión a la base de datos:

```
DATABASE_URL="mysql://usuario:contraseña@127.0.0.1:3306/nombre_basededatos?serverVersion=8.0"
```

⚠️ Sustituye `usuario`, `contraseña` y `nombre_basededatos` por los datos reales de tu entorno.

### 4. Crear la base de datos

```bash
php bin/console doctrine:database:create
```

### 5. Generar y ejecutar las migraciones

Como se han eliminado los ficheros de migraciones del proyecto, primero hay que generarlos:

```bash
php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate
```

Esto generará las tablas necesarias en la base de datos.

### 6. (Opcional) Cargar datos de prueba (fixtures)

Si el proyecto incluye clases de fixtures, puedes cargarlas así:

```bash
php bin/console doctrine:fixtures:load
```

### 7. Levantar el servidor de desarrollo

Puedes usar el servidor embebido de Symfony:

```bash
symfony server:start
```

O el servidor PHP integrado:

```bash
php -S 127.0.0.1:8000 -t public
```

```
