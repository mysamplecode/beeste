<?php
if( !is_user_logged_in() ){
	wp_redirect( home_url() . '/wp-login.php' );
	exit;
}

global $jv_str;

$javo_this_user = wp_get_current_user();
?>