(function () {
  document.documentElement.classList.remove('no-js');
  var header = document.querySelector('.site-header');
  var toggle = document.querySelector('.nav-toggle');
  var nav = document.getElementById('mainNav');
  window.addEventListener('scroll', function () { header.classList.toggle('small', window.scrollY > 40); }, { passive: true });
  if (toggle) {
    toggle.addEventListener('click', function () {
      var open = nav.classList.toggle('open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
    nav.querySelectorAll('a').forEach(function (a) { a.addEventListener('click', function () { nav.classList.remove('open'); toggle.setAttribute('aria-expanded', 'false'); }); });
  }
  // scroll reveal
  var items = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (es) { es.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); } }); }, { threshold: 0.12 });
    items.forEach(function (el) { io.observe(el); });
  } else { items.forEach(function (el) { el.classList.add('in'); }); }
  // menu filter
  var chips = document.querySelectorAll('.chip');
  chips.forEach(function (c) {
    c.addEventListener('click', function () {
      chips.forEach(function (x) { x.classList.remove('on'); }); c.classList.add('on');
      var f = c.dataset.filter;
      document.querySelectorAll('.menu-section').forEach(function (s) { s.hidden = !(f === 'all' || s.dataset.cat === f); s.classList.add('in'); });
    });
  });
  // reservation form validation
  var form = document.getElementById('resForm');
  if (form) {
    var rules = {
      name: function (v) { return v.trim().length >= 2 || 'Please enter your full name.'; },
      email: function (v) { return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v) || 'Please enter a valid email address.'; },
      phone: function (v) { return /^\+?[0-9 ()\-]{7,20}$/.test(v) || 'Please enter a valid phone number.'; },
      date: function (v) {
        if (!v) return 'Please choose a date.';
        var d = new Date(v + 'T00:00:00'), t = new Date(); t.setHours(0, 0, 0, 0);
        if (d < t) return 'Please choose a future date.';
        if (d.getDay() === 1) return 'We are closed on Mondays.';
        return true;
      },
      time: function (v) { return !!v || 'Please choose a time.'; },
      guests: function (v) { return (v >= 1 && v <= 12) || 'Choose 1–12 guests.'; }
    };
    function check(name) {
      var el = form.elements[name], r = rules[name](el.value), box = el.closest('.field');
      box.classList.toggle('bad', r !== true); box.querySelector('.msg').textContent = r === true ? '' : r;
      return r === true;
    }
    Object.keys(rules).forEach(function (n) { form.elements[n].addEventListener('blur', function () { check(n); }); });
    form.addEventListener('submit', function (e) {
      var ok = true; Object.keys(rules).forEach(function (n) { if (!check(n)) ok = false; });
      if (!ok) { e.preventDefault(); var b = form.querySelector('.bad input,.bad select'); if (b) b.focus(); }
    });
  }
  // login / signup validation
  var auth = document.getElementById('authForm');
  if (auth) {
    auth.addEventListener('submit', function (e) {
      var bad = false;
      auth.querySelectorAll('input[required]').forEach(function (i) {
        var box = i.closest('.field'), msg = box.querySelector('.msg'), m = '';
        if (!i.value.trim()) m = 'This field is required.';
        else if (i.type === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(i.value)) m = 'Please enter a valid email address.';
        else if (auth.hasAttribute('data-signup') && i.name === 'password' && i.value.length < 8) m = 'Use at least 8 characters.';
        else if (i.name === 'confirm' && i.value !== auth.elements.password.value) m = 'The passwords do not match.';
        box.classList.toggle('bad', !!m); msg.textContent = m; if (m) bad = true;
      });
      if (bad) e.preventDefault();
    });
  }
})();
