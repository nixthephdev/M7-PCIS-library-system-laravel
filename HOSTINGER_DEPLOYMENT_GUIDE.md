# 🚀 Hostinger Deployment Guide - M7 PCIS Library System
## Deploying Laravel on a Subdomain

---

## 📋 Prerequisites

Before starting, ensure you have:
- ✅ Active Hostinger hosting account (with PHP 8.0+ support)
- ✅ Access to Hostinger hPanel
- ✅ Your subdomain created (e.g., `library.pcis.edu.ph`)
- ✅ FTP/SFTP client (FileZilla recommended) OR use Hostinger File Manager
- ✅ Database backup file (from `db_backup/` folder)
- ✅ All project files ready

---

## 🎯 STEP-BY-STEP DEPLOYMENT PROCESS

### **STEP 1: Create Subdomain in Hostinger**

1. **Login to hPanel** (Hostinger Control Panel)
2. Navigate to **"Domains"** → **"Subdomains"**
3. Click **"Create Subdomain"**
4. Enter subdomain name: `library` (will become `library.pcis.edu.ph`)
5. **IMPORTANT:** Note the document root path shown (usually `/home/u816959808/domains/pcis.edu.ph/public_html/library`)
6. Click **"Create"**

---

### **STEP 2: Prepare Your Local Files**

1. **Create a `.env` file** in your project root (if not exists):
   ```bash
   # Copy from .env.example
   cp .env.example .env
   ```

2. **Edit `.env` file** with production settings:
   ```env
   APP_NAME="M7 PCIS Library"
   APP_ENV=production
   APP_KEY=base64:YOUR_KEY_HERE
   APP_DEBUG=false
   APP_URL=https://library.pcis.edu.ph

   LOG_CHANNEL=stack
   LOG_DEPRECATIONS_CHANNEL=null
   LOG_LEVEL=error

   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=u816959808_library_db
   DB_USERNAME=u816959808_library_user
   DB_PASSWORD=YOUR_STRONG_PASSWORD

   BROADCAST_DRIVER=log
   CACHE_DRIVER=file
   FILESYSTEM_DISK=local
   QUEUE_CONNECTION=sync
   SESSION_DRIVER=file
   SESSION_LIFETIME=120
   ```

3. **Generate Application Key** (if not set):
   ```bash
   php artisan key:generate
   ```
   Copy the generated key to your `.env` file.

4. **Create a ZIP file** of your entire project:
   - Include ALL folders: `app/`, `bootstrap/`, `config/`, `database/`, `public/`, `resources/`, `routes/`, `storage/`, `vendor/`
   - Include files: `.env`, `.htaccess`, `artisan`, `composer.json`, `composer.lock`
   - **EXCLUDE:** `node_modules/`, `.git/`, `vendor.zip`

---

### **STEP 3: Create MySQL Database in Hostinger**

1. In hPanel, go to **"Databases"** → **"MySQL Databases"**
2. Click **"Create New Database"**
   - Database Name: `u816959808_library_db` (Hostinger adds prefix automatically)
   - Click **"Create"**

3. **Create Database User:**
   - Username: `u816959808_library_user`
   - Password: Create a strong password (save it!)
   - Click **"Create"**

4. **Assign User to Database:**
   - Select the database and user
   - Grant **ALL PRIVILEGES**
   - Click **"Add"**

5. **Note down these credentials:**
   ```
   Database Name: u816959808_library_db
   Username: u816959808_library_user
   Password: [your password]
   Host: localhost
   ```

---

### **STEP 4: Upload Files to Hostinger**

#### **Option A: Using File Manager (Recommended for beginners)**

1. In hPanel, go to **"Files"** → **"File Manager"**
2. Navigate to your subdomain folder: `/domains/pcis.edu.ph/public_html/library/`
3. **Upload your ZIP file**
4. Right-click the ZIP → **"Extract"**
5. Delete the ZIP file after extraction

#### **Option B: Using FTP/SFTP (FileZilla)**

1. **Get FTP credentials** from hPanel → "Files" → "FTP Accounts"
2. **Connect via FileZilla:**
   - Host: `ftp.pcis.edu.ph` or your server IP
   - Username: Your FTP username
   - Password: Your FTP password
   - Port: 21 (FTP) or 22 (SFTP)

