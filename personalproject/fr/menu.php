<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/db_error.php';
try {
    $cats = db()->query('SELECT id,name FROM categories ORDER BY sort_order')->fetchAll();
    $stmt = db()->prepare('SELECT id,name,short_description,price,image FROM dishes WHERE category_id = ? ORDER BY id');
} catch (Throwable $t) { db_fail($t); }
$pageTitle = 'Our Menu'; $active = 'menu';
require __DIR__ . '/includes/header.php';
?>
<section class="page-hero"><p class="eyebrow">La Carte</p><h1>Our Menu</h1><p>Authentic French cuisine, prepared fresh every day.</p></section>
<div class="filter" role="tablist" aria-label="Menu categories">
  <button class="chip on" data-filter="all">All</button>
  <?php foreach ($cats as $c): ?><button class="chip" data-filter="c<?= (int)$c['id'] ?>"><?= e($c['name']) ?></button><?php endforeach; ?>
</div>
<div class="container menu-page">
<?php foreach ($cats as $c): $stmt->execute([$c['id']]); $dishes = $stmt->fetchAll(); ?>
  <section class="menu-section reveal" data-cat="c<?= (int)$c['id'] ?>" id="cat-<?= (int)$c['id'] ?>">
    <h2 class="cat-title"><?= e($c['name']) ?></h2>
    <div class="cards">
    <?php foreach ($dishes as $d): ?>
      <a class="card" href="food.php?id=<?= (int)$d['id'] ?>">
        <div class="card-img"><img loading="lazy" src="<?= e(dish_photo_url($d['image'], (int)$d['id'])) ?>" alt="<?= e($d['name']) ?>"></div>
        <div class="card-body"><h3><?= e($d['name']) ?></h3><p><?= e($d['short_description']) ?></p>
          <div class="card-foot"><span class="price"><?= euro($d['price']) ?></span><span class="more">View details</span></div></div>
      </a>
    <?php endforeach; ?>
    </div>
  </section>
<?php endforeach; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
