<?php
/**
 * Plugin Name: Dealership Information
 * Description: This plugin will allow site-specific dealership information to be saved and displayed on the website.
 * Version: 0.1
 * Author: Scott Warren
 */
include_once 'admin-page/dealership_info.php';

/***********************Triggers check for Site options******************************************************/
function dld_dealer_information_apply_site_features_from_options(){
	$a_Option = get_option( 'hide_site_options', false  );
	if( !empty($a_Option)  ){
		$s_HiddenInlineStyle ="";
		foreach ($a_Option as $key => $class) {
			$s_HiddenInlineStyle .= $class.', ';
		}
		$s_HiddenInlineStyle = rtrim($s_HiddenInlineStyle, ' ,');
		$s_HiddenInlineStyle .= "{display:none !important;}";
		wp_add_inline_style( 'newplatform', $s_HiddenInlineStyle );
	}
}
add_action( 'wp_enqueue_scripts', 'dld_dealer_information_apply_site_features_from_options', 20 );