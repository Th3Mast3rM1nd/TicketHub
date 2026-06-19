(function () {
  'use strict';

  /* ══════════════════════════════════════════════════════
     Particle Constellation Field
     ══════════════════════════════════════════════════════ */
  var canvas = document.createElement('canvas');
  canvas.id = 'landing-canvas';
  canvas.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;z-index:0;pointer-events:none;';
  document.body.insertBefore(canvas, document.body.firstChild);

  var ctx = canvas.getContext('2d');
  var W, H;
  var mouse = { x: -9999, y: -9999 };

  function resize() {
    W = canvas.width  = window.innerWidth;
    H = canvas.height = window.innerHeight;
  }
  resize();
  window.addEventListener('resize', resize);

  window.addEventListener('mousemove', function (e) {
    mouse.x = e.clientX;
    mouse.y = e.clientY;
  });
  window.addEventListener('mouseleave', function () {
    mouse.x = -9999;
    mouse.y = -9999;
  });

  var COUNT        = 150;
  var CONNECT_DIST = 130;
  var CONNECT_D2   = CONNECT_DIST * CONNECT_DIST;
  var REPEL_DIST   = 120;
  var REPEL_D2     = REPEL_DIST * REPEL_DIST;
  var REPEL_FORCE  = 0.55;
  var MAX_SPEED    = 1.4;

  var particles = [];
  for (var i = 0; i < COUNT; i++) {
    particles.push({
      x:      Math.random() * window.innerWidth,
      y:      Math.random() * window.innerHeight,
      vx:     (Math.random() - 0.5) * 0.5,
      vy:     (Math.random() - 0.5) * 0.5,
      r:      Math.random() * 1.3 + 0.4,
      indigo: Math.random() < 0.15,
      phase:  Math.random() * Math.PI * 2,
    });
  }

  function tick() {
    ctx.clearRect(0, 0, W, H);
    var light = document.documentElement.getAttribute('data-theme') === 'light';

    var i, j, p, a, b, dx, dy, d2, d, alpha, force, spd;

    for (i = 0; i < COUNT; i++) {
      p = particles[i];

      /* Mouse repulsion */
      dx = p.x - mouse.x;
      dy = p.y - mouse.y;
      d2 = dx * dx + dy * dy;
      if (d2 < REPEL_D2 && d2 > 0) {
        d     = Math.sqrt(d2);
        force = (REPEL_DIST - d) / REPEL_DIST * REPEL_FORCE;
        p.vx += (dx / d) * force;
        p.vy += (dy / d) * force;
      }

      /* Dampen */
      p.vx *= 0.97;
      p.vy *= 0.97;

      /* Clamp speed */
      spd = Math.sqrt(p.vx * p.vx + p.vy * p.vy);
      if (spd > MAX_SPEED) {
        p.vx = p.vx / spd * MAX_SPEED;
        p.vy = p.vy / spd * MAX_SPEED;
      }

      p.x += p.vx;
      p.y += p.vy;

      /* Wrap edges */
      if (p.x < -2)  p.x = W + 2;
      if (p.x > W+2) p.x = -2;
      if (p.y < -2)  p.y = H + 2;
      if (p.y > H+2) p.y = -2;

      /* Pulse */
      p.phase += 0.022;
      var baseAlpha = p.indigo
        ? 0.45 + Math.sin(p.phase) * 0.2
        : (light ? 0.45 : 0.3);

      ctx.beginPath();
      ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
      ctx.fillStyle = p.indigo
        ? 'rgba(79,70,229,' + baseAlpha + ')'
        : (light ? 'rgba(50,50,80,' + baseAlpha + ')' : 'rgba(90,90,115,' + baseAlpha + ')');
      ctx.fill();
    }

    /* Connection lines */
    for (i = 0; i < COUNT; i++) {
      for (j = i + 1; j < COUNT; j++) {
        a  = particles[i];
        b  = particles[j];
        dx = a.x - b.x;
        dy = a.y - b.y;
        d2 = dx * dx + dy * dy;
        if (d2 < CONNECT_D2) {
          d     = Math.sqrt(d2);
          alpha = (1 - d / CONNECT_DIST) * (light ? 0.28 : 0.18);
          ctx.beginPath();
          ctx.moveTo(a.x, a.y);
          ctx.lineTo(b.x, b.y);
          ctx.strokeStyle = (a.indigo || b.indigo)
            ? 'rgba(79,70,229,' + (alpha * 2) + ')'
            : (light ? 'rgba(50,50,80,' + alpha + ')' : 'rgba(90,90,115,' + alpha + ')');
          ctx.lineWidth = 0.5;
          ctx.stroke();
        }
      }
    }

    requestAnimationFrame(tick);
  }

  tick();

  /* ══════════════════════════════════════════════════════
     Navbar Scroll Border
     Adds class "scrolled" to the nav when the user has
     scrolled more than 10px — CSS uses this to show a
     bottom border that separates nav from content.
     ══════════════════════════════════════════════════════ */
  var nav = document.getElementById('lp-nav');
  if (nav) {
    window.addEventListener('scroll', function () {
      nav.classList.toggle('scrolled', window.scrollY > 10);
    }, { passive: true });
  }

  /* ══════════════════════════════════════════════════════
     Hamburger Toggle (mobile menu)
     Opens/closes #nav-mobile and keeps aria-expanded in
     sync. Clicking outside the nav closes it automatically.
     ══════════════════════════════════════════════════════ */
  var hamburger = document.getElementById('nav-hamburger');
  var mobileMenu = document.getElementById('nav-mobile');
  if (hamburger && mobileMenu && nav) {
    hamburger.addEventListener('click', function () {
      var isOpen = mobileMenu.classList.toggle('open');
      hamburger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
    document.addEventListener('click', function (e) {
      if (!nav.contains(e.target)) {
        mobileMenu.classList.remove('open');
        hamburger.setAttribute('aria-expanded', 'false');
      }
    });
  }

  /* ══════════════════════════════════════════════════════
     Scroll Reveal (IntersectionObserver)
     Adds class "revealed" to any element with [data-reveal]
     once it enters the viewport.
     ══════════════════════════════════════════════════════ */
  var reveals = document.querySelectorAll('[data-reveal]');

  if (reveals.length) {
    if ('IntersectionObserver' in window) {
      var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('revealed');
            io.unobserve(entry.target);
          }
        });
      }, { threshold: 0.1 });

      reveals.forEach(function (el) { io.observe(el); });
    } else {
      reveals.forEach(function (el) { el.classList.add('revealed'); });
    }
  }

  /* ══════════════════════════════════════════════════════
     FAQ Accordion
     Clicking a .faq-q button expands that item and
     collapses any previously open item.
     ══════════════════════════════════════════════════════ */
  document.querySelectorAll('.faq-q').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var item = btn.closest('.faq-item');
      if (!item) { return; }
      var body = item.querySelector('.faq-body');
      if (!body) { return; }
      var isOpen = item.classList.contains('open');

      /* Close all open items */
      document.querySelectorAll('.faq-item.open').forEach(function (openItem) {
        openItem.classList.remove('open');
        var openBody = openItem.querySelector('.faq-body');
        if (openBody) { openBody.style.maxHeight = '0'; }
      });

      /* Open the clicked item if it was closed */
      if (!isOpen) {
        item.classList.add('open');
        body.style.maxHeight = body.scrollHeight + 'px';
      }
    });
  });

  /* ══════════════════════════════════════════════════════
     Button Ripple
     Creates an expanding circle from the click point.
     ══════════════════════════════════════════════════════ */
  function attachRipple(btn) {
    btn.addEventListener('click', function (e) {
      var rect = btn.getBoundingClientRect();
      var span = document.createElement('span');
      span.className = 'ripple';
      span.style.top  = (e.clientY - rect.top)  + 'px';
      span.style.left = (e.clientX - rect.left) + 'px';
      btn.appendChild(span);
      setTimeout(function () { span.remove(); }, 650);
    });
  }

  document.querySelectorAll('.btn, .btn-white, .btn-ghost-white').forEach(attachRipple);

  /* ══════════════════════════════════════════════════════
     Dashboard Preview 3D Tilt
     Tilts .preview-browser ±6° following the mouse.
     Eases back slowly on mouseleave.
     ══════════════════════════════════════════════════════ */
  var previewEl = document.querySelector('.preview-browser');
  if (previewEl) {
    previewEl.addEventListener('mousemove', function (e) {
      var rect = previewEl.getBoundingClientRect();
      var cx   = rect.left + rect.width  / 2;
      var cy   = rect.top  + rect.height / 2;
      var rx   = ((e.clientY - cy) / (rect.height / 2)) * -6;
      var ry   = ((e.clientX - cx) / (rect.width  / 2)) *  6;
      previewEl.style.transition = 'transform 0.1s ease-out';
      previewEl.style.transform  =
        'perspective(900px) rotateX(' + rx.toFixed(2) + 'deg) rotateY(' + ry.toFixed(2) + 'deg)';
    });
    previewEl.addEventListener('mouseleave', function () {
      previewEl.style.transition = 'transform 0.6s cubic-bezier(0.16, 1, 0.3, 1)';
      previewEl.style.transform  = 'perspective(900px) rotateX(0deg) rotateY(0deg)';
    });
  }

})();
