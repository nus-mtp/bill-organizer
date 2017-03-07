[![CircleCI](https://circleci.com/gh/nus-mtp/bill-organizer/tree/master.svg?style=svg)](https://circleci.com/gh/nus-mtp/bill-organizer/tree/master)
## Setup guide
### 1. Prepare development server
#### Option 1: Using vagrant  
Follow instructions at https://laravel.com/docs/5.4/homestead  
#### Option 2: using xamp  
Follow instructions at https://www.apachefriends.org/download.html  
#### Additional setup
Enable required extensions by removing the semi-colons [php.ini](http://lmgtfy.com/?q=where+is+php.ini)

```php
extension=php_openssl.dll
extension=php_curl.dll
extension=php_sockets.dll
```

### 2. Install dependency managers
Download and install Composer at https://getcomposer.org/download/  
Download and install nodejs at https://nodejs.org/en/download/

### 3. Setup the project
1 clone project from github
```bash
git clone git@github.com:nus-mtp/bill-organizer.git
```
2 install dependencies
```bash
npm install npm@latest
composer install
npm install
```
3 start development server
```bash
php artisan serve
npm run watch
```

### Accessing Development Database Server

#### Via Web portal
1. Visit http://128.199.82.128/phpmyadmin
2. username: **developer** | password:**password**

#### Via SQLClient (e.g mysql workbench)
Establish connection from local machine via sqlclient using the following settings
```
host:128.199.82.128
port:3306
user:developer 
password:password
```

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
--------------------------------------------------------------

> NPM compilation error

#### Solution
```
1. delete node_modules folder
2. type npm install in terminal/command prompt
```
-----------------------------------------------------------------
> Error: Cannot find module 'D:\vhosts\bill-organizer\node_modules\cross-env\bin\cross-env.js'  

#### Solution
This happens because cross-env.js package updated its build path recently,  
refer to https://github.com/JeffreyWay/laravel-mix/issues/478
```
edit package.json
change all references of 
node_modules/cross-env/bin/cross-env.js to 
node_modules/cross-env/dist/bin/cross-env.js
```
if this does not resolve the problem, manually remove node_modules/cross-env and type
```
npm install
```
