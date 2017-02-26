![circle ci build status badge](https://circleci.com/gh/nus-mtp/bill-organizer.png?circle-token=:circle-token)
# Setup guide

## Access development database server
1. Visit http://128.199.82.128/phpmyadmin
2. username:developer | password:password

## Common problems and solutions:

> The only supported ciphers are AES-128-CBC and AES-256-CBC with the correct key lengths.
Inside commandline, at root of app folder, type:
```sh
php artisan config:clear
cp .env.example .env
php artisan key:generate
```
