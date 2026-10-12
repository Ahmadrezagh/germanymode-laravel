(function () {
  var root = document.getElementById('orkHero');
  if (!root) return;

  var slides = Array.prototype.slice.call(root.querySelectorAll('[data-hero-slide]'));
  var dots = Array.prototype.slice.call(root.querySelectorAll('[data-hero-dot]'));
  var progress = root.querySelector('[data-hero-progress]');
  if (!slides.length) return;

  var index = 0;
  var timer = null;
  var duration = 5600;
  var started = 0;
  var paused = false;

  var dragging = false;
  var dragMoved = false;
  var startX = 0;
  var lastX = 0;
  var pointerId = null;

  function setActive(i, resetProgress) {
    index = (i + slides.length) % slides.length;
    slides.forEach(function (slide, n) {
      var on = n === index;
      slide.classList.toggle('is-active', on);
      slide.hidden = !on;
    });
    dots.forEach(function (dot, n) {
      dot.classList.toggle('is-active', n === index);
    });
    if (resetProgress !== false) {
      started = Date.now();
      if (progress) progress.style.width = '0%';
    }
  }

  function tick() {
    if (paused || dragging) {
      started += 40;
      return;
    }
    var ratio = (Date.now() - started) / duration;
    if (progress) progress.style.width = Math.min(100, ratio * 100) + '%';
    if (ratio >= 1) setActive(index + 1, true);
  }

  function start() {
    clearInterval(timer);
    started = Date.now();
    timer = setInterval(tick, 40);
  }

  dots.forEach(function (dot) {
    dot.addEventListener('click', function () {
      setActive(parseInt(dot.getAttribute('data-hero-dot'), 10) || 0, true);
    });
  });

  function onPointerDown(e) {
    if (e.pointerType === 'mouse' && e.button !== 0) return;
    if (e.target.closest('.ork-hero-dots') || e.target.closest('.ork-hero-cta')) return;
    dragging = true;
    dragMoved = false;
    startX = e.clientX;
    lastX = e.clientX;
    pointerId = e.pointerId;
    paused = true;
    root.classList.add('is-dragging');
    try { root.setPointerCapture(e.pointerId); } catch (err) {}
  }

  function onPointerMove(e) {
    if (!dragging || e.pointerId !== pointerId) return;
    lastX = e.clientX;
    if (Math.abs(lastX - startX) > 8) {
      dragMoved = true;
    }
  }

  function onPointerUp(e) {
    if (!dragging || e.pointerId !== pointerId) return;
    dragging = false;
    pointerId = null;
    root.classList.remove('is-dragging');
    try { root.releasePointerCapture(e.pointerId); } catch (err) {}

    var dx = lastX - startX;
    // RTL: drag right (positive) => previous, drag left => next
    if (Math.abs(dx) >= 40) {
      if (dx > 0) setActive(index - 1, true);
      else setActive(index + 1, true);
    }
    paused = false;
    started = Date.now();
  }

  root.addEventListener('pointerdown', onPointerDown);
  root.addEventListener('pointermove', onPointerMove);
  root.addEventListener('pointerup', onPointerUp);
  root.addEventListener('pointercancel', onPointerUp);
  root.addEventListener('pointerleave', function (e) {
    if (dragging) onPointerUp(e);
  });

  // Prevent accidental link navigation after a drag
  root.addEventListener('click', function (e) {
    if (!dragMoved) return;
    if (e.target.closest('a')) {
      e.preventDefault();
      e.stopPropagation();
    }
    dragMoved = false;
  }, true);

  root.addEventListener('dragstart', function (e) {
    e.preventDefault();
  });

  setActive(0, true);
  start();
})();
