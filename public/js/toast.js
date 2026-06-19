(function () {
  var container;

  var icons = {
    success: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>',
    error:   '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',
    info:    '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
  };

  function init() {
    container = document.createElement('div');
    container.id = 'toast-container';
    document.body.appendChild(container);
  }

  window.showToast = function (message, type) {
    if (!container) init();
    type = type || 'success';

    var toast    = document.createElement('div');
    var iconEl   = document.createElement('div');
    var msgEl    = document.createElement('div');
    var closeEl  = document.createElement('button');
    var barEl    = document.createElement('div');

    toast.className   = 'toast toast-' + type;
    iconEl.className  = 'toast-icon';
    msgEl.className   = 'toast-msg';
    closeEl.className = 'toast-close';
    barEl.className   = 'toast-progress';

    iconEl.innerHTML       = icons[type] || icons.info;
    msgEl.textContent      = message;
    closeEl.textContent    = '\xd7';
    closeEl.setAttribute('aria-label', 'Dismiss');

    toast.appendChild(iconEl);
    toast.appendChild(msgEl);
    toast.appendChild(closeEl);
    toast.appendChild(barEl);
    container.appendChild(toast);

    /* Trigger enter animation on next two frames */
    requestAnimationFrame(function () {
      requestAnimationFrame(function () {
        toast.classList.add('toast-show');
        barEl.style.transition = 'width 3.2s linear';
        barEl.style.width = '0%';
      });
    });

    var timer = setTimeout(function () { dismiss(toast); }, 3400);

    closeEl.addEventListener('click', function () {
      clearTimeout(timer);
      dismiss(toast);
    });
  };

  function dismiss(toast) {
    toast.classList.remove('toast-show');
    toast.classList.add('toast-out');
    setTimeout(function () {
      if (toast.parentNode) toast.parentNode.removeChild(toast);
    }, 300);
  }

  /* Auto-init if DOM is already ready */
  if (document.body) {
    init();
  } else {
    document.addEventListener('DOMContentLoaded', init);
  }
}());
