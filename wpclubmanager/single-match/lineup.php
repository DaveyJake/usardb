<?php
/**
 * Single Match - Lineup
 *
 * @author  ClubPress
 * @package WPClubManager/Templates
 * @version 2.5.0
 */

defined( 'ABSPATH' ) || exit;

global $post;

$played                   = get_post_meta( $post->ID, 'wpcm_played', true );
$players                  = unserialize( get_post_meta( $post->ID, 'wpcm_players', true ) );
$wpcm_player_stats_labels = wpcm_get_preset_labels();
$subs_not_used            = get_post_meta( $post->ID, '_wpcm_match_subs_not_used', true );
$wr_id                    = get_post_meta( $post->ID, 'wr_id', true );

unset( $wpcm_player_stats_labels['rating'] );

if ( $played && $players )
{
    if ( ! empty( rdb_match_timeline( $wr_id ) ) ) {
        echo '<div class="wpcm-column wpcm-match-lineup">';
    }

    if ( array_key_exists( 'lineup', $players ) && is_array( $players['lineup'] ) )
    {
        echo '<div class="wpcm-match-stats-start">';
            echo '<table class="wpcm-lineup-table display responsive nowrap" data-page-length="15" width="100%">';
                echo '<thead>';
                    echo '<tr>';

                    if ( 'yes' === get_option( 'wpcm_lineup_show_shirt_numbers' ) )
                    {
                        echo '<th class="shirt-number">First XV</th>';
                    }

                    echo '<th class="name">';
                        esc_html_e( 'Name', 'wp-club-manager' );
                    echo '</th>';

                    foreach ( $wpcm_player_stats_labels as $key => $val )
                    {
                        if ( ! in_array( $key, wpcm_exclude_keys() ) && get_option( "wpcm_show_stats_{$key}" ) && get_option( "wpcm_match_show_stats_{$key}" ) )
                        {
                            echo '<th class="' . $key . '">' . $val . '</th>';
                        }
                    }

                    if ( 'yes' === get_option( 'wpcm_show_stats_yellowcards' ) &&
                         'yes' === get_option( 'wpcm_match_show_stats_yellowcards' ) ||
                         'yes' === get_option( 'wpcm_show_stats_redcards' ) &&
                         'yes' === get_option( 'wpcm_match_show_stats_redcards' ) ) {

                        echo '<th class="notes">';
                            esc_html_e( 'Cards', 'wp-club-manager' );
                        echo '</th>';
                    }

                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                    $sorted_xv = array();
                    $starters  = 0;

                    foreach ( $players['lineup'] as $key => $value ) {
                        $sorted_xv[ $value['shirtnumber'] ] = array(
                            'key' => $key,
                            $key  => $value
                        );
                    }

                    ksort( $sorted_xv );

                    foreach ( $sorted_xv as $shirtnumber => $row ) {
                        $starters ++;

                        $key   = $row['key'];
                        $value = $row[ $key ];

                        wpclubmanager_get_template( 'single-match/lineup-row.php', array(
                            'key'   => $key,
                            'value' => $value,
                            'count' => $starters
                        ) );
                    }

                echo '</tbody>';
            echo '</table>';
        echo '</div>';
    }

    if ( array_key_exists( 'subs', $players ) && is_array( $players['subs'] ) || is_array( $subs_not_used ) )
    {
        echo '<div class="wpcm-match-stats-subs">';
            echo '<table class="wpcm-subs-table display responsive nowrap" data-page-length="8" width="100%">';
                echo '<thead>';
                    echo '<tr>';

                        if ( 'yes' === get_option( 'wpcm_lineup_show_shirt_numbers' ) ) {
                            echo '<th class="shirt-number">Reserves</th>';
                        }

                        echo '<th class="name">';
                            esc_html_e( 'Name', 'wp-club-manager' );
                        echo '</th>';

                        foreach ( $wpcm_player_stats_labels as $key => $val ) {
                            if ( ! in_array( $key, wpcm_exclude_keys() ) &&
                                 'yes' === get_option( "wpcm_show_stats_{$key}" ) &&
                                 'yes' === get_option( "wpcm_match_show_stats_{$key}" ) ) {

                                echo '<th class="' . $key . '">' . $val . '</th>';
                            }
                        }

                        if ( 'yes' === get_option( 'wpcm_show_stats_yellowcards' ) && get_option( 'wpcm_match_show_stats_yellowcards' ) ||
                             'yes' === get_option( 'wpcm_show_stats_redcards' ) && get_option( 'wpcm_match_show_stats_redcards' ) ) {

                            echo '<th class="notes">';
                                esc_html_e( 'Cards', 'wp-club-manager' );
                            echo '</th>';
                        }

                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                $sorted_subs = array();
                $count       = 0;

                foreach ( $players['subs'] as $key => $value ) {
                    $sorted_subs[ $value['shirtnumber'] ] = array(
                        'key' => $key,
                        $key  => $value,
                    );
                }

                if ( ! empty( $subs_not_used ) && is_array( $subs_not_used ) ) {
                    foreach( $subs_not_used as $key => $value ) {
                        $sorted_subs[ $value['shirtnumber'] ] = array(
                            'key' => $key,
                            $key  => $value,
                            'dnp' => true,
                        );
                    }
                }

                ksort( $sorted_subs );

                foreach ( $sorted_subs as $shirtnumber => $row ) {
                    $count ++;

                    $key   = $row['key'];
                    $value = $row[ $key ];

                    $template_args = array(
                        'key'   => $key,
                        'value' => $value,
                        'count' => $count,
                    );

                    if ( isset( $row['dnp'] ) ) {
                        $dnp = $row['dnp'];

                        $template_args['dnp'] = $dnp;
                    }

                    wpclubmanager_get_template( 'single-match/lineup-row.php', $template_args );
                }

                echo '</tbody>';
            echo '</table>';
        echo '</div>';
    }

    if ( ! empty( rdb_match_timeline( $wr_id ) ) ) {
        echo '</div>';
    }
}
