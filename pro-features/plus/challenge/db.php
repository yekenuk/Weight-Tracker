<?php

defined('ABSPATH') or die("Jog on!");

/**
 * Insert a new challenge
 * @param $challenge_id
 * @param null $start_date
 * @param null $end_date
 * @return bool
 */
function ws_ls_challenges_add( $challenge_id, $start_date = NULL, $end_date = NULL ) {

    if ( true === empty( $challenge_id ) ) {
        return false;
    }

    $data       = [ 'id' => (int) $challenge_id ];
    $formats    = [ '%d' ];

    if ( false === empty( $start_date ) && false === empty( $end_date ) ) {
        $data[ 'start_date' ]   = $start_date;
        $formats[]              = '%s';
        $data[ 'end_date' ]     = $end_date;
        $formats[]              = '%s';
    }

    global $wpdb;

    $result = $wpdb->insert( $wpdb->prefix . WE_LS_MYSQL_CHALLENGES, $data, $formats );

    return ! empty( $result );
}

/**
 * Update enabled flag for a challenge
 * @param $challenge_id
 * @param bool $enabled
 * @return bool
 */
function ws_ls_challenges_enabled( $challenge_id, $enabled = true ) {

    if ( true === empty( $challenge_id ) ) {
        return false;
    }

    global $wpdb;

    $result = $wpdb->update( $wpdb->prefix . WE_LS_MYSQL_CHALLENGES,
        [ 'enabled' => ( true === $enabled ) ? 1 : 0 ],
        [ 'id' => $challenge_id ],
        [ '%d' ],
        [ '%d' ]
    );

    ws_ls_delete_cache( 'challenge-' . (int) $challenge_id );

    return ! empty( $result );
}

/**
 * Fetch a challenge
 * @param $challenge_id
 * @return bool
 */
function ws_ls_challenges_get( $challenge_id ) {

    if ( true === empty( $challenge_id ) ) {
        return false;
    }

    if ( $cache = ws_ls_get_cache( 'challenge-' . (int) $challenge_id ) ) {
        echo 'cache';
        return $cache;
    }

    global $wpdb;

    $sql = $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . WE_LS_MYSQL_CHALLENGES . ' WHERE id = %d', $challenge_id );

    $result = $wpdb->get_row( $sql, ARRAY_A );

    $result = ( false === empty( $result ) ) ? $result : false;

    ws_ls_set_cache( 'challenge-' . (int) $challenge_id, $result );

    return $result;
}

/**
 * Look at the weight entry table and and look for any users that have at least one weight entry for the given time period.
 *
 * @param $challenge_id
 * @param null $start_date
 * @param null $end_date
 * @return int|false - number of entries inserted or false for none.
 */
function ws_ls_challenges_identify_entries( $challenge_id, $start_date = NULL, $end_date = NULL ) {

    if ( true === empty( $challenge_id ) ) {
        return false;
    }

    global $wpdb;

    $sql = $wpdb->prepare( 'INSERT IGNORE INTO ' . $wpdb->prefix . WE_LS_MYSQL_CHALLENGES_DATA . ' ( user_id, challenge_id ) 
                            SELECT Distinct weight_user_id AS user_id, %d AS challenge_id FROM ' . $wpdb->prefix . WE_LS_TABLENAME, $challenge_id );

    // Do we have a start and end date?
    if ( false === empty( $start_date ) && false === empty( $end_date ) ) {
        $sql .= $wpdb->prepare( ' WHERE weight_date >= %s and weight_date <= %s', $start_date, $end_date );
    }

    return $wpdb->query( $sql );
}