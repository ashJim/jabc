<?php
/**
 * Plugin Name: Jim Ashford's Breakfast Club
 * Description: Manage a school breakfast club direct from your website.
 */

// The templates used to generate forms and pages
 require 'jabc_templates/jabc_form_add_user.php';
 require 'jabc_templates/jabc_form_add_parent.php';
 require 'jabc_templates/jabc_form_add_child.php';
 require 'jabc_templates/jabc_parent.php';
 require 'jabc_templates/jabc_manager.php';
 require 'jabc_templates/jabc_display_prefix.php';


// Function to add user to database after form data has been submitted
function jabc_add_user() {
     $jabc_password = $_POST['password'];
     $jabc_email = $_POST['email'];

     wp_create_user($jabc_email, $jabc_password, $jabc_email);
}
// Hooks the 'jabc_add_user' function onto the 'admin_post' 
// page required by 'jabc-form-processing'
add_action('admin_post_nopriv_custom_action_hook', 'jabc_add_user');
add_action('admin_post_custom_action_hook', 'jabc_add_user');


//  Hides the admin bar from the top of the screen
function jabc_hide_admin_bar(){
    show_admin_bar(false);
}
add_action('init', 'jabc_hide_admin_bar');

// Defines and adds the required front-end pages
function jabc_setup_pages() {
    $profile = array(
        'post_title'        => 'Parent Page',
        'post_type'         => 'page',
        'post_name'         => 'my-page',
        'post_content'      => '[jabc_parent]',
        'post_status'       => 'publish',
        'comment_status'    => 'closed',
        'ping_status'       => 'closed',
        'post_author'       => 1,
        'menu_order'        => 0
    );
        
    $add_user = array(
        'post_title'        => 'Add User',
        'post_type'         => 'page',
        'post_name'         => 'add-user',
        'post_content'      => '[jabc_form_add_user]',
        'post_status'       => 'publish',
        'comment_status'    => 'closed',
        'ping_status'       => 'closed',
        'post_author'       => 1,
        'menu_order'        => 1
    );

    $add_parent = array(
        'post_title'        => 'Add Parent',
        'post_type'         => 'page',
        'post_name'         => 'add-parent',
        'post_content'      => '[jabc_form_add_parent]',
        'post_status'       => 'publish',
        'comment_status'    => 'closed',
        'ping_status'       => 'closed',
        'post_author'       => 1,
        'menu_order'        => 2
    );
        
    $add_child = array(
        'post_title'        => 'Add Child',
        'post_type'         => 'page',
        'post_name'         => 'add-child',
        'post_content'      => '[jabc_form_add_child]',
        'post_status'       => 'publish',
        'comment_status'    => 'closed',
        'ping_status'       => 'closed',
        'post_author'       => 1,
        'menu_order'        => 3
    );

    $verify_email = array(
        'post_title'        => 'Verify Your Email Address',
        'post_type'         => 'page',
        'post_name'         => 'verify-email',
        'post_content'      => '
                               <h2>We have sent an email to your inbox</h2>
                               <p>Please click the link in the email to verify your email address and continue to your account.</p>
                               ',
        'post_status'       => 'publish',
        'comment_status'    => 'closed',
        'ping_status'       => 'closed',
        'post_author'       => 1,
        'menu_order'        => 4
    );

    $manager = array(
        'post_title'        => 'Manager Page',
        'post_type'         => 'page',
        'post_name'         => 'jabc-manager',
        'post_content'      => '[jabc_manager]',
        'post_status'       => 'publish',
        'comment_status'    => 'closed',
        'ping_status'       => 'closed',
        'post_author'       => 1,
        'menu_order'        => 5
    );

    $menu = array(
        'post_title'        => 'JABC Breakfast Club',
        'post_type'         => 'page',
        'post_name'         => 'jabc-menu',
        'post_content'      => '',
        'post_status'       => 'publish',
        'comment_status'    => 'closed',
        'ping_status'       => 'closed',
        'post_author'       => 1,
        'menu_order'        => 6
    );

    wp_insert_post($profile);
    wp_insert_post($add_user);
    wp_insert_post($add_parent);
    wp_insert_post($add_child);
    wp_insert_post($verify_email);
    wp_insert_post($manager);
    wp_insert_post($menu);   
}
//  Run the 'jabc_setup_pages' function at plugin activation
register_activation_hook(__FILE__, 'jabc_setup_pages');


