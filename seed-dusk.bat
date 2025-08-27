@echo off
echo Seeding Dusk test data...

REM Clear cache
php artisan cache:clear
php artisan config:clear

REM Migrate fresh và seed
php artisan migrate:fresh --env=dusk
php artisan db:seed --class=DuskTestSeeder --env=dusk

echo.
echo Dusk test data seeded successfully!
echo.
echo Test accounts available:
echo - Admin: admin@educore.com / password
echo - Teacher: teacher@educore.com / password  
echo - Student: student@educore.com / password
echo.
echo You can now run Dusk tests:
echo   php artisan dusk tests/Browser/BasicTest.php
echo   php artisan dusk tests/Browser/AuthTest.php
echo   php artisan dusk
echo.
pause 