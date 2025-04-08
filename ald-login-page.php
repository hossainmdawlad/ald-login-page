<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * Plugin Name: ALD Login Page
 * Plugin URI: https://github.com/hossainmdawlad/ald-login-page
 * Description: ALD Login Page can manage login page flexibly with simple markup with the help of wordpress.
 * Version: 1.3
 * Author: Hossain Md. Awlad
 * Author URI: https://www.technoviable.com/
 *
 * Text Domain: ald-login-page
 * Domain Path: /languages/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Version declaration
if ( ! function_exists( 'get_plugin_data' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}
$plugin_data = get_plugin_data( __FILE__ );
$plugin_version = $plugin_data['Version'];
define( 'ald_login_page_db_version', $plugin_version );

// Backwards compatibility for older than PHP 5.3.0
if ( !defined( '__DIR__' ) ) {
    define( '__DIR__', dirname( __FILE__ ) );
}

$ald_login_page_path = esc_url(admin_url('admin.php?page=ald-login-page', 'http' ));

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
	// enqueue admin script - ensure wp-color-picker is a dependency
	wp_enqueue_script( 'ald-login-page-admin-script', plugins_url('js/ald-login-page.admin.js', __FILE__), array('jquery', 'wp-color-picker'), ald_login_page_db_version, true );

}
add_action( 'admin_enqueue_scripts', 'ald_login_page_admin_scripts_js' );

// Register settings using the Settings API
function ald_login_page_register_settings() {
    // Register settings group
    register_setting( 'ald_login_page_options_group', 'ald_login_page_logo', 'esc_url_raw' );
    register_setting( 'ald_login_page_options_group', 'ald_login_page_logo_width', 'sanitize_text_field' ); // Added
    register_setting( 'ald_login_page_options_group', 'ald_login_page_logo_height', 'sanitize_text_field' ); // Added
    register_setting( 'ald_login_page_options_group', 'ald_login_page_logo_padding', 'sanitize_text_field' ); // Added
    register_setting( 'ald_login_page_options_group', 'ald_login_page_bgcolor', 'sanitize_hex_color' );
    register_setting( 'ald_login_page_options_group', 'ald_login_page_fontcolor', 'sanitize_hex_color' );

    // Add settings section for Logo
    add_settings_section(
        'ald_login_page_logo_section',
        'Logo Settings',
        'ald_login_page_section_callback', // Optional callback for section description
        'ald_login_page'
    );

    // Add settings field for Logo URL
    add_settings_field(
        'ald_login_page_logo',
        'Login Page Logo',
        'ald_login_page_logo_callback',
        'ald_login_page',
        'ald_login_page_logo_section'
    );

    // Add settings field for Logo Width
    add_settings_field(
        'ald_login_page_logo_width',
        'Logo Width',
        'ald_login_page_logo_width_callback',
        'ald_login_page',
        'ald_login_page_logo_section'
    );

    // Add settings field for Logo Height
    add_settings_field(
        'ald_login_page_logo_height',
        'Logo Height',
        'ald_login_page_logo_height_callback',
        'ald_login_page',
        'ald_login_page_logo_section'
    );

    // Add settings field for Logo Padding
    add_settings_field(
        'ald_login_page_logo_padding',
        'Logo Padding',
        'ald_login_page_logo_padding_callback',
        'ald_login_page',
        'ald_login_page_logo_section'
    );

    // Add settings section for Colors
    add_settings_section(
        'ald_login_page_color_section',
        'Color Settings',
        'ald_login_page_section_callback', // Optional callback for section description
        'ald_login_page'
    );

    // Add settings field for Background Color
    add_settings_field(
        'ald_login_page_bgcolor',
        'Background Color',
        'ald_login_page_bgcolor_callback',
        'ald_login_page',
        'ald_login_page_color_section'
    );

    // Add settings field for Link Font Color
    add_settings_field(
        'ald_login_page_fontcolor',
        'Link Font Color',
        'ald_login_page_fontcolor_callback',
        'ald_login_page',
        'ald_login_page_color_section'
    );
}
add_action( 'admin_init', 'ald_login_page_register_settings' );

// Section callback function (optional description)
function ald_login_page_section_callback( $args ) {
    // You can add descriptions for sections here if needed
    // Example: echo '<p>Configure the color settings for the login page.</p>';
}

// Field callback functions
function ald_login_page_logo_callback() {
    $logo_url = get_option( 'ald_login_page_logo' );
    ?>
    <input type="text" name="ald_login_page_logo" class="regular-text" id="logo-image" value="<?php echo esc_url( $logo_url ); ?>" />
    <input type="button" class="button button-secondary" id="upload-btn" value="Upload Logo" />
    <p class="description">Upload or paste the URL for your login page logo.</p>
    <div id="logo-image-preview" style="margin-top: 10px;">
        <img src="<?php echo esc_url( $logo_url ); ?>" style="max-width: 200px; height: auto; display: <?php echo $logo_url ? 'block' : 'none'; ?>;" />
    </div>
    <?php
}

