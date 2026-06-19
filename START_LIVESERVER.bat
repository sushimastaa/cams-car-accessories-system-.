@echo off
REM Car Accessories Management System - Live Server Starter
REM This script starts XAMPP services

echo.
echo ====================================================
echo  Car Accessories Management System - Live Server
echo ====================================================
echo.

REM Change to XAMPP directory
cd /d "%~dp0New folder"

REM Start XAMPP Control Panel
echo Starting XAMPP Control Panel...
start xampp-control.exe

REM Wait a moment for the GUI to load
timeout /t 2 /nobreak

REM Start Apache
echo.
echo Starting Apache Server...
start apache_start.bat

REM Start MySQL
echo Starting MySQL Server...
start mysql_start.bat

REM Wait for services to start
timeout /t 3 /nobreak

REM Open browser to project
echo.
echo Opening Live Server...
timeout /t 2 /nobreak

REM Check if project folder exists in htdocs
if exist "%~dp0New folder\htdocs\cams\home.html" (
    start http://localhost/cams/home.html
) else (
    echo.
    echo NOTE: Project files not found in htdocs\cams\
    echo Please copy your project files to: New folder\htdocs\cams\
    echo Then open: http://localhost/cams/ in your browser
    start http://localhost/phpmyadmin/
)

echo.
echo ====================================================
echo  Services Started Successfully!
echo ====================================================
echo.
echo Access your application at:
echo   http://localhost/cams/
echo.
echo phpMyAdmin available at:
echo   http://localhost/phpmyadmin/
echo.
echo To stop services, close the XAMPP Control Panel
echo or run: New folder\apache_stop.bat and mysql_stop.bat
echo.
pause
