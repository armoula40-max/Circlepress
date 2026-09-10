/**
 * CirclePress GDPR consent — vanilla JS, no dependencies (~3KB).
 * Stores {analytics, marketing} in cookie + localStorage for 180 days.
 */
(function () {
	'use strict';

	var COOKIE = 'cp_consent';
	var banner = document.getElementById('cp-consent');
	var fab = document.getElementById('cp-consent-fab');
	var mode = (window.circlepressConsent && window.circlepressConsent.mode) || 'strict';
	var expiryDays = (window.circlepressConsent && window.circlepressConsent.expiryDays) || 180;

	/* Strict-mode backup: hide ad slots client-side too (covers cached pages). */
	try {
		var _mode = (window.circlepressConsent && window.circlepressConsent.mode) || 'strict';
		var _cm = document.cookie.match(/(?:^|; )cp_consent=([^;]*)/);
		var _ok = false;
		if (_cm) { try { _ok = !!JSON.parse(decodeURIComponent(_cm[1])).marketing; } catch (_e) {} }
		if (_mode === 'strict' && !_ok) {
			document.querySelectorAll('.cp-ad').forEach(function (el) { el.style.display = 'none'; });
		}
	} catch (_e2) {}

	function get() {
		try {
			var m = document.cookie.match(/(?:^|; )cp_consent=([^;]*)/);
			if (m) { return JSON.parse(decodeURIComponent(m[1])); }
		} catch (e) {}
		try {
			return JSON.parse(localStorage.getItem(COOKIE) || 'null');
		} catch (e2) {}
		return null;
	}

	function set(v) {
		v.t = Date.now();
		var s = encodeURIComponent(JSON.stringify(v));
		var d = new Date();
		d.setTime(d.getTime() + expiryDays * 864e5);
		document.cookie = COOKIE + '=' + s + ';expires=' + d.toUTCString() + ';path=/;SameSite=Lax';
		try { localStorage.setItem(COOKIE, JSON.stringify(v)); } catch (e) {}
	}

	function gtagUpdate(v) {
		if (typeof gtag === 'function') {
			gtag('consent', 'update', {
				'ad_storage': v.marketing ? 'granted' : 'denied',
				'ad_user_data': v.marketing ? 'granted' : 'denied',
				'ad_personalization': v.marketing ? 'granted' : 'denied',
				'analytics_storage': v.analytics ? 'granted' : 'denied'
			});
		}
	}

	function prefsBox() {
		return banner ? banner.querySelector('.cp-consent__prefs') : null;
	}

	function show(withPrefs) {
		if (!banner) { return; }
		banner.hidden = false;
		var p = prefsBox();
		if (p && withPrefs) { p.hidden = false; }
	}

	function hide() {
		if (banner) { banner.hidden = true; }
		if (fab) { fab.hidden = false; }
	}

	function save(v) {
		set(v);
		gtagUpdate(v);
		hide();
		/* Strict mode gates ads/embeds server-side → reload to apply. */
		if (mode === 'strict') {
			setTimeout(function () { window.location.reload(); }, 350);
		}
	}

	if (banner) {
		var existing = get();
		if (existing && typeof existing.marketing !== 'undefined') {
			hide();
		} else {
			setTimeout(function () { show(false); }, 800);
		}

		var accept = banner.querySelector('[data-cp-accept]');
		var reject = banner.querySelector('[data-cp-reject]');
		var custom = banner.querySelector('[data-cp-custom]');
		var saveBtn = banner.querySelector('[data-cp-save]');

		if (accept) { accept.addEventListener('click', function () { save({ analytics: 1, marketing: 1 }); }); }
		if (reject) { reject.addEventListener('click', function () { save({ analytics: 0, marketing: 0 }); }); }
		if (custom) {
			custom.addEventListener('click', function () {
				var p = prefsBox();
				if (p) { p.hidden = !p.hidden; }
			});
		}
		if (saveBtn) {
			saveBtn.addEventListener('click', function () {
				var v = { analytics: 0, marketing: 0 };
				banner.querySelectorAll('[data-cp-pref]').forEach(function (cb) {
					if (cb.checked) { v[cb.getAttribute('data-cp-pref')] = 1; }
				});
				save(v);
			});
		}
	}

	/* Re-open from footer FAB or [cookie_settings] links. */
	if (fab) { fab.addEventListener('click', function () { show(true); }); }
	document.querySelectorAll('.cp-cookie-settings').forEach(function (a) {
		a.addEventListener('click', function (e) { e.preventDefault(); show(true); });
	});

	/* Click-to-load video facades (strict mode, no marketing consent). */
	document.querySelectorAll('.cp-video-facade').forEach(function (f) {
		if (f.dataset.bound) { return; }
		f.dataset.bound = '1';
		f.addEventListener('click', function () {
			var src = f.getAttribute('data-src');
			if (!src) { return; }
			var ifr = document.createElement('iframe');
			ifr.src = src + (src.indexOf('?') > -1 ? '&' : '?') + 'autoplay=1';
			ifr.setAttribute('allow', 'accelerometer; autoplay; encrypted-media; picture-in-picture');
			ifr.setAttribute('allowfullscreen', '');
			ifr.setAttribute('title', 'Video');
			ifr.style.cssText = 'position:absolute;inset:0;width:100%;height:100%;border:0';
			f.innerHTML = '';
			f.appendChild(ifr);
		});
	});
})();
