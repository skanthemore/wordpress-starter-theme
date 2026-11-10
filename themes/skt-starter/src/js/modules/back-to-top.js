/**
 * Shows the back-to-top link after the first screen.
 *
 * It is a plain anchor to #main, so it also works without JavaScript; CSS
 * `scroll-behavior` makes the jump smooth.
 */

export function initBackToTop() {
	const link = document.querySelector('[data-back-to-top]');

	if (!link || !('IntersectionObserver' in window)) {
		return;
	}

	const sentinel = document.createElement('div');
	sentinel.style.cssText = 'position:absolute;top:100vh;height:1px;width:1px';
	document.body.prepend(sentinel);

	new IntersectionObserver(([entry]) => {
		link.classList.toggle('is-visible', entry.boundingClientRect.top < 0);
	}).observe(sentinel);
}
