<?php
/**
 * USA Rugby Database API: WP Club Manager Admin Assets
 *
 * @author Davey Jacobson <djacobson@usa.rugby>
 * @package Rugby_Database
 * @subpackage WPCM_Admin_Assets
 * @since RDB 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class RDB_WPCM_Admin_Assets extends WPCM_Admin_Assets {

    /**
     * Script dependencies.
     *
     * @since 1.0.0
     *
     * @var array
     */
    private $deps;

    /**
    * Primary constructor.
    *
    * @return RDB_WPCM_Admin_Assets
    */
    public function __construct() {
        $this->deps[] = 'jquery';
        $this->deps[] = 'jquery-tiptip';

        add_action( 'before_wpcm_init', array( $this, 'unset_reset_wpcm_admin_scripts' ) );
    }

    /**
     * Unset and reset WPCM admin scripts.
     */
    public function unset_reset_wpcm_admin_scripts() {
        rdb_remove_class_method( 'admin_enqueue_scripts', 'WPCM_Admin_Assets', 'admin_scripts', 10 );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
    }

    /**
    * Loads the scripts for the backend.
    *
    * @since WPCM 1.1
    *
    * @return void
    */
    public function admin_scripts( $hook ) {

        global $wp_query, $post;

        $screen		    = get_current_screen();
        $screen_id	    = $screen ? $screen->id : '';
        $wpcm_screen_id = strtolower( __( 'WPClubManager', 'wp-club-manager' ) );
        $suffix		    = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
        $api_key		= get_option( 'wpcm_google_map_api', GOOGLE_MAPS );

        // Register custom style.
        wp_register_style( 'rdb-wpcm-admin', get_template_directory_uri() . '/wpclubmanager/custom/admin/assets/css/rdb-wpcm-admin.css', false, WPCM_VERSION );

        // Register scripts
        wp_register_script( 'wpclubmanager_admin', WPCM()->plugin_url() . '/assets/js/admin/wpclubmanager_admin' . $suffix . '.js', array( 'jquery', 'jquery-ui-widget', 'jquery-ui-core', 'jquery-ui-sortable' ), WPCM_VERSION );

        wp_register_script( 'ajax-chosen', WPCM()->plugin_url() . '/assets/js/jquery-chosen/ajax-chosen.jquery' . $suffix . '.js', array( 'jquery', 'chosen' ), WPCM_VERSION );

        wp_register_script( 'order-chosen', WPCM()->plugin_url() . '/assets/js/jquery-chosen/chosen.order.jquery.min.js', array( 'jquery' ), '1.2.1' );

        wp_register_script( 'chosen', WPCM()->plugin_url() . '/assets/js/jquery-chosen/chosen.jquery' . $suffix . '.js', array( 'jquery' ), '1.8.2' );

        wp_register_script( 'wpcm-tax-order', WPCM()->plugin_url() . '/assets/js/admin/wpclubmanager_tax_order' . $suffix . '.js', array( 'jquery-ui-core', 'jquery-ui-sortable' ), WPCM_VERSION );

        wp_register_script( 'google-maps', '//maps.googleapis.com/maps/api/js?key=' . $api_key . '&libraries=places' );

        wp_register_script( 'jquery-locationpicker', get_template_directory_uri() . "/wpclubmanager/custom/admin/assets/js/locationpicker.jquery{$suffix}.js", array( 'jquery', 'google-maps' ), '0.1.16', true );

        wp_register_script( 'wpclubmanager-admin-locationpicker', get_template_directory_uri() . '/wpclubmanager/custom/admin/assets/js/locationpicker.js', array( 'jquery', 'google-maps', 'jquery-locationpicker' ), WPCM_VERSION, true );

        if ( SCRIPT_DEBUG ) {
            wp_register_script( 'jquery-timepicker', get_template_directory_uri() . '/wpclubmanager/custom/admin/assets/js/jquery.timepicker.js', array( 'jquery' ), WPCM_VERSION, true );
        } else {
            wp_register_script( 'jquery-timepicker', WPCM()->plugin_url() . '/assets/js/jquery.timepicker' . $suffix . '.js', array( 'jquery' ), WPCM_VERSION, true );
        }

        wp_register_script( 'jquery-tiptip', get_template_directory_uri() . '/wpclubmanager/custom/admin/assets/js/jquery.tipTip' . $suffix . '.js', array( 'jquery' ), WPCM_VERSION, true );

        wp_register_script( 'wpclubmanager-admin-combify', WPCM()->plugin_url() . '/assets/js/admin/combify' . $suffix . '.js', array( 'jquery' ), WPCM_VERSION, true );

        wp_register_script( 'wpclubmanager_admin_meta_boxes', WPCM()->plugin_url() . '/assets/js/admin/meta-boxes' . $suffix . '.js', array( 'jquery', 'chosen', 'order-chosen', 'iris', 'jquery-timepicker', 'wpcm-tax-order', 'jquery-ui-datepicker', 'wpclubmanager-admin-combify' ), WPCM_VERSION );

        if ( in_array( $screen_id, array( 'edit-wpcm_match', 'edit-wpcm_player', 'edit-wpcm_staff' ), true ) ) {
            $this->deps[] = 'wpclubmanager_quick-edit';
            wp_register_script( 'wpclubmanager_quick-edit', WPCM()->plugin_url() . '/assets/js/admin/quick-edit.js', array( 'jquery', 'wpclubmanager_admin' ), WPCM_VERSION );
            wp_enqueue_script( 'wpclubmanager_quick-edit' );
        }

        if ( in_array( $screen_id, array( 'edit-wpcm_team', 'edit-wpcm_season', 'edit-wpcm_position', 'edit-wpcm_job', 'edit-wpcm_comp' ), true ) ) {
            wp_enqueue_script( 'jquery-ui-core' );
            wp_enqueue_script( 'jquery-ui-sortable' );
            wp_enqueue_script( 'wpcm-tax-order' );
            wp_localize_script( 'wpcm-tax-order', 'localized_data', array(
                'ajax_url'	    => esc_url( admin_url( 'admin-ajax.php' ) ),
                'preloader_url' => esc_url( admin_url( 'images/wpspin_light.gif' ) ),
            ) );
        }

        // Edit venue pages
        if ( in_array( $screen_id, array( 'edit-wpcm_venue' ), true ) ) {
            $this->deps[] = 'wpclubmanager-admin-locationpicker';

            wp_enqueue_script( 'google-maps' );
            wp_enqueue_script( 'jquery-locationpicker' );
            wp_enqueue_script( 'wpclubmanager-admin-locationpicker' );
        }

        // WPlubManager admin pages
        if ( in_array( $screen_id, wpcm_get_screen_ids() ) ) {
            $this->deps[] = 'jquery-tiptip';

            wp_enqueue_style( 'rdb-wpcm-admin' );
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'ajax-chosen' );
            wp_enqueue_script( 'order-chosen' );
            wp_enqueue_script( 'chosen' );
            wp_enqueue_script( 'jquery-timepicker' );
            wp_enqueue_script( 'jquery-tiptip' );
            wp_enqueue_script( 'wpclubmanager_admin' );
        }

        if ( in_array( $screen_id, array( 'wpcm_player', 'wpcm_club', 'wpcm_staff', 'wpcm_sponsor', 'wpcm_table', 'wpcm_roster', 'wpcm_match' ), true ) ) {
            $this->deps[] = 'jquery-tiptip';

            wp_enqueue_style( 'rdb-wpcm-admin' );
            wp_enqueue_script( 'ajax-chosen' );
            wp_enqueue_script( 'order-chosen' );
            wp_enqueue_script( 'chosen' );
            wp_enqueue_script( 'iris' );
            wp_enqueue_script( 'jquery-timepicker' );
            wp_enqueue_script( 'jquery-tiptip' );
            wp_enqueue_script( 'wpclubmanager-admin-combify' );
            wp_enqueue_script( 'wpclubmanager_admin_meta_boxes' );
        }

        // Tooltip for non-test matches.
        if ( in_array( $screen_id, array( 'edit-wpcm_match', 'edit-wpcm_player' ), true ) ) {
            $this->deps[] = 'jquery-ui-tooltip';

            wp_enqueue_script( 'jquery-ui-tooltip' );
        }

        // System status
        if ( 'club-manager_page_wpcm-status' === $screen_id ) {
            wp_enqueue_script( 'zeroclipboard', WPCM()->plugin_url() . '/assets/js/zeroclipboard/jquery.zeroclipboard' . $suffix . '.js', array( 'jquery' ), WPCM_VERSION );
        }

        if ( in_array( $screen_id, array( 'toplevel_page_wpcm-dashboard' ) ) ) {
            wp_enqueue_script( 'wpclubmanager_dashboard_js', WPCM()->plugin_url() . '/assets/js/admin/wpcm-dashboard.js', array( 'jquery' ), WPCM_VERSION );
        }

        // Bulk edit.
        // if ( in_array( $screen_id, array( 'wpcm_match' ) ) ) {
        //     wp_enqueue_script( 'handle_name', get_template_directory_uri() . '/wpclubmanager/custom/admin/assets/rdb-wpcm-admin-bulk-edit.js', array( 'jquery' ), WPCM_VERSION, true );
        // }

        // Custom admin script.
        wp_enqueue_script( 'rdb-wpcm-admin', get_template_directory_uri() . '/wpclubmanager/custom/admin/assets/js/rdb-wpcm-admin.js', $this->deps, WPCM_VERSION );

    }

}

return new RDB_WPCM_Admin_Assets();
