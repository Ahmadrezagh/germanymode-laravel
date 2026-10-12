(function () {
  var stories = window.__ORK_STORIES__ || [];
  if (!stories.length) return;

  var viewer = document.getElementById('orkStoryViewer');
  var progress = document.getElementById('orkStoryProgress');
  var avatar = document.getElementById('orkStoryAvatar');
  var nameEl = document.getElementById('orkStoryName');
  var caption = document.getElementById('orkStoryCaption');
  var titleEl = document.getElementById('orkStoryTitle');
  var textEl = document.getElementById('orkStoryText');
  var video = document.getElementById('orkStoryVideo');
  var image = document.getElementById('orkStoryImage');
  var cta = document.getElementById('orkStoryCta');
  var ctaImg = document.getElementById('orkStoryCtaImg');
  var ctaTitle = document.getElementById('orkStoryCtaTitle');
  var mediaBox = document.getElementById('orkStoryMedia');

  var index = 0;
  var timer = null;
  var imageDuration = 5500;
  var holdPause = false;

  function buildProgress() {
    progress.innerHTML = stories
      .map(function () {
        return '<span><i></i></span>';
      })
      .join('');
  }

  function setProgressState(active, ratio) {
    var bars = progress.querySelectorAll('span');
    bars.forEach(function (bar, i) {
      var fill = bar.querySelector('i');
      bar.classList.remove('done', 'active');
      if (i < active) {
        bar.classList.add('done');
        fill.style.width = '100%';
      } else if (i === active) {
        bar.classList.add('active');
        fill.style.width = Math.max(0, Math.min(100, ratio * 100)) + '%';
      } else {
        fill.style.width = '0%';
      }
    });
  }

  function clearTimer() {
    if (timer) {
      clearInterval(timer);
      timer = null;
    }
  }

  function stopMedia() {
    clearTimer();
    video.pause();
    video.removeAttribute('src');
    video.load();
    image.removeAttribute('src');
    image.hidden = true;
    video.hidden = true;
  }

  function markSeen(i) {
    try {
      var seen = JSON.parse(localStorage.getItem('ork_stories_seen') || '[]');
      var id = stories[i] && stories[i].id;
      if (id && seen.indexOf(id) === -1) {
        seen.push(id);
        localStorage.setItem('ork_stories_seen', JSON.stringify(seen));
      }
    } catch (e) {}
    var btn = document.querySelector('[data-story-open="' + i + '"]');
    if (btn) btn.classList.add('seen');
  }

  function restoreSeen() {
    try {
      var seen = JSON.parse(localStorage.getItem('ork_stories_seen') || '[]');
      stories.forEach(function (story, i) {
        if (seen.indexOf(story.id) !== -1) {
          var btn = document.querySelector('[data-story-open="' + i + '"]');
          if (btn) btn.classList.add('seen');
        }
      });
    } catch (e) {}
  }

  function open(i) {
    index = Math.max(0, Math.min(stories.length - 1, i));
    viewer.hidden = false;
    viewer.setAttribute('aria-hidden', 'false');
    document.body.classList.add('ork-story-open');
    markSeen(index);
    render();
  }

  function close() {
    stopMedia();
    viewer.hidden = true;
    viewer.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('ork-story-open');
  }

  function next() {
    if (index >= stories.length - 1) {
      close();
      return;
    }
    index += 1;
    render();
  }

  function prev() {
    if (index <= 0) {
      index = 0;
      render();
      return;
    }
    index -= 1;
    render();
  }

  function startImageTimer() {
    clearTimer();
    var started = Date.now();
    setProgressState(index, 0);
    timer = setInterval(function () {
      if (holdPause) {
        started += 50;
        return;
      }
      var ratio = (Date.now() - started) / imageDuration;
      setProgressState(index, ratio);
      if (ratio >= 1) next();
    }, 50);
  }

  function render() {
    var story = stories[index];
    if (!story) return;

    stopMedia();
    buildProgress();
    setProgressState(index, 0);

    avatar.src = story.profile_image || '';
    avatar.hidden = !story.profile_image;
    nameEl.textContent = story.profile_name || '';
    caption.textContent = story.short_text || '';
    titleEl.textContent = story.title || '';
    textEl.textContent = story.short_text || '';

    if (story.link_url) {
      cta.hidden = false;
      cta.href = story.link_url;
      ctaTitle.textContent = story.link_title || 'مشاهده';
      if (story.link_image) {
        ctaImg.hidden = false;
        ctaImg.src = story.link_image;
      } else {
        ctaImg.hidden = true;
        ctaImg.removeAttribute('src');
      }
    } else {
      cta.hidden = true;
    }

    if (story.is_video && story.media) {
      video.hidden = false;
      video.src = story.media;
      video.currentTime = 0;
      var playPromise = video.play();
      if (playPromise && playPromise.catch) playPromise.catch(function () {});
      video.ontimeupdate = function () {
        if (!video.duration) return;
        setProgressState(index, video.currentTime / video.duration);
      };
      video.onended = function () {
        next();
      };
    } else if (story.media) {
      image.hidden = false;
      image.src = story.media;
      image.alt = story.title || story.profile_name || '';
      startImageTimer();
    } else {
      startImageTimer();
    }
  }

  document.querySelectorAll('[data-story-open]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      open(parseInt(btn.getAttribute('data-story-open'), 10) || 0);
    });
  });

  viewer.querySelectorAll('[data-story-close]').forEach(function (el) {
    el.addEventListener('click', close);
  });
  viewer.querySelector('[data-story-prev]').addEventListener('click', prev);
  viewer.querySelector('[data-story-next]').addEventListener('click', next);

  document.addEventListener('keydown', function (e) {
    if (viewer.hidden) return;
    if (e.key === 'Escape') close();
    if (e.key === 'ArrowLeft') next();
    if (e.key === 'ArrowRight') prev();
  });

  mediaBox.addEventListener('pointerdown', function () {
    holdPause = true;
    if (!video.hidden) video.pause();
  });
  mediaBox.addEventListener('pointerup', function () {
    holdPause = false;
    if (!video.hidden) {
      var p = video.play();
      if (p && p.catch) p.catch(function () {});
    }
  });
  mediaBox.addEventListener('pointerleave', function () {
    holdPause = false;
  });

  buildProgress();
  restoreSeen();
})();
