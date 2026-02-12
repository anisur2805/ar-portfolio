<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once get_template_directory() . '/inc/portfolio-data.php';

/**
 * Enqueue scripts and styles.
 */
function anisur_portfolio_scripts() {
	// Main Stylesheet
	wp_enqueue_style( 'anisur-main-style', get_template_directory_uri() . '/assets/css/main.css', array(), '1.0.0' );

	// Google Fonts: Outfit (Headings), Inter (Body), JetBrains Mono (Code)
	wp_enqueue_style( 'anisur-google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=JetBrains+Mono:wght@400;500&family=Outfit:wght@400;500;700;800&display=swap', array(), null );

	// Font Awesome for Social Icons
	wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css', array(), '6.0.0' );

	// Load More & Contact Scripts
	if ( is_front_page() ) {
		wp_enqueue_script( 'anisur-load-more', get_template_directory_uri() . '/assets/js/load-more.js', array( 'jquery' ), '1.0.0', true );

		$recaptcha_site_key = get_theme_mod( 'recaptcha_site_key', '' );

		if ( ! empty( $recaptcha_site_key ) ) {
			wp_enqueue_script(
				'google-recaptcha',
				'https://www.google.com/recaptcha/api.js?render=' . rawurlencode( $recaptcha_site_key ),
				array(),
				null,
				true
			);
		}

		wp_enqueue_script(
			'anisur-contact',
			get_template_directory_uri() . '/assets/js/contact.js',
			! empty( $recaptcha_site_key ) ? array( 'jquery', 'google-recaptcha' ) : array( 'jquery' ),
			'1.0.0',
			true
		);

		wp_localize_script(
			'anisur-contact',
			'anisur_ajax',
			array(
				'ajax_url'           => admin_url( 'admin-ajax.php' ),
				'nonce'              => wp_create_nonce( 'anisur_load_more_nonce' ),
				'recaptcha_site_key' => $recaptcha_site_key,
			)
		);
	}
}
add_action( 'wp_enqueue_scripts', 'anisur_portfolio_scripts' );

/**
 * Theme Support & Menus
 */
function anisur_portfolio_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

	// Register Menus
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'anisur-portfolio' ),
	) );
}
add_action( 'after_setup_theme', 'anisur_portfolio_setup' );

/**
 * Remove Gutenberg Block Library CSS from loading on the front end
 * (We want pure custom CSS for this portfolio, unless specific blocks are used)
 */
function anisur_remove_wp_block_library_css() {
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'wc-blocks-style' );
}
// Uncomment the line below if we really want to go "raw HTML" feel inside WP
// add_action( 'wp_enqueue_scripts', 'anisur_remove_wp_block_library_css', 100 );

/**
 * Register WordPress Customizer settings.
 */
