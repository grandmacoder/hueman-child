<?php
/*
Template Name: Activate User
*/
/**
 * Confirms that the activation key that is sent in an email after a user signs
 * up for a new blog matches the key for that user and then displays confirmation.
 *
 * @package WordPress
 */

if ( !is_multisite() OR (empty($_GET['key']) && empty($_POST['key'])) ) {
    wp_redirect( site_url( '/wp-login.php?action=register' ) );
    die();
}

if ( is_object( $wp_object_cache ) )
    $wp_object_cache->cache_enabled = false;

// Fix for page title
$wp_query->is_404 = false;

/**
 * Fires before the Site Activation page is loaded.
 *
 * @since 3.0
 */

/**
 * Adds an action hook specific to this page that fires on wp_head
 *
 * @since MU
 */
            $key = !empty($_GET['key']) ? $_GET['key'] : $_POST['key'];
            $result = wpmu_activate_signup($key);
            $siteurl = site_url();
            if ( is_wp_error($result) ) {
            if ( 'already_active' == $result->get_error_code() || 'blog_taken' == $result->get_error_code() ) {
                $signup = $result->get_error_data();
                $errortype='alreadyactivated';
                if ( $signup->domain . $signup->path == '' ) {
                    $errortext.= "Your account has already been activated.";
                } else {
                    $errortext.= "Your new site is activated.";
             }
   
            } else {
            $errortype='activatefail';
            $errortext=$result->get_error_message();
            }
        //redirect to a general error page
             $redirect_to = '/oops?errortype='. $errortype .'&errortext='. urlencode($errortext);
             wp_safe_redirect( $redirect_to );
             exit();
        } else {
        
          extract($result);
            $url = get_blogaddress_by_id( (int) $blog_id);
            $user = get_userdata( (int) $user_id);
            $username = $user->user_login;
            $useremail = $user->user_email;
            $meta = get_user_meta( $user);
            wp_clear_auth_cookie();
            wp_set_current_user ( $user->ID );
            wp_set_auth_cookie  ( $user->ID );
            $redirect_to = '/new-user-survey';
            wp_safe_redirect( $redirect_to );
            exit();
        }

    ?>
