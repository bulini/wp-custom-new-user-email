<?php
/**
 * Plugin Name:     My User Email
 * Plugin URI:      https://www.giuseppesurace.com/
 * Description:     Quickly edit your new user welcome notification. No configuration needed, just change the subject the email text using all useful placeholedrs.
 * Author:          Giuseppe
 * Author URI:      https://www.giuseppesurace.com/
 * Text Domain:     custom-user-email
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Custom_User_Email
 */

define('PLUGIN_NAME','Custom New User Email');

require_once('options-page.php');

add_action( 'plugins_loaded', 'custom_user_email_textdomain' );
/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
function custom_user_email_textdomain() {
  load_plugin_textdomain( 'custom_user_email', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
}


 /**
 * redefine new user notification function
 *
 * emails new users their login info
 *
 * @author	Giuseppe Surace <info@giuseppesurace.com>
 * @param 	integer $user_id user id
 */
 if ( !function_exists( 'wp_new_user_notification' ) ) {
     function wp_new_user_notification( $user_id ) {

         // html content type
         add_filter( 'wp_mail_content_type', 'wpmail_content_type' );

         // new user object
         $user_object = new WP_User( $user_id );
         $user_email = stripslashes( $user_object->user_email );
         $site_url = get_option( 'siteurl');

         $_placeholders = array(
            'first_name'=> $user_object->first_name,
            'last_name' => $user_object->last_name,
            'site_url'  => $site_url,
            'username'  => $user_object->user_login
        );

         //to do: customize....
         $subject = get_option('_email_subject');

         $subject = replaceTags($subject, $_placeholders);
         
         $headers = 'From: '.get_option('blogname').' <'.get_option('admin_email').'>';

         // if admin email on (todo) ---
         $message  = __("A new user has been created", "custom_user_email")."\r\n\r\n";
         $message .= 'Email: '.$user_email."\r\n";
         @wp_mail( get_option( 'admin_email' ), __('New user created', 'custom_user_email'), $message, $headers );
         // end if admin email -----

         //load email template w wpautop
         $new_user_message = wpautop(get_option('_email_text'));
         //replace mai tags 
         $new_user_message = replaceTags($new_user_message, $_placeholders);

         ob_start();
         include plugin_dir_path( __FILE__ ).'/email/_welcome_new_user.php';
         $message = ob_get_contents();
         ob_end_clean();

         @wp_mail( $user_email, $subject, $message, $headers );

         // remove html content type
         remove_filter ( 'wp_mail_content_type', 'wpmail_content_type' );
     }
 }


 /**
 * wpmail_content_type
 * allow html emails
 * @author Giuseppe Surace <info@giuseppesurace.com>
 * @return string
 */
function wpmail_content_type() {
    return 'text/html';
}

function replaceTags($template = "", $tags = array()) {
  $_tags = array();
  foreach(array_keys($tags) as $key) $_tags[] = "[[$key]]";
  return str_replace($_tags, $tags, $template);

}
