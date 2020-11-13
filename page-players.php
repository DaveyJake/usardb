<?php
/**
 * The template for displaying all players.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Rugby_Database
 */

defined( 'ABSPATH' ) || exit;

get_header();

echo '<main id="primary" class="site-main">';
the_content();
echo '</main><!-- #main -->';

wp_nonce_field( 'get_players', 'nonce' );
get_sidebar();
get_footer();
