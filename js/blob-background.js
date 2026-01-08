/*
 * Blob Background: standalone initializer for Gutenberg Group blocks
 * Usage:
 *   cbInitBlobBackground(element, { variant: 'default' });
 * Auto-init:
 *   Scans .wp-block-group with is-style-blob-bg-* and attaches blobs.
 */
(function(){
  function createBlobContainer() {
    const container = document.createElement('div');
    container.className = 'cb-blob-background';
    for (let i = 1; i <= 5; i++) {
      const b = document.createElement('div');
      b.className = 'cb-blob blob' + i;
      container.appendChild(b);
    }
    return container;
  }

  function initAnimationForContainer(container) {
    const blobs = [
      container.querySelector('.blob1'),
      container.querySelector('.blob2'),
      container.querySelector('.blob3'),
      container.querySelector('.blob4'),
      container.querySelector('.blob5'),
    ];

    const anim = [
      {x:0, y:0, s:1, r:0},
      {x:0, y:0, s:1, r:0},
      {x:0, y:0, s:1, r:0},
      {x:0, y:0, s:1, r:0},
      {x:0, y:0, s:1, r:0},
    ];

    let mouse = { x: 0.5, y: 0.5 };
    let scrollY = 0;
    let t = 0;

    const onMouseMove = (e) => {
      mouse.x = e.clientX / window.innerWidth;
      mouse.y = e.clientY / window.innerHeight;
    };
    window.addEventListener('mousemove', onMouseMove, { passive: true });

    // Fallback to window.scrollY; if Lenis is present, use it
    scrollY = window.scrollY || 0;
    window.addEventListener('scroll', () => { scrollY = window.scrollY || 0; }, { passive: true });
    if (window.Lenis && typeof window.Lenis === 'function') {
      // If a Lenis instance is globally available as window.__lenis, hook into it
      const lenis = window.__lenis;
      if (lenis && typeof lenis.on === 'function') {
        lenis.on('scroll', ({ scroll }) => { scrollY = scroll; });
      }
    }

    function animate() {
      t += 0.016;
      if (!blobs[0]) return; // safety

      anim[0].x += ((mouse.x - 0.3) * 400 + Math.sin(t) * 40 - anim[0].x) * 0.13;
      anim[0].y += ((mouse.y - 0.3) * 400 + scrollY * 0.12 + Math.cos(t*0.7) * 30 - anim[0].y) * 0.13;
      anim[0].s = 1 + Math.sin(t*0.8) * 0.08;
      anim[0].r = Math.sin(t*0.5) * 8;
      blobs[0].style.transform = `translate(${anim[0].x}px, ${anim[0].y}px) scale(${anim[0].s}) rotate(${anim[0].r}deg)`;

      anim[1].x += ((mouse.x - 0.7) * -350 + Math.cos(t*0.9) * 30 - anim[1].x) * 0.11;
      anim[1].y += ((mouse.y - 0.7) * -350 + scrollY * 0.18 + Math.sin(t*0.6) * 25 - anim[1].y) * 0.11;
      anim[1].s = 1 + Math.cos(t*0.7) * 0.09;
      anim[1].r = Math.cos(t*0.4) * 10;
      blobs[1].style.transform = `translate(${anim[1].x}px, ${anim[1].y}px) scale(${anim[1].s}) rotate(${anim[1].r}deg)`;

      anim[2].x += ((mouse.x - 0.5) * 250 + Math.sin(t*1.2) * 20 - anim[2].x) * 0.10;
      anim[2].y += ((mouse.y - 0.5) * 250 + scrollY * 0.22 + Math.cos(t*0.8) * 20 - anim[2].y) * 0.10;
      anim[2].s = 1 + Math.sin(t*0.6) * 0.07;
      anim[2].r = Math.sin(t*0.3) * 7;
      blobs[2].style.transform = `translate(${anim[2].x}px, ${anim[2].y}px) scale(${anim[2].s}) rotate(${anim[2].r}deg)`;

      anim[3].x += ((mouse.x - 0.8) * -200 + Math.cos(t*1.1) * 25 - anim[3].x) * 0.09;
      anim[3].y += ((mouse.y - 0.2) * 200 + scrollY * 0.13 + Math.sin(t*0.5) * 18 - anim[3].y) * 0.09;
      anim[3].s = 1 + Math.cos(t*0.5) * 0.06;
      anim[3].r = Math.cos(t*0.2) * 6;
      blobs[3].style.transform = `translate(${anim[3].x}px, ${anim[3].y}px) scale(${anim[3].s}) rotate(${anim[3].r}deg)`;

      anim[4].x += ((mouse.x - 0.1) * 160 + Math.sin(t*1.3) * 15 - anim[4].x) * 0.08;
      anim[4].y += ((mouse.y - 0.9) * 160 + scrollY * 0.09 + Math.cos(t*0.9) * 12 - anim[4].y) * 0.08;
      anim[4].s = 1 + Math.sin(t*0.9) * 0.05;
      anim[4].r = Math.sin(t*0.4) * 5;
      blobs[4].style.transform = `translate(${anim[4].x}px, ${anim[4].y}px) scale(${anim[4].s}) rotate(${anim[4].r}deg)`;

      requestAnimationFrame(animate);
    }
    requestAnimationFrame(animate);

    return () => {
      window.removeEventListener('mousemove', onMouseMove);
    };
  }

  function cbInitBlobBackground(element, opts) {
    if (!element) return;
    const container = createBlobContainer();
    element.prepend(container); // place behind content via z-index
    // Allow variant override via opts.variant by toggling class
    if (opts && opts.variant) {
      const valid = ['default','blue','teal','warm'];
      const variant = valid.includes(opts.variant) ? opts.variant : 'default';
      // Ensure element has style class to pick up CSS variables
      element.classList.add('is-style-blob-bg-' + variant);
    }
    return initAnimationForContainer(container);
  }

  function autoInit() {
    const selectors = [
      '.wp-block-group.is-style-blob-bg-default',
      '.wp-block-group.is-style-blob-bg-blue',
      '.wp-block-group.is-style-blob-bg-teal',
      '.wp-block-group.is-style-blob-bg-warm',
    ];
    const groups = document.querySelectorAll(selectors.join(','));
    groups.forEach((el) => {
      // Avoid double-init
      if (el.dataset.cbBlobInit === '1') return;
      el.dataset.cbBlobInit = '1';
      cbInitBlobBackground(el);
    });
  }

  // Expose function globally
  window.cbInitBlobBackground = cbInitBlobBackground;

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', autoInit);
  } else {
    autoInit();
  }
})();
