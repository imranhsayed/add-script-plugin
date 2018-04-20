<?php
/*
Plugin Name: Add Script Plugin
Description: A plugin to insert style codes
Plugin URI:   https://github.com/imranhsayed/add-script-plugin
Author: Imran Sayed
Author URI:   https://profiles.wordpress.org/gsayed786
Version: 1.0.0
License: GPLv2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
*/

add_action( 'admin_menu', 'ihs_create_insert_code_menu' );
if ( ! function_exists( 'ihs_create_insert_code_menu' ) ) {
	/**
	 * Creates Menu for Plugin in the dashboard.
	 */
	function ihs_create_insert_code_menu() {
		// Create new top-level menu.
		add_menu_page( 'Insert code Settings', 'Insert Code', 'administrator', __FILE__, 'ihs_insert_codes', 'dashicons-admin-plugins' );
		// Call register settings function.
		add_action( 'admin_init', 'register_ihs_post_tax_settings' );
	}
}
if ( ! function_exists( 'register_ihs_post_tax_settings' ) ) {
	/**
	 * Register our settings.
	 */
	function register_ihs_post_tax_settings() {
		register_setting( 'ihs_insert_code_settings_group', 'ihs_style_codes' );
		register_setting( 'ihs_insert_code_settings_group', 'ihs_script_codes' );
	}
}

if ( ! function_exists( 'ihs_insert_codes' ) ) {
	/**
	 * Settings Page for Plugin.
	 */
	function ihs_insert_codes() {
		?>
		<div class="wrap">
			<h1>Insert Code</h1>

			<form method="post" action="options.php">
				<?php settings_fields( 'ihs_insert_code_settings_group' ); ?>
				<?php do_settings_sections( 'ihs_insert_code_settings_group' ); ?>
				<table class="form-table">
					<tr valign="top">
						<th scope="row">Insert Style Code Here to be added to the header of the page</th>
						<td><label for="">
								<textarea cols='40' rows='5' name='ihs_style_codes'>
								<?php echo esc_html( get_option( 'ihs_style_codes' ) ); ?>
 	                            </textarea>
							</label>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">Insert JavaScript Code Here to be added in your theme</th>
						<td><label for="">
								<textarea cols='40' rows='5' name='ihs_script_codes'>
								<?php echo esc_html( get_option( 'ihs_script_codes' ) ); ?>
 	                            </textarea>
							</label>
						</td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
}

if ( ! function_exists( 'ihs_enqueue_custom_scripts' ) ) {
	/**
	 * Includes custom scripts and scripts and adds the inline styles and scripts to the theme added by the user in the dashboard.
	 */
	function ihs_enqueue_custom_scripts() {
		wp_enqueue_style( 'ihs_custom_code_style', plugins_url( 'add-script-plugin' ) . '/style.css' );
		wp_enqueue_script( 'ihs_custom_code_script', plugins_url( 'add-script-plugin' ) . '/js/javascript.js', array( 'jquery' ), '', true );
		$custom_css = esc_html( get_option( 'ihs_style_codes' ) );
		$custom_js = get_option( 'ihs_script_codes' );
		wp_add_inline_style( 'ihs_custom_code_style', $custom_css );
		wp_add_inline_script( 'ihs_custom_code_script', $custom_js );
	}
	add_action( 'wp_enqueue_scripts', 'ihs_enqueue_custom_scripts', 100 );
}
