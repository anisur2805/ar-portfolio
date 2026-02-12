jQuery(document).ready(function($) {
    const $form = $('#contact-form');
    const $submitBtn = $form.find('button[type="submit"]');
    const originalBtnText = $submitBtn.text();

    $form.on('submit', function(e) {
        e.preventDefault();

        // Basic validation
		const name = $('#contact-name').val().trim();
		const email = $('#contact-email').val().trim();
		const message = $('#contact-message').val().trim();

		const $subjectField = $('#contact-subject');
		const $phoneField   = $('#contact-phone');
		const $hpField      = $('#contact-website'); // honeypot (hidden from humans)

		const subject = $subjectField.length ? $subjectField.val().trim() : '';
		const phone   = $phoneField.length ? $phoneField.val().trim() : '';
		const website = $hpField.length ? $hpField.val().trim() : '';

        if (!name || !email || !message) {
            alert('Please fill in all fields.');
            return;
        }

        $submitBtn.prop('disabled', true).text('Sending...');

        const sendRequest = function(recaptchaToken) {
            $.ajax({
                url: anisur_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'send_contact_email',
                    nonce: anisur_ajax.nonce,
                    name: name,
                    email: email,
                    message: message,
                    subject: subject,
                    phone: phone,
                    website: website,
                    recaptcha_token: recaptchaToken || ''
                },
                success: function(response) {
                    if (response.success) {
                        $form.html('<div class="success-message" style="text-align: center; padding: 40px;"><h3>Thank You!</h3><p>Your message has been sent successfully. I will get back to you soon.</p></div>');
                    } else {
                        alert(response.data.message || 'Something went wrong. Please try again.');
                        $submitBtn.prop('disabled', false).text(originalBtnText);
                    }
                },
                error: function() {
                    alert('Server error. Please try again later.');
                    $submitBtn.prop('disabled', false).text(originalBtnText);
                }
            });
        };

        // If reCAPTCHA v3 is configured, request a token before sending.
        if (typeof anisur_ajax !== 'undefined'
            && anisur_ajax.recaptcha_site_key
            && typeof grecaptcha !== 'undefined') {

            grecaptcha.ready(function() {
                grecaptcha.execute(anisur_ajax.recaptcha_site_key, { action: 'contact' })
                    .then(function(token) {
                        sendRequest(token);
                    })
                    .catch(function() {
                        // If token fetch fails, fall back to sending without it.
                        sendRequest('');
                    });
            });
        } else {
            // No reCAPTCHA configured.
            sendRequest('');
        }
    });
});
