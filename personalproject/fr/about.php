<?php
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'About Us'; $active = 'about';
require __DIR__ . '/includes/header.php'; ?>
<section class="page-hero"><p class="eyebrow">Notre Histoire</p><h1>About Us</h1><p>Where Provence meets the Mediterranean.</p></section>
<section class="section container split reveal">
  <div class="split-img"><img src="images/about.svg" alt="The French Riviera coastline at sunset"></div>
  <div>
    <p class="eyebrow dark">Our Inspiration</p>
    <h2>A table by the sea</h2>
    <p>French Riviera was born from a love of the Côte d'Azur: the scent of lavender, the glitter of the bay and tables that stay full long after dessert. Our kitchen honours the culinary traditions of France, from the slow braises of Burgundy and Lyon to the sun-filled dishes of Provence.</p>
    <p>We believe great French cooking is simple at heart: honest ingredients, careful technique and time. Stocks simmer for hours, sauces are made to order and every plate leaves the pass with care.</p>
  </div>
</section>
<section class="section tinted reveal"><div class="container">
  <div class="center"><p class="eyebrow dark">Nos Valeurs</p><h2>Our Commitment to Quality</h2></div>
  <div class="values">
    <div><h3>Fresh &amp; Local</h3><p>Fish from the morning market, vegetables from Provençal growers, cheeses and butter from small French dairies.</p></div>
    <div><h3>Tradition</h3><p>Classic recipes cooked the classic way: no shortcuts, no compromises on technique.</p></div>
    <div><h3>Hospitality</h3><p>Warm, attentive service in the French tradition, from the first aperitif to the last espresso.</p></div>
    <div><h3>The Cellar</h3><p>A carefully chosen list of French wines, from Provence rosé to Bordeaux, selected by our sommelier.</p></div>
  </div></div></section>
<section class="cta"><h2>Come and taste the Riviera</h2><a class="btn btn-gold" href="contact.php#reserve">Reserve a Table</a></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
