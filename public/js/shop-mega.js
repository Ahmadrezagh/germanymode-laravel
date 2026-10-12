(function () {
  var menu = document.getElementById('orkMenu');
  var trigger = document.getElementById('orkMegaTrigger');
  var panel = document.getElementById('orkMegaPanel');
  if (!menu || !trigger || !panel) return;

  var tabs = panel.querySelectorAll('[data-mega-tab]');
  var panes = panel.querySelectorAll('[data-mega-pane]');
  var closeTimer = null;

  function openPanel() {
    clearTimeout(closeTimer);
    panel.hidden = false;
    trigger.setAttribute('aria-expanded', 'true');
    menu.classList.add('mega-open');
  }

  function closePanel() {
    panel.hidden = true;
    trigger.setAttribute('aria-expanded', 'false');
    menu.classList.remove('mega-open');
  }

  function scheduleClose() {
    clearTimeout(closeTimer);
    closeTimer = setTimeout(closePanel, 180);
  }

  function activate(id) {
    tabs.forEach(function (tab) {
      var on = tab.getAttribute('data-mega-tab') === String(id);
      tab.classList.toggle('active', on);
      tab.setAttribute('aria-selected', on ? 'true' : 'false');
    });
    panes.forEach(function (pane) {
      var on = pane.getAttribute('data-mega-pane') === String(id);
      pane.classList.toggle('active', on);
      pane.hidden = !on;
    });
  }

  trigger.addEventListener('click', function (e) {
    e.preventDefault();
    e.stopPropagation();
    if (menu.classList.contains('mega-open')) closePanel();
    else openPanel();
  });

  trigger.addEventListener('mouseenter', function () {
    if (window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
      openPanel();
    }
  });

  panel.addEventListener('mouseenter', function () {
    clearTimeout(closeTimer);
    openPanel();
  });
  panel.addEventListener('mouseleave', function () {
    if (window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
      scheduleClose();
    }
  });
  menu.addEventListener('mouseleave', function () {
    if (window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
      scheduleClose();
    }
  });

  tabs.forEach(function (tab) {
    function go() {
      activate(tab.getAttribute('data-mega-tab'));
      openPanel();
    }
    tab.addEventListener('mouseenter', go);
    tab.addEventListener('focus', go);
    tab.addEventListener('click', function (e) {
      e.preventDefault();
      go();
    });
  });

  document.addEventListener('click', function (e) {
    if (!menu.contains(e.target)) closePanel();
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closePanel();
  });
})();
