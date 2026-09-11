/**
 * CirclePress frontend — vanilla JS, no dependencies.
 * Drawer menu, search overlay, progress, share copy, checklists, ads.
 */
(function () {
	'use strict';

	/* Mobile drawer. */
	var burger = document.querySelector('.cp-hamburger');
	var drawer = document.querySelector('.cp-drawer');
	var overlay = document.querySelector('.cp-drawer-overlay');
	function openDrawer(open) {
		if (!drawer) { return; }
		drawer.classList.toggle('open', open);
		if (overlay) { overlay.classList.toggle('show', open); }
		document.body.classList.toggle('drawer-open', open);
		if (burger) { burger.setAttribute('aria-expanded', open ? 'true' : 'false'); }
	}
	if (burger) { burger.addEventListener('click', function () { openDrawer(true); }); }
	var drawerClose = document.querySelector('.cp-drawer__close');
	if (drawerClose) { drawerClose.addEventListener('click', function () { openDrawer(false); }); }
	if (overlay) { overlay.addEventListener('click', function () { openDrawer(false); }); }

	/* Search overlay. */
	var searchOverlay = document.querySelector('.cp-search-overlay');
	function closeSearch() {
		if (searchOverlay) { searchOverlay.classList.remove('open'); }
		document.body.classList.remove('search-open');
	}
	document.querySelectorAll('.cp-search-toggle').forEach(function (btn) {
		btn.addEventListener('click', function () {
			if (!searchOverlay) { return; }
			var open = searchOverlay.classList.toggle('open');
			document.body.classList.toggle('search-open', open);
			if (open) {
				var input = searchOverlay.querySelector('input[type="search"]');
				if (input) { setTimeout(function () { input.focus(); }, 60); }
			}
		});
	});
	var searchClose = document.querySelector('.cp-search-overlay__close');
	if (searchClose) { searchClose.addEventListener('click', closeSearch); }
	if (searchOverlay) {
		searchOverlay.addEventListener('click', function (e) { if (e.target === searchOverlay) { closeSearch(); } });
	}
	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape') { openDrawer(false); closeSearch(); }
	});

	/* Drawer submenu toggles (touch-friendly). */
	document.querySelectorAll('.cp-mobile-nav .menu-item-has-children').forEach(function (li) {
		var link = li.querySelector(':scope > a');
		var sub = li.querySelector(':scope > ul');
		if (!link || !sub) { return; }
		sub.classList.add('sub-menu');
		var btn = document.createElement('button');
		btn.type = 'button';
		btn.className = 'cp-sub-toggle';
		btn.setAttribute('aria-expanded', 'false');
		btn.setAttribute('aria-label', 'Expand submenu');
		btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>';
		link.after(btn);
		btn.addEventListener('click', function () {
			var open = li.classList.toggle('open');
			btn.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
	});

	/* Close drawer on desktop resize or link tap. */
	window.addEventListener('resize', function () {
		if (window.innerWidth > 900 && drawer && drawer.classList.contains('open')) { openDrawer(false); }
	});
	if (drawer) {
		drawer.querySelectorAll(':scope a').forEach(function (a) {
			a.addEventListener('click', function () { openDrawer(false); });
		});
	}

	/* Reading progress + back-to-top + header shadow. */
	var progress = document.querySelector('.cp-progress span');
	var toTop = document.querySelector('.cp-top');
	var header = document.querySelector('.cp-header');
	function onScroll() {
		var h = document.documentElement;
		var max = h.scrollHeight - h.clientHeight;
		var pct = max > 0 ? (h.scrollTop / max) * 100 : 0;
		if (progress) { progress.style.width = pct + '%'; }
		if (toTop) { toTop.classList.toggle('show', h.scrollTop > 600); }
		if (header) { header.classList.toggle('is-scrolled', h.scrollTop > 10); }
	}
	document.addEventListener('scroll', onScroll, { passive: true });
	onScroll();
	if (toTop) {
		toTop.addEventListener('click', function () {
			window.scrollTo({ top: 0, behavior: 'smooth' });
		});
	}

	/* Copy link buttons. */
	document.querySelectorAll('.cp-copy-link').forEach(function (btn) {
		btn.addEventListener('click', function (e) {
			e.preventDefault();
			var url = btn.getAttribute('href');
			function done() {
				btn.classList.add('is-copied');
				setTimeout(function () { btn.classList.remove('is-copied'); }, 1600);
			}
			if (navigator.clipboard && navigator.clipboard.writeText) {
				navigator.clipboard.writeText(url).then(done, done);
			} else {
				var tmp = document.createElement('input');
				tmp.value = url;
				document.body.appendChild(tmp);
				tmp.select();
				try { document.execCommand('copy'); } catch (err) {}
				document.body.removeChild(tmp);
				done();
			}
		});
	});

	/* Checkable ingredient / materials lists. */
	document.querySelectorAll('.cp-check li').forEach(function (li) {
		li.addEventListener('click', function () {
			li.classList.toggle('done');
		});
	});

	/* Sticky footer ad close. */
	var stickyClose = document.querySelector('.cp-ad__close');
	if (stickyClose) {
		stickyClose.addEventListener('click', function () {
			var slot = stickyClose.closest('.cp-ad--sticky-footer');
			if (slot) { slot.style.display = 'none'; }
		});
	}

	/* Lazy ad slots: only reveal when near viewport (saves initial load). */
	var lazyAds = document.querySelectorAll('.cp-ad--lazy .cp-ad__code');
	if ('IntersectionObserver' in window && lazyAds.length) {
		lazyAds.forEach(function (code) {
			code.style.minHeight = '60px';
		});
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					entry.target.style.minHeight = '';
					io.unobserve(entry.target);
				}
			});
		}, { rootMargin: '300px' });
		lazyAds.forEach(function (code) { io.observe(code); });
	}

	/* Homepage featured slider (2.2.0). */
	document.querySelectorAll('[data-cp-slider]').forEach(function (slider) {
		var slides = Array.prototype.slice.call(slider.querySelectorAll('.cp-slide'));
		var dots = Array.prototype.slice.call(slider.querySelectorAll('.cp-slider__dots button'));
		if (slides.length < 2) { return; }
		var cur = 0, timer = null;
		function go(n) {
			cur = (n + slides.length) % slides.length;
			slides.forEach(function (s, i) { s.classList.toggle('is-active', i === cur); });
			dots.forEach(function (d, i) { d.classList.toggle('is-active', i === cur); });
		}
		function stop() { if (timer) { clearInterval(timer); timer = null; } }
		function auto() { stop(); timer = setInterval(function () { go(cur + 1); }, 6000); }
		var prev = slider.querySelector('.cp-slider__prev');
		var next = slider.querySelector('.cp-slider__next');
		if (prev) { prev.addEventListener('click', function () { go(cur - 1); auto(); }); }
		if (next) { next.addEventListener('click', function () { go(cur + 1); auto(); }); }
		dots.forEach(function (d, i) { d.addEventListener('click', function () { go(i); auto(); }); });
		slider.addEventListener('mouseenter', stop);
		slider.addEventListener('mouseleave', auto);
		auto();
	});
})();

/* Calm presentation layer: compact mobile menu and search panel. */
document.addEventListener('DOMContentLoaded',function(){
  const header=document.getElementById('site-header-main');
  const menu=document.querySelector('.menu-toggle');
  const search=document.querySelector('.search-toggle');
  const panel=document.getElementById('search-panel');
  if(menu&&header){menu.addEventListener('click',function(){const open=header.classList.toggle('is-open');menu.setAttribute('aria-expanded',String(open));});}
  if(search&&panel){search.addEventListener('click',function(){const open=panel.classList.toggle('is-open');search.setAttribute('aria-expanded',String(open));if(open){panel.querySelector('input')?.focus();}});}
});
