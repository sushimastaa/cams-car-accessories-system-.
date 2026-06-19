@echo off
REM Car Accessories Management System - Auto Setup
REM This script copies project files to XAMPP htdocs and creates database

echo.
echo ====================================================
echo  Car Accessories Management System - Auto Setup
echo ====================================================
echo.

REM Define paths
set XAMPP_PATH=%~dp0New folder
set HTDOCS_PATH=%XAMPP_PATH%\htdocs\cams
set PROJECT_PATH=%~dp0

echo.
echo Step 1: Creating project directory in htdocs...
if not exist "%HTDOCS_PATH%" mkdir "%HTDOCS_PATH%"
echo Created: %HTDOCS_PATH%

echo.
echo Step 2: Copying project files...
REM Copy HTML files
copy "%PROJECT_PATH%*.html" "%HTDOCS_PATH%\" /Y >nul
echo Copied: HTML files

REM Copy PHP files
copy "%PROJECT_PATH%*.php" "%HTDOCS_PATH%\" /Y >nul
echo Copied: PHP files

REM Copy CSS files
copy "%PROJECT_PATH%*.css" "%HTDOCS_PATH%\" /Y >nul
echo Copied: CSS files

REM Copy images folder if exists
if exist "%PROJECT_PATH%images" (
    xcopy "%PROJECT_PATH%images" "%HTDOCS_PATH%\images\" /E /I /Y >nul
    echo Copied: Images folder
)

echo.
echo Step 3: Verifying setup...
if exist "%HTDOCS_PATH%\home.html" (
    echo [OK] Project files copied successfully
) else (
    echo [ERROR] Failed to copy files. Please check permissions.
    pause
    exit /b 1
)

echo.
echo ====================================================
echo  Setup Complete!
echo ====================================================
echo.
echo Project Location: %HTDOCS_PATH%
echo.
echo Next Steps:
echo 1. Run: START_LIVESERVER.bat
echo 2. Open: http://localhost/cams/ in your browser
echo 3. Access phpMyAdmin: http://localhost/phpmyadmin/
echo 4. Create database 'cams_db' in phpMyAdmin
echo.
echo ====================================================
echo.
pause
