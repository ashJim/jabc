<?php

    // handles user registration, following the submission of an 'add user' form

    // required for wordpress functions
    require_once "../../../../wp-load.php";

    // access the POST variable from the form for 'email'
    $email = $_POST['email'];

    // check if user's email is already registered and cancel registration if so
    $exists = email_exists($email);
    if($exists){
        // redirect user to the home page without creating user
        wp_safe_redirect(get_site_url());
    } else {
        // create user
        require '../../../../wp-admin/admin-post.php';
        require_once "../../../../wp-load.php";

        // Access user object
        $user = get_user_by('email', $email);
    
        // Add email verification code to user meta
        add_user_meta($user_id = $user->ID, $meta_key = 'email_verified', $meta_value = $email . rand());

        // Email the user a verification link
        $user_email = $user->user_email;
        $verify = get_user_meta($user_id, 'email_verified', true);
        $site_url = get_site_url();
    
        // The email contents
        $to = $user_email;
        $subject = 'Verify your email address';
        $body = '
            <h1>Hi!</h1>
            <p>You recently registered for an account with Breakfast Club.</p>
            <p>Please click the verification link below in order to verify your account with us 
            and continue with enrolment:</p>
            <h3><a href="' . $site_url . '/wp-content/plugins/jabc_breakfast_club/jabc_includes/jabc_verify_email.php?verify=' . $verify . '&user=' . $user_id . '">VERIFY EMAIL ADDRESS</a></h3>
            <p>Kind regards</p>
            <p>The Breakfast Club Team</p>
            ';
        $headers = array(
            'Content-Type: text/html; charset=UTF-8'
        );
        
        // Send the email
        wp_mail($to, $subject, $body, $headers);

        // Redirect to 'verify email' page on the front end
        wp_safe_redirect('verify-email/');
        }
        
        