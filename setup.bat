@echo off
echo ===================================================
echo   Rasel Bepari Portfolio - Setup
echo ===================================================
echo.
echo [1/5] Installing PHP dependencies...
call composer install
echo.
echo [2/5] Generating application key...
call php artisan key:generate
echo.
echo [3/5] Creating the storage symlink...
call php artisan storage:link
echo.
echo [4/5] Running database migrations...
call php artisan migrate
echo.
echo [5/5] Seeding the database with sample data...
call php artisan db:seed
echo.
echo ===================================================
echo   Setup complete!
echo.
echo   Start the server with:  php artisan serve
echo   Public site:            http://localhost:8000
echo   Admin panel:            http://localhost:8000/admin/login
echo.
echo   NOTE: The admin password was generated and shown
echo   in the console output above during seeding.
echo   Use "Forgot password?" on the login page to reset.
echo ===================================================
pause
