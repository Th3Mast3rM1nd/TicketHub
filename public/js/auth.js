(function () {
  'use strict';

  var SVG_EYE = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
  var SVG_EYE_OFF = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';

  window.togglePw = function (fieldId, iconId) {
    var input = document.getElementById(fieldId);
    var icon  = document.getElementById(iconId);
    if (input.type === 'password') {
      input.type = 'text';
      icon.innerHTML = SVG_EYE_OFF;
    } else {
      input.type = 'password';
      icon.innerHTML = SVG_EYE;
    }
  };

  /* ── Mouse glow blob on form panel ── */
  var formWrap = document.querySelector('.auth-form-wrap');
  if (formWrap) {
    var blob = document.createElement('div');
    blob.className = 'auth-glow-blob';
    formWrap.insertBefore(blob, formWrap.firstChild);

    formWrap.addEventListener('mousemove', function (e) {
      var r = formWrap.getBoundingClientRect();
      blob.style.transform = 'translate(' + (e.clientX - r.left - 250) + 'px,' + (e.clientY - r.top - 250) + 'px)';
      blob.style.opacity = '1';
    });

    formWrap.addEventListener('mouseleave', function () {
      blob.style.opacity = '0';
    });
  }

  /* ── Aside ticket feed (stacking rows every 2 s) ── */
  var FEED_TICKETS = [
    { id: 'TS-042', p: 'high',   label: 'High',   title: 'API gateway timeout on /tickets endpoint' },
    { id: 'TS-038', p: 'medium', label: 'Medium', title: 'Dashboard stats not updating in real time' },
    { id: 'TS-051', p: 'low',    label: 'Low',    title: 'Update email notification template' },
    { id: 'TS-029', p: 'high',   label: 'High',   title: 'Login session expires too quickly' },
    { id: 'TS-067', p: 'medium', label: 'Medium', title: 'CSV export missing priority column' },
    { id: 'TS-073', p: 'low',    label: 'Low',    title: 'Add dark mode to the admin panel' },
    { id: 'TS-081', p: 'high',   label: 'High',   title: 'Password reset emails not sending' },
  ];

  var feedIdx  = 0;
  var MAX_ROWS = 3;
  var feed     = document.getElementById('aside-ticket-feed');

  function addFeedRow() {
    if (!feed) return;

    var t   = FEED_TICKETS[feedIdx % FEED_TICKETS.length];
    feedIdx++;

    /* Build row with DOM methods (no innerHTML on variable data) */
    var row = document.createElement('div');
    row.className = 'feed-ticket';

    var dot = document.createElement('span');
    dot.className = 'feed-dot p-' + t.p;

    var id = document.createElement('span');
    id.className = 'feed-id font-mono';
    id.textContent = t.id;

    var title = document.createElement('span');
    title.className = 'feed-title';
    title.textContent = t.title;

    var pillDot = document.createElement('span');
    pillDot.className = 'pill-dot';

    var pill = document.createElement('span');
    pill.className = 'pill priority-' + t.p;
    pill.appendChild(pillDot);
    pill.appendChild(document.createTextNode(t.label));

    row.appendChild(dot);
    row.appendChild(id);
    row.appendChild(title);
    row.appendChild(pill);
    feed.appendChild(row);

    /* Remove oldest row when over limit */
    var rows = feed.querySelectorAll('.feed-ticket:not(.exiting)');
    if (rows.length > MAX_ROWS) {
      var oldest = rows[0];
      oldest.classList.add('exiting');
      setTimeout(function () {
        if (oldest.parentNode) oldest.parentNode.removeChild(oldest);
      }, 370);
    }
  }

  if (feed) {
    addFeedRow();                          /* first row immediately */
    setInterval(addFeedRow, 2000);
  }

  /* ── Input border spotlight (top + bottom edge tracks mouse X) ── */
  document.querySelectorAll('.auth-form .input').forEach(function (input) {
    var topBar = document.createElement('div');
    var botBar = document.createElement('div');
    topBar.className = 'ig-top';
    botBar.className = 'ig-bot';

    var parent = input.parentElement;
    parent.style.position = 'relative';
    parent.appendChild(topBar);
    parent.appendChild(botBar);

    function reposition() {
      var ir = input.getBoundingClientRect();
      var pr = parent.getBoundingClientRect();
      topBar.style.top   = (ir.top    - pr.top)    + 'px';
      botBar.style.top   = (ir.bottom - pr.top - 2) + 'px';
      topBar.style.left  = botBar.style.left = (ir.left - pr.left) + 'px';
      topBar.style.width = botBar.style.width = ir.width + 'px';
    }
    reposition();
    window.addEventListener('resize', reposition);

    input.addEventListener('mousemove', function (e) {
      reposition();
      var x = e.clientX - input.getBoundingClientRect().left;
      var g = 'radial-gradient(32px circle at ' + x + 'px 1px, rgba(199,209,219,0.9) 0%, transparent 70%)';
      topBar.style.background = botBar.style.background = g;
      topBar.style.opacity = botBar.style.opacity = '1';
    });

    input.addEventListener('mouseleave', function () {
      topBar.style.opacity = botBar.style.opacity = '0';
    });
  });

}());
