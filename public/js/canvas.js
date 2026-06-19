(function () {
  'use strict';

  /* ── Config ── */
  var DOT_RADIUS  = 2;
  var SPACING     = 22;
  var MAX_OPACITY = 0.32;
  var TIME_STEP   = 0.0008;
  var DOT_COLOR   = '79, 70, 229'; /* indigo RGB */

  /* ── Create canvas ── */
  var canvas = document.createElement('canvas');
  var ctx    = canvas.getContext('2d');

  canvas.setAttribute('aria-hidden', 'true');
  canvas.style.cssText =
    'position:fixed;top:0;left:0;width:100%;height:100%;' +
    'z-index:0;pointer-events:none;';

  /* Insert as first child so all page content sits on top */
  document.body.insertBefore(canvas, document.body.firstChild);

  /* ── Grid state ── */
  var cols   = 0;
  var rows   = 0;
  var phases = null;
  var time   = 0;

  /* ── Resize ── */
  function resize() {
    canvas.width  = window.innerWidth;
    canvas.height = window.innerHeight;

    cols = Math.ceil(canvas.width  / SPACING) + 2;
    rows = Math.ceil(canvas.height / SPACING) + 2;

    /* Seed a unique phase offset for every dot so they pulse independently */
    phases = new Float32Array(cols * rows);
    for (var i = 0; i < phases.length; i++) {
      phases[i] = Math.random() * Math.PI * 2;
    }
  }

  /* ── Draw loop ── */
  function draw() {
    var w = canvas.width;
    var h = canvas.height;

    ctx.clearRect(0, 0, w, h);

    /* Centre the grid so edge dots are symmetrical */
    var offsetX = (w % SPACING) * 0.5;
    var offsetY = (h % SPACING) * 0.5;

    for (var r = 0; r < rows; r++) {
      for (var c = 0; c < cols; c++) {
        var idx     = r * cols + c;
        var opacity = ((Math.sin(time + phases[idx]) + 1) * 0.5) * MAX_OPACITY;

        var x = offsetX + c * SPACING;
        var y = offsetY + r * SPACING;

        ctx.beginPath();
        ctx.arc(x, y, DOT_RADIUS, 0, Math.PI * 2);
        ctx.fillStyle = 'rgba(' + DOT_COLOR + ',' + opacity.toFixed(3) + ')';
        ctx.fill();
      }
    }

    /* ── Radial vignette ── dims centre for readability, edges stay lively ── */
    var cx   = w * 0.5;
    var cy   = h * 0.5;
    var grad = ctx.createRadialGradient(cx, cy, 0, cx, cy, Math.max(w, h) * 0.72);
    grad.addColorStop(0,   'rgba(15,15,19,0.82)');
    grad.addColorStop(0.5, 'rgba(15,15,19,0.45)');
    grad.addColorStop(1,   'rgba(15,15,19,0.05)');
    ctx.fillStyle = grad;
    ctx.fillRect(0, 0, w, h);

    time += TIME_STEP;
    requestAnimationFrame(draw);
  }

  /* ── Init ── */
  resize();
  window.addEventListener('resize', resize);
  draw();

}());
