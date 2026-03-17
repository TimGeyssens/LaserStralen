<?php
// ─── LaserStralen Configuration ───

// Admin credentials
// Default password: LaserStralen2026!
// To change: run `php -r "echo password_hash('YourNewPassword', PASSWORD_DEFAULT);"` and paste below
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD_HASH', '$2y$12$wVDptnHIByQaAONQlUGIBeF1em3QYFHozEaORNWnl2eH6jZ2j.eHW');

// Data file path
define('DATA_FILE', __DIR__ . '/data/content.json');

// Site settings
define('SITE_NAME', 'LaserStralen');
