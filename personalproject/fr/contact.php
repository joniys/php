<?php
require_once __DIR__ . '/includes/bootstrap.php';
$lunch = ['12:00','12:30','13:00','13:30','14:00','14:30'];
$dinner = ['19:00','19:30','20:00','20:30','21:00','21:30','22:00','22:30'];
$allowed = array_merge($lunch, $dinner);
$errors = []; $old = ['name'=>'','email'=>'','phone'=>'','date'=>'','time'=>'','guests'=>'2','notes'=>''];

$u = current_user(); if ($u) { $old['name'] = $u['name']; $old['email'] = $u['email']; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($old as $k => $_) $old[$k] = trim((string)($_POST[$k] ?? ''));
    if (!csrf_valid($_POST['csrf'] ?? null)) $errors['form'] = 'Your session expired. Please try again.';
    if (!empty($_POST['website'])) { header('Location: contact.php'); exit; } // honeypot

    if (mb_strlen($old['name']) < 2 || mb_strlen($old['name']) > 80) $errors['name'] = 'Please enter your full name (2–80 characters).';
    if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($old['email']) > 120) $errors['email'] = 'Please enter a valid email address.';
    if (!preg_match('/^\+?[0-9 ()\-]{7,20}$/', $old['phone'])) $errors['phone'] = 'Please enter a valid phone number.';
    $d = DateTimeImmutable::createFromFormat('!Y-m-d', $old['date']);
    $today = new DateTimeImmutable('today');
    if (!$d || $d->format('Y-m-d') !== $old['date']) $errors['date'] = 'Please choose a date.';
    elseif ($d < $today || $d > $today->modify('+6 months')) $errors['date'] = 'Please choose a date within the next six months.';
    elseif ($d->format('N') === '1') $errors['date'] = 'We are closed on Mondays.';
    if (!in_array($old['time'], $allowed, true)) $errors['time'] = 'Please choose an available time.';
    if (!ctype_digit($old['guests']) || (int)$old['guests'] < 1 || (int)$old['guests'] > 12) $errors['guests'] = 'We accept 1–12 guests online. Call us for larger groups.';
    if (mb_strlen($old['notes']) > 500) $errors['notes'] = 'Notes are limited to 500 characters.';

    if (!$errors) {
        try {
            $s = db()->prepare('INSERT INTO reservations (name,email,phone,res_date,res_time,guests,notes) VALUES (?,?,?,?,?,?,?)');
            $s->execute([$old['name'],$old['email'],$old['phone'],$old['date'],$old['time'].':00',(int)$old['guests'],$old['notes'] !== '' ? $old['notes'] : null]);
            $_SESSION['confirm'] = ['ref' => (int)db()->lastInsertId(), 'name' => $old['name'], 'date' => $d->format('l, j F Y'), 'time' => $old['time'], 'guests' => (int)$old['guests']];
            header('Location: contact.php?ok=1#reserve'); exit;
        } catch (Throwable $t) { $errors['form'] = 'Sorry, we could not save your reservation. Please call us instead.'; }
    }
}
$confirm = null;
if (isset($_GET['ok']) && !empty($_SESSION['confirm'])) { $confirm = $_SESSION['confirm']; unset($_SESSION['confirm']); }

