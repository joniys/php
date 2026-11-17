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
