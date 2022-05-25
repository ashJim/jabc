<?php

    // Removes a child entry from the 'jabc_children' table, as sent to the page via GET in query string

    // Required by wordpress functions
    require_once "../../../../wp-load.php";
    
    // Access the wordpress database
    global $wpdb;

    // Delete entry in 'jabc_children' table where 'child_id' matches 'id' value in query string
    $wpdb->delete('jabc_children', array('child_id' => $_GET['id']));

    // Redirect the user back to their parent profile page
    $redirect_to = 'my-page/';
    wp_safe_redirect($redirect_to);