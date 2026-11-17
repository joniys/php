<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/db_error.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$dish = null; $related = [];
try {
    if ($id) {
        $s = db()->prepare('SELECT d.*, c.name AS category FROM dishes d JOIN categories c ON c.id = d.category_id WHERE d.id = ?');
        $s->execute([$id]); $dish = $s->fetch();
        if ($dish) {
            $r = db()->prepare('SELECT id,name,price,image FROM dishes WHERE category_id = ? AND id <> ? ORDER BY id LIMIT 3');
            $r->execute([$dish['category_id'], $dish['id']]); $related = $r->fetchAll();
        }
    }
} catch (Throwable $t) { db_fail($t); }
if (!$dish) { http_response_code(404); }
$pageTitle = $dish ? $dish['name'] : 'Dish not found'; $active = 'menu';
require __DIR__ . '/includes/header.php';
if (!$dish): ?>
<section class="page-hero"><h1>Dish not found</h1><p>We couldn't find that dish.</p><a class="btn btn-gold" href="menu.php">Back to Menu</a></section>
<?php else: ?>
<section class="detail container">
  <div class="detail-img"><img src="<?= e($dish['image']) ?>" alt="<?= e($dish['name']) ?>"></div>
  <div class="detail-body">
    <a class="back" href="menu.php">&larr; Back to Menu</a>
    <p class="eyebrow dark"><?= e($dish['category']) ?></p>
    <h1><?= e($dish['name']) ?></h1>
    <p class="price big"><?= euro($dish['price']) ?></p>
    <div class="rule left"></div>
    <p class="lead"><?= e($dish['short_description']) ?></p>
    <p><?= nl2br(e($dish['description'])) ?></p>
    <dl class="facts"><div><dt>Category</dt><dd><?= e($dish['category']) ?></dd></div><div><dt>Price</dt><dd><?= euro($dish['price']) ?></dd></div></dl>
    <div class="hero-actions"><a class="btn btn-navy" href="menu.php">Back to Menu</a><a class="btn btn-gold" href="contact.php#reserve">Reserve a Table</a></div>
  </div>
</section>
<?php if ($related): ?>
<section class="section tinted"><div class="container"><h2 class="center">You may also enjoy</h2>
  <div class="cards three">
  <?php foreach ($related as $d): ?>
    <a class="card" href="food.php?id=<?= (int)$d['id'] ?>"><div class="card-img"><img loading="lazy" src="<?= e($d['image']) ?>" alt="<?= e($d['name']) ?>"></div>
    <div class="card-body"><h3><?= e($d['name']) ?></h3><div class="card-foot"><span class="price"><?= euro($d['price']) ?></span><span class="more">View details</span></div></div></a>
  <?php endforeach; ?></div></div></section>
<?php endif; endif;
require __DIR__ . '/includes/footer.php'; ?>
