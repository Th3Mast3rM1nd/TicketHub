(function () {
  'use strict';

  var KEY = 'ts-theme';

  function applyTheme(mode) {
    document.documentElement.setAttribute('data-theme', mode);
    localStorage.setItem(KEY, mode);
  }

  /* Apply saved preference immediately — prevents flash of wrong theme */
  applyTheme(localStorage.getItem(KEY) || 'dark');

  /* Build and inject the toggle widget after the DOM is ready */
  document.addEventListener('DOMContentLoaded', function () {
    var NS = 'http://www.w3.org/2000/svg';

    function makeSvg(attrs, children) {
      var svg = document.createElementNS(NS, 'svg');
      Object.keys(attrs).forEach(function (k) { svg.setAttribute(k, attrs[k]); });
      children.forEach(function (child) {
        var el = document.createElementNS(NS, child.tag);
        Object.keys(child).forEach(function (k) { if (k !== 'tag') el.setAttribute(k, child[k]); });
        svg.appendChild(el);
      });
      return svg;
    }

    var sunSvg = makeSvg(
      { width: '14', height: '14', viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', 'stroke-width': '2', 'stroke-linecap': 'round', 'stroke-linejoin': 'round' },
      [
        { tag: 'circle', cx: '12', cy: '12', r: '5' },
        { tag: 'line', x1: '12', y1: '1',    x2: '12',   y2: '3' },
        { tag: 'line', x1: '12', y1: '21',   x2: '12',   y2: '23' },
        { tag: 'line', x1: '4.22',  y1: '4.22',  x2: '5.64',  y2: '5.64' },
        { tag: 'line', x1: '18.36', y1: '18.36', x2: '19.78', y2: '19.78' },
        { tag: 'line', x1: '1',  y1: '12', x2: '3',  y2: '12' },
        { tag: 'line', x1: '21', y1: '12', x2: '23', y2: '12' },
        { tag: 'line', x1: '4.22',  y1: '19.78', x2: '5.64',  y2: '18.36' },
        { tag: 'line', x1: '18.36', y1: '5.64',  x2: '19.78', y2: '4.22' },
      ]
    );

    var moonSvg = makeSvg(
      { width: '14', height: '14', viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', 'stroke-width': '2', 'stroke-linecap': 'round', 'stroke-linejoin': 'round' },
      [{ tag: 'path', d: 'M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z' }]
    );

    function makeBtn(cls, title, svgEl) {
      var btn = document.createElement('button');
      btn.className = 'tt-icon ' + cls;
      btn.title = title;
      btn.appendChild(svgEl);
      return btn;
    }

    var track = document.createElement('button');
    track.className = 'tt-track';
    track.setAttribute('role', 'switch');
    track.setAttribute('aria-label', 'Toggle dark / light mode');
    var thumb = document.createElement('span');
    thumb.className = 'tt-thumb';
    track.appendChild(thumb);

    var widget = document.createElement('div');
    widget.id = 'theme-toggle';
    widget.setAttribute('aria-label', 'Toggle colour theme');
    widget.appendChild(makeBtn('tt-sun',  'Switch to light mode', sunSvg));
    widget.appendChild(track);
    widget.appendChild(makeBtn('tt-moon', 'Switch to dark mode',  moonSvg));

    /* ── Place the widget where it fits each page layout ── */
    var sidebarBottom = document.querySelector('.sidebar-bottom');
    var navActions    = document.querySelector('.nav-actions');

    if (sidebarBottom) {
      /* Dashboard / admin / ticket pages — slot above the sign-out link */
      widget.classList.add('tt-in-sidebar');
      sidebarBottom.insertBefore(widget, sidebarBottom.firstChild);
    } else if (navActions) {
      /* Landing page — append to the top navbar */
      widget.classList.add('tt-in-nav');
      navActions.insertBefore(widget, navActions.firstChild);
    } else {
      /* Auth pages — float above the chat widget */
      widget.classList.add('tt-floating');
      document.body.appendChild(widget);
    }

    function isDark() {
      return document.documentElement.getAttribute('data-theme') !== 'light';
    }

    function syncUI() {
      var dark = isDark();
      widget.setAttribute('data-dark', dark ? '1' : '0');
      track.setAttribute('aria-checked', dark ? 'true' : 'false');
    }

    syncUI();

    track.addEventListener('click', function () {
      applyTheme(isDark() ? 'light' : 'dark');
      syncUI();
    });
    widget.querySelector('.tt-sun').addEventListener('click', function () {
      applyTheme('light');
      syncUI();
    });
    widget.querySelector('.tt-moon').addEventListener('click', function () {
      applyTheme('dark');
      syncUI();
    });
  });
}());
