(function () {
  var form = document.querySelector('.ork-search[data-suggest-url]');
  if (!form) return;

  var input = form.querySelector('input[name="q"]');
  var box = document.getElementById('orkSearchSuggest');
  var suggestUrl = form.getAttribute('data-suggest-url');
  if (!input || !box || !suggestUrl) return;

  var timer = null;
  var abortCtrl = null;
  var lastQ = '';

  function hide() {
    box.hidden = true;
    box.innerHTML = '';
    input.setAttribute('aria-expanded', 'false');
  }

  function show() {
    box.hidden = false;
    input.setAttribute('aria-expanded', 'true');
  }

  function escapeHtml(value) {
    return String(value)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  function render(data) {
    var items = Array.isArray(data.items) ? data.items : [];
    if (!items.length) {
      hide();
      return;
    }

    var html = '<ul class="ork-search-suggest-list">';
    items.forEach(function (item) {
      var img = item.image
        ? '<img src="' + escapeHtml(item.image) + '" alt="" loading="lazy">'
        : '<span class="ork-search-suggest-empty">بدون تصویر</span>';
      html +=
        '<li role="option">' +
          '<a class="ork-search-suggest-item" href="' + escapeHtml(item.url) + '">' +
            '<span class="ork-search-suggest-thumb">' + img + '</span>' +
            '<span class="ork-search-suggest-meta">' +
              '<span class="ork-search-suggest-title">' + escapeHtml(item.title) + '</span>' +
              '<span class="ork-search-suggest-price">' + escapeHtml(item.price || '') + '</span>' +
            '</span>' +
          '</a>' +
        '</li>';
    });
    html += '</ul>';
    html +=
      '<a class="ork-search-suggest-more" href="' + escapeHtml(data.more_url || form.action) + '">' +
        'مشاهده بیشتر' +
      '</a>';

    box.innerHTML = html;
    show();
  }

  function fetchSuggest(q) {
    if (abortCtrl) abortCtrl.abort();
    abortCtrl = new AbortController();

    var url = suggestUrl + (suggestUrl.indexOf('?') >= 0 ? '&' : '?') + 'q=' + encodeURIComponent(q);

    fetch(url, {
      headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
      signal: abortCtrl.signal,
      credentials: 'same-origin',
    })
      .then(function (res) {
        if (!res.ok) throw new Error('suggest failed');
        return res.json();
      })
      .then(function (data) {
        if (q !== lastQ) return;
        render(data);
      })
      .catch(function (err) {
        if (err && err.name === 'AbortError') return;
        hide();
      });
  }

  function onInput() {
    var q = (input.value || '').trim();
    lastQ = q;
    clearTimeout(timer);

    if (q.length < 2) {
      hide();
      return;
    }

    timer = setTimeout(function () {
      fetchSuggest(q);
    }, 250);
  }

  input.addEventListener('input', onInput);
  input.addEventListener('focus', function () {
    if ((input.value || '').trim().length >= 2 && box.innerHTML) {
      show();
    } else {
      onInput();
    }
  });

  document.addEventListener('click', function (e) {
    if (!form.contains(e.target)) hide();
  });

  form.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') hide();
  });
})();
