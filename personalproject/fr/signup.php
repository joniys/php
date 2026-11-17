<?php
$public = true;
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/db_error.php';
if (current_user()) { header('Location: index.php'); exit; }
$errors = []; $old = ['name' => '', 'email' => ''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['name'] = trim((string)($_POST['name'] ?? ''));
    $old['email'] = mb_strtolower(trim((string)($_POST['email'] ?? '')));
    $pass = (string)($_POST['password'] ?? ''); $conf = (string)($_POST['confirm'] ?? '');
    if (!csrf_valid($_POST['csrf'] ?? null)) $errors['form'] = 'Your session expired. Please try again.';
    if (mb_strlen($old['name']) < 2 || mb_strlen($old['name']) > 80) $errors['name'] = 'Please enter your name (2–80 characters).';
    if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($old['email']) > 120) $errors['email'] = 'Please enter a valid email address.';
    if (strlen($pass) < 8) $errors['password'] = 'Use at least 8 characters.';
    elseif (strlen($pass) > 72) $errors['password'] = 'Use at most 72 characters.';
    if ($pass !== $conf) $errors['confirm'] = 'The passwords do not match.';
    if (!$errors) {
        try {
            $s = db()->prepare('INSERT INTO users (name, email, password_hash, is_admin) VALUES (?, ?, ?, 0)');
            $s->execute([$old['name'], $old['email'], password_hash($pass, PASSWORD_DEFAULT)]);
            session_regenerate_id(true);
            $_SESSION['user'] = [
                'id'       => (int)db()->lastInsertId(),
                'name'     => $old['name'],
                'email'    => $old['email'],
                'is_admin' => 0,
            ];
            header('Location: index.php'); exit;
        } catch (PDOException $t) {
            if ($t->getCode() === '23000') $errors['email'] = 'An account with this email already exists.';
            else db_fail($t);
        }
    }
}
$pageTitle = 'Create account'; $active = 'auth';
require __DIR__ . '/includes/header.php';
$f = function (string $k, string $label, string $type, string $auto, bool $keep = true) use ($old, $errors) {
    echo '<div class="field' . (isset($errors[$k]) ? ' bad' : '') . '"><label for="' . $k . '">' . e($label) . '</label><input id="' . $k . '" name="' . $k . '" type="' . $type . '" autocomplete="' . $auto . '"' . ($keep ? ' value="' . e($old[$k] ?? '') . '"' : '') . ' required><small class="msg">' . e($errors[$k] ?? '') . '</small></div>';
}; ?>
<section class="auth-wrap">
  <div class="auth-card">
    <p class="eyebrow dark">Rejoignez-nous</p>
    <h1>Create account</h1>
    <p class="sub">Sign up to explore our menu and book a table.</p>
    <?php if (isset($errors['form'])): ?><div class="alert err" role="alert"><?= e($errors['form']) ?></div><?php endif; ?>
    <form method="post" action="signup.php" id="authForm" novalidate data-signup>
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
      <?php $f('name', 'Full name', 'text', 'name'); $f('email', 'Email', 'email', 'email'); $f('password', 'Password (min. 8 characters)', 'password', 'new-password', false); $f('confirm', 'Confirm password', 'password', 'new-password', false); ?>
      <button class="btn btn-gold wide" type="submit">Sign up</button>
    </form>
    <p class="switch">Already have an account? <a href="login.php">Log in</a></p>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
