<?php

    // removes the parent entry associated with the current user from the jabc_parents table

    // required by wordpress functions
    require_once "../../../../wp-load.php";
    
    // access the wordpress database
    global $wpdb;

    // delete all children from the jabc_child table who are associated with the current user
    $wpdb->delete('jabc_children', array('parent_id_fk' => $_GET['id']));

    // delete from the jabc_parents table parent entry associated with current user
    $wpdb->delete('jabc_parents', array('parent_id' => $_GET['id']));

    // remove the jabc_parent role from the current user
    // access the current user object and assign to $user variable
    $user = wp_get_current_user();
    // remove the jabc_parent role from selected user object
    $user->remove_role('jabc_parent');

    // redirect user to the home page
    $redirect_to = get_site_url();
    wp_safe_redirect($redirect_to);