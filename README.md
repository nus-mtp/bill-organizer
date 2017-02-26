[![CircleCI](https://circleci.com/gh/nus-mtp/bill-organizer/tree/master.svg?style=svg)](https://circleci.com/gh/nus-mtp/bill-organizer/tree/master)
## Setup guide
### 1. Prepare development server
Option 1: Using vagrant
Follow instructions at https://laravel.com/docs/5.4/homestead
Option 2: using xamp
Follow instructions at https://www.apachefriends.org/download.html

### Access development database server

#### Via Web portal
1. Visit http://128.199.82.128/phpmyadmin
2. username:developer | password:password
#### Via SQLClient (e.g mysql workbench)
1. Configure connection from local machine using the following settings
2. host: 128.199.82.128 | port: 3306 | user:developer | password:password

------------------------------------------

### Common problems and solutions:

> The only supported ciphers are AES-128-CBC and AES-256-CBC with the correct key lengths.

#### Solution
Inside commandline, at root of app folder, type:
```sh
php artisan config:clear
cp .env.example .env
php artisan key:generate
```
-----------------------------------------------------
