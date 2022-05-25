<?php

    // Add a new child entry to the 'jabc_children' table

    // Required by wordpress functions
    require_once "../../../../wp-load.php";

    // Pull variables from the POST data submitted via the form
    $forename = $_POST['forename'];
    $surname = $_POST['surname'];
    $dob = $_POST['dob'];

    // Access the wordpress database
    global $wpdb;

    // find the current user's parent_id
    $parent_id = $wpdb->get_results(
        $wpdb->prepare("SELECT parent_id
                    FROM jabc_parents
                    WHERE parent_wp_user_fk=%d",
                    get_current_user_id()), ARRAY_N
    );

    // Create a new entry in the 'jabc_children' table
    // Define table to create new entry in
    $table = "jabc_children";

    // Define data to be input into new entry
    $data = array(
        'child_forename' => $forename,
        'child_surname'  => $surname,
        'child_dob'      => $dob,
        'parent_id_fk'   => $parent_id[0][0],
        'vegetarian'     => $_POST['vegetarian']
    );

    // Insert the new child entry into the jabc_children table
    $wpdb->insert($table, $data);

    // redirect the user to their parent profile page
    $redirect_to = 'my-page/';
    wp_safe_redirect($redirect_to);