function anisur_portfolio_customize_register( $wp_customize ) {
	// Add Panel for Seera Theme
	$wp_customize->add_panel( 'seera_theme_panel', array(
		'title'       => __( 'Seera Theme Settings', 'anisur-portfolio' ),
		'priority'    => 30,
		'description' => __( 'Customize your Seera portfolio theme.', 'anisur-portfolio' ),
	) );

	// Section: Hero
	$wp_customize->add_section( 'seera_hero_section', array(
		'title' => __( 'Hero Section', 'anisur-portfolio' ),
		'panel' => 'seera_theme_panel',
	) );

	$wp_customize->add_setting( 'hero_intro', array( 'default' => 'WordPress Developer & Architect', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'hero_intro', array( 'label' => 'Intro Text', 'section' => 'seera_hero_section', 'type' => 'text' ) );

	$wp_customize->add_setting( 'hero_name_top', array( 'default' => 'Transforming Your', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'hero_name_top', array( 'label' => 'Name Top Line', 'section' => 'seera_hero_section', 'type' => 'text' ) );

	$wp_customize->add_setting( 'hero_name_bottom', array( 'default' => 'Ideas into Reality', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'hero_name_bottom', array( 'label' => 'Name Bottom Line', 'section' => 'seera_hero_section', 'type' => 'text' ) );

	$wp_customize->add_setting( 'hero_description', array( 'default' => 'Passionate about creating intuitive and engaging user experiences. I specialize in transforming ideas into beautifully crafted WordPress solutions.', 'sanitize_callback' => 'textarea_field' ) );
	$wp_customize->add_control( 'hero_description', array( 'label' => 'Description', 'section' => 'seera_hero_section', 'type' => 'textarea' ) );

	// Section: Stats
	$wp_customize->add_section( 'seera_stats_section', array(
		'title' => __( 'Statistics', 'anisur-portfolio' ),
		'panel' => 'seera_theme_panel',
	) );

	for ( $i = 1; $i <= 3; $i++ ) {
		$wp_customize->add_setting( "stat_number_$i", array( 'default' => '0', 'sanitize_callback' => 'sanitize_text_field' ) );
		$wp_customize->add_control( "stat_number_$i", array( 'label' => "Stat $i Number", 'section' => 'seera_stats_section', 'type' => 'text' ) );
		$wp_customize->add_setting( "stat_label_$i", array( 'default' => 'Label', 'sanitize_callback' => 'sanitize_text_field' ) );
		$wp_customize->add_control( "stat_label_$i", array( 'label' => "Stat $i Label", 'section' => 'seera_stats_section', 'type' => 'text' ) );
	}

	// Section: Contact
	$wp_customize->add_section( 'seera_contact_section', array(
		'title' => __( 'Contact Information', 'anisur-portfolio' ),
		'panel' => 'seera_theme_panel',
	) );

	$wp_customize->add_setting( 'contact_email', array( 'default' => 'anisur2805@gmail.com', 'sanitize_callback' => 'sanitize_email' ) );
	$wp_customize->add_control( 'contact_email', array( 'label' => 'Email Address', 'section' => 'seera_contact_section', 'type' => 'email' ) );

	$wp_customize->add_setting( 'github_url', array( 'default' => 'https://github.com/anisur2805', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( 'github_url', array( 'label' => 'GitHub URL', 'section' => 'seera_contact_section', 'type' => 'url' ) );

	// reCAPTCHA v3 settings
	$wp_customize->add_setting(
		'recaptcha_site_key',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'recaptcha_site_key',
		array(
			'label'       => __( 'reCAPTCHA v3 Site Key', 'anisur-portfolio' ),
			'description' => __( 'Get keys from Google reCAPTCHA admin console and paste the site key here.', 'anisur-portfolio' ),
			'section'     => 'seera_contact_section',
			'type'        => 'text',
		)
	);

	$wp_customize->add_setting(
		'recaptcha_secret_key',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'recaptcha_secret_key',
		array(
			'label'       => __( 'reCAPTCHA v3 Secret Key', 'anisur-portfolio' ),
			'description' => __( 'Secret key used on the server to verify reCAPTCHA tokens.', 'anisur-portfolio' ),
			'section'     => 'seera_contact_section',
			'type'        => 'text',
		)
	);
}
add_action( 'customize_register', 'anisur_portfolio_customize_register' );

/**
 * AJAX Load More Portfolio
 */
function anisur_load_more_portfolio() {
	check_ajax_referer( 'anisur_load_more_nonce', 'nonce' );

	$page = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1;
	$posts_per_page = 6;
	$offset = $page * $posts_per_page;

	$items = anisur_get_portfolio_items();
	$total_items = count( $items );
	$sliced_items = array_slice( $items, $offset, $posts_per_page );

	ob_start();

	if ( ! empty( $sliced_items ) ) {
		foreach ( $sliced_items as $item ) {
			?>
			<article class="case-study-card revealed" style="opacity: 1; transform: translateY(0);">
				<div class="case-study-image" style="background: <?php echo esc_attr( $item['bg_color'] ); ?>;">
					<?php if ( strpos( $item['image'], '.svg' ) !== false || strpos( $item['image'], '.gif' ) !== false ) : ?>
						<img src="<?php echo esc_url( $item['image'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" style="width: 100%; height: 100%; object-fit: cover;">
					<?php else : ?>
						<img src="<?php echo esc_url( $item['image'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" style="width: 100%; height: 100%; object-fit: cover;">
					<?php endif; ?>
				</div>
				<div class="case-study-content">
					<div class="case-study-meta">
						<?php foreach ( $item['tags'] as $tag ) : ?>
							<span><?php echo esc_html( $tag ); ?></span>
						<?php endforeach; ?>
					</div>
					<h4><?php echo esc_html( $item['title'] ); ?></h4>
					<p><?php echo esc_html( $item['description'] ); ?></p>
					<a href="<?php echo esc_url( $item['link'] ); ?>" target="_blank" class="case-study-link"><?php echo esc_html( $item['link_text'] ); ?></a>
				</div>
			</article>
			<?php
		}
	}

	$content = ob_get_clean();
	$has_more = ( $offset + $posts_per_page ) < $total_items;

	wp_send_json_success( array(
		'html'     => $content,
		'has_more' => $has_more,
	) );
}
add_action( 'wp_ajax_load_more_portfolio', 'anisur_load_more_portfolio' );
add_action( 'wp_ajax_load_more_portfolio', 'anisur_load_more_portfolio' );
add_action( 'wp_ajax_nopriv_load_more_portfolio', 'anisur_load_more_portfolio' );

/**
 * AJAX Contact Form Handler
 */
function anisur_send_contact_email() {
	check_ajax_referer( 'anisur_load_more_nonce', 'nonce' ); // Using the same nonce action for simplicity

	$name          = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email         = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$message       = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';
	$phone         = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$user_subject  = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : '';
	$honeypot      = isset( $_POST['website'] ) ? trim( wp_unslash( $_POST['website'] ) ) : '';
	$recaptcha     = isset( $_POST['recaptcha_token'] ) ? sanitize_text_field( wp_unslash( $_POST['recaptcha_token'] ) ) : '';

	// Honeypot check: real users never see/fill this field.
	if ( ! empty( $honeypot ) ) {
		// Pretend success to avoid giving bots feedback.
		wp_send_json_success();
	}

	// Basic validation.
	if ( empty( $name ) || empty( $email ) || empty( $message ) || ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => 'Please provide a valid name, email, and message.' ) );
	}

	// reCAPTCHA v3 verification (if configured).
	$recaptcha_secret = get_theme_mod( 'recaptcha_secret_key', '' );
	if ( ! empty( $recaptcha_secret ) ) {
		if ( empty( $recaptcha ) ) {
			wp_send_json_error( array( 'message' => 'reCAPTCHA verification failed. Please try again.' ) );
		}

		$verify_response = wp_remote_post(
			'https://www.google.com/recaptcha/api/siteverify',
			array(
				'timeout' => 10,
				'body'    => array(
					'secret'   => $recaptcha_secret,
					'response' => $recaptcha,
					'remoteip' => isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '',
				),
			)
		);

		if ( is_wp_error( $verify_response ) ) {
			wp_send_json_error( array( 'message' => 'Unable to verify reCAPTCHA. Please try again later.' ) );
		}

		$verify_body = json_decode( wp_remote_retrieve_body( $verify_response ), true );

		// Require success and a reasonable score when using reCAPTCHA v3.
		if ( empty( $verify_body['success'] ) || ( isset( $verify_body['score'] ) && (float) $verify_body['score'] < 0.5 ) ) {
			wp_send_json_error( array( 'message' => 'reCAPTCHA verification failed. Please try again.' ) );
		}
	}

	// Very simple rate limiting: block excessive submits from same IP.
	$ip_address = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	if ( ! empty( $ip_address ) ) {
		$rate_key = 'anisur_contact_rate_' . md5( $ip_address );
		$count    = (int) get_transient( $rate_key );

		if ( $count >= 5 ) {
			wp_send_json_error( array( 'message' => 'Too many submissions detected from your IP. Please try again later.' ) );
		}

		set_transient( $rate_key, $count + 1, 10 * MINUTE_IN_SECONDS );
	}

	$to      = get_option( 'admin_email' );
	$subject = 'New Portfolio Inquiry from ' . $name;

	if ( ! empty( $user_subject ) ) {
		$subject .= ' – ' . $user_subject;
	}

	$body_lines   = array();
	$body_lines[] = 'You have received a new message from your portfolio contact form.';
	$body_lines[] = '';
	$body_lines[] = 'Name: ' . $name;
	$body_lines[] = 'Email: ' . $email;

	if ( ! empty( $phone ) ) {
		$body_lines[] = 'Phone: ' . $phone;
	}

	if ( ! empty( $user_subject ) ) {
		$body_lines[] = 'Subject: ' . $user_subject;
	}

	$body_lines[] = '';
	$body_lines[] = 'Message:';
	$body_lines[] = $message;
	$body_lines[] = '';
	$body_lines[] = '---';
	$body_lines[] = 'Submitted from: ' . home_url();
	$body_lines[] = 'Date: ' . current_time( 'F j, Y g:i a' );

	$body = implode( "\n", $body_lines );

	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		'From: ' . $name . ' <' . $email . '>',
		'Reply-To: ' . $name . ' <' . $email . '>',
	);

	if ( wp_mail( $to, $subject, $body, $headers ) ) {
		wp_send_json_success();
	} else {
		// Fallback for local environments or if wp_mail fails (mock success for now if needed, but let's try real first)
		// For portfolio demo, sometimes wp_mail isn't configured. I'll assume it works or log it.
		error_log( "Contact Form Email Failed: $to, $subject" );
		wp_send_json_error( array( 'message' => 'Failed to send email. Please try again later.' ) );
	}
}
add_action( 'wp_ajax_send_contact_email', 'anisur_send_contact_email' );
add_action( 'wp_ajax_nopriv_send_contact_email', 'anisur_send_contact_email' );

