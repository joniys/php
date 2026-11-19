<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/db_error.php';
try {
    $featured = db()->query('SELECT d.id,d.name,d.short_description,d.price,d.image FROM dishes d WHERE d.featured = 1 ORDER BY d.id LIMIT 3')->fetchAll();
} catch (Throwable $t) { db_fail($t); }
$pageTitle = 'Fine French Dining on the Côte d\'Azur'; $active = 'home';
require __DIR__ . '/includes/header.php';
?>
<section class="hero">
  <div class="hero-inner">
    <p class="eyebrow">Côte d'Azur · Since 1987</p>
    <h1>FRENCH RIVIERA</h1>
    <p class="slogan"><em>A Taste of France, An Experience to Remember.</em></p>
    <div class="hero-actions">
      <a href="contact.php#reserve" class="btn btn-gold">Reserve a Table</a>
      <a href="menu.php" class="btn btn-ghost">View the Menu</a>
    </div>
  </div>
</section>

<section class="island-wrap">
  <a class="island" href="menu.php" aria-label="Discover our menu">
    <div class="island-img"><img src="https://images.unsplash.com/photo-1546833999-b9f581a1996d?auto=format&fit=crop&w=1200&q=85" alt="Boeuf Bourguignon served on a white plate"></div>
    <div class="island-body">
      <p class="eyebrow dark">Bienvenue</p>
      <h2>Discover the Art of French Cuisine.</h2>
      <p>Seasonal ingredients, time-honoured recipes and the light of the Mediterranean on every plate.</p>
      <span class="btn btn-navy">Explore the Menu &rarr;</span>
    </div>
  </a>
</section>

<section class="section intro reveal">
  <div class="container narrow center">
    <p class="eyebrow dark">Notre Cuisine</p>
    <h2>Provençal sun, Parisian technique</h2>
    <p>At French Riviera we celebrate the cooking of France, from the rustic braises of Burgundy to the fragrant stews of Marseille. Every dish is built on fresh local produce, patient technique and generous hospitality, served within sight of the sea.</p>
    <div class="rule"></div>
  </div>
</section>

<section class="section tinted reveal">
  <div class="container">
    <div class="center"><p class="eyebrow dark">Chef's Selection</p><h2>Signature Dishes</h2></div>
    <div class="cards three">
      <?php foreach ($featured as $f): ?>
      <a class="card" href="food.php?id=<?= (int)$f['id'] ?>">
        <div class="card-img"><img loading="lazy" src="<?= e(dish_photo_url($f['image'], (int)$f['id'])) ?>" alt="<?= e($f['name']) ?>"></div>
        <div class="card-body"><h3><?= e($f['name']) ?></h3><p><?= e($f['short_description']) ?></p><div class="card-foot"><span class="price"><?= euro($f['price']) ?></span><span class="more">View details</span></div></div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="cta reveal">
  <h2>Join us for an unforgettable evening</h2>
  <a href="contact.php#reserve" class="btn btn-gold">Book Your Table</a>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
