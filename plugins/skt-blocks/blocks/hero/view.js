/**
 * Hero background video.
 *
 * The <video> ships without a source so it never competes with the hero
 * image for bandwidth. Once the page has loaded and the browser is idle, the
 * source is attached and playback starts. Visitors who prefer reduced motion
 * or have data saving enabled keep the still image.
 */
(() => {
	const videos = document.querySelectorAll('.skt-hero__video[data-src]');
	const saveData = navigator.connection?.saveData === true;
	const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	if (!videos.length || saveData || reducedMotion) {
		return;
	}

	const start = () => {
		videos.forEach((video) => {
			video.src = video.dataset.src;
			video.removeAttribute('data-src');
			video.addEventListener('playing', () => video.classList.add('is-playing'), { once: true });
			video.play().catch(() => {});
		});
	};

	const schedule = () => ('requestIdleCallback' in window ? requestIdleCallback(start, { timeout: 2000 }) : setTimeout(start, 300));

	if (document.readyState === 'complete') {
		schedule();
	} else {
		window.addEventListener('load', schedule, { once: true });
	}
})();
