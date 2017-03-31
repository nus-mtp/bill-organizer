# Setup guide
## 1. Prepare development server
### Option 1: Using vagrant  
Follow instructions at https://laravel.com/docs/5.4/homestead  
### Option 2: using xamp  
Follow instructions at https://www.apachefriends.org/download.html  
### Additional setup
Enable required extensions by removing the semi-colons [php.ini](http://lmgtfy.com/?q=where+is+php.ini)

```php
extension=php_openssl.dll
extension=php_curl.dll
extension=php_sockets.dll
```

## 2. Install dependency managers
Download and install Composer at https://getcomposer.org/download/  
Download and install nodejs at https://nodejs.org/en/download/

## 3. Setup the project
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

## Accessing Development Database Server

### Via Web portal
1. Visit http://128.199.82.128/phpmyadmin
2. username: **developer** | password:**password**

### Via SQLClient (e.g mysql workbench)
Establish connection from local machine via sqlclient using the following settings
```
host:128.199.82.128
port:3306
user:developer 
password:password
```
