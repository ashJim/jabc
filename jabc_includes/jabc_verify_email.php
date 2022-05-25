<?php

    // verifies a user's email address so they can log in to the website

    // required for wordpress functions
    require_once "../../../../wp-load.php";

    // access the query string variables for verification code and user id, 
    // assign them to the variables $verify and $user_id
    $verify = $_GET['verify'];
    $user_id = $_GET['user'];

    // access the current user's 'email_verified' verification code in their metadata
    $auth = get_user_meta($user_id, 'email_verified', true);

    // check if the verification code from the query string matches the verification code 
    // in the user's metadata
    if($verify == $auth){
        // if the two codes are the same, delete the user's metadata for 'email_verified'
        delete_user_meta($user_id, 'email_verified');
        echo 'email address verified! <a href="../../../../wp-login.php">Click here to login</a>';
    } else {
        // if the 2 codes do not match, display an error
        echo 'there was an error';
    }