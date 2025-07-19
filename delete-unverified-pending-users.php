<?php
/*
Plugin Name: Delete Unverified Users (Manual)
Description: This WordPress MU-plugin helps delete a very high number of pending users that have not verified their emails. If you have thousands of unverified users—mostly spammers—one click will handle it.
Author: Matthew John Alex
Version: 1.0
*/

if ( ! defined( 'ABSPATH' ) ) exit;

// Add submenu under Users
add_action( 'admin_menu', function() {
    add_users_page(
        'Delete Unverified Users',
        'Delete Unverified Users',
        'manage_options',
        'delete-unverified-users',
        'duu_render_admin_page'
    );
});

// Render the admin page
function duu_render_admin_page() {
    if ( isset( $_POST['duu_run_deletion'] ) && check_admin_referer( 'duu_delete_users_action' ) ) {
        $deleted = duu_delete_unverified_users();
        echo '<div class="updated"><p>Deleted users: ' . ( $deleted ? implode( ', ', $deleted ) : 'None' ) . '</p></div>';
    }
    ?>
    <div class="wrap">
        <h1>Delete Unverified Users</h1>
        <p>This will delete users who:</p>
        <ul>
            <li>Have <code>user_status = 0</code> (not verified)</li>
            <li>Registered more than 24 hours ago</li>
            <li>Have the role <code>subscriber</code></li>
        </ul>
        <form method="post">
            <?php wp_nonce_field( 'duu_delete_users_action' ); ?>
            <input type="submit" class="button button-primary" name="duu_run_deletion" value="Delete Unverified Users">
        </form>
    </div>
    <?php
}

// Delete users
function duu_delete_unverified_users() {
    global $wpdb;

    $cutoff = strtotime( '-24 hours' );

    $users = $wpdb->get_results( $wpdb->prepare(
        "SELECT ID FROM {$wpdb->users} 
         WHERE user_status = 0 
         AND user_registered <= %s",
         date( 'Y-m-d H:i:s', $cutoff )
    ) );

    $deleted = [];

    foreach ( $users as $user ) {
        $user_id = $user->ID;
        $roles = get_userdata( $user_id )->roles;

        if ( in_array( 'subscriber', $roles ) ) {
            wp_delete_user( $user_id );
            $deleted[] = $user_id;
        }
    }

    if ( ! empty( $deleted ) ) {
        $log_file = wp_upload_dir()['basedir'] . '/deleted-unverified-users.log';
        $timestamp = current_time( 'mysql' );
        file_put_contents(
            $log_file,
            "[$timestamp] Deleted user IDs: " . implode( ', ', $deleted ) . PHP_EOL,
            FILE_APPEND
        );
    }

    return $deleted;
}
