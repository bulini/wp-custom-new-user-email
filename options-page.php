<?php
// create custom custom_user_email_menu
add_action('admin_menu', 'custom_user_email_menu');

function custom_user_email_menu() {
	//create new menu for plugin
	add_menu_page('User email Settings', 'New User email', 'administrator', __FILE__, 'custom_user_email_settings_page' , plugins_url('/mail.png', __FILE__) );
	//call register settings function
	add_action( 'admin_init', 'custom_user_email_settings' );
}

function custom_user_email_settings() {
	//register settings
	register_setting( 'custom-user-email-settings-group', '_email_subject' );
	register_setting( 'custom-user-email-settings-group', '_email_text' );
	//register_setting( 'custom-user-email-settings-group', '_email_footer_text' );
}

function custom_user_email_settings_page() {
	?>
	<div class="wrap">
		<h1><?php echo PLUGIN_NAME; ?></h1>

		<div class="wp_new_user_panel">
			<b><?php _e('Placeholders', 'custom-user-email'); ?></b>
			<p><?php _e('You can use these placeholder to customize each message. (Ex Dear [[first_name]]).', 'custom-user-email'); ?></p>
			<b>[[site_url]] [[username]] [[first_name]] [[last_name]]</b>
		</div>

		<form method="post" action="options.php">
			<?php settings_fields( 'custom-user-email-settings-group' ); ?>
			<?php do_settings_sections( 'custom-user-email-settings-group' ); ?>
			<table class="form-table">

				<tr valign="top">
					<th scope="row">
						<?php _e('Welcome email Subject', 'custom-user-email'); ?>
					</th>
					<td>
						<input type="text" name="_email_subject" class="regular-text" value="<?php echo esc_attr( get_option('_email_subject') ); ?>" />
						<small><?php _e('Your welcome email subject for new user notification', 'custom-user-email'); ?></small>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">
						<?php _e('Welcome email text', 'custom-user-email'); ?>
					</th>
					<td>
						<?php
						$content = get_option('_email_text');
						$editor_id = '_email_text';
						wp_editor( $content, $editor_id ); ?>
					</td>
				</tr>

				<tr>
					<th scope="row"></th>
					<td><?php submit_button(); ?></td>
				</tr>

			</table>
		</form>

	</div>
<?php } ?>
