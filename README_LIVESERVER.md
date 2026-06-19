# 🚗 Car Accessories Management System - Quick Start Guide

## Live Server Setup (Windows with XAMPP)

### Quick Start (Recommended)

**Run these files in order:**

1. **SETUP_PROJECT.bat** - Copies project files to XAMPP
   - Double-click to execute
   - Waits for confirmation
   - Creates the `htdocs\cams` folder with all project files

2. **START_LIVESERVER.bat** - Starts the Live Server
   - Double-click to execute
   - Starts Apache and MySQL
   - Opens browser to http://localhost/cams/

### Manual Setup (If Batch Files Don't Work)

#### Option A: Copy Files to XAMPP
1. Open: `New folder\htdocs\`
2. Create folder: `cams`
3. Copy all project files (.html, .php, .css) into `cams` folder

#### Option B: Start XAMPP Services
1. Double-click: `New folder\xampp-control.exe`
2. Click "Start" next to Apache
3. Click "Start" next to MySQL
4. Wait for them to show "Running"

#### Option C: Create Database
1. Open browser: `http://localhost/phpmyadmin`
2. Click "New" on the left
3. Database name: `cams_db`
4. Click "Create"

#### Option D: Access Your Project
1. Open browser
2. Type: `http://localhost/cams/`
3. Done! Your project is live

---

## 📋 Files Included

### Web Pages
- `home.html` - Homepage
- `shop.html` - Product shop
- `garage.html` - Vehicle garage
- `cart.html` - Shopping cart
- `booking.html` - Booking system
- `profile.html` - User profile
- `settings.html` - User settings
- `payment.html` - Payment page

### Admin Pages
- `admin_dashboard.html` - Admin dashboard
- `admin_login.html` - Admin login
- `admin_register.html` - Admin registration

### Backend Files
- `api_register.php` - User registration API
- `api_garage.php` - Garage management API
- `api_admin.php` - Admin operations API
- `db_config.php` - Database configuration
- `register.php` - Registration handler

### Styling
- `style.css` - Global stylesheet

---

## 🔧 System Requirements

- **XAMPP** (included in "New folder")
- **Windows 7+**
- **Port 80** available (or modify to 8080)
- **RAM**: 4GB minimum recommended

---

## 🌐 Access URLs

| Page | URL |
|------|-----|
| Home | http://localhost/cams/ |
| Shop | http://localhost/cams/shop.html |
| Garage | http://localhost/cams/garage.html |
| Admin Panel | http://localhost/cams/admin_dashboard.html |
| Database | http://localhost/phpmyadmin/ |
| XAMPP Control | http://localhost/ |

---

## ⚠️ Troubleshooting

### "Cannot Access http://localhost/cams/"
- [ ] Apache is running (check XAMPP Control Panel)
- [ ] MySQL is running
- [ ] Files are in `New folder\htdocs\cams\`
- [ ] Firewall allows Apache on port 80

### "Connection failed" when using database
- [ ] MySQL is running in XAMPP Control Panel
- [ ] Database `cams_db` is created in phpMyAdmin
- [ ] User is `root` with empty password

### "Port 80 is in use"
- [ ] Change Apache port to 8080:
  - Edit: `New folder\apache\conf\httpd.conf`
  - Find: `Listen 80`
  - Change to: `Listen 8080`
  - URL becomes: `http://localhost:8080/cams/`

### PHP Code Not Running
- [ ] Files must be `.php` not `.txt`
- [ ] Apache must be restarted after configuration changes
- [ ] Check `New folder\apache\logs\error.log` for errors

---

## 🎯 Database Setup

### Create Tables (if not already created)

1. Open phpMyAdmin: `http://localhost/phpmyadmin/`
2. Select database: `cams_db`
3. Create tables as needed:

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE vehicles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    year INT NOT NULL,
    make VARCHAR(50) NOT NULL,
    model VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50),
    price DECIMAL(10, 2),
    compatibility VARCHAR(50) DEFAULT 'Universal',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 🛑 Stopping the Server

### Graceful Shutdown
1. Open XAMPP Control Panel
2. Click "Stop" for Apache
3. Click "Stop" for MySQL
4. Close the panel

### Quick Stop
- Double-click: `New folder\apache_stop.bat`
- Double-click: `New folder\mysql_stop.bat`

---

## 📝 Default Credentials

| Service | Host | User | Password |
|---------|------|------|----------|
| MySQL | localhost | root | (empty) |
| phpMyAdmin | localhost | root | (empty) |

---

## 🚀 Tips for Development

1. **Keep the console window open** while developing
2. **Restart Apache** if you modify `.php` files
3. **Clear browser cache** (Ctrl+Shift+Del) if changes don't appear
4. **Check error logs** in XAMPP for debugging
5. **Use phpMyAdmin** to manage databases visually

---

## 📞 Support

If you encounter issues:
1. Check XAMPP Control Panel - ensure services are "Running"
2. Review error logs in `New folder\apache\logs\`
3. Verify database connectivity in phpMyAdmin
4. Ensure port 80 is not blocked by firewall

---

**Last Updated:** 2026-06-19
**Project Name:** Car Accessories Management System
**Status:** Ready for Development
