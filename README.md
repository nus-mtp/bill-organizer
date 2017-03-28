Awesome!

[![CircleCI](https://circleci.com/gh/nus-mtp/bill-organizer/tree/master.svg?style=svg)](https://circleci.com/gh/nus-mtp/bill-organizer/tree/master)
## Database Schema
![img](http://i.imgur.com/W6u4fgx.png)

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
