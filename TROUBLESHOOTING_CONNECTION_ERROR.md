# 🔍 Troubleshooting "ERR_TUNNEL_CONNECTION_FAILED" Error

## Current Error:
```
This site can't be reached
https://library.pcis.edu.ph/deploy.php
ERR_TUNNEL_CONNECTION_FAILED
```

---

## 🎯 What This Error Means:

This error means **the browser cannot establish a connection to the server**. This happens when:

1. ❌ The subdomain doesn't exist yet
2. ❌ DNS hasn't propagated (can take 24-48 hours)
3. ❌ Files haven't been uploaded to the server
4. ❌ The subdomain is pointing to the wrong directory
5. ❌ Server/hosting is down

---

## 📋 DIAGNOSTIC CHECKLIST - Check These IN ORDER:

### ✅ Step 1: Verify Subdomain Was Created

1. **Login to Hostinger hPanel**
2. Go to **"Domains"** → **"Subdomains"**
3. **Check if `library` subdomain is listed**

**If NOT listed:**
- You need to create it first! (See DEPLOYMENT_STEPS_IN_ORDER.md Step 2)

**If listed:**
- Note the **Document Root** path (e.g., `/home/u816959808/domains/pcis.edu.ph/public_html/library`)
- Check the **Status** - should be "Active"
- Proceed to Step 2

---

### ✅ Step 2: Check DNS Propagation

**Option A: Use Online Tool**
1. Go to: https://www.whatsmydns.net/
2. Enter: `library.pcis.edu.ph`
3. Click "Search"
4. **Check results:**
   - ✅ Green checkmarks = DNS propagated
   - ❌ Red X's = Still propagating (wait 1-24 hours)

**Option B: Use Command Line**
```bash
# Windows
nslookup library.pcis.edu.ph

# Mac/Linux
dig library.pcis.edu.ph
```

**What to look for:**
- Should return an IP address
- If "NXDOMAIN" or "can't find" = DNS not propagated yet

---

### ✅ Step 3: Verify Files Were Uploaded

1. **Login to Hostinger hPanel**
2. Go to **"Files"** → **"File Manager"**
3. Navigate to: `/domains/pcis.edu.ph/public_html/library/`

**Check if these files exist:**
- [ ] `index.php`
- [ ] `.htaccess`
- [ ] `.env`
- [ ] `artisan`
- [ ] `composer.json`
- [ ] Folders: `app/`, `bootstrap/`, `config/`, `database/`, `public/`, `resources/`, `routes/`, `storage/`, `vendor/`

**If files are MISSING:**
- You need to upload them! (See DEPLOYMENT_STEPS_IN_ORDER.md Step 5)

**If files are in a `public/` subfolder:**
- You need to move them to root! (See DEPLOYMENT_STEPS_IN_ORDER.md Step 6)

---

### ✅ Step 4: Check Server Error Logs

**Method 1: Via File Manager**
1. In File Manager, navigate to: `/library/storage/logs/`
2. Look for `laravel.log` file
3. Right-click → **View** or **Download**
4. Check the **last few lines** for errors

**Method 2: Via hPanel**
1. In hPanel, go to **"Advanced"** → **"Error Logs"**
2. Select your domain: `pcis.edu.ph`
3. Look for recent errors related to `/library/`

**Common errors to look for:**
- `Permission denied` = Fix permissions (Step 7 in deployment guide)
- `No such file or directory` = Files not uploaded or wrong location
- `Database connection failed` = Check .env credentials
- `Class not found` = Vendor folder missing or corrupted

---

### ✅ Step 5: Test Basic Connectivity

**Try accessing just the subdomain root:**
1. Visit: `https://library.pcis.edu.ph` (without /deploy.php)

**Possible outcomes:**

**A) Same error (can't be reached):**
- DNS not propagated yet OR subdomain not created
- **Solution:** Wait 1-24 hours for DNS, or verify subdomain exists

**B) 404 Not Found:**
- DNS works but files missing or in wrong location
- **Solution:** Upload files or restructure (move from public/ to root)

**C) 500 Internal Server Error:**
- Files are there but configuration issue
- **Solution:** Check error logs, verify .env file, check permissions

**D) Blank white page:**
- PHP error with display_errors off
- **Solution:** Check storage/logs/laravel.log

**E) Laravel error page:**
- Good! Laravel is running, just needs configuration
- **Solution:** Follow remaining deployment steps

