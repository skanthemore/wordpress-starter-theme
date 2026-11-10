/**
 * Off-canvas menu panel.
 *
 * The panel is `inert` while closed, so its links are neither focusable nor
 * announced. Escape closes it and returns focus to the toggle.
 */

const OPEN_CLASS = 'has-menu-open';

export function initMenuPanel() {
	const toggle = document.querySelector('[data-menu-toggle]');
	const panel = document.querySelector('[data-menu-panel]');

	if (!toggle || !panel) {
		return;
	}

	const label = toggle.querySelector('[data-menu-toggle-label]');

	const setOpen = (isOpen) => {
		document.body.classList.toggle(OPEN_CLASS, isOpen);
		panel.inert = !isOpen;
		toggle.setAttribute('aria-expanded', String(isOpen));

		if (label) {
			label.textContent = isOpen ? toggle.dataset.labelClose : toggle.dataset.labelOpen;
		}

		if (!isOpen) {
			panel.querySelectorAll('[aria-expanded="true"]').forEach((button) => setSubmenu(button, false));
		}
	};

	toggle.addEventListener('click', () => {
		setOpen(!document.body.classList.contains(OPEN_CLASS));
	});

	panel.addEventListener('click', (event) => {
		const button = event.target.closest('.menu-panel__toggle');

		if (button) {
			setSubmenu(button, button.getAttribute('aria-expanded') !== 'true');
		}
	});

	document.addEventListener('keydown', (event) => {
		if (event.key === 'Escape' && document.body.classList.contains(OPEN_CLASS)) {
			setOpen(false);
			toggle.focus();
		}
	});
}

function setSubmenu(button, isOpen) {
	const submenu = document.getElementById(button.getAttribute('aria-controls'));

	button.setAttribute('aria-expanded', String(isOpen));

	if (submenu) {
		submenu.hidden = !isOpen;
	}
}
