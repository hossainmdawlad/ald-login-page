<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * Plugin Name: ALD Login Page
 * Description: ALD Login Page can manage login page flexibly with simple markup with the help of wordpress.
 * Version: 1.0
 * Author: Hossain Md. Awlad
 * Author URI: https://www.technoviable.com/
 *
 * Text Domain: ald-login-page
 * Domain Path: /languages/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html

ALD Login Page is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

ALD Login Page is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with ALD Login Page. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
 */

// Version declar
define( 'ald_login_page_db_version', '1.0' );

// Backwards compatibility for older than PHP 5.3.0
if ( !defined( '__DIR__' ) ) {
    define( '__DIR__', dirname( __FILE__ ) );
}

$ald_login_page_path = admin_url('admin.php?page=ald-login-page', 'http' );
$ald_login_page_db_version = '1.0';

// initialize plugin
function install_ald_login_page(){
	global $ald_login_page_db_version;
	add_option( 'ald_login_page_db_version', $ald_login_page_db_version );
}
if (isset($_GET['activate']) && $_GET['activate'] == 'true'){
	add_action('init', 'install_ald_login_page');
}
add_action( 'admin_menu', 'ald_login_page_menu' );
function ald_login_page_menu() {
	add_options_page( 'ALD Login Page Options', 'Login Page', 'manage_options', 'ald_login_page', 'ald_login_page_options' );
}

// enqueue admin scripts 
function ald_login_page_admin_scripts_js() {
	// jQuery
	wp_enqueue_script('jquery');
	// color pick up
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker' );
	// This will enqueue the Media Uploader script
	wp_enqueue_media();
	// enqueue admin script
	wp_enqueue_script( 'ald-login-page-admin-script', plugins_url('js/ald-login-page.admin.js', __FILE__), array('jquery','jquery-ui-dialog'), ald_login_page_db_version, true );

}
add_action( 'admin_enqueue_scripts', 'ald_login_page_admin_scripts_js' );

// Plugin main admin page
function ald_login_page_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';
	?>
	<span class="alignleft"><h1>ALD Login Page Options</h1></span>
	<?php
		if(isset($_GET['message'])){
			if( $_GET['message'] = 'true'){
				?>
					<div id="" class="updated notice notice-success is-dismissible"><p>Updated successfully.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
				<?php
			}
			else if( $_GET['message'] = 'false'){
				?>
					<div id="" class="updated notice notice-error is-dismissible"><p>Sorry. Didn't Updated. Please try again later.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
				<?php
			}
		}
		?>
	<br>
	<form action="<?php echo admin_url( 'admin.php' ); ?>" method="post">
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">Site Name</th>
					<td><?php echo get_bloginfo(); ?></td>
				</tr>
				<tr>
					<th scope="row">Site URL</th>
					<td><?php echo get_site_url(); ?></td>
				</tr>
				<tr>
					<th scope="row">Logo</th>
					<td>
						<input type="text" name="logourl" class="regular-text" id="logo-image" value="<?php echo get_option('ald_login_page_logo'); ?>" />
						<input type="button" class="button button-secondary" id="upload-btn" value="Upload" required />
						<div id="logo-image-preview">
							<img src="<?php echo get_option('ald_login_page_logo'); ?>" style="width: 100px;" />
						</div>
					</td>
				</tr>
				<tr>
					<th scope="row">Background Color</th>
					<td><input type="text" name="bgcolor" placeholder="#000000" class="regular-text color-pick" value="<?php echo get_option('ald_login_page_bgcolor'); ?>" /></td>
				</tr>
				<tr>
					<th scope="row">Link Font Color</th>
					<td><input type="text" name="fontcolor" placeholder="#000000" class="regular-text color-pick" value="<?php echo get_option('ald_login_page_fontcolor'); ?>" /></td>
				</tr>
			</tbody>
		</table>
		<input type="hidden" name="logo" value="<?php echo esc_html($logo); ?>" />
		<input type="hidden" name="action" value="add_logo" />
		<input type="submit" value="Update" class="button button-primary" />
	</form>
	<?php
	echo '</div>';

}

// plugin data update
add_action( 'admin_action_add_logo', 'add_logo_admin_action' );
function add_logo_admin_action()
{
	$logourl = sanitize_text_field( trim($_POST['logourl'] ));
	$bgcolor = sanitize_text_field( trim($_POST['bgcolor'] ));
	$fontcolor = sanitize_text_field( trim($_POST['fontcolor'] ));

	if (!empty($logourl) || !empty($bgcolor) || !empty($fontcolor)) {
		if (!empty($logourl)) {
			$lu = update_option( 'ald_login_page_logo', $logourl );
		}

		if (!empty($bgcolor)) {
			$bc = update_option( 'ald_login_page_bgcolor', $bgcolor );
		}

		if (!empty($fontcolor)) {
			$fc = update_option( 'ald_login_page_fontcolor', $fontcolor );
		}
		$msg = 'true';
	}
	else{
		$msg = 'false';
	}
	$path = add_query_arg('message', $msg, $_SERVER['HTTP_REFERER']);
	wp_redirect( $path, $status = 302 );
	exit();
}

// frontend view
function ald_login_logo() { ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
        	<?php
        	if(get_option('ald_login_page_logo')){
        	?>
            background-image: url(<?php echo get_option('ald_login_page_logo'); ?>);
            <?php
        	}
            ?>
			<!-- height:65px; -->
			width:320px;
			<!-- background-size: 320px 65px; -->
			background-repeat: no-repeat;
        }

        body{
        	background-color: <?php echo get_option('ald_login_page_bgcolor'); ?>!important;
        }

        body.login div#login p#nav a, body.login div#login p#backtoblog a{
        	color: <?php echo get_option('ald_login_page_fontcolor'); ?>!important;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'ald_login_logo' );

function ald_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'ald_login_logo_url' );

function ald_login_logo_url_title() {
    return get_bloginfo();
}
add_filter( 'login_headertitle', 'ald_login_logo_url_title' );

