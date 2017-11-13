<?php
// create custom plugin settings menu
add_action('admin_menu', 'custom_user_email_menu');

function custom_user_email_menu() {

	//create new top-level menu
	add_menu_page('User email Settings', 'New User email', 'administrator', __FILE__, 'custom_user_email_settings_page' , plugins_url('/mail.png', __FILE__) );

	//call register settings function
	add_action( 'admin_init', 'custom_user_email_settings' );
}


function custom_user_email_settings() {
	//register settings
	register_setting( 'custom-user-email-settings-group', '_email_subject' );
	register_setting( 'custom-user-email-settings-group', '_email_text' );
	register_setting( 'custom-user-email-settings-group', '_email_footer_text' );
}

function custom_user_email_settings_page() {
?>
<div class="wrap">
<h1><?php echo PLUGIN_NAME; ?></h1>

<span>[[site_url]] [[username]] [[first_name]] [[last_name]]</span>

<form method="post" action="options.php">
    <?php settings_fields( 'custom-user-email-settings-group' ); ?>
    <?php do_settings_sections( 'custom-user-email-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php _e('Welcome email Subject', 'custom_user_email'); ?></th>
        <td><input type="text" name="_email_subject" value="<?php echo esc_attr( get_option('_email_subject') ); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row"><?php _e('Welcome email text', 'custom_user_email'); ?></th>
        <td><?php $content = get_option('_email_text');
                  $editor_id = '_email_text';
                  wp_editor( $content, $editor_id );?></td>
        </tr>
        
        <tr valign="top">
        <th scope="row"><?php _e('Welcome email Footer Text', 'custom_user_email'); ?></th>
        <td><input type="text" name="_email_footer_text" value="<?php echo esc_attr( get_option('_email_footer_text') ); ?>" /></td>
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php } ?>