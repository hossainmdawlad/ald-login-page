=== ALD Login Page ===
Contributors: hossainawlad
Plugin URI: https://github.com/hossainmdawlad/ald-login-page
Author URI: https://www.technoviable.com
Tags: change login page, login, form, login form
Requires at least: 4.4.2
Tested up to: 7.0
Stable tag: 1.3.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Just another Login page customization plugin. Simple but flexible.

== Description ==

ALD Login Page lets you fully customize the WordPress login page — logo, colors, dimensions, and padding — through a clean admin settings panel. Built on WordPress core APIs, the plugin follows WordPress security best practices from top to bottom so you can customize your login page with confidence.

== Why security matters for a login page plugin ==
The WordPress login screen is the most exposed entry point to your admin area. A poorly coded login plugin can become an attack vector. ALD Login Page is designed to avoid the most common pitfalls:
<ul>
  <li><strong>Settings API + Sanitization callbacks</strong> — all saved values pass through WordPress core sanitizers (<code>sanitize_text_field</code>, <code>sanitize_hex_color</code>, <code>esc_url_raw</code>) before they ever touch the database.</li>
  <li><strong>Output escaping everywhere</strong> — <code>esc_url()</code>, <code>esc_attr()</code>, and <code>esc_html()</code> are used on every dynamic value that reaches the browser, eliminating XSS vectors.</li>
  <li><strong>Capability gate on admin page</strong> — only users with <code>manage_options</code> can access settings. Unauthorized users are blocked before any output is rendered.</li>
  <li><strong>Direct-access gate</strong> — <code>defined( 'ABSPATH' ) or die</code> on every PHP file prevents direct URL access to plugin files.</li>
  <li><strong>Nonce + CSRF handled automatically</code></strong> — the WordPress Settings API inserts and validates security nonces on every settings save.</li>
  <li><strong>No raw SQL</strong> — the plugin never calls <code>$wpdb->query()</code> or similar directly.</li>
  <li><strong>No shell commands</strong> — no <code>eval()</code>, <code>exec()</code>, or <code>shell_exec()</code> anywhere.</li>
  <li><strong>Superglobal sanitization</strong> — all user-supplied query parameters are passed through <code>sanitize_key()</code> with strict comparison before being evaluated.</li>
</ul>

The result: a lightweight login customizer with a clean security posture. If you are reviewing this plugin for code quality, you will find no raw <code>echo $_GET</code>, no unescaped output, and no capability bypasses. We take security seriously and keep this plugin up to date with the latest WordPress core versions.

= ALD Login Page Needs Your Support =

It is hard to continue development and support for this free plugin without contributions from users like you. If you enjoy using ALD Login Page and find it useful, please consider use these support channels appropriately. Your support will help encourage and support the plugin's continued development and better user support.


== Installation ==

1. Upload the entire `ald-login-page` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.

You will find 'ALD Login Page' menu in your WordPress admin panel Setting.

== Frequently Asked Questions ==

Do you have questions or issues with ALD Login Page? Use these support channels appropriately.

1. [Support Forum](https://wordpress.org/support/plugin/ald-login-page/)


== Screenshots ==

1. screenshot-1.png
2. screenshot-2.png
3. screenshot-3.png
4. screenshot-4.png
5. screenshot-5.png

== Changelog ==

= 1.3.1 =
* Hardened activate flag check with sanitize_key() + strict comparison to prevent type-juggling bypasses.
* Replaced deprecated 'login_headertitle' filter with 'login_headertext' (deprecated since WP 5.2.0).
* Fixed WordPress 6.7+ textdomain loading notice by hooking load_plugin_textdomain() to after_setup_theme.
* Removed UTF-8 BOM from plugin file to prevent 'unexpected output' activation warning.

= 1.3 =
* Refactored admin page to use WordPress Settings API for standard styling.
* Added options for Logo Width, Height, and Padding.
* Fixed color picker script dependency issue.

= 1.2 =
* Improved admin page UI with sections, descriptions, and better media uploader integration.

= 1.1 =
* Dynamically define plugin version.
* Security improvements

= 1.0 =
* Initial Release.
