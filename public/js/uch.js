(() => {
  const body = document.body;
  const header = document.querySelector('[data-site-header]');
  const menuButton = document.querySelector('[data-menu-toggle]');
  const mobileMenu = document.querySelector('[data-mobile-menu]');
  const chatPanel = document.querySelector('[data-chat-panel]');
  const chatToggle = document.querySelector('[data-chat-toggle]');
  const backToTop = document.querySelector('[data-back-to-top]');

  const setMenu = (open) => {
    if (!menuButton || !mobileMenu) return;
    menuButton.setAttribute('aria-expanded', String(open));
    menuButton.setAttribute('aria-label', open ? 'Close navigation' : 'Open navigation');
    mobileMenu.hidden = !open;
    body.classList.toggle('menu-open', open);
  };

  menuButton?.addEventListener('click', () => {
    setMenu(menuButton.getAttribute('aria-expanded') !== 'true');
  });
  mobileMenu?.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => setMenu(false)));

  const setChat = (open) => {
    if (!chatPanel || !chatToggle) return;
    chatPanel.hidden = !open;
    chatToggle.setAttribute('aria-expanded', String(open));
    body.classList.toggle('chat-open', open && window.innerWidth <= 560);
    if (open) window.setTimeout(() => chatPanel.querySelector('button, input')?.focus(), 60);
  };

  chatToggle?.addEventListener('click', () => setChat(chatPanel?.hidden ?? true));
  document.querySelectorAll('[data-chat-open]').forEach((button) => button.addEventListener('click', () => setChat(true)));
  document.querySelector('[data-chat-close]')?.addEventListener('click', () => setChat(false));

  document.querySelectorAll('.technical-partner-card').forEach((card) => {
    const details = card.querySelector('[data-technical-partner-details]');
    const toggles = card.querySelectorAll('[data-technical-partner-toggle]');
    if (!details || toggles.length === 0) return;

    const setPartnerDetails = (open) => {
      details.hidden = !open;
      card.classList.toggle('is-expanded', open);
      toggles.forEach((button) => {
        button.setAttribute('aria-expanded', String(open));
      });
      if (!open) {
        toggles[0]?.focus({ preventScroll: true });
      }
    };

    toggles.forEach((button) => {
      button.addEventListener('click', () => {
        setPartnerDetails(details.hidden);
      });
    });
  });

  document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') return;
    setMenu(false);
    setChat(false);
    closeVideo();
  });

  const videoModal = document.querySelector('[data-video-modal]');
  const videoPlayer = document.querySelector('[data-video-player]');
  const videoTitle = videoModal?.querySelector('[data-video-title]');
  const videoCaption = videoModal?.querySelector('[data-video-caption]');
  const videoCloseButtons = document.querySelectorAll('[data-video-close]');
  let lastVideoTrigger = null;

  const clearVideoPlayer = () => {
    if (videoPlayer) videoPlayer.innerHTML = '';
  };

  function closeVideo() {
    if (!videoModal) return;
    videoModal.hidden = true;
    body.classList.remove('video-open');
    clearVideoPlayer();
    lastVideoTrigger?.focus();
  }

  const openVideo = (button) => {
    if (!videoModal || !videoPlayer || !button.dataset.videoSrc) return;
    lastVideoTrigger = button;
    clearVideoPlayer();

    const src = button.dataset.videoSrc;
    const kind = button.dataset.videoKind || 'iframe';
    const aspect = button.dataset.videoAspect || 'wide';
    const title = button.dataset.videoTitle || 'Humelix video';
    const caption = button.dataset.videoCaption || '';
    const poster = button.dataset.videoPoster || '';
    const dialog = videoModal.querySelector('.video-modal-dialog');

    if (videoTitle) videoTitle.textContent = title;
    if (videoCaption) {
      videoCaption.textContent = caption;
      videoCaption.hidden = !caption;
    }

    if (kind === 'video') {
      const video = document.createElement('video');
      video.controls = true;
      video.playsInline = true;
      video.preload = 'metadata';
      video.src = src;
      if (poster) video.poster = poster;
      video.setAttribute('aria-label', title);
      videoPlayer.append(video);
    } else {
      const iframe = document.createElement('iframe');
      iframe.src = src;
      iframe.title = title;
      iframe.loading = 'lazy';
      iframe.allow = 'accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
      iframe.allowFullscreen = true;
      videoPlayer.append(iframe);
    }

    dialog?.classList.toggle('is-short-video', aspect === 'short');
    videoPlayer.classList.toggle('is-short-video', aspect === 'short');
    videoModal.hidden = false;
    body.classList.add('video-open');
    videoModal.querySelector('[data-video-close]')?.focus();
  };

  document.querySelectorAll('[data-video-open]').forEach((button) => {
    button.addEventListener('click', () => openVideo(button));
  });
  videoCloseButtons.forEach((button) => button.addEventListener('click', closeVideo));

  document.querySelectorAll('[data-service-option]').forEach((button) => {
    button.addEventListener('click', () => {
      document.querySelectorAll('[data-service-option]').forEach((option) => option.classList.remove('is-selected'));
      button.classList.add('is-selected');
      const input = document.querySelector('[data-chat-service]');
      if (input) input.value = button.dataset.serviceOption || '';
    });
  });

  document.querySelector('[data-chat-form]')?.addEventListener('submit', async (event) => {
    event.preventDefault();
    const form = event.currentTarget;
    const submit = form.querySelector('button[type="submit"]');
    const status = document.querySelector('[data-chat-status]');
    if (!status || !submit) return;
    submit.disabled = true;
    submit.textContent = 'Sending...';
    status.hidden = true;
    status.classList.remove('is-error');

    try {
      const response = await fetch(form.action, {
        method: 'POST',
        headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: new FormData(form),
      });
      const data = await response.json();
      if (!response.ok) throw new Error(data.message || 'Please check the form and try again.');
      status.textContent = data.message;
      status.hidden = false;
      form.reset();
      document.querySelectorAll('[data-service-option]').forEach((option) => option.classList.remove('is-selected'));
    } catch (error) {
      status.textContent = error.message || 'We could not send your request. Please try again.';
      status.classList.add('is-error');
      status.hidden = false;
    } finally {
      submit.disabled = false;
      submit.textContent = 'Request Service';
    }
  });

  const onScroll = () => {
    header?.classList.toggle('is-scrolled', window.scrollY > 8);
    if (!backToTop) return;
    const documentHeight = document.documentElement.scrollHeight;
    const nearBottom = documentHeight > window.innerHeight + 120
      && window.scrollY + window.innerHeight >= documentHeight - Math.max(180, window.innerHeight * .35);
    backToTop.hidden = !nearBottom;
  };
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });
  backToTop?.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

  const prefetchedLinks = new Set();
  const maxPrefetchLinks = 10;
  const ignoredPrefetchExtensions = /\.(?:pdf|zip|rar|7z|docx?|xlsx?|pptx?|jpg|jpeg|png|gif|webp|avif|svg|mp4|mov|webm)$/i;

  const canPrefetch = (link) => {
    if (!link || prefetchedLinks.size >= maxPrefetchLinks) return false;
    if (link.dataset.noPrefetch !== undefined || link.target === '_blank') return false;

    const href = link.getAttribute('href') || '';
    if (!href || href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:') || href.startsWith('javascript:')) return false;

    try {
      const url = new URL(href, window.location.href);
      if (url.origin !== window.location.origin) return false;
      if (url.pathname === window.location.pathname) return false;
      if (url.pathname.startsWith('/admin') || url.pathname.startsWith('/logout')) return false;
      if (ignoredPrefetchExtensions.test(url.pathname)) return false;
      return !prefetchedLinks.has(url.href);
    } catch (_) {
      return false;
    }
  };

  const prefetchLink = (link) => {
    if (!canPrefetch(link)) return;
    const url = new URL(link.getAttribute('href'), window.location.href);
    prefetchedLinks.add(url.href);

    const resourceHint = document.createElement('link');
    resourceHint.rel = 'prefetch';
    resourceHint.href = url.href;
    resourceHint.as = 'document';
    document.head.append(resourceHint);
  };

  document.querySelectorAll('a[href]').forEach((link) => {
    link.addEventListener('pointerenter', () => prefetchLink(link), { once: true });
    link.addEventListener('touchstart', () => prefetchLink(link), { once: true, passive: true });
  });

  const animated = document.querySelectorAll('[data-animate]');
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (reduceMotion || !('IntersectionObserver' in window)) {
    animated.forEach((item) => item.classList.add('is-visible'));
  } else {
    animated.forEach((item) => {
      const delay = Number.parseInt(item.dataset.delay || '0', 10);
      item.style.transitionDelay = `${Math.min(delay, 400)}ms`;
    });
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -30px' });
    animated.forEach((item) => observer.observe(item));
  }
})();
