# Symfony 5.4 Skeleton Application

Only for DEV, not for production!

**Docker + PHP 8.1 + MySQL 8 + Nginx + XDebug 3.1.2 + Symfony 5.4 + Bootstrap 5 + Adminer**

## Setup

See hostnames in the `.env` file.

Add to `/etc/hosts` file lines:
```
127.0.0.1 symfony5-skeleton.test
127.0.0.1 adminer.test
```

Clone and run the `docker-compose`:
```
git clone https://github.com/amberlex78/symfony5-skeleton
cd symfony5-skeleton
cp project/.env project/.env.local
make init
make setup
sudo chown -R $USER:$USER project/
```

### Database

See database connection parameters in the `.env` file.

Database connection in the `project/.env.local` file:
```
DATABASE_URL="mysql://symfony:symfony@mysql:3306/symfony?serverVersion=8.0"
```

### Seeding

Seeding creates users in the database
```
make db-dul
```
(schema:drop, schema:update, fixtures:load)

## Docker

### Up

Docker up `docker-compose up -d` or:
```
make up
```

### Down

Docker down `docker-compose down --remove-orphans` or:
```
make down
```

See all command in `Makefile` file.

## Access to site

Front:
```
http://symfony5-skeleton.test
```

Admin:
```
http://symfony5-skeleton.test
```

## Users

```
user@example.com   - User
admin@example.com  - Admin
```
Password: `password`