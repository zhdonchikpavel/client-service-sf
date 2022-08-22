# Symfony Client Service app example

## Installation

Requires Docker to be installed.

Configure environment variables and run Docker containers

```sh
cp .env.example .env
# update key values here if you want/need (in case some ports are being used currently)
cd service
cp .env.example .env
# update DATABASE_URL value to match DB connection parameters defined in previous .env
cd ../client
cp .env.example .env
# update API_URL port to match NGINX port defined in first .env
docker-compose up -d --build # should run 4 containers
```

Service application setup

```sh
docker exec -ti cs_app-php-fpm-service bash
composer install
php bin/console make:migration
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

Client application setup

```sh
docker exec -ti cs_app-php-fpm-client bash
composer install
```

Commands to run from client:

```sh
docker exec -ti cs_app-php-fpm-client bash
# Users Groups
php bin/console.php list-groups [<page>]
php bin/console.php create-group <newGroupName>
php bin/console.php update-group <groupId> <groupName>
php bin/console.php delete-group <groupId>

# Users
php bin/console.php list-users [<page>]
php bin/console.php create-user <newUserName> <newUserEmail> <groupId>
php bin/console.php update-user <userId> <userName> <userEmail> <groupId>
php bin/console.php delete-user <userId>

# Group Users List
php bin/console.php list-group-users <groupId>
```

Swagger http://host.docker.internal:8010/api