<?php
/**
 * The main template file for displaying venue information.
 *
 * @author Davey Jacobson <daveyjake21@gmail.com>
 *
 * @package Rugby_Database
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

// phpcs:disable

get_header();

    /**
     * Hook: `wpclubmanager_before_main_content`
     *
     * @hooked wpclubmanager_output_content_wrapper - 10 (outputs opening divs for the content)
     * @hooked wpclubmanager_breadcrumb - 20
     */
    do_action( 'wpclubmanager_before_main_content' );

    if ( taxonomy_exists( 'wpcm_venue' ) ) :

        get_template_part( 'wpclubmanager/content', 'single-venue' );

    endif;

    /**
     * Hook: `wpclubmanager_after_main_content`
     *
     * @hooked wpclubmanager_output_content_wrapper_end - 10 (outputs closing divs for the content)
     */
    do_action( 'wpclubmanager_after_main_content' );

    /**
     * Hook: `wpclubmanager_sidebar`
     *
     * @hooked wpclubmanager_get_sidebar - 10
     */
    do_action( 'wpclubmanager_sidebar' );

get_footer();