3. **Upload all files** to `/domains/pcis.edu.ph/public_html/library/`

---

### **STEP 5: Configure Laravel for Subdomain**

**CRITICAL:** Laravel's entry point is `public/index.php`, but Hostinger expects files in the subdomain root.

#### **Solution: Restructure Files**

1. **Move all files from `public/` folder to subdomain root:**
   ```
   /library/
   ├── index.php (moved from public/)
   ├── .htaccess (moved from public/)
   ├── favicon.ico
   ├── robots.txt
   ├── images/
   ├── app/
   ├── bootstrap/
   ├── config/
   ├── database/
   ├── resources/
   ├── routes/
   ├── storage/
   ├── vendor/
   └── .env
   ```

2. **Edit `index.php`** (now in root):
   
   Find these lines:
   ```php
   require __DIR__.'/../vendor/autoload.php';
   $app = require_once __DIR__.'/../bootstrap/app.php';
   ```

   Change to:
   ```php
   require __DIR__.'/vendor/autoload.php';
   $app = require_once __DIR__.'/bootstrap/app.php';
   ```

3. **Update `.htaccess`** (now in root) - should already be correct from `public/.htaccess`

---

### **STEP 6: Set Correct Permissions**

Using File Manager or FTP:

1. **Set folder permissions to 755:**
   - `bootstrap/cache/`
   - `storage/`
   - `storage/app/`
   - `storage/framework/`
   - `storage/logs/`

2. **Set file permissions to 644:**
   - `.env`
   - All files in `storage/` subfolders

**Via SSH (if available):**
```bash
cd /home/u816959808/domains/pcis.edu.ph/public_html/library
chmod -R 755 storage bootstrap/cache
chmod 644 .env
```

---

### **STEP 7: Import Database**

1. In hPanel, go to **"Databases"** → **"phpMyAdmin"**
2. Select your database: `u816959808_library_db`
3. Click **"Import"** tab
4. **Choose file:** Upload `db_backup/library_db_final.sql`
5. Click **"Go"**
6. Wait for success message

**Verify tables created:**
- users
- books
- book_copies
- borrow_transactions
- migrations
- password_resets
- personal_access_tokens
- failed_jobs

---

### **STEP 8: Configure Environment File**

1. **Edit `.env` file** on server (via File Manager):
   - Update `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` with Hostinger credentials
   - Set `APP_URL=https://library.pcis.edu.ph`
   - Set `APP_DEBUG=false`
   - Set `APP_ENV=production`

2. **Verify `.env` settings:**
   ```env
   APP_URL=https://library.pcis.edu.ph
   DB_HOST=localhost
   DB_DATABASE=u816959808_library_db
   DB_USERNAME=u816959808_library_user
   DB_PASSWORD=your_actual_password
   ```

---

### **STEP 9: Run Laravel Artisan Commands**

**If you have SSH access:**

```bash
cd /home/u816959808/domains/pcis.edu.ph/public_html/library

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link
php artisan storage:link
```

**If NO SSH access:**
- Create a temporary PHP file `deploy.php` in root:

```php
<?php
// deploy.php - DELETE THIS FILE AFTER USE!
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "Clearing caches...\n";
$kernel->call('config:clear');
$kernel->call('cache:clear');
$kernel->call('route:clear');
$kernel->call('view:clear');

echo "Optimizing...\n";
$kernel->call('config:cache');
$kernel->call('route:cache');
$kernel->call('view:cache');

echo "Creating storage link...\n";
$kernel->call('storage:link');

echo "Done! DELETE THIS FILE NOW!";
?>
```

Visit: `https://library.pcis.edu.ph/deploy.php`
Then **DELETE** `deploy.php` immediately!

---

### **STEP 10: Create Storage Symbolic Link**

Laravel needs a symbolic link from `public/storage` to `storage/app/public`.

**Manual method (if artisan fails):**

