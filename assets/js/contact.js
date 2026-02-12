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

        if (!name || !email || !message) {
            alert('Please fill in all fields.');
            return;
        }

        $submitBtn.prop('disabled', true).text('Sending...');

        $.ajax({
            url: anisur_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'send_contact_email',
                nonce: anisur_ajax.nonce,
                name: name,
                email: email,
                message: message
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
    });
});
