<?php

    // Add a new parent entry to the 'jabc_parents' table

    // Required by wordpress functions
    require_once "../../../../wp-load.php";

    // Pull variables from the POST data submitted via the form
    $title = $_POST['title'];
    $forename = $_POST['forename'];
    $surname = $_POST['surname'];
    $tel = $_POST['tel'];

    // Access the wordpress database
    global $wpdb;

    // Create a new entry in the 'jabc_parents' table
    // Define table to create new entry in
    $table = "jabc_parents";

    // Define data to be input into new entry
    $data = array(
        'parent_wp_user_fk' => get_current_user_id(),
        'parent_title'      => $title,
        'parent_forename'   => $forename,
        'parent_surname'    => $surname,
        'parent_tel'        => $tel
    );

    // Insert the new parent entry into the 'jabc_parents' table
    $wpdb->insert($table, $data);

    // Add the 'jabc_parent' role to the current user's roles list
    $user = wp_get_current_user();
    $user->add_role('jabc_parent');

    // Redirect the user to their parent profile page
    $redirect_to = 'my-page/';
    wp_safe_redirect($redirect_to);
    