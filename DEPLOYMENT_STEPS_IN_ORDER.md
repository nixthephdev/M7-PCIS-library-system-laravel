# 🚀 CORRECT DEPLOYMENT ORDER - Start Here!

## Current Status: ❌ Site Not Accessible
**Error:** ERR_TUNNEL_CONNECTION_FAILED at `https://library.pcis.edu.ph/deploy.php`

**This is NORMAL!** You haven't deployed yet. Follow these steps:

---

## STEP-BY-STEP DEPLOYMENT (Do These IN ORDER)

### ✅ STEP 1: Login to Hostinger
1. Go to https://hostinger.com
2. Login to your account
3. Access **hPanel** (Hostinger Control Panel)

---

### ✅ STEP 2: Create the Subdomain
1. In hPanel, click **"Domains"** in the left sidebar
2. Click **"Subdomains"**
3. Click **"Create Subdomain"** button
4. Enter: `library` (it will become library.pcis.edu.ph)
5. Click **"Create"**
6. **IMPORTANT:** Note the document root path shown (example: `/home/u816959808/domains/pcis.edu.ph/public_html/library`)
7. Wait 5-10 minutes for DNS propagation

---

### ✅ STEP 3: Create MySQL Database
1. In hPanel, click **"Databases"** → **"MySQL Databases"**
2. Click **"Create New Database"**
   - Database Name: `library_db` (Hostinger will add prefix like `u816959808_library_db`)
   - Click **"Create"**
3. Click **"Create New User"**
   - Username: `library_user` (will become `u816959808_library_user`)
   - Password: Create a STRONG password (save it!)
   - Click **"Create"**
4. **Assign User to Database:**
   - Select your database: `u816959808_library_db`
   - Select your user: `u816959808_library_user`
   - Grant **ALL PRIVILEGES**
   - Click **"Add"**

**SAVE THESE CREDENTIALS:**
```
Database: u816959808_library_db
Username: u816959808_library_user
Password: [your password]
Host: localhost
```

---

### ✅ STEP 4: Prepare Your Files Locally

1. **Create/Update `.env` file** in your project root:
   ```env
   APP_NAME="M7 PCIS Library"
   APP_ENV=production
   APP_KEY=base64:YOUR_KEY_HERE
   APP_DEBUG=false
   APP_URL=https://library.pcis.edu.ph

   LOG_CHANNEL=stack
   LOG_LEVEL=error

   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=u816959808_library_db
   DB_USERNAME=u816959808_library_user
   DB_PASSWORD=YOUR_ACTUAL_PASSWORD_HERE

   SESSION_DRIVER=file
   SESSION_LIFETIME=120
   ```

2. **Generate APP_KEY** (if not already set):
   ```bash
   php artisan key:generate
   ```
   Copy the key from `.env` file

3. **Create ZIP file** of your project:
   - Include: `app/`, `bootstrap/`, `config/`, `database/`, `public/`, `resources/`, `routes/`, `storage/`, `vendor/`
   - Include: `.env`, `.htaccess`, `artisan`, `composer.json`, `composer.lock`, `deploy.php`
   - **EXCLUDE:** `node_modules/`, `.git/`, `vendor.zip`, `tests/`

---

### ✅ STEP 5: Upload Files to Hostinger

**Option A: Using File Manager (Easier)**
1. In hPanel, go to **"Files"** → **"File Manager"**
2. Navigate to your subdomain folder: `/domains/pcis.edu.ph/public_html/library/`
3. Click **"Upload"** button
4. Upload your ZIP file
5. After upload completes, right-click the ZIP file
6. Select **"Extract"**
7. Wait for extraction to complete
8. Delete the ZIP file

**Option B: Using FTP (FileZilla)**
1. Get FTP credentials from hPanel → "Files" → "FTP Accounts"
2. Connect via FileZilla
3. Upload all files to `/domains/pcis.edu.ph/public_html/library/`

---

### ✅ STEP 6: Restructure Files for Subdomain

**CRITICAL STEP:** Laravel's entry point is in `public/` but Hostinger expects files in root.