---

### ✅ Step 6: Verify PHP Version

Laravel 9 requires **PHP 8.0 or higher**

1. In hPanel, go to **"Advanced"** → **"PHP Configuration"**
2. Select your domain: `pcis.edu.ph`
3. Check **PHP Version** - should be 8.0, 8.1, or 8.2
4. If lower than 8.0, change it to 8.1 or 8.2

---

### ✅ Step 7: Check .htaccess File

1. In File Manager, navigate to `/library/`
2. Check if `.htaccess` file exists
3. Right-click → **View**

**Should contain:**
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

**If missing or different:**
- Upload the `.htaccess` from your `public/` folder
- Or use `.htaccess.production` file

---

## 🔧 SPECIFIC SOLUTIONS BY SCENARIO:

### Scenario 1: "I just created the subdomain"
**Solution:** Wait 10-30 minutes for DNS propagation, then try again

### Scenario 2: "I uploaded files but still getting error"
**Check:**
1. Files are in `/library/` root, not `/library/public/`
2. `index.php` is in `/library/` root
3. `.htaccess` is in `/library/` root
4. Permissions: `storage/` and `bootstrap/cache/` = 755

### Scenario 3: "DNS propagated but still can't connect"
**Try:**
1. Access via IP: `http://YOUR_SERVER_IP/library/`
2. Check if subdomain is pointing to correct directory in hPanel
3. Verify Apache/web server is running (contact Hostinger support)

### Scenario 4: "It worked before, now it doesn't"
**Check:**
1. Hosting account is active (not suspended)
2. Domain hasn't expired
3. SSL certificate is valid
4. Check Hostinger status page for outages

---

## 📞 WHERE TO FIND EXACT ERRORS:

### Location 1: Laravel Log File
**Path:** `/library/storage/logs/laravel.log`
**How to access:**
- File Manager → navigate to path → View file
- Look at the **bottom** of the file for most recent errors

### Location 2: Apache/PHP Error Logs
**Path:** hPanel → Advanced → Error Logs
**Look for:**
- Recent timestamps
- Errors mentioning `/library/` path
- PHP fatal errors, warnings

### Location 3: Browser Console
**How to access:**
1. Press `F12` in your browser
2. Click **"Console"** tab
3. Refresh the page
4. Look for red error messages

### Location 4: Network Tab
**How to access:**
1. Press `F12` in your browser
2. Click **"Network"** tab
3. Refresh the page
4. Click on the failed request
5. Check **"Response"** and **"Headers"** tabs

---

## 🚨 EMERGENCY CHECKLIST:

If you've done everything and still getting errors:

1. **Take screenshots of:**
   - [ ] The error in browser
   - [ ] Subdomain list in hPanel
   - [ ] File Manager showing `/library/` contents
   - [ ] Error logs (if any)

2. **Verify these basics:**
   - [ ] Hosting account is active
   - [ ] Domain is not expired
   - [ ] You're accessing the correct URL
   - [ ] You're not behind a firewall/VPN blocking the site

3. **Contact Hostinger Support with:**
   - Your subdomain: `library.pcis.edu.ph`
   - Error message: "ERR_TUNNEL_CONNECTION_FAILED"
   - What you've tried so far
   - Screenshots

---

## 💡 QUICK DIAGNOSTIC COMMAND:

**Try this in your browser:**

1. Visit: `https://library.pcis.edu.ph/` (just the root)
2. Note what happens
3. Visit: `http://library.pcis.edu.ph/` (HTTP not HTTPS)
4. Note what happens

**Report back with:**
- What error/page you see for each
- Any error codes
- Screenshots if possible

---

## ✅ NEXT STEPS:

Based on your findings above, you should now know:
1. **If subdomain exists** → If not, create it
2. **If DNS propagated** → If not, wait
3. **If files uploaded** → If not, upload them
4. **What the actual error is** → Check logs

Once you identify the specific issue, refer back to the appropriate section in:
- `DEPLOYMENT_STEPS_IN_ORDER.md`
- `HOSTINGER_DEPLOYMENT_GUIDE.md`

---

**Need more help?** Share:
1. What you see in `storage/logs/laravel.log`
2. What happens when you visit just `https://library.pcis.edu.ph`
3. Screenshots of your File Manager showing the `/library/` folder contents
