/**
 * Scroll reveal.
 *
 * Markup contract:
 *   data-reveal          fades in and rises when it enters the viewport
 *   data-reveal="fade"   opacity only
 *   data-reveal="image"  opacity and a subtle scale, for media
 *   data-reveal-group    reveals its [data-reveal] children together, staggered
 *
 * Nothing is hidden until `html.has-motion` is set, which happens last. If
 * this script fails, never runs or the visitor prefers reduced motion, the
 * page renders fully visible. Elements already on screen are shown at once,
 * so first paint and LCP never wait for it. Only opacity and transform are
 * animated.
 */

const STAGGER_MS = 80;
const MAX_STAGGER_STEPS = 6;

export function initReveal() {
	if (window.matchMedia('(prefers-reduced-motion: reduce)').matches || !('IntersectionObserver' in window)) {
		return;
	}

	const groups = [...document.querySelectorAll('[data-reveal-group]')];
	const singles = [...document.querySelectorAll('[data-reveal]')].filter((element) => !element.closest('[data-reveal-group]'));
	const targets = [...groups, ...singles];

	if (!targets.length) {
		return;
	}

	const observer = new IntersectionObserver(
		(entries) => {
			entries
				.filter((entry) => entry.isIntersecting)
				.forEach((entry) => {
					reveal(entry.target, true);
					observer.unobserve(entry.target);
				});
		},
		{ rootMargin: '0px 0px -12% 0px' }
	);

	const viewportHeight = document.documentElement.clientHeight;

	targets.forEach((target) => {
		const { top, bottom } = target.getBoundingClientRect();

		if (top < viewportHeight && bottom > 0) {
			reveal(target, false);
		} else {
			observer.observe(target);
		}
	});

	document.documentElement.classList.add('has-motion');
}

function reveal(target, stagger) {
	const items = target.hasAttribute('data-reveal-group') ? target.querySelectorAll('[data-reveal]') : [target];

	items.forEach((item, index) => {
		if (stagger && index > 0) {
			item.style.setProperty('--reveal-delay', `${Math.min(index, MAX_STAGGER_STEPS) * STAGGER_MS}ms`);
		}

		item.classList.add('is-inview');
	});
}
