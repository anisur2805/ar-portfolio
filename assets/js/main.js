document.addEventListener('DOMContentLoaded', () => {
	// Sticky Header
	const header = document.querySelector('.site-header');
	window.addEventListener('scroll', () => {
		if (window.scrollY > 50) {
			header.classList.add('scrolled');
		} else {
			header.classList.remove('scrolled');
		}
	});

	// Reveal Animations on Scroll
	const revealElements = document.querySelectorAll('.section, .card, .expertise-card, .case-study-card');
	const revealObserver = new IntersectionObserver((entries) => {
		entries.forEach(entry => {
			if (entry.isIntersecting) {
				entry.target.classList.add('revealed');
				revealObserver.unobserve(entry.target);
			}
		});
	}, { threshold: 0.1 });

	revealElements.forEach(el => {
		el.style.opacity = '0';
		el.style.transform = 'translateY(20px)';
		el.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
		revealObserver.observe(el);
	});

	// CSS for revealed state
	const style = document.createElement('style');
	style.textContent = `
		.revealed {
			opacity: 1 !important;
			transform: translateY(0) !important;
		}
	`;
	document.head.appendChild(style);

	// Smooth Scroll for Navigation
	document.querySelectorAll('a[href^="#"]').forEach(anchor => {
		anchor.addEventListener('click', function (e) {
			const href = this.getAttribute('href');
			if (href === '#') return;
			
			e.preventDefault();
			const target = document.querySelector(href);
			if (target) {
				const headerHeight = header.offsetHeight;
				window.scrollTo({
					top: target.offsetTop - headerHeight,
					behavior: 'smooth'
				});
			}
		});
	});
});