// Callback for Logo Width
function ald_login_page_logo_width_callback() {
    $width = get_option( 'ald_login_page_logo_width' );
    ?>
    <input type="text" name="ald_login_page_logo_width" class="small-text" id="logo-width" value="<?php echo esc_attr( $width ); ?>" placeholder="e.g., 320px or 80%" />
    <p class="description">Enter the width for the logo (e.g., <code>320px</code>, <code>80%</code>). Leave blank for default.</p>
    <?php
}

// Callback for Logo Height
function ald_login_page_logo_height_callback() {
    $height = get_option( 'ald_login_page_logo_height' );
    ?>
    <input type="text" name="ald_login_page_logo_height" class="small-text" id="logo-height" value="<?php echo esc_attr( $height ); ?>" placeholder="e.g., 84px" />
    <p class="description">Enter the height for the logo (e.g., <code>84px</code>). Leave blank for default.</p>
    <?php
}

// Callback for Logo Padding
function ald_login_page_logo_padding_callback() {
    $padding = get_option( 'ald_login_page_logo_padding' );
    ?>
    <input type="text" name="ald_login_page_logo_padding" class="small-text" id="logo-padding" value="<?php echo esc_attr( $padding ); ?>" placeholder="e.g., 20px 0" />
    <p class="description">Enter the padding for the logo container (e.g., <code>20px 0</code>, <code>10px</code>). Leave blank for default.</p>
    <?php
}

function ald_login_page_bgcolor_callback() {
    $bgcolor = get_option( 'ald_login_page_bgcolor' );
    ?>
    <input type="text" name="ald_login_page_bgcolor" placeholder="#f0f0f1" class="color-pick" id="bgcolor" value="<?php echo esc_attr( $bgcolor ); ?>" data-default-color="#f0f0f1" />
    <p class="description">Select the background color for the login page.</p>
    <?php
}

function ald_login_page_fontcolor_callback() {
    $fontcolor = get_option( 'ald_login_page_fontcolor' );
    ?>
    <input type="text" name="ald_login_page_fontcolor" placeholder="#50575e" class="color-pick" id="fontcolor" value="<?php echo esc_attr( $fontcolor ); ?>" data-default-color="#50575e" />
    <p class="description">Select the font color for the links (e.g., 'Lost your password?', 'Back to ...') on the login page.</p>
    <?php
}


// Plugin main admin page - Refactored to use Settings API
function ald_login_page_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
			<?php
			// Output security fields for the registered setting group
			settings_fields( 'ald_login_page_options_group' );
			// Output setting sections and their fields
			do_settings_sections( 'ald_login_page' );
			// Output save settings button
			submit_button( 'Save Settings' );
			?>
		</form>
	</div>
	<?php
}

// Remove the old manual save action
// add_action( 'admin_action_add_logo', 'add_logo_admin_action' ); // No longer needed
// function add_logo_admin_action() { ... } // No longer needed

// frontend view
function ald_login_logo() {
    $logo_url = get_option('ald_login_page_logo');
    $logo_width = get_option('ald_login_page_logo_width');
    $logo_height = get_option('ald_login_page_logo_height');
    $logo_padding = get_option('ald_login_page_logo_padding');
    $bgcolor = get_option('ald_login_page_bgcolor');
    $fontcolor = get_option('ald_login_page_fontcolor');
    ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            <?php if ($logo_url) : ?>
            background-image: url(<?php echo esc_url($logo_url); ?>);
            <?php endif; ?>
            <?php if ($logo_width) : ?>
            width: <?php echo esc_attr($logo_width); ?>;
            <?php else : ?>
            width: 320px; /* Default width */
            <?php endif; ?>
            <?php if ($logo_height) : ?>
            height: <?php echo esc_attr($logo_height); ?>;
            <?php endif; ?>
            <?php if ($logo_padding) : ?>
            padding: <?php echo esc_attr($logo_padding); ?>;
            <?php endif; ?>
            background-size: contain; /* Ensure logo fits within dimensions */
            background-repeat: no-repeat;
            background-position: center; /* Center the logo */
        }

        body.login {
            <?php if ($bgcolor) : ?>
            background-color: <?php echo esc_attr($bgcolor); ?> !important;
            <?php endif; ?>
        }

        body.login div#login p#nav a,
        body.login div#login p#backtoblog a {
            <?php if ($fontcolor) : ?>
            color: <?php echo esc_attr($fontcolor); ?> !important;
            <?php endif; ?>
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
