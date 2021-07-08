<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}
function ald_login_page_delete_plugin() {
	delete_option( 'ald_login_page_logo' );
	delete_option( 'ald_login_page_bgcolor' );
	delete_option( 'ald_login_page_fontcolor' );
}

ald_login_page_delete_plugin();