</main>
<footer class="site-footer">
  <div class="foot-grid">
    <div><h3>French <span>Riviera</span></h3><p>A taste of France, an experience to remember.</p></div>
    <div><h4>Visit</h4><p><?= e(SITE_ADDRESS) ?></p><p><a href="tel:<?= e(preg_replace('/\s+/', '', SITE_PHONE)) ?>"><?= e(SITE_PHONE) ?></a></p><p><a href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a></p></div>
    <div><h4>Hours</h4><p>Tue – Sun · Lunch 12:00 – 14:30</p><p>Tue – Sun · Dinner 19:00 – 22:30</p><p>Closed on Mondays</p></div>
    <div><h4>Explore</h4><p><a href="menu.php">Menu</a></p><p><a href="about.php">About Us</a></p><p><a href="contact.php#reserve">Reservations</a></p></div>
  </div>
  <p class="copy">&copy; <?= date('Y') ?> French Riviera. All rights reserved.</p>
</footer>
<script src="assets/js/main.js"></script>
</body>
</html>
