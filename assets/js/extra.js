/**
 * CirclePress extra features — vanilla JS, no dependencies.
 * Next-post loader, ratings, bookmarks, TTS, dark mode, font size,
 * coupons, servings scaler, gift/AZ filters, shop-look toggles.
 */
function __circlepressReady(fn) {
	if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', fn); }
	else { fn(); }
}
__circlepressReady(function () {
	'use strict';

	var cfg = window.circlepressExtra || {};
	var ajax = cfg.ajax || '';
	var nonce = cfg.nonce || '';
	var i18n = cfg.i18n || {};
	function t(k, fb) { return i18n[k] || fb || k; }
	function $(s, c) { return (c || document).querySelector(s); }
	function $all(s, c) { return Array.prototype.slice.call((c || document).querySelectorAll(s)); }

	function post(data) {
		var fd = new FormData();
		Object.keys(data).forEach(function (k) { fd.append(k, data[k]); });
		fd.append('nonce', nonce);
		return fetch(ajax, { method: 'POST', credentials: 'same-origin', body: fd }).then(function (r) { return r.json(); });
	}

	/* ---------- #1 Auto-load next post ---------- */
	var sentinel = $('#cp-next-sentinel');
	if (sentinel && ajax && 'IntersectionObserver' in window) {
		var loadingNext = false;
		var io = new IntersectionObserver(function (entries) {
			if (!entries[0].isIntersecting || loadingNext) { return; }
			var remaining = parseInt(sentin.getAttribute('data-remaining') || '0', 10);
			var id = sentinel.getAttribute('data-id');
			if (remaining < 1 || !id) { io.disconnect(); return; }
			loadingNext = true;
			post({ action: 'cp_nextpost', id: id }).then(function (res) {
				loadingNext = false;
				if (!res || !res.success || (res.data && res.data.done)) { io.disconnect(); return; }
				var tmp = document.createElement('div');
				tmp.innerHTML = res.data.html;
				while (tmp.firstChild) { sentinel.parentNode.insertBefore(tmp.firstChild, sentinel); }
				sentin.setAttribute('data-id', res.data.id);
				sentin.setAttribute('data-remaining', String(remaining - 1));
				if (res.data.title) { document.title = res.data.title; }
				if (res.data.url && history.replaceState) { try { history.replaceState(null, '', res.data.url); } catch (e) {} }
				/* Refresh AdSense units inside appended content. */
				try {
					$all('.cp-next-appended ins.adsbygoogle').forEach(function () {
						(window.adsbygoogle = window.adsbygoogle || []).push({});
					});
				} catch (e2) {}
				if (remaining - 1 < 1) { io.disconnect(); }
			}).catch(function () { loadingNext = false; io.disconnect(); });
		}, { rootMargin: '600px' });
		io.observe(sentinel);
	}

	/* ---------- #7 Visitor ratings ---------- */
	var rateBox = $('#cp-rate');
	if (rateBox && ajax) {
		$all('[data-rate]', rateBox).forEach(function (btn) {
			btn.addEventListener('click', function () {
				var id = rateBox.getAttribute('data-id');
				var val = btn.getAttribute('data-rate');
				$all('[data-rate]', rateBox).forEach(function (b) { b.disabled = true; });
				post({ action: 'cp_rate', id: id, rating: val }).then(function (res) {
					var msg = $('.cp-rate__msg', rateBox);
					var info = $('.cp-rate__info', rateBox);
					if (res && res.success) {
						if (info) { info.innerHTML = '<strong>' + res.data.avg + '/5</strong> <small>(' + res.data.votes + ')</small>'; }
						if (msg) { msg.textContent = t('thanks', 'Thanks for rating!'); }
						try { document.cookie = 'cp_rated_' + id + '=' + val + ';max-age=15552000;path=/;SameSite=Lax'; } catch (e) {}
					} else {
						if (msg) { msg.textContent = (res && res.data && res.data.msg) || 'Error'; }
						$all('[data-rate]', rateBox).forEach(function (b) { b.disabled = false; });
					}
				}).catch(function () {
					$all('[data-rate]', rateBox).forEach(function (b) { b.disabled = false; });
				});
			});
		});
		/* Hover preview. */
		$all('[data-rate]', rateBox).forEach(function (btn) {
			btn.addEventListener('mouseenter', function () {
				var v = parseInt(btn.getAttribute('data-rate'), 10);
				$all('[data-rate]', rateBox).forEach(function (b) {
					b.classList.toggle('lit', parseInt(b.getAttribute('data-rate'), 10) <= v);
				});
			});
		});
	}

	/* ---------- #12 Bookmarks ---------- */
	var BM_KEY = 'cp_saved';
	function bmGet() {
		try { return JSON.parse(localStorage.getItem(BM_KEY) || '[]'); } catch (e) { return []; }
	}
	function bmSet(list) {
		try { localStorage.setItem(BM_KEY, JSON.stringify(list)); } catch (e) {}
	}
	function bmHas(id) {
		return bmGet().some(function (p) { return String(p.id) === String(id); });
	}
	function paintSaveButtons() {
		$all('[data-cp-save]').forEach(function (btn) {
			var saved = bmHas(btn.getAttribute('data-id'));
			btn.classList.toggle('is-saved', saved);
			var _lbl = btn.querySelector('span'); if (_lbl) { _lbl.textContent = saved ? t('saved', 'Saved') : t('save', 'Save'); }
		});
	}
	paintSaveButtons();
	$all('[data-cp-save]').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var id = btn.getAttribute('data-id');
			var list = bmGet();
			var idx = -1;
			list.forEach(function (p, i) { if (String(p.id) === String(id)) { idx = i; } });
			if (idx > -1) { list.splice(idx, 1); }
			else {
				list.unshift({ id: id, title: btn.getAttribute('data-title') || '', url: btn.getAttribute('data-url') || '', img: btn.getAttribute('data-img') || '' });
				list = list.slice(0, 60);
			}
			bmSet(list);
			paintSaveButtons();
		});
	});
	/* Render [saved_posts] grids. */
	$all('[data-cp-saved-grid]').forEach(function (grid) {
		var list = bmGet();
		if (!list.length) {
			grid.innerHTML = '<p>🤍 ' + t('empty', 'No saved posts yet.') + '</p>';
			return;
		}
		grid.innerHTML = '';
		list.forEach(function (p) {
			var a = document.createElement('article');
			a.className = 'cp-card';
			var media = p.img ? '<a class="cp-card__media" href="' + p.url + '"><img src="' + p.img + '" alt="" loading="lazy"></a>' : '';
			a.innerHTML = media + '<div class="cp-card__body"><h3><a href="' + p.url + '">' + p.title.replace(/</g, '&lt;') + '</a></h3></div>';
			grid.appendChild(a);
		});
	});

	/* ---------- #13 Text-to-speech ---------- */
	var listenBtn = $('[data-cp-listen]');
	if (listenBtn && 'speechSynthesis' in window) {
		var speaking = false;
		function stopSpeak() {
			try { speechSynthesis.cancel(); } catch (e) {}
			speaking = false;
			listenBtn.classList.remove('is-speaking');
			var _ls = listenBtn.querySelector('span'); if (_ls) { _ls.textContent = t('listen', 'Listen'); }
		}
		listenBtn.addEventListener('click', function () {
			if (speaking) { stopSpeak(); return; }
			var entry = $('.cp-single .cp-entry');
			if (!entry) { return; }
			var text = (entry.innerText || entry.textContent || '').replace(/\s+/g, ' ').trim().slice(0, 20000);
			if (!text) { return; }
			try {
				speechSynthesis.cancel();
				var u = new SpeechSynthesisUtterance(text);
				var lang = (document.documentElement.lang || 'en').slice(0, 2);
				u.lang = document.documentElement.lang || 'en-US';
				var voices = speechSynthesis.getVoices ? speechSynthesis.getVoices() : [];
				for (var i = 0; i < voices.length; i++) {
					if ((voices[i].lang || '').slice(0, 2) === lang) { u.voice = voices[i]; break; }
				}
				u.onend = stopSpeak;
				speaking = true;
				listenBtn.classList.add('is-speaking');
				var _ls2 = listenBtn.querySelector('span'); if (_ls2) { _ls2.textContent = t('stop', 'Stop'); }
				speechSynthesis.speak(u);
			} catch (e) { stopSpeak(); }
		});
		window.addEventListener('beforeunload', function () { try { speechSynthesis.cancel(); } catch (e) {} });
	} else if (listenBtn) {
		listenBtn.style.display = 'none';
	}

	/* ---------- #14 Dark mode + font size ---------- */
	var themeBtn = $('[data-cp-theme]');
	if (themeBtn) {
		if (document.documentElement.getAttribute('data-theme') === 'dark') { themeBtn.textContent = '☀️'; }
		themeBtn.addEventListener('click', function () {
			var dark = document.documentElement.getAttribute('data-theme') === 'dark';
			if (dark) {
				document.documentElement.removeAttribute('data-theme');
				themeBtn.textContent = '🌙';
				try { localStorage.setItem('cp_theme', 'light'); } catch (e) {}
			} else {
				document.documentElement.setAttribute('data-theme', 'dark');
				themeBtn.textContent = '☀️';
				try { localStorage.setItem('cp_theme', 'dark'); } catch (e) {}
			}
		});
	}
	function fontScale() {
		try { return parseFloat(localStorage.getItem('cp_font') || '1'); } catch (e) { return 1; }
	}
	function setFont(f) {
		f = Math.min(1.25, Math.max(0.85, Math.round(f * 100) / 100));
		document.documentElement.style.zoom = (f === 1 ? '' : String(f));
		try { localStorage.setItem('cp_font', String(f)); } catch (e) {}
	}
	var fInc = $('[data-cp-font-inc]');
	var fDec = $('[data-cp-font-dec]');
	if (fInc) { fInc.addEventListener('click', function () { setFont(fontScale() + 0.05); }); }
	if (fDec) { fDec.addEventListener('click', function () { setFont(fontScale() - 0.05); }); }

	/* ---------- #4 Coupon: copy + countdown ---------- */
	$all('[data-cp-coupon]').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var code = btn.getAttribute('data-cp-coupon');
			function done() {
				var small = btn.querySelector('small');
				if (small) { small.textContent = '✓ ' + t('copied', 'Copied!'); }
			}
			if (navigator.clipboard && navigator.clipboard.writeText) { navigator.clipboard.writeText(code).then(done, done); }
			else {
				var tmp = document.createElement('input');
				tmp.value = code;
				document.body.appendChild(tmp);
				tmp.select();
				try { document.execCommand('copy'); } catch (e) {}
				document.body.removeChild(tmp);
				done();
			}
		});
	});
	function tickCountdowns() {
		$all('[data-expiry]').forEach(function (el) {
			var end = Date.parse(el.getAttribute('data-expiry'));
			if (isNaN(end)) { return; }
			var diff = end - Date.now();
			if (diff <= 0) {
				el.textContent = '⏰ ' + t('expired', 'Expired');
				el.classList.add('is-expired');
				return;
			}
			var d = Math.floor(diff / 864e5), h = Math.floor(diff / 36e5) % 24, m = Math.floor(diff / 6e4) % 60;
			el.textContent = '⏰ ' + t('endsIn', 'Ends in') + ' ' + d + 'd ' + h + 'h ' + m + 'm';
		});
	}
	tickCountdowns();
	setInterval(tickCountdowns, 60000);

	/* ---------- #11 Recipe tools: copy ingredients + servings scaler ---------- */
	var FRAC = { '¼': 0.25, '½': 0.5, '¾': 0.75, '⅓': 1 / 3, '⅔': 2 / 3, '⅛': 0.125, '⅜': 0.375, '⅝': 0.625, '⅞': 0.875 };
	function parseQty(s) {
		var m = s.match(/^\s*(\d+)?\s*([¼½¾⅓⅔⅛⅜⅝⅞])?\s*(\d+\s*\/\s*\d+)?\s*(\d+(?:\.\d+)?)?/);
		if (!m || (!m[1] && !m[2] && !m[3] && !m[4])) { return null; }
		var val = 0, len = m[0].length;
		if (m[1]) { val += parseFloat(m[1]); }
		if (m[2]) { val += FRAC[m[2]] || 0; }
		if (m[3]) { var p = m[3].split('/'); val += parseFloat(p[0]) / parseFloat(p[1]); }
		if (m[4] && !m[1]) { val += parseFloat(m[4]); }
		if (!val) { return null; }
		return { val: val, len: len };
	}
	function fmtQty(v) {
		if (v <= 0) { return ''; }
		var rounded = Math.round(v * 4) / 4;
		var whole = Math.floor(rounded + 1e-6);
		var frac = rounded - whole;
		var fmap = { 0: '', 0.25: '¼', 0.5: '½', 0.75: '¾' };
		var key = Math.round(frac * 100) / 100;
		var fs = fmap[key] !== undefined ? fmap[key] : '';
		if (!fs && frac > 0.02) { return String(Math.round(v * 10) / 10); }
		if (whole === 0) { return fs || '¼'; }
		return fs ? whole + ' ' + fs : String(whole);
	}
	$all('.cp-recipe').forEach(function (card) {
		var list = card.querySelector('ul');
		if (!list) { return; }
		var items = $all('li', list);
		/* Toolbar. */
		var bar = document.createElement('div');
		bar.className = 'cp-recipe__tools';
		var copyBtn = document.createElement('button');
		copyBtn.type = 'button';
		copyBtn.className = 'cp-btn cp-btn--sm cp-btn--outline';
		copyBtn.textContent = '⧉ ' + t('copyIng', 'Copy ingredients');
		copyBtn.addEventListener('click', function () {
			var txt = items.map(function (li) { return '• ' + li.textContent.trim(); }).join('\n');
			function done() { copyBtn.textContent = '✓ ' + t('copied', 'Copied!'); }
			if (navigator.clipboard && navigator.clipboard.writeText) { navigator.clipboard.writeText(txt).then(done, done); }
		});
		bar.appendChild(copyBtn);
		/* Servings stepper. */
		var meta = card.querySelector('.cp-recipe__meta');
		var servDiv = null;
		if (meta) {
			$all('div', meta).forEach(function (d) {
				if (/serving/i.test(d.textContent)) { servDiv = d; }
			});
		}
		if (servDiv) {
			var b = servDiv.querySelector('b');
			var base = b ? parseFloat(b.textContent) : NaN;
			if (b && !isNaN(base) && base > 0) {
				items.forEach(function (li) { li.setAttribute('data-orig', li.textContent); });
				var step = document.createElement('span');
				step.className = 'cp-serv';
				step.innerHTML = '<button type="button" aria-label="−">−</button><b></b><button type="button" aria-label="+">+</button>';
				var label = step.querySelector('b');
				var cur = base;
				function render() {
					label.textContent = t('servings', 'Servings') + ': ' + cur;
					b.textContent = cur;
					items.forEach(function (li) {
						var orig = li.getAttribute('data-orig');
						var q = parseQty(orig);
						if (!q) { return; }
						li.textContent = fmtQty(q.val * (cur / base)) + orig.slice(q.len);
					});
				}
				step.querySelectorAll('button')[0].addEventListener('click', function () { if (cur > 1) { cur--; render(); } });
				step.querySelectorAll('button')[1].addEventListener('click', function () { if (cur < 48) { cur++; render(); } });
				render();
				bar.appendChild(step);
			}
		}
		var h3 = card.querySelector('h3');
		if (h3) { h3.after(bar); } else { card.insertBefore(bar, list); }
	});

	/* ---------- #3 Shop-the-look touch toggles ---------- */
	$all('.cp-look__dot').forEach(function (dot) {
		dot.addEventListener('click', function (e) {
			if (e.target.closest('a')) { return; }
			var was = dot.classList.contains('open');
			$all('.cp-look__dot.open').forEach(function (d) { d.classList.remove('open'); });
			if (!was) { dot.classList.add('open'); }
		});
	});

	/* ---------- #5 Gift filters ---------- */
	$all('.cp-gift').forEach(function (wrap) {
		$all('[data-gift-filter]', wrap).forEach(function (btn) {
			btn.addEventListener('click', function () {
				$all('[data-gift-filter]', wrap).forEach(function (b) { b.classList.remove('is-active'); });
				btn.classList.add('is-active');
				var f = btn.getAttribute('data-gift-filter');
				$all('.cp-gift__item', wrap).forEach(function (item) {
					item.style.display = (f === 'all' || item.getAttribute('data-cat') === f) ? '' : 'none';
				});
			});
		});
	});

	/* ---------- #10 A-Z index filters ---------- */
	var azSearch = $('#cp-az-search');
	var azCat = $('#cp-az-cat');
	function azApply() {
		var q = azSearch ? azSearch.value.toLowerCase() : '';
		var c = azCat ? azCat.value : '';
		$all('[data-az-item]').forEach(function (li) {
			var okQ = !q || li.textContent.toLowerCase().indexOf(q) > -1;
			var okC = !c || (li.getAttribute('data-cats') || '').split('|').indexOf(c) > -1;
			li.style.display = (okQ && okC) ? '' : 'none';
		});
		$all('[data-az-group]').forEach(function (g) {
			var any = $all('[data-az-item]', g).some(function (li) { return li.style.display !== 'none'; });
			g.style.display = any ? '' : 'none';
		});
	}
	if (azSearch) { azSearch.addEventListener('input', azApply); }
	if (azCat) { azCat.addEventListener('change', azApply); }
});
