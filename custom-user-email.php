<?php
/**
* Plugin Name:     New user custom email
* Plugin URI:      https://www.giuseppesurace.com/
* Description:     Quickly edit your new user welcome notification. No configuration needed, just change the subject the email text using all useful available shortcodes. Send to new users their login info and the activation link to set the password.
* Author:          Giuseppe
* Author URI:      https://www.giuseppesurace.com/
* Text Domain:     custom-user-email
* Domain Path:     /languages
* Version:         1.0.0
*
* @package         Custom_User_Email
*/

define('PLUGIN_NAME','New user custom email');
define('PLUGIN_VERSION','1.0.0');
require_once('options-page.php');

add_action( 'plugins_loaded', 'custom_user_email_textdomain' );
/**
* Load plugin textdomain.
*
* @since 1.0.0
*/
function custom_user_email_textdomain() {
  load_plugin_textdomain( 'custom-user-email', false, basename( dirname( __FILE__ ) ) . '/languages' );
}


add_action('admin_head', 'wp_new_user_style');

/**
 * Add some inline style
 * in order to create a future custom css to enqueue
 */
function wp_new_user_style() {
  echo '<style>.wp_new_user_panel { padding:5px; background:#fff; border-left:4px solid #2ada70; font-size:14px; box-shadow: 0 1px 1px 0 rgba(0,0,0,.1); }</style>';
}

/**
* Override new user notification function
* send ew users their login info
* and the activation link to set the password
* @author	Giuseppe Surace <info@giuseppesurace.com>
* @param 	integer $user_id user id
*/
if ( !function_exists( 'wp_new_user_notification' ) ) {
  function wp_new_user_notification( $user_id ) {
    global $wpdb, $wp_hasher;
    // html content type
    add_filter( 'wp_mail_content_type', 'wpmail_content_type' );

    // new user object
    $user_object = new WP_User( $user_id );
    $user_email = stripslashes( $user_object->user_email );
    $site_url = get_option( 'siteurl');

    // Generate something random for a password reset key.
    $key = wp_generate_password( 20, false );
    do_action( 'retrieve_password_key', $user_object->user_login, $key );

    // Now insert the key, hashed, into the DB.
    if ( empty( $wp_hasher ) ) {
        require_once ABSPATH . WPINC . '/class-phpass.php';
        $wp_hasher = new PasswordHash( 8, true );
    }
    $hashed = time() . ':' . $wp_hasher->HashPassword( $key );

    $wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user_object->user_login ) );

    $switched_locale = switch_to_locale( get_user_locale( $user_object ) );

    //$message = sprintf(__('Username: %s'), $user->user_login) . "\r\n\r\n";
    //$new_user_message .= __('To set your password, visit the following address:') . "\r\n\r\n";

    $password_url = network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_object->user_login), 'login');

    $_shortcodes = array(
      'first_name'=> $user_object->first_name,
      'last_name' => $user_object->last_name,
      'site_url'  => $site_url,
      'username'  => $user_object->user_login,
      'password_url' => $password_url
    );

    $subject = get_option('_email_subject');

    $subject = replaceTags($subject, $_shortcodes);

    $headers = 'From: '.get_option('blogname').' <'.get_option('admin_email').'>';

    // if admin email on (todo) ---
    $message  = __('A new user has been created', 'custom-user-email')."\r\n\r\n";
    $message .= 'Email: '.$user_email."\r\n";
    @wp_mail( get_option( 'admin_email' ), __('New user created', 'custom-user-email'), $message, $headers );
    // end if admin email -----

    //load email template w wpautop
    $new_user_message = wpautop(get_option('_email_text'));
    //replace mai tags
    $new_user_message = replaceTags($new_user_message, $_shortcodes);

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

add_action( 'user_register', 'myplugin_registration_save', 10, 1 );

function myplugin_registration_save( $user_id ) {

    if ( isset( $_POST['first_name'] ) )
        update_user_meta($user_id, 'first_name', $_POST['first_name']);

}
