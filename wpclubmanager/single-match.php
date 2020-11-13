<?php
/**
 * The Template for displaying all single matches.
 *
 * Override this template by copying it to yourtheme/wpclubmanager/single-match.php
 *
 * @author  ClubPress
 * @package WPClubManager/Templates
 * @version 1.5.0
 */

defined( 'ABSPATH' ) || exit;

get_header();

    /**
     * wpclubmanager_before_main_content hook
     *
     * @hooked wpclubmanager_output_content_wrapper - 10 (outputs opening divs for the content)
     * @hooked wpclubmanager_breadcrumb - 20
     */
    do_action( 'wpclubmanager_before_main_content' );

        while ( have_posts() ) : the_post();

            wpclubmanager_get_template_part( 'content', 'single-match' );

        endwhile; // end of the loop.

    /**
     * wpclubmanager_after_main_content hook
     *
     * @hooked wpclubmanager_output_content_wrapper_end - 10 (outputs closing divs for the content)
     */
    do_action( 'wpclubmanager_after_main_content' );

    /**
     * wpclubmanager_sidebar hook
     *
     * @hooked wpclubmanager_get_sidebar - 10
     */
    do_action( 'wpclubmanager_sidebar' );

get_footer();
