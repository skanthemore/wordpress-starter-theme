/**
 * Upgrade every posts slider to Swiper.
 */
(() => {
	if (typeof window.Swiper !== 'function') {
		return;
	}

	document.querySelectorAll('[data-skt-posts-slider]').forEach((element) => {
		new window.Swiper(element, {
			slidesPerView: 'auto',
			spaceBetween: 16,
			watchOverflow: true,
			grabCursor: true,
			a11y: { enabled: true },
			pagination: {
				el: element.querySelector('.swiper-pagination'),
				clickable: true,
			},
			breakpoints: {
				768: { spaceBetween: 24 },
			},
		});
	});
})();