// Defines and adds the required tables to the database
function jabc_setup_tables(){
    global $wpdb;

    $parent_table = "jabc_parents";

    $charset_collate = $wpdb->get_charset_collate();

    $parent_sql = "CREATE TABLE $parent_table (
        parent_id int(11) NOT NULL AUTO_INCREMENT,
        parent_wp_user_fk bigint(20),
        parent_title text,
        parent_forename text,
        parent_surname text,
        parent_tel text,
        PRIMARY KEY  (parent_id)
    ) $charset_collate;";

    require_once ( ABSPATH . 'wp-admin/includes/upgrade.php' );
    
    maybe_create_table($parent_table, $parent_sql);

    $child_table = "jabc_children";

    $child_sql = "CREATE TABLE $child_table (
        child_id int(11) NOT NULL AUTO_INCREMENT,
        child_forename text,
        child_surname text,
        child_dob date,
        parent_id_fk int(11),
        vegetarian tinyint(1),
        PRIMARY KEY  (child_id)
    ) $charset_collate;";

    maybe_create_table($child_table, $child_sql);
}
// Run the 'jabc_setup_tables' function at plugin activation
register_activation_hook(__FILE__, 'jabc_setup_tables');


// Adds the required user roles for this plugin
function jabc_setup_roles() {
    add_role('jabc_manager', 'Breakfast Club Manager', array('read' => true));
    add_role('jabc_parent', 'Breakfast Club Parent', array('read' => true));
}
// Run the 'jabc_setup_roles' function at plugin activation
register_activation_hook(__FILE__, 'jabc_setup_roles');


function jabc_add_menu() {

    // Plugin documentation assumes theme has a primary menu location
    // Determine whether there is a nav menu in the primary location. Return either false or the name of the menu
    $primary_menu_exists = wp_get_nav_menu_name('primary');

    if($primary_menu_exists) {
        // append the existing menu with breakfast club menu item
        // retrieve ID for menu currently in the primary location
        $all_locations = get_nav_menu_locations();
        $primary_menu_name = $all_locations['primary'];
        $primary_menu = wp_get_nav_menu_object($primary_menu_name);
        $primary_menu_ID = $primary_menu->term_id;
        // add nav menu item to the menu
        wp_update_nav_menu_item($primary_menu_ID, 0, array(
            'menu-item-title'   => 'JABC Breakfast Club',
            'menu-item-url'     => home_url('/jabc-menu/'),
            'menu-item-status'  => 'publish'
        ));

    } else {
        // create breakfast club menu
        $menu_name = 'JABC Breakfast Club';
        $menu_id = wp_create_nav_menu($menu_name);

        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title'   => 'JABC Breakfast Club',
            'menu-item-url'     => home_url('/jabc-menu/'),
            'menu-item-status'  => 'publish'
            )
        );
        // set to primary location
        $locations = get_theme_mod('nav_menu_locations');

        $locations['primary'] = $menu_id;
        set_theme_mod('nav_menu_locations', $locations);
    }
}
register_activation_hook(__FILE__, 'jabc_add_menu');

// Remove breakfast club menu on plugin deactivation, if exists
function jabc_remove_menu() {
    $menu_name = 'JABC Breakfast Club';
    wp_delete_nav_menu($menu_name);
}
register_deactivation_hook(__FILE__, 'jabc_remove_menu');


