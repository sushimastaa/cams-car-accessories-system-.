# Car Accessories Management System - Live Server Setup Guide

## Prerequisites
- XAMPP installed (included in "New folder")
- Project files ready

## Setup Instructions

### Step 1: Copy Project Files to XAMPP htdocs
1. Navigate to: `New folder\htdocs\`
2. Create a new folder named `cams` (or your preferred name)
3. Copy all project files (.html, .php, .css, images) to `New folder\htdocs\cams\`

### Step 2: Start XAMPP Services

#### Start Apache Server:
```
Double-click: New folder\apache_start.bat
OR
Double-click: New folder\xampp_start.exe (starts all services)
```

#### Start MySQL Server:
```
Double-click: New folder\mysql_start.bat
```

### Step 3: Create Database

1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Create a new database named `cams_db`
3. Import the database schema if available, OR create tables manually

### Step 4: Access Your Project

**Local URL:** `http://localhost/cams/` (replace "cams" with your folder name)

**Main pages:**
- Home: `http://localhost/cams/home.html`
- Shop: `http://localhost/cams/shop.html`
- Garage: `http://localhost/cams/garage.html`
- Admin Dashboard: `http://localhost/cams/admin_dashboard.html`

### Step 5: Configure API Endpoints

All PHP files are configured to use `localhost`:
- Database Host: `localhost`
- Database Name: `cams_db`
- Database User: `root` (no password)

### API Endpoints Available:
- `api_register.php` - User registration
- `api_garage.php` - Garage management
- `api_admin.php` - Admin operations

## Troubleshooting

**Port Conflicts:**
If port 80 is in use, modify:
- `New folder\apache\conf\httpd.conf` → Change port to 8080
- Access URL: `http://localhost:8080/cams/`

**Database Connection Error:**
- Ensure MySQL is running
- Check `db_config.php` for correct credentials

**"Access Forbidden" Error:**
- Ensure files are in the correct htdocs folder
- Check file permissions

**PHP Not Executing:**
- Verify Apache is started and showing "running" in XAMPP Control Panel
- Check `New folder\apache\logs\error.log` for errors

## Stopping Services

```
Double-click: New folder\apache_stop.bat
Double-click: New folder\mysql_stop.bat
```

## Additional Resources

- phpMyAdmin: `http://localhost/phpmyadmin/`
- XAMPP Control Panel: `New folder\xampp-control.exe`
- MySQL Client: `New folder\mysql\bin\mysql.exe`

---
**Note:** Keep XAMPP running while developing. Close services when done to free system resources.
