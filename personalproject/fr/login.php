<?php
$public = true;
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/db_error.php';
if (current_user()) { header('Location: index.php'); exit; }
$error = ''; $email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mb_strtolower(trim((string)($_POST['email'] ?? '')));
    $pass = (string)($_POST['password'] ?? '');
    if (!csrf_valid($_POST['csrf'] ?? null)) {
        $error = 'Your session expired. Please try again.';
    } elseif ($email === '' || $pass === '') {
        $error = 'Please enter your email and password.';
    } else {
        try {
            $s = db()->prepare('SELECT id, name, email, password_hash, is_admin FROM users WHERE email = ?');
            $s->execute([$email]); $u = $s->fetch();
            if ($u && password_verify($pass, $u['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['user'] = [
                    'id'       => (int)$u['id'],
                    'name'     => $u['name'],
                    'email'    => $u['email'],
                    'is_admin' => (int)$u['is_admin'],
                ];
                header('Location: index.php'); exit;
            }
            $error = 'Incorrect email or password.';
        } catch (Throwable $t) { db_fail($t); }
    }
}
$pageTitle = 'Log in'; $active = 'auth';
require __DIR__ . '/includes/header.php'; ?>
<section class="auth-wrap">
  <div class="auth-card">
    <p class="eyebrow dark">Bienvenue</p>
    <h1>Log in</h1>
    <p class="sub">Sign in to discover our menu and reserve your table.</p>
    <?php if ($error): ?><div class="alert err" role="alert"><?= e($error) ?></div><?php endif; ?>
    <?php if (isset($_GET['bye'])): ?><div class="alert ok" role="status">You have been logged out. À bientôt!</div><?php endif; ?>
    <form method="post" action="login.php" id="authForm" novalidate>
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
      <div class="field"><label for="email">Email</label><input id="email" name="email" type="email" value="<?= e($email) ?>" autocomplete="email" required autofocus><small class="msg"></small></div>
      <div class="field"><label for="password">Password</label><input id="password" name="password" type="password" autocomplete="current-password" required><small class="msg"></small></div>
      <button class="btn btn-gold wide" type="submit">Log in</button>
    </form>
    <p class="switch">New to French Riviera? <a href="signup.php">Create an account</a></p>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
