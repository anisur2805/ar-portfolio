<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Enqueue scripts and styles.
 */
function anisur_portfolio_scripts() {
	// Main Stylesheet
	wp_enqueue_style( 'anisur-main-style', get_template_directory_uri() . '/assets/css/main.css', array(), '1.0.0' );

	// Google Fonts: Outfit (Headings), Inter (Body), JetBrains Mono (Code)
	wp_enqueue_style( 'anisur-google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=JetBrains+Mono:wght@400;500&family=Outfit:wght@400;500;700;800&display=swap', array(), null );

	// Main Custom Script
	wp_enqueue_script( 'anisur-main-script', get_template_directory_uri() . '/assets/js/main.js', array(), '1.0.0', true );
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
}
add_action( 'customize_register', 'anisur_portfolio_customize_register' );
