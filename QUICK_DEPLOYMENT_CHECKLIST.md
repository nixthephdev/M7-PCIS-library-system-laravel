# ✅ Quick Deployment Checklist for Hostinger

## Before Upload
- [ ] Create `.env` file with production settings
- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Set `APP_URL=https://library.pcis.edu.ph` in `.env`
- [ ] Generate `APP_KEY` using `php artisan key:generate`
- [ ] Create ZIP of entire project (exclude `node_modules/`, `.git/`)

## In Hostinger hPanel
- [ ] Create subdomain: `library.pcis.edu.ph`
- [ ] Note the document root path (e.g., `/home/u816959808/domains/pcis.edu.ph/public_html/library`)
- [ ] Create MySQL database (e.g., `u816959808_library_db`)
- [ ] Create database user with strong password
- [ ] Grant ALL PRIVILEGES to user on database
- [ ] Note database credentials for `.env` file

## Upload Files
- [ ] Upload ZIP file to subdomain folder via File Manager
- [ ] Extract ZIP file
- [ ] Delete ZIP file after extraction

## Restructure for Subdomain
- [ ] Move ALL files from `public/` folder to root of subdomain
- [ ] Edit `index.php` - change `__DIR__.'/../vendor/autoload.php'` to `__DIR__.'/vendor/autoload.php'`
- [ ] Edit `index.php` - change `__DIR__.'/../bootstrap/app.php'` to `__DIR__.'/bootstrap/app.php'`
- [ ] Ensure `.htaccess` is in root (moved from public/)
- [ ] Optionally replace `.htaccess` with `.htaccess.production` for enhanced security

## Set Permissions
- [ ] Set `storage/` folder to 755
- [ ] Set `bootstrap/cache/` folder to 755
- [ ] Set `.env` file to 644

## Database Setup
- [ ] Go to phpMyAdmin in hPanel
- [ ] Select your database
- [ ] Import `db_backup/library_db_final.sql`
- [ ] Verify all tables created successfully

## Configure Environment
- [ ] Edit `.env` file on server with correct database credentials:
  ```
  DB_HOST=localhost
  DB_DATABASE=u816959808_library_db
  DB_USERNAME=u816959808_library_user
  DB_PASSWORD=your_password_here
  ```

## Run Deployment Commands
- [ ] Upload `deploy.php` to root
- [ ] Visit `https://library.pcis.edu.ph/deploy.php` in browser
- [ ] Verify all commands executed successfully
- [ ] **DELETE `deploy.php` immediately!**

## Test Your Site
- [ ] Visit `https://library.pcis.edu.ph`
- [ ] Check homepage loads correctly
- [ ] Visit `/login` page
- [ ] Test login with admin credentials
- [ ] Check dashboard functionality
- [ ] Verify images/avatars display
- [ ] Test dark/light mode toggle
- [ ] Check inventory page
- [ ] Test circulation features

## Security Final Steps
- [ ] Verify `.env` file is NOT accessible via browser (try visiting `https://library.pcis.edu.ph/.env`)
- [ ] Confirm `APP_DEBUG=false` in `.env`
- [ ] Change default admin password
- [ ] Enable HTTPS (should be automatic with Hostinger)
- [ ] Test all forms and features

## Post-Deployment
- [ ] Backup database from phpMyAdmin
- [ ] Document admin credentials securely
- [ ] Set up regular backup schedule
- [ ] Monitor `storage/logs/laravel.log` for errors

---

## Common Issues & Quick Fixes

| Issue | Quick Fix |
|-------|-----------|
| 500 Error | Check `.env` database credentials |
| 404 on routes | Verify `.htaccess` in root, check `index.php` paths |
| CSS not loading | Clear cache via `deploy.php`, check `APP_URL` |
| Permission denied | Set `storage/` and `bootstrap/cache/` to 755 |
| Database error | Verify credentials, check host is `localhost` |
| Images not showing | Run storage:link via `deploy.php` |

---

## Emergency Rollback
If something goes wrong:
1. Restore database from backup in phpMyAdmin
2. Re-upload files from local backup
3. Check error logs in `storage/logs/laravel.log`
4. Contact Hostinger support if server issue

---

**Time Estimate:** 30-45 minutes for complete deployment
**Difficulty:** Intermediate
**Support:** Refer to `HOSTINGER_DEPLOYMENT_GUIDE.md` for detailed instructions
