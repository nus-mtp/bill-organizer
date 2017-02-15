ECHO ON
SETLOCAL ENABLEEXTENSIONS
echo "use run -install to install npm dependency and update packages"
echo "use run -semantic to compile/build semantic ui library"
echo "use run without any argument to launch php serve"

set npm_operation=%~1

IF "%npm_operation%"=="" (
   start cmd /k php artisan serve
   start cmd /k npm run watch
)

IF "%npm_operation%"=="install" (
   npm install
   npm update
)

IF "%npm_operation%"=="semantic" (
   cd semantic
   gulp build
   cd ..
) 







