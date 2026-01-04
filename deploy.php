<?php
/**
 * Laravel Deployment Helper for Hostinger
 * 
 * INSTRUCTIONS:
 * 1. Upload this file to your subdomain root (same folder as index.php)
 * 2. Visit: https://library.pcis.edu.ph/deploy.php in your browser
 * 3. DELETE THIS FILE immediately after successful execution!
 * 
 * WARNING: This file should NEVER remain on production server!
 */

echo "<!DOCTYPE html>
<html>
<head>
    <title>Laravel Deployment Helper</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #2563EB; }
        .success { color: #10b981; padding: 10px; background: #d1fae5; border-radius: 4px; margin: 10px 0; }
        .error { color: #ef4444; padding: 10px; background: #fee2e2; border-radius: 4px; margin: 10px 0; }
        .warning { color: #f59e0b; padding: 10px; background: #fef3c7; border-radius: 4px; margin: 10px 0; }
        .command { background: #1e293b; color: #10b981; padding: 15px; border-radius: 4px; margin: 10px 0; font-family: monospace; }
        .step { margin: 20px 0; padding: 15px; border-left: 4px solid #2563EB; background: #f8fafc; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🚀 Laravel Deployment Helper</h1>
        <p><strong>Current Directory:</strong> " . __DIR__ . "</p>
        <hr>
";

try {
    // Check if Laravel is properly installed
    if (!file_exists(__DIR__.'/vendor/autoload.php')) {
        echo "<div class='error'>❌ ERROR: vendor/autoload.php not found! Run 'composer install' first.</div>";
        echo "</div></body></html>";
        exit;
    }

    if (!file_exists(__DIR__.'/bootstrap/app.php')) {
        echo "<div class='error'>❌ ERROR: bootstrap/app.php not found! Check your Laravel installation.</div>";
        echo "</div></body></html>";
        exit;
    }

    // Load Laravel
    require __DIR__.'/vendor/autoload.php';
    $app = require_once __DIR__.'/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

    echo "<div class='step'><h3>Step 1: Clearing Caches</h3>";
    
    // Clear configuration cache
    echo "<div class='command'>$ php artisan config:clear</div>";
    $kernel->call('config:clear');
    echo "<div class='success'>✓ Configuration cache cleared</div>";
    
    // Clear application cache
    echo "<div class='command'>$ php artisan cache:clear</div>";
    $kernel->call('cache:clear');
    echo "<div class='success'>✓ Application cache cleared</div>";
    
    // Clear route cache
    echo "<div class='command'>$ php artisan route:clear</div>";
    $kernel->call('route:clear');
    echo "<div class='success'>✓ Route cache cleared</div>";
    
    // Clear view cache
    echo "<div class='command'>$ php artisan view:clear</div>";
    $kernel->call('view:clear');
    echo "<div class='success'>✓ View cache cleared</div>";
    
    echo "</div>";

    echo "<div class='step'><h3>Step 2: Optimizing for Production</h3>";
    
    // Cache configuration
    echo "<div class='command'>$ php artisan config:cache</div>";
    $kernel->call('config:cache');
    echo "<div class='success'>✓ Configuration cached</div>";
    
    // Cache routes
    echo "<div class='command'>$ php artisan route:cache</div>";
    $kernel->call('route:cache');
    echo "<div class='success'>✓ Routes cached</div>";
    
    // Cache views
    echo "<div class='command'>$ php artisan view:cache</div>";
    $kernel->call('view:cache');
    echo "<div class='success'>✓ Views cached</div>";
    
    echo "</div>";

    echo "<div class='step'><h3>Step 3: Creating Storage Link</h3>";
    echo "<div class='command'>$ php artisan storage:link</div>";
    
    try {
        $kernel->call('storage:link');
        echo "<div class='success'>✓ Storage link created successfully</div>";
    } catch (Exception $e) {
        echo "<div class='warning'>⚠ Storage link creation failed: " . $e->getMessage() . "</div>";
        echo "<div class='warning'>You may need to create this manually or upload images directly to /images/ folder</div>";
    }
    
    echo "</div>";

    // Check environment
    echo "<div class='step'><h3>Step 4: Environment Check</h3>";
    
    $env = app()->environment();
    echo "<p><strong>Environment:</strong> " . $env . "</p>";
    
    if ($env !== 'production') {
        echo "<div class='warning'>⚠ WARNING: APP_ENV is not set to 'production' in .env file</div>";
    } else {
        echo "<div class='success'>✓ Environment is set to production</div>";
    }
    
    $debug = config('app.debug');
    if ($debug) {
        echo "<div class='warning'>⚠ WARNING: APP_DEBUG is TRUE! Set to false in .env for production</div>";
    } else {
        echo "<div class='success'>✓ Debug mode is disabled</div>";
    }
    
    echo "<p><strong>App URL:</strong> " . config('app.url') . "</p>";
    echo "<p><strong>Database:</strong> " . config('database.connections.mysql.database') . "</p>";
    
    echo "</div>";

    // Test database connection
    echo "<div class='step'><h3>Step 5: Database Connection Test</h3>";
    
    try {
        DB::connection()->getPdo();
        echo "<div class='success'>✓ Database connection successful!</div>";
        
        // Count tables
        $tables = DB::select('SHOW TABLES');
        echo "<p>Found " . count($tables) . " tables in database</p>";
        
    } catch (Exception $e) {
        echo "<div class='error'>❌ Database connection failed: " . $e->getMessage() . "</div>";
        echo "<div class='warning'>Check your .env file database credentials</div>";
    }
    
    echo "</div>";

    // Final instructions
    echo "<div class='step' style='border-left-color: #ef4444;'>";
    echo "<h3>⚠️ CRITICAL: Final Steps</h3>";
    echo "<ol>";
    echo "<li><strong>DELETE THIS FILE (deploy.php) IMMEDIATELY!</strong></li>";
    echo "<li>Verify your site works: <a href='/' target='_blank'>Visit Homepage</a></li>";
    echo "<li>Test login functionality: <a href='/login' target='_blank'>Visit Login Page</a></li>";
    echo "<li>Check .env file has APP_DEBUG=false</li>";
    echo "<li>Ensure .env file is protected (not accessible via browser)</li>";
    echo "</ol>";
    echo "</div>";

    echo "<div class='success'>";
    echo "<h3>✅ Deployment Commands Executed Successfully!</h3>";
    echo "<p>Your Laravel application should now be ready for production use.</p>";
    echo "</div>";

} catch (Exception $e) {
    echo "<div class='error'>";
    echo "<h3>❌ Error Occurred</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
    echo "</div>";
}

echo "
    </div>
</body>
</html>";
?>
