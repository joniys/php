<?php
// Database settings (XAMPP defaults)
const DB_HOST = '127.0.0.1';
const DB_PORT = 3306; // change to 3307 if XAMPP MySQL runs on another port
const DB_NAME = 'french_riviera';
const DB_USER = 'root';
const DB_PASS = '';

const SITE_ADDRESS = 'Promenade des Anglais 12, 06000 Nice, France';
const SITE_PHONE   = '+33 4 93 00 00 00';
const SITE_EMAIL   = 'bonjour@frenchriviera.example';

session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
session_start();

function current_user(): ?array { return $_SESSION['user'] ?? null; }

function is_admin(): bool {
    $u = current_user();
    return $u && !empty($u['is_admin']);
}

// Every page requires a login, except pages that set $public = true before including this file.
if (empty($public) && !current_user()) { header('Location: login.php'); exit; }

function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
    return $pdo;
}

function e($v): string { return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function euro($v): string { return number_format((float)$v, 2, ',', ' ') . ' €'; }

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function csrf_valid(?string $t): bool { return is_string($t) && hash_equals($_SESSION['csrf'] ?? '', $t); }

// Prefer the real food photographs supplied with the SQL seed data. This also
// upgrades older databases that still contain the original SVG placeholder paths.
function dish_photo_url($storedImage, int $dishId): string {
    $photos = [
        1 => 'https://images.unsplash.com/photo-1547592180-85f173e0f1d5?auto=format&fit=crop&w=1200&q=85',
        2 => 'https://images.unsplash.com/photo-1476224203421-9ac39bcb3327?auto=format&fit=crop&w=1200&q=85',
        3 => 'https://images.unsplash.com/photo-1547592180-85f173e0f1d5?auto=format&fit=crop&w=1200&q=85',
        4 => 'https://images.unsplash.com/photo-1546833999-b9f581a1996d?auto=format&fit=crop&w=1200&q=85',
        5 => 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=1200&q=85',
        6 => 'https://images.unsplash.com/photo-1518895949257-7621c3c786d7?auto=format&fit=crop&w=1200&q=85',
        7 => 'https://images.unsplash.com/photo-1546833999-b9f581a1996d?auto=format&fit=crop&w=1200&q=85',
        8 => 'https://images.unsplash.com/photo-1547592180-85f173e0f1d5?auto=format&fit=crop&w=1200&q=85',
        9 => 'https://images.unsplash.com/photo-1510130387422-82bed34b37e1?auto=format&fit=crop&w=1200&q=85',
        10 => 'https://images.unsplash.com/photo-1559847844-5315695dadae?auto=format&fit=crop&w=1200&q=85',
        11 => 'https://images.unsplash.com/photo-1488477181946-6428a0291777?auto=format&fit=crop&w=1200&q=85',
        12 => 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?auto=format&fit=crop&w=1200&q=85',
        13 => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=1200&q=85',
        14 => 'https://images.unsplash.com/photo-1559737558-2f5a35f4523?auto=format&fit=crop&w=1200&q=85',
        15 => 'https://images.unsplash.com/photo-1510812431401-41d2bd2722f3?auto=format&fit=crop&w=1200&q=85',
        16 => 'https://images.unsplash.com/photo-1488477181946-6428a0291777?auto=format&fit=crop&w=1200&q=85',
        17 => 'https://images.unsplash.com/photo-1547592180-85f173e0f1d5?auto=format&fit=crop&w=1200&q=85',
        18 => 'https://images.unsplash.com/photo-1476224203421-9ac39bcb3327?auto=format&fit=crop&w=1200&q=85',
    ];
    $stored = trim((string)$storedImage);
    if ($stored !== '' && preg_match('~^https?://~i', $stored)) return $stored;
    return $photos[$dishId] ?? 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=1200&q=85';
}
