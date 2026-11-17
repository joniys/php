<?php
function db_fail(Throwable $t): void {
    http_response_code(500);
    $pageTitle = 'Service unavailable'; $active = '';
    $local = in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1'], true);
    $detail = $t->getMessage();
    $hint = 'Please check that MySQL is running and that database.sql has been imported.';
    if (stripos($detail, 'Unknown database') !== false) $hint = 'The database "french_riviera" does not exist. Import database.sql in phpMyAdmin.';
    elseif (stripos($detail, 'Base table or view not found') !== false || stripos($detail, "doesn't exist") !== false) $hint = 'The tables are missing. Re-import database.sql in phpMyAdmin.';
    elseif (stripos($detail, 'Access denied') !== false) $hint = 'Wrong MySQL username or password. Edit DB_USER / DB_PASS in includes/bootstrap.php.';
    elseif (stripos($detail, 'Connection refused') !== false || stripos($detail, '2002') !== false) $hint = 'MySQL is not reachable. Start MySQL in XAMPP, or set the correct port in includes/bootstrap.php (DB_PORT).';
    elseif (stripos($detail, 'could not find driver') !== false) $hint = 'The pdo_mysql PHP extension is disabled. In php.ini remove the ";" before extension=pdo_mysql, then restart Apache.';
    require __DIR__ . '/header.php';
    echo '<section class="page-hero"><h1>Temporarily unavailable</h1><p>' . e($hint) . '</p>';
    if ($local) echo '<p style="font-size:.85rem;opacity:.8">Technical detail: ' . e($detail) . '</p>';
    echo '</section>';
    require __DIR__ . '/footer.php';
    exit;
}
