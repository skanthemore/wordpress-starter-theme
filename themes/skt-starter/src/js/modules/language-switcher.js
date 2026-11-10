/**
 * Language switcher disclosure.
 */

export function initLanguageSwitcher() {
	const switcher = document.querySelector('[data-language-switcher]');
	const toggle = switcher?.querySelector('button');
	const list = switcher?.querySelector('ul');

	if (!toggle || !list) {
		return;
	}

	const setOpen = (isOpen) => {
		toggle.setAttribute('aria-expanded', String(isOpen));
		list.hidden = !isOpen;
	};

	toggle.addEventListener('click', () => setOpen(list.hidden));

	document.addEventListener('click', (event) => {
		if (!list.hidden && !switcher.contains(event.target)) {
			setOpen(false);
		}
	});

	// Safari reports a null relatedTarget on mousedown; only close when focus
	// actually moves to another element outside the switcher.
	switcher.addEventListener('focusout', (event) => {
		if (event.relatedTarget && !switcher.contains(event.relatedTarget)) {
			setOpen(false);
		}
	});

	switcher.addEventListener('keydown', (event) => {
		if (event.key === 'Escape' && !list.hidden) {
			setOpen(false);
			toggle.focus();
		}
	});
}
