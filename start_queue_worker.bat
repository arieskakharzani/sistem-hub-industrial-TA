@echo off
echo Starting Queue Worker for SIPPPHI...
echo.
echo This will process pending jobs and send notifications
echo Press Ctrl+C to stop
echo.

php artisan queue:work --tries=3 --timeout=60

pause 