// Finds and removes front end pages associated with this plugin
function jabc_remove_pages(){
    global $wpdb;

    $prefix = $wpdb->prefix;

    $table = $prefix . 'posts';
    $profile = array('post_content' => '[jabc_parent]');
    $user = array('post_content' => '[jabc_form_add_user]');
    $parent = array('post_content' => '[jabc_form_add_parent]');
    $child = array('post_content' => '[jabc_form_add_child]');
    $verify = array('post_title' => 'Verify Your Email Address');
    $manager = array('post_name' => 'jabc-manager');
    $menu = array('post_name' => 'jabc-menu');

    $wpdb->delete($table, $profile);
    $wpdb->delete($table, $user);
    $wpdb->delete($table, $parent);
    $wpdb->delete($table, $child);
    $wpdb->delete($table, $verify);
    $wpdb->delete($table, $manager);
    $wpdb->delete($table, $menu);
}
// Run the 'jabc_remove_pages' function at plugin deactivation
register_deactivation_hook(__FILE__, 'jabc_remove_pages');


// If user navigates to the 'jabc_menu' page, redirect them
function jabc_check_route() {
    global $post;
    $post_slug = $post->post_name;

    // If the user is not on the 'jabc_menu' page, exit the function
    if($post_slug != 'jabc-menu') {
        return;
    }

    // If the user navigates to the 'jabc_menu' page, check to see if they're logged in
    if(is_user_logged_in()) {
        $user = wp_get_current_user();
        $user_roles = $user->roles;
        
        if(in_array('jabc_manager', $user_roles)) {
            return wp_safe_redirect(get_site_url() . '/jabc-manager');
        }
        
        // if user is logged on and has jabc_parent role, send to parent page
        if(in_array('jabc_parent', $user_roles)) {
            return wp_safe_redirect(get_site_url() . '/my-page');
        } else {
            // if user is logged on and does not have bc_parent role, send to add-parent page
            return wp_safe_redirect(get_site_url() . '/add-parent');
        }
    } else {
        // if user is not logged on, send to add-user page
        return wp_safe_redirect(get_site_url() . '/add-user');
    }
}
// Run the 'jabc_check_route' function each time wordpress loads
add_action('wp', 'jabc_check_route');
 

// Check if user has verified their email address
function jabc_check_user_verification($user_login){
    $user = get_user_by('login', $user_login);
    
    // If $user returns a wp_error, exit function
    if(is_wp_error($user)){
        return;
    }

    // If $user has metadata for 'email_verified', prevent them from logging in
    if(metadata_exists('user', $user->ID, 'email_verified')){
        wp_die('Email not verified. <a href="wp-login.php">Back to login</a>');
    }
}
// Hooks the verification check to the user logging in action
add_action('wp_authenticate', 'jabc_check_user_verification');


// Returns a url to send the user to upon login
function jabc_user_profile_redirect(){
    return get_site_url() . '/wp-content/plugins/jabc_breakfast_club/jabc_includes/jabc_login_script.php';
}
// Hooks the 'user_profile_redirect' function to the 'login_redirect' filter
add_filter('login_redirect', 'jabc_user_profile_redirect');
 

// Stops users without the 'jabc_manager' role from accessing the manager page
function jabc_lock_manager() {

    function get_current_user_roles() {
        $user = wp_get_current_user();
        $roles = $user->roles;
        return $roles[0];
    }
    $user_role = get_current_user_roles();
     
    global $post;

    $post_slug = $post->post_name;

    if($post_slug == 'jabc-manager' && $user_role != 'jabc_manager' && $user_role != 'administrator') {
        wp_die('unauthorised');
    }
}
// Runs 'jabc_lock_manager' each time wordpress loads
add_action('wp', 'jabc_lock_manager');


// Change the 'from' name
add_filter('wp_mail_from_name', function(){
    return 'Breakfast Club';
});


// The shortcode to add templates to pages in Wordpress
add_shortcode('jabc_form_add_user', 'jabc_form_add_user');
add_shortcode('jabc_form_add_parent', 'jabc_form_add_parent');
add_shortcode('jabc_form_add_child', 'jabc_form_add_child');
add_shortcode('jabc_parent', 'jabc_parent');
add_shortcode('jabc_manager', 'jabc_manager');
add_shortcode('jabc_display_prefix', 'jabc_display_prefix');