<?php

    // Sends users to the appropriate part of the site, depending on their user roles

    // Required by wordpress functions
    require_once "../../../../wp-load.php";

    // Access the wordpress database
    global $wpdb;

    // Returns an array of the current user's roles
    function get_current_user_roles() {
        $user = wp_get_current_user();
        $roles = $user->roles;
        return $roles;
    }
    // Stores the current user's roles in a '$user_roles' variable
    $user_roles = get_current_user_roles();

    if (in_array('administrator', $user_roles)) {
        // Sends administrators to the admin area of the site
        wp_safe_redirect(get_site_url() . '/wp-admin');
    } else {
        // Sends non-admin users to the site's homepage
        wp_safe_redirect(get_site_url());
    }
    