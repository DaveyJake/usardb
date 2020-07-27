<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package USARDB
 */

if ( ! defined( 'ABSPATH' ) ) exit; // phpcs:ignore
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<?php
	do_action( 'usardb_head_open' );
	echo '<meta charset="' . esc_attr( get_bloginfo( 'charset' ) ) . '">';
	echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
	echo '<link rel="profile" href="https://gmpg.org/xfn/11">';
	wp_head();
	do_action( 'usardb_head_close' );
?>
</head>
<body <?php body_class(); ?>>
<?php // phpcs:disable Generic.WhiteSpace.ScopeIndent
	usardb_body_open();

	echo '<div id="page" class="site">';

		echo '<a class="skip-link screen-reader-text" href="#primary">' . esc_html__( 'Skip to content', 'usardb' ) . '</a>';

		echo '<header id="masthead" class="site-header">';

			echo '<div class="site-branding">';
				the_custom_logo();

				$usardb_bloginfo = get_bloginfo( 'name', 'display' );
				if ( is_front_page() && is_home() ) :
					echo '<h1 class="site-title"><a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . esc_html( $usardb_bloginfo ) . '</a></h1>';
				else :
					echo '<p class="site-title"><a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . esc_html( $usardb_bloginfo ) . '</a></p>';
				endif;

				$usardb_description = get_bloginfo( 'description', 'display' );
				if ( $usardb_description || is_customize_preview() ) :
					echo '<p class="site-description">' . esc_html( $usardb_description ) . '</p>';
				endif;
			echo '</div><!-- .site-branding -->';

			echo '<nav id="site-navigation" class="main-navigation">';
				echo '<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">' . esc_html__( 'Primary Menu', 'usardb' ) . '</button>';
				wp_nav_menu(
					array(
						'theme_location' => 'main-menu',
						'menu_id'        => 'primary-menu',
					)
				);
			echo '</nav><!-- #site-navigation -->';

		echo '</header><!-- #masthead -->';
