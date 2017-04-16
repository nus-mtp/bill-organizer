# Setup guide
## 1. Prepare development server
### Option 1: Using vagrant  
Follow instructions at https://laravel.com/docs/5.4/homestead but use the custom Homestead in [TeddyHartanto/homestead](https://github.com/TeddyHartanto/homestead) instead of the official one. After running `init.sh` or `init.bat`, in homestead folder, copy `./homestead_conf_dir/after.sh` to your homestead config directory (might be `~/.homestead`) and follow the rest of the instructions in the above website.

Jump to **section 4. Setup the project** number 3.

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

## 3. Install the following dependencies
### Ubuntu 16.04
1. Ghostscript  
   `sudo apt-get install ghostscript libgs-dev`
2. ImageMagick
   Follow the instructions [here](https://www.imagemagick.org/script/install-source.php).  
   Also, during the configure phase, use the `--with-gslib=yes` flag. ie: `./configure --with-gslib=yes`
3. PHP Imagick extension
   `sudo apt install php-imagick` (for php >=7.0)
4. Tesseract
   `sudo apt-get install tesseract-ocr`

### Windows
1. Ghostscript
   Download the `.exe` file from [here](https://sourceforge.net/projects/ghostscript/files/GPL%20Ghostscript/9.09/)
2. ImageMagick
   Follow the instructions [here](https://www.imagemagick.org/script/download.php#windows)
   If there's any configuration asking about ghostscript, choose yes?
3. PHP Imagick extension
   Try [this](https://refreshless.com/blog/imagick-pecl-imagemagick-windows/)?
4. Tesseract
   Download version 3.02 from [here](https://sourceforge.net/projects/tesseract-ocr-alt/files/). I'm pretty sure you should download `tesseract-ocr-setup-3.02.02.exe`, but not sure if `tesseract-3.02.02-win32-lib-include-dirs.zip` is needed as well. Try it out.

## 4. Setup the project
1. clone project from github
```bash
git clone git@github.com:nus-mtp/bill-organizer.git
```
2. install dependencies
```bash
npm install npm@latest
composer install
npm install
```
3. setup .env file
```
Follow the format in .env.example
```

4. start development server
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
