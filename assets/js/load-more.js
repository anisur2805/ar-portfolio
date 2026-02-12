jQuery(document).ready(function($) {
	let page = 1;
	const loadingText = 'Loading...';
	const originalText = 'Load More';
	const $btn = $('#load-more-btn');
	const $container = $('.case-studies-grid');

	$btn.on('click', function(e) {
		e.preventDefault();

		if ($btn.hasClass('loading')) return;

		$btn.addClass('loading').text(loadingText);

		$.ajax({
			url: anisur_ajax.ajax_url,
			type: 'POST',
			data: {
				action: 'load_more_portfolio',
				nonce: anisur_ajax.nonce,
				page: page
			},
			success: function(response) {
				if (response.success) {
					const $content = $(response.data.html);
					$container.append($content);
					
					// Trigger reveal animation if observer exists
					if (typeof IntersectionObserver !== 'undefined') {
						const revealObserver = new IntersectionObserver((entries) => {
							entries.forEach(entry => {
								if (entry.isIntersecting) {
									entry.target.classList.add('revealed');
									revealObserver.unobserve(entry.target);
								}
							});
						}, { threshold: 0.1 });
						
						// Filter for element nodes only to avoid "parameter 1 is not of type 'Element'" error
						$content.filter(function() {
							return this.nodeType === 1; // Element node
						}).each(function() {
							revealObserver.observe(this);
						});
					}

					page++;
					$btn.removeClass('loading').text(originalText);

					if (!response.data.has_more) {
						$('#load-more-container').fadeOut();
					}
				} else {
					$btn.removeClass('loading').text('Error');
				}
			},
			error: function() {
				$btn.removeClass('loading').text('Error');
			}
		});
	});
});