Using File Manager:
1. Navigate to `/library/public/` folder
2. **Move these files to `/library/` (parent folder):**
   - `index.php`
   - `.htaccess`
   - `favicon.ico`
   - `robots.txt`
   - `images/` folder (if exists)

3. **Edit `index.php`** (now in `/library/` root):
   - Find line: `require __DIR__.'/../vendor/autoload.php';`
   - Change to: `require __DIR__.'/vendor/autoload.php';`
   - Find line: `$app = require_once __DIR__.'/../bootstrap/app.php';`
   - Change to: `$app = require_once __DIR__.'/bootstrap/app.php';`
   - Save the file

4. **Optional:** Replace `.htaccess` with `.htaccess.production` for better security

---

### ✅ STEP 7: Set File Permissions

Using File Manager:
1. Right-click `storage/` folder → **Permissions** → Set to **755**
2. Right-click `bootstrap/cache/` folder → **Permissions** → Set to **755**
3. Right-click `.env` file → **Permissions** → Set to **644**

---

### ✅ STEP 8: Import Database

1. In hPanel, go to **"Databases"** → **"phpMyAdmin"**
2. Click on your database: `u816959808_library_db`
3. Click **"Import"** tab at the top
4. Click **"Choose File"**
5. Select: `db_backup/library_db_final.sql` from your computer
6. Scroll down and click **"Go"**
7. Wait for "Import has been successfully finished" message
8. Click **"Structure"** tab to verify tables were created

---

### ✅ STEP 9: Run Deployment Script

1. **Now visit:** `https://library.pcis.edu.ph/deploy.php`
2. You should see a page with deployment commands running
3. Verify all steps show ✓ (green checkmarks)
4. **IMMEDIATELY DELETE `deploy.php`** after successful execution:
   - Go back to File Manager
   - Find `deploy.php` in `/library/` folder
   - Right-click → Delete

---

### ✅ STEP 10: Test Your Site

1. Visit: `https://library.pcis.edu.ph`
2. You should see the landing page
3. Click **"Login"** or visit: `https://library.pcis.edu.ph/login`
4. Try logging in with your admin credentials
5. Test the dashboard, inventory, circulation pages

---

## 🔧 Troubleshooting

### If you still get "Site can't be reached":
- Wait 10-30 minutes for DNS propagation
- Try accessing via IP address (get from Hostinger)
- Clear your browser cache
- Try incognito/private browsing mode

### If you get 500 Internal Server Error:
1. Check `.env` file has correct database credentials
2. Verify file permissions (storage/ and bootstrap/cache/ = 755)
3. Check `storage/logs/laravel.log` for errors

### If CSS/Images don't load:
1. Clear cache by visiting `/deploy.php` again
2. Check `APP_URL` in `.env` matches your subdomain
3. Verify `.htaccess` is in the root folder

### If database connection fails:
1. Double-check credentials in `.env`
2. Verify database and user exist in phpMyAdmin
3. Try `DB_HOST=127.0.0.1` instead of `localhost`

---

## 📞 Need Help?

1. Check `storage/logs/laravel.log` for detailed errors
2. Review the full guide: `HOSTINGER_DEPLOYMENT_GUIDE.md`
3. Contact Hostinger support for server-specific issues
4. Check Hostinger knowledge base

---

## ✅ Success Checklist

- [ ] Subdomain created in Hostinger
- [ ] Database created with user and privileges
- [ ] Files uploaded and extracted
- [ ] Files restructured (index.php in root)
- [ ] index.php paths updated
- [ ] Permissions set correctly
- [ ] Database imported successfully
- [ ] deploy.php executed successfully
- [ ] deploy.php deleted
- [ ] Site loads at https://library.pcis.edu.ph
- [ ] Can login successfully
- [ ] Dashboard works
- [ ] All features functional

---

**Current Step:** You need to start from STEP 1 above!
**Time Required:** 30-45 minutes
**Difficulty:** Intermediate

Good luck with your deployment! 🚀