$pageTitle = 'Contact & Reservations'; $active = 'contact';
require __DIR__ . '/includes/header.php'; ?>
<section class="page-hero"><p class="eyebrow">Réservations</p><h1>Contact &amp; Reservations</h1><p>We look forward to welcoming you.</p></section>
<section class="section container contact-grid">
  <div class="info reveal">
    <h2>Find Us</h2>
    <p><strong>Address</strong><br><?= e(SITE_ADDRESS) ?></p>
    <p><strong>Telephone</strong><br><a href="tel:<?= e(preg_replace('/\s+/', '', SITE_PHONE)) ?>"><?= e(SITE_PHONE) ?></a></p>
    <p><strong>Email</strong><br><a href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a></p>
    <h3>Opening Hours</h3>
    <ul class="hours"><li><span>Tuesday – Sunday</span><span>Lunch 12:00 – 14:30</span></li><li><span>Tuesday – Sunday</span><span>Dinner 19:00 – 22:30</span></li><li><span>Monday</span><span>Closed</span></li></ul>
  </div>
  <div class="form-card reveal" id="reserve">
    <h2>Reserve a Table</h2>
    <?php if ($confirm): ?>
      <div class="alert ok" role="status"><strong>Merci, <?= e($confirm['name']) ?>!</strong> Your table for <?= (int)$confirm['guests'] ?> on <?= e($confirm['date']) ?> at <?= e($confirm['time']) ?> is reserved. Reference: <strong>#<?= (int)$confirm['ref'] ?></strong>.</div>
    <?php endif; ?>
    <?php if (isset($errors['form'])): ?><div class="alert err" role="alert"><?= e($errors['form']) ?></div><?php endif; ?>
    <form method="post" action="contact.php#reserve" id="resForm" novalidate>
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
      <div class="hp" aria-hidden="true"><label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
      <?php
      $f = function(string $k, string $label, string $type, array $extra = []) use ($old, $errors) {
          $attrs = ''; foreach ($extra as $a => $v) $attrs .= ' ' . $a . '="' . e($v) . '"';
          echo '<div class="field' . (isset($errors[$k]) ? ' bad' : '') . '"><label for="' . $k . '">' . e($label) . '</label><input id="' . $k . '" name="' . $k . '" type="' . $type . '" value="' . e($old[$k]) . '"' . $attrs . ' required><small class="msg">' . e($errors[$k] ?? '') . '</small></div>';
      }; ?>
      <?php $f('name', 'Full name', 'text', ['maxlength' => 80, 'autocomplete' => 'name']); ?>
      <div class="row2"><?php $f('email', 'Email', 'email', ['maxlength' => 120, 'autocomplete' => 'email']); $f('phone', 'Phone', 'tel', ['maxlength' => 20, 'autocomplete' => 'tel']); ?></div>
      <div class="row3">
        <?php $f('date', 'Date', 'date', ['min' => date('Y-m-d'), 'max' => date('Y-m-d', strtotime('+6 months'))]); ?>
        <div class="field<?= isset($errors['time']) ? ' bad' : '' ?>"><label for="time">Time</label>
          <select id="time" name="time" required><option value="">Select…</option>
            <optgroup label="Lunch"><?php foreach ($lunch as $t): ?><option <?= $old['time'] === $t ? 'selected' : '' ?>><?= e($t) ?></option><?php endforeach; ?></optgroup>
            <optgroup label="Dinner"><?php foreach ($dinner as $t): ?><option <?= $old['time'] === $t ? 'selected' : '' ?>><?= e($t) ?></option><?php endforeach; ?></optgroup>
          </select><small class="msg"><?= e($errors['time'] ?? '') ?></small></div>
        <div class="field<?= isset($errors['guests']) ? ' bad' : '' ?>"><label for="guests">Guests</label>
          <select id="guests" name="guests" required><?php for ($i = 1; $i <= 12; $i++): ?><option value="<?= $i ?>" <?= (int)$old['guests'] === $i ? 'selected' : '' ?>><?= $i ?></option><?php endfor; ?></select><small class="msg"><?= e($errors['guests'] ?? '') ?></small></div>
      </div>
      <div class="field<?= isset($errors['notes']) ? ' bad' : '' ?>"><label for="notes">Special requests (optional)</label><textarea id="notes" name="notes" rows="3" maxlength="500"><?= e($old['notes']) ?></textarea><small class="msg"><?= e($errors['notes'] ?? '') ?></small></div>
      <button class="btn btn-gold" type="submit">Confirm Reservation</button>
    </form>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
