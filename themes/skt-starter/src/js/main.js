/**
 * Theme entry point.
 *
 * Vite bundles this file and its stylesheet into dist/. Every module is a
 * progressive enhancement: the page is fully usable before it runs.
 */

import '../scss/main.scss';

import { initBackToTop } from './modules/back-to-top.js';
import { initHeaderScroll } from './modules/header-scroll.js';
import { initLanguageSwitcher } from './modules/language-switcher.js';
import { initMenuPanel } from './modules/menu-panel.js';
import { initReveal } from './modules/reveal.js';

function init() {
	initMenuPanel();
	initLanguageSwitcher();
	initHeaderScroll();
	initBackToTop();
	initReveal();
}

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', init, { once: true });
} else {
	init();
}
