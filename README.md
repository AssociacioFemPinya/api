# Com començo?

![PHP 8.3](https://img.shields.io/badge/PHP-8.3-777BB4?logo=php)
![Laravel 8](https://img.shields.io/badge/Laravel-11-FF2D20?logo=laravel)
![Mysql 10](https://img.shields.io/badge/Mysql-8.0-003545?logo=mariadb)

## Creació de l'entorn

### Opció 1 — Docker (RECOMANADA)
![Linux] ![Windows+WSL2] ![Docker]

#### Requisits:

- Docker:
  - Windows: [Docker Desktop 2.0+](https://www.docker.com/products/docker-desktop) + [WSL2](https://aka.ms/vscode-remote/containers/docker-wsl2)
  - Linux: [Docker CE/EE 18.06+](https://docs.docker.com/install/#supported-platforms) + [Docker Compose 1.21+](https://docs.docker.com/compose/install)

#### Passos:

1. Clona aquest repositori al teu ordinador:
   ```shell
   git clone https://github.com/AssociacioFemPinya/api.git
   cd api
   ```

2. Crea el fitxer .env 
   - Copia el fitxer .env.example a .env
   - Emplena les variables necessàries (En general, les de l'apartat `APP CONFIG`)
   - A les variables referents a la BBDD, hi ha dues connexions:
      - `mysql` -> La que es connecta a la BBDD de Fempinya
      - `mysql_api` -> La que es connecta a la BBDD de l'API

3. Crea la bases de dades de l'API al container `db`:
   ```sql
   CREATE DATABASE '$DB_DATABASE_API';
   GRANT ALL PRIVILEGES ON '$DB_DATABASE_API'.* TO '$DB_USERNAME_API'@'%';
   ```

3. Engega els containers
    A la carpeta on hi ha el repositori clonat:
   ```shell
   docker compose up -d
   ```

4. Instal·la dependències
   Dins del container:
   ```shell
   composer install
   ```

4. Continua amb la [configuració inicial](#configuració-inicial)

### Opció 2 — Directe
![Linux] ![Windows]

#### Requisits:

- [PHP 8.3](https://www.php.net/) + `pdo_mysql`, `mbstring`, `exif`, `pcntl`, `bcmath`, `gd`, `zip` i `curl`
- [Composer](https://getcomposer.org/)
- [Mysql .0](https://www.mysql.com/)

#### Passos:

1. Clona aquest repositori al teu ordinador:
   ```shell
   git clone https://github.com/AssociacioFemPinya/api.git
   cd api
   ```

2. Crea el fitxer .env 
   - Copia el fitxer .env.example a .env
   - Emplena les variables necessàries (En general, les de l'apartat `APP CONFIG`)
   - A les variables referents a la BBDD, hi ha dues connexions:
      - `mysql` -> La que es connecta a la BBDD de Fempinya
      - `mysql_api` -> La que es connecta a la BBDD de l'API

3. Crea la bases de dades de l'API al mysql local:
   ```sql
   CREATE DATABASE '$DB_DATABASE_API';
   GRANT ALL PRIVILEGES ON '$DB_DATABASE_API'.* TO '$DB_USERNAME_API'@'%';
   ```

4. Instal·la les dependències:
   ```shell
   composer install
   ```

5. Inicia el servidor de desenvolupament:
   ```shell
   php artisan serve
   ```

6. Continua amb la [configuració inicial](#configuració-inicial)


## Configuració inicial

Si fas servir docker, executa-ho dins en local o dins del container:

1. Prepara l'app:
   ```shell
   php artisan key:generate
   ```
2. Prepara la BBDD en local:

   - Revisa les migrations per elegir què vols crear-hi i després executa:
   ```shell
   php artisan migrate
   ```   

## Executar tests

Pots executar els tests amb:

```shell
php artisan test
```

## Netejar el codi

Per mantenir el codi net i ordenat, executa el Pint de Laravel abans de crear la PR:
```shell
./vendor/bin/pint
```

[Linux]: https://img.shields.io/badge/Linux-FCC624?logo=linux&logoColor=000
[Windows]: https://img.shields.io/badge/Windows-0078D6?logo=windows&logoColor=fff
[Windows+WSL2]: https://img.shields.io/static/v1?label=Windows&message=WSL2&color=FCC624&logo=windows&labelColor=0078D6
[Docker]: https://img.shields.io/badge/Docker-2496ED?logo=docker&logoColor=fff
