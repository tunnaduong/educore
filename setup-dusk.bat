@echo off
echo Setting up Dusk tests...

echo Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

echo Migrate database for testing
php artisan migrate:fresh --env=dusk

echo Seed database with test data
php artisan db:seed --env=dusk

echo Run basic test first
echo Running basic test...
php artisan dusk tests/Browser/BasicTest.php

if %ERRORLEVEL% EQU 0 (
    echo Basic test passed! You can now run other tests.
    echo.
    echo Available commands:
    echo   php artisan dusk tests/Browser/AuthTest.php
    echo   php artisan dusk tests/Browser/AdminTest.php
    echo   php artisan dusk tests/Browser/TeacherTest.php
    echo   php artisan dusk tests/Browser/StudentTest.php
    echo   php artisan dusk tests/Browser/AITest.php
    echo   php artisan dusk tests/Browser/NavigationTest.php
    echo   php artisan dusk tests/Browser/ErrorHandlingTest.php
    echo.
    echo Or run all tests:
    echo   php artisan dusk
) else (
    echo Basic test failed! Check the errors above.
)

pause 