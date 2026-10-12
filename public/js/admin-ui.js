(function () {
  function formatSize(bytes) {
    if (!bytes && bytes !== 0) return '';
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1024 * 1024) return Math.max(1, Math.round(bytes / 1024)) + ' KB';
    return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
  }

  function bindFileDrop(root) {
    (root || document).querySelectorAll('[data-file-drop]').forEach(function (box) {
      if (box.dataset.bound === '1') return;
      box.dataset.bound = '1';
      var input = box.querySelector('input[type="file"]');
      var nameEl = box.querySelector('.file-drop-name');
      if (!input) return;

      function sync() {
        var files = input.files;
        if (files && files.length) {
          box.classList.add('has-file');
          if (nameEl) {
            var label;
            if (files.length === 1) {
              label = files[0].name + ' • ' + formatSize(files[0].size);
            } else {
              label = files.length + ' فایل انتخاب شد';
            }
            nameEl.innerHTML =
              '<span>' + label + '</span>' +
              '<button type="button" class="file-drop-clear" title="حذف فایل" aria-label="حذف فایل">×</button>';
            var clearBtn = nameEl.querySelector('.file-drop-clear');
            if (clearBtn) {
              clearBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                input.value = '';
                sync();
              });
            }
          }
        } else {
          box.classList.remove('has-file');
          if (nameEl) nameEl.innerHTML = '';
        }
      }

      input.addEventListener('change', sync);
      ['dragenter', 'dragover'].forEach(function (ev) {
        box.addEventListener(ev, function (e) {
          e.preventDefault();
          box.classList.add('is-dragover');
        });
      });
      ['dragleave', 'drop'].forEach(function (ev) {
        box.addEventListener(ev, function (e) {
          e.preventDefault();
          box.classList.remove('is-dragover');
        });
      });
      box.addEventListener('drop', function (e) {
        if (!e.dataTransfer || !e.dataTransfer.files || !e.dataTransfer.files.length) return;
        try {
          input.files = e.dataTransfer.files;
        } catch (err) {
          // Some browsers block assigning FileList; ignore silently.
        }
        sync();
      });
    });
  }

  function bindMiniUploaders(root) {
    (root || document).querySelectorAll('[data-file-upload-mini]').forEach(function (box) {
      if (box.dataset.bound === '1') return;
      box.dataset.bound = '1';
      var input = box.querySelector('input[type="file"]');
      var url = box.querySelector('.file-upload-mini-url');
      if (!input) return;
      input.addEventListener('change', function () {
        if (!input.files || !input.files.length) return;
        var file = input.files[0];
        if (url) {
          url.value = file.name;
          url.classList.add('has-file');
        }
        box.classList.add('has-file');
      });
    });
  }

  function upgradeBareFileInputs() {
    document.querySelectorAll('input[type="file"]:not([data-skip-upgrade])').forEach(function (input) {
      if (input.closest('[data-file-drop], [data-file-upload-mini]')) return;
      var wrap = document.createElement('label');
      wrap.className = 'file-drop';
      wrap.setAttribute('data-file-drop', '');
      wrap.innerHTML =
        '<span class="file-drop-ico" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 16V6M12 6l-3.5 3.5M12 6l3.5 3.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M5 16.5V18a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-1.5" stroke-linecap="round"/></svg></span>' +
        '<span class="file-drop-title">فایل را بکشید و اینجا رها کنید</span>' +
        '<span class="file-drop-sub">یا روی دکمه انتخاب فایل کلیک کنید</span>' +
        '<span class="file-drop-browse">انتخاب فایل</span>' +
        '<span class="file-drop-name"></span>';
      input.parentNode.insertBefore(wrap, input);
      wrap.insertBefore(input, wrap.firstChild);
      if (input.hasAttribute('multiple')) {
        var sub = wrap.querySelector('.file-drop-sub');
        if (sub) sub.textContent = 'می‌توانید چند فایل را همزمان انتخاب کنید';
      }
    });
    bindFileDrop();
  }

  function polishSelects() {
    document.querySelectorAll('select:not([data-skip-select])').forEach(function (select) {
      if (select.closest('.select-wrap')) return;
      if (!select.classList.contains('shop-select')) {
        select.classList.add('shop-select');
      }
      var wrap = document.createElement('div');
      wrap.className = 'select-wrap';
      var maxW = select.style.maxWidth;
      if (maxW) {
        wrap.style.maxWidth = maxW;
        select.style.maxWidth = '100%';
      }
      select.parentNode.insertBefore(wrap, select);
      wrap.appendChild(select);
    });
  }

  function polishActionCells() {
    document.querySelectorAll('.shop-table tbody td, table.data tbody td').forEach(function (td) {
      if (td.querySelector('.row-actions')) return;
      var hasBtn = td.querySelector('.btn, button, form button, a.btn');
      if (!hasBtn) return;
      var forms = td.querySelectorAll('form');
      var links = td.querySelectorAll('a.btn, button.btn, .btn');
      if (!links.length && !forms.length) return;
      var wrap = document.createElement('div');
      wrap.className = 'row-actions';
      while (td.firstChild) wrap.appendChild(td.firstChild);
      td.appendChild(wrap);
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    upgradeBareFileInputs();
    bindFileDrop();
    bindMiniUploaders();
    polishSelects();
    polishActionCells();
  });
})();
