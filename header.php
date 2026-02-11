<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
	<div class="container header-container">
		<div class="site-branding">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo">
				Anisur<span class="accent-text"> Rahman.</span>
			</a>
		</div>

		<nav id="site-navigation" class="main-navigation">
			<ul class="nav-list">
				<li><a href="#hero">HOME</a></li>
				<li><a href="#projects">WORK</a></li>
				<li><a href="#expertise">EXPERTISE</a></li>
				<li><a href="#history">HISTORY</a></li>
				<li><a href="#blog">INSIGHTS</a></li>
			</ul>
		</nav>

		<div class="header-cta">
			<a href="https://calendly.com/anisur2805/30min" target="_blank" class="btn btn-primary btn-sm">BOOK A CALL</a>
		</div>
	</div>
</header>
