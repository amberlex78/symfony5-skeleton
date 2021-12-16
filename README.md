# Symfony 5.4 Skeleton Application

**ONLY for DEV, not for production!**

### Docker + PHP 8.0 + MySQL 8 + Nginx + Symfony 5.4 + Bootstrap 5 + Adminer

Add to `/etc/hosts` file line `127.0.0.1 symfony5-skeleton.test`

```
git clone https://github.com/amberlex78/symfony5-skeleton
cd symfony5-skeleton
cp .env .env.local
```

### Up
Docker up `docker-compose up -d` or:
```
make init
```

### Down
Docker down `docker-compose down --remove-orphans` or:
```
make down
```
See all command in `Makefile` file.

See database connection in `docker-compose.yml` and config in `.env.local` file.

## Access to site

Front:
```
http://symfony5-skeleton.test
```