1. In File Manager, navigate to `/library/`
2. Create folder: `storage/app/public/avatars/` (if not exists)
3. Note: Some hosts don't support symlinks; you may need to:
   - Upload avatars directly to `/library/images/avatars/`
   - Update code to use `/images/avatars/` instead of `/storage/avatars/`

---

### **STEP 11: Test Your Deployment**

1. **Visit:** `https://library.pcis.edu.ph`
2. **Expected:** Landing page loads with logo and styling
3. **Test login:** Navigate to `/login`
4. **Check database connection:** Try logging in with existing credentials

**Common Issues & Fixes:**

| Issue | Solution |
|-------|----------|
| 500 Error | Check `.env` file, verify DB credentials |
| 404 on routes | Verify `.htaccess` is in root, check mod_rewrite |
| CSS not loading | Check `APP_URL` in `.env`, clear cache |
| Permission denied | Set `storage/` and `bootstrap/cache/` to 755 |
| Database connection failed | Verify DB credentials, check host is `localhost` |

---

### **STEP 12: Security Hardening**

1. **Protect sensitive files** - Create `.htaccess` in root (if not exists):
   ```apache
   # Deny access to sensitive files
   <FilesMatch "^\.env">
       Order allow,deny
       Deny from all
   </FilesMatch>
   
   <FilesMatch "composer\.(json|lock)">
       Order allow,deny
       Deny from all
   </FilesMatch>
   ```

2. **Disable directory listing:**
   ```apache
   Options -Indexes
   ```

3. **Force HTTPS** (add to `.htaccess`):
   ```apache
   RewriteEngine On
   RewriteCond %{HTTPS} off
   RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
   ```

4. **Change default admin password** after first login!

---

## 🔧 POST-DEPLOYMENT CHECKLIST

- [ ] Site loads at `https://library.pcis.edu.ph`
- [ ] Login page accessible
- [ ] Can login with admin credentials
- [ ] Dashboard displays correctly
- [ ] Database queries work (check inventory, circulation)
- [ ] Images/avatars display properly
- [ ] Dark/Light mode toggle works
- [ ] Forms submit successfully
- [ ] No errors in browser console
- [ ] `.env` file is protected
- [ ] `APP_DEBUG=false` in production
- [ ] SSL certificate active (HTTPS)
- [ ] Changed default passwords

---

## 📞 TROUBLESHOOTING

### **Issue: "No input file specified"**
**Fix:** Check `index.php` paths are correct (removed `../`)

### **Issue: "Class not found"**
**Fix:** Run `composer install --optimize-autoloader --no-dev` via SSH

### **Issue: Images not loading**
**Fix:** 
1. Check `APP_URL` in `.env`
2. Clear cache: `php artisan config:clear`
3. Verify image paths in blade files

### **Issue: Database connection error**
**Fix:**
1. Verify credentials in `.env`
2. Check database exists in phpMyAdmin
3. Ensure user has privileges
4. Try `DB_HOST=127.0.0.1` instead of `localhost`

### **Issue: 500 Internal Server Error**
**Fix:**
1. Enable debug temporarily: `APP_DEBUG=true`
2. Check `storage/logs/laravel.log`
3. Verify file permissions
4. Check PHP version (needs 8.0+)

---

## 🎉 SUCCESS!

Your M7 PCIS Library Management System should now be live at:
**https://library.pcis.edu.ph**

**Default Admin Login:**
- Email: Check your database `users` table for admin account
- Password: As set in your local development

---

## 📝 MAINTENANCE TIPS

1. **Regular Backups:**
   - Export database weekly via phpMyAdmin
   - Download `/storage/app/public/avatars/` folder

2. **Monitor Logs:**
   - Check `storage/logs/laravel.log` for errors

3. **Update Dependencies:**
   - Run `composer update` periodically (in local, then upload)

4. **Clear Cache After Updates:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```

---

## 📧 SUPPORT

If you encounter issues:
1. Check Hostinger Knowledge Base
2. Review Laravel documentation
3. Check `storage/logs/laravel.log` for detailed errors
4. Contact Hostinger support for server-specific issues

---

**Deployment Guide Version:** 1.0  
**Last Updated:** January 2025  
**Developer:** Nikko Calumpiano
