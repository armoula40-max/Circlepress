/**
 * CirclePress frontend — vanilla JS, no dependencies (~4KB).
 */
(function () {
	'use strict';

	/* Mobile nav toggle. */
	var burger = document.querySelector('.cp-hamburger');
	var mobileNav = document.querySelector('.cp-mobile-nav');
	if (burger && mobileNav) {
		burger.addEventListener('click', function () {
			var open = mobileNav.classList.toggle('open');
			burger.setAttribute('aria-expanded', open ? 'true' : 'false');
			burger.textContent = open ? '✕' : '☰';
		});
	}

	/* Search toggle. */
	var searchBtn = document.querySelector('.cp-search-toggle');
	var searchBar = document.querySelector('.cp-search-bar');
	if (searchBtn && searchBar) {
		searchBtn.addEventListener('click', function () {
			var open = searchBar.classList.toggle('open');
			searchBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
			if (open) {
				var input = searchBar.querySelector('input[type="search"]');
				if (input) { input.focus(); }
			}
		});
	}

	/* Reading progress + back-to-top. */
	var progress = document.querySelector('.cp-progress span');
	var toTop = document.querySelector('.cp-top');
	function onScroll() {
		var h = document.documentElement;
		var max = h.scrollHeight - h.clientHeight;
		var pct = max > 0 ? (h.scrollTop / max) * 100 : 0;
		if (progress) { progress.style.width = pct + '%'; }
		if (toTop) { toTop.classList.toggle('show', h.scrollTop > 600); }
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
				var msg = (window.circlepressData && window.circlepressData.copied) || 'Link copied!';
				btn.textContent = '✓';
				btn.setAttribute('title', msg);
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

	/* Mobile submenu toggles (touch-friendly dropdowns). */
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
		btn.textContent = '+';
		link.after(btn);
		btn.addEventListener('click', function () {
			var open = li.classList.toggle('open');
			btn.setAttribute('aria-expanded', open ? 'true' : 'false');
			btn.textContent = open ? '−' : '+';
		});
	});

	/* Close mobile nav on desktop resize or link tap. */
	window.addEventListener('resize', function () {
		if (window.innerWidth > 820 && mobileNav && mobileNav.classList.contains('open')) {
			mobileNav.classList.remove('open');
			if (burger) { burger.setAttribute('aria-expanded', 'false'); burger.textContent = '☰'; }
		}
	});
	if (mobileNav) {
		mobileNav.querySelectorAll(':scope a').forEach(function (a) {
			a.addEventListener('click', function () {
				mobileNav.classList.remove('open');
				if (burger) { burger.setAttribute('aria-expanded', 'false'); burger.textContent = '☰'; }
			});
		});
	}
})();
