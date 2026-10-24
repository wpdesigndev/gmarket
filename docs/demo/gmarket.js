/* GMarket (desarrollado por GMETI): menú lateral, filtros en móvil y búsqueda en vivo. */
(function () {
	'use strict';

	var body = document.body;

	// Menú lateral «Todas las categorías».
	var drawer = document.querySelector('[data-gmk-drawer-panel]');
	document.addEventListener('click', function (e) {
		var t = e.target.closest('[data-gmk-drawer]');
		if (t && drawer) {
			var open = t.getAttribute('data-gmk-drawer') === 'open';
			drawer.hidden = !open;
			body.classList.toggle('gmk-lock', open);
		}
		var f = e.target.closest('[data-gmk-filters]');
		if (f) {
			body.classList.toggle('gmk-filters-open', f.getAttribute('data-gmk-filters') === 'open');
		}
	});
	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape') {
			if (drawer) { drawer.hidden = true; }
			body.classList.remove('gmk-lock', 'gmk-filters-open');
		}
	});

	// Botón flotante para volver arriba.
	var toTop = document.querySelector('.gmk-totop');
	if (toTop) {
		var onScroll = function () { toTop.hidden = window.scrollY < 500; };
		window.addEventListener('scroll', onScroll, { passive: true });
		onScroll();
		toTop.addEventListener('click', function (e) {
			e.preventDefault();
			window.scrollTo({ top: 0, behavior: 'smooth' });
		});
	}

	// Menús emergentes (categorías del buscador y «Más»): se cierran al tocar fuera o con Escape.
	function closePops(except) {
		document.querySelectorAll('.gmk-menu-pop').forEach(function (m) {
			if (m === except) { return; }
			m.hidden = true;
			var b = m.previousElementSibling;
			if (b) { b.setAttribute('aria-expanded', 'false'); }
		});
	}
	function togglePop(btn, menu) {
		var open = menu.hidden;
		closePops(menu);
		menu.hidden = !open;
		btn.setAttribute('aria-expanded', open ? 'true' : 'false');
		return open;
	}
	document.addEventListener('click', function (e) {
		if (!e.target.closest('.gmk-picker, .gmk-nav__more')) { closePops(); }
	});
	document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { closePops(); } });

	// Selector de categoría del buscador (estilo macOS).
	var picker = document.querySelector('.gmk-picker');
	if (picker) {
		var pBtn = picker.querySelector('.gmk-picker__btn');
		var pMenu = picker.querySelector('.gmk-picker__menu');
		var pLabel = picker.querySelector('.gmk-picker__label');
		var pSelect = document.querySelector('.gmk-search__cat');
		var pItems = pMenu.querySelectorAll('[role="option"]');
		var choose = function (li) {
			pItems.forEach(function (o) { o.setAttribute('aria-selected', o === li ? 'true' : 'false'); });
			pLabel.textContent = li.textContent;
			pSelect.value = li.getAttribute('data-value');
			pSelect.dispatchEvent(new Event('change', { bubbles: true }));
			closePops();
			pBtn.focus();
		};
		pBtn.addEventListener('click', function () {
			if (togglePop(pBtn, pMenu)) {
				var sel = pMenu.querySelector('[aria-selected="true"]') || pItems[0];
				sel.classList.add('is-active');
			}
		});
		pItems.forEach(function (li) {
			li.addEventListener('click', function () { choose(li); });
			li.addEventListener('mouseenter', function () {
				pItems.forEach(function (o) { o.classList.toggle('is-active', o === li); });
			});
		});
		pMenu.addEventListener('mouseleave', function () { pItems.forEach(function (o) { o.classList.remove('is-active'); }); });
		pBtn.addEventListener('keydown', function (e) {
			if (e.key !== 'ArrowDown' && e.key !== 'ArrowUp' && e.key !== 'Enter') { return; }
			if (pMenu.hidden) { if (e.key !== 'Enter') { e.preventDefault(); pBtn.click(); } return; }
			e.preventDefault();
			var list = Array.prototype.slice.call(pItems);
			var i = list.findIndex(function (o) { return o.classList.contains('is-active'); });
			if (e.key === 'Enter') { if (i > -1) { choose(list[i]); } return; }
			i = (i + (e.key === 'ArrowDown' ? 1 : -1) + list.length) % list.length;
			list.forEach(function (o, n) { o.classList.toggle('is-active', n === i); });
			list[i].scrollIntoView({ block: 'nearest' });
		});
	}

	// Listas desplegables de la tienda (ordenar) con el mismo estilo macOS.
	document.querySelectorAll('select.orderby').forEach(function (select) {
		select.classList.add('gmk-enhanced');
		var wrap = document.createElement('div');
		wrap.className = 'gmk-picker gmk-picker--select';
		var btn = document.createElement('button');
		btn.type = 'button';
		btn.className = 'gmk-picker__btn';
		btn.setAttribute('aria-haspopup', 'listbox');
		btn.setAttribute('aria-expanded', 'false');
		btn.innerHTML = '<span class="gmk-picker__label"></span><svg class="gmk-picker__arrow" viewBox="0 0 24 24" aria-hidden="true"><path d="M7 10l5 5 5-5" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>';
		var menu = document.createElement('ul');
		menu.className = 'gmk-menu-pop gmk-picker__menu';
		menu.setAttribute('role', 'listbox');
		menu.hidden = true;
		Array.prototype.forEach.call(select.options, function (opt) {
			var li = document.createElement('li');
			li.setAttribute('role', 'option');
			li.setAttribute('aria-selected', opt.selected ? 'true' : 'false');
			li.textContent = opt.textContent;
			li.addEventListener('mouseenter', function () {
				menu.querySelectorAll('li').forEach(function (o) { o.classList.toggle('is-active', o === li); });
			});
			li.addEventListener('click', function () {
				select.value = opt.value;
				btn.querySelector('.gmk-picker__label').textContent = opt.textContent;
				closePops();
				select.dispatchEvent(new Event('change', { bubbles: true }));
			});
			menu.appendChild(li);
		});
		btn.querySelector('.gmk-picker__label').textContent = select.options[select.selectedIndex].textContent;
		btn.addEventListener('click', function () { togglePop(btn, menu); });
		wrap.appendChild(btn);
		wrap.appendChild(menu);
		select.parentNode.insertBefore(wrap, select.nextSibling);
	});

	// Barra de categorías: lo que no cabe en una línea pasa al menú «Más».
	var navList = document.querySelector('.gmk-nav__list');
	var more = document.querySelector('.gmk-nav__more');
	if (navList && more) {
		var moreBtn = more.querySelector('.gmk-nav__more-btn');
		var moreList = more.querySelector('.gmk-nav__more-list');
		var navItems = Array.prototype.slice.call(navList.children);
		var layout = function () {
			navItems.forEach(function (li) { li.hidden = false; });
			moreList.innerHTML = '';
			// Primero se mide sin «Más»: si todo cabe, no hace falta el botón.
			more.hidden = true;
			var hiddenItems = [];
			if (navList.scrollWidth <= navList.clientWidth + 1) {
				return;
			}
			more.hidden = false;
			for (var i = navItems.length - 1; i >= 0 && navList.scrollWidth > navList.clientWidth + 1; i--) {
				navItems[i].hidden = true;
				hiddenItems.unshift(navItems[i]);
			}
			hiddenItems.forEach(function (li) {
				var a = li.querySelector('a');
				var item = document.createElement('li');
				var link = document.createElement('a');
				link.href = a.href;
				link.textContent = a.textContent;
				item.appendChild(link);
				moreList.appendChild(item);
			});
			more.hidden = !hiddenItems.length;
		};
		moreBtn.addEventListener('click', function () { togglePop(moreBtn, moreList); });
		var t;
		window.addEventListener('resize', function () { clearTimeout(t); t = setTimeout(layout, 120); });
		if (document.fonts && document.fonts.ready) { document.fonts.ready.then(layout); }
		layout();
	}

	// Búsqueda en vivo.
	var form = document.querySelector('.gmk-search');
	if (!form || !window.GMK) { return; }
	var input = form.querySelector('.gmk-search__input');
	var cat = form.querySelector('.gmk-search__cat');
	var box = form.querySelector('.gmk-suggest');
	var timer, last = '', active = -1;

	// En pantallas estrechas el texto de ayuda del buscador es más corto.
	var longPlaceholder = input.getAttribute('placeholder');
	var setPlaceholder = function () {
		input.setAttribute('placeholder', window.innerWidth < 768 && GMK.i18n.short ? GMK.i18n.short : longPlaceholder);
	};
	window.addEventListener('resize', setPlaceholder);
	setPlaceholder();

	function esc(s) {
		return String(s).replace(/[&<>"']/g, function (c) {
			return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
		});
	}

	function render(data, q) {
		var html = '';
		data.cats.forEach(function (c) {
			html += '<a class="gmk-suggest__cat" href="' + esc(c.url) + '">' + esc(GMK.i18n.cat) + ' <strong>' + esc(c.name) + '</strong></a>';
		});
		data.products.forEach(function (p) {
			html += '<a class="gmk-suggest__item" href="' + esc(p.url) + '">' +
				(p.img ? '<img src="' + esc(p.img) + '" alt="" loading="lazy">' : '<span></span>') +
				'<span><strong>' + esc(p.title) + '</strong><small>' + esc(p.cat) + '</small></span>' +
				'<em>' + esc(p.price) + '</em></a>';
		});
		if (!html) {
			html = '<p class="gmk-suggest__none">' + esc(GMK.i18n.none) + '</p>';
		} else {
			html += '<button type="submit" class="gmk-suggest__all">' + esc(GMK.i18n.all) + ' “' + esc(q) + '”</button>';
		}
		box.innerHTML = html;
		box.hidden = false;
		active = -1;
	}

	function search() {
		var q = input.value.trim();
		if (q.length < 2) { box.hidden = true; last = ''; return; }
		var key = q + '|' + cat.value;
		if (key === last) { box.hidden = false; return; }
		last = key;
		var url = GMK.ajax + '?action=gmk_search&nonce=' + encodeURIComponent(GMK.nonce) +
			'&lang=' + encodeURIComponent(GMK.lang || '') + '&q=' + encodeURIComponent(q) + '&cat=' + encodeURIComponent(cat.value);
		fetch(url, { credentials: 'same-origin' })
			.then(function (r) { return r.json(); })
			.then(function (r) { if (r && r.success && key === last) { render(r.data, q); } })
			.catch(function () {});
	}

	input.addEventListener('input', function () {
		clearTimeout(timer);
		timer = setTimeout(search, 220);
	});
	input.addEventListener('focus', function () { if (box.innerHTML && input.value.trim().length > 1) { box.hidden = false; } });
	cat.addEventListener('change', function () { last = ''; search(); });
	input.addEventListener('keydown', function (e) {
		var items = box.querySelectorAll('a');
		if (box.hidden || !items.length) { return; }
		if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
			e.preventDefault();
			active = (active + (e.key === 'ArrowDown' ? 1 : -1) + items.length) % items.length;
			items.forEach(function (a, i) { a.classList.toggle('is-active', i === active); });
		} else if (e.key === 'Enter' && active > -1) {
			e.preventDefault();
			window.location.href = items[active].href;
		}
	});
	document.addEventListener('click', function (e) {
		if (!form.contains(e.target)) { box.hidden = true; }
	});
})();
