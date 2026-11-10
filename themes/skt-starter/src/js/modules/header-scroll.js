/**
 * Adds `is-scrolled` to the header once the page leaves the top, so CSS can
 * switch to the compact state.
 */

const THRESHOLD = 24;

export function initHeaderScroll() {
	const header = document.querySelector('.site-header');

	if (!header) {
		return;
	}

	let ticking = false;

	const update = () => {
		header.classList.toggle('is-scrolled', window.scrollY > THRESHOLD);
		ticking = false;
	};

	window.addEventListener(
		'scroll',
		() => {
			if (!ticking) {
				ticking = true;
				window.requestAnimationFrame(update);
			}
		},
		{ passive: true }
	);

	update();
}
