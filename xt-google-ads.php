<?php
/*
Plugin Name: XT Google Ads
Plugin URI: http://xtrsyz.org/
Description: Just plugin to show Google Ads on every page.
Author: Satria Adhi
Version: 1.1
Author URI: http://xtrsyz.org/
*/
add_action('admin_menu', 'xt_google_ads_settings');
 
function xt_google_ads_settings() {
 
    add_menu_page('XT Google Ads', 'XT Google Ads', 'administrator', 'xt-google-ads', 'xt_google_ads_page');
 
}

function xt_google_ads_page() {
	require_once (dirname ( __FILE__ ) . '/xt-google-ads-page.php');
}
function xt_google_ads( $content ) {
	if(is_singular()) {
	$post_id = get_the_ID();
	$xt_google_ads = get_post_meta($post_id, "_xt_google_ads", true);
	$xt_hide = get_post_meta($post_id, "hide_xt_google_ads", true);
	}
	
	if (!$xt_hide) {
	if (stristr($content, '<!--noads-->')) {
		// no ads
	} else if (stristr($content,'<!--ads-->')) {
		$ads_code = $xt_google_ads?$xt_google_ads:get_option('xt_google_ads_code');
		$ads_code = $ads_code?$ads_code:'<span class="alignright"><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script><!-- serbagratisandiinternet_sidebar_AdSense2_200x200_as --><ins class="adsbygoogle" style="display:inline-block;width:200px;height:200px" data-ad-client="ca-pub-3709515531962980" data-ad-slot="9236036214"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script></span>';
		str_ireplace('<!--ads-->',$ads_code,$content);
	} else {
		$tmpcontent = $content;
		while ((stristr($tmpcontent,'<br />') || stristr($tmpcontent,'</p>'))&& $titik < strlen($content)/4) {
		$tmpcontent = substr($tmpcontent,$ttk);
		$ttk = strpos($tmpcontent, '<br />');
		if ($ttk) {
			$ttk+=6;
		} else {
			$ttk = strpos($tmpcontent, '</p>');
			if ($ttk) $ttk+=4;
		}
		$titik += $ttk;
		}
		$ads_code = $xt_google_ads?$xt_google_ads:get_option('xt_google_ads_code');
		$ads_code = $ads_code?$ads_code:'<span class="alignright"><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script><!-- serbagratisandiinternet_sidebar_AdSense2_200x200_as --><ins class="adsbygoogle" style="display:inline-block;width:200px;height:200px" data-ad-client="ca-pub-3709515531962980" data-ad-slot="9236036214"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script></span>';
		$cc = substr($content,0,$titik);
		$dd = substr($content,$titik);
		$content = $cc . $ads_code . $dd;
	}
	}
	return $content;
}
add_action( 'the_content', 'xt_google_ads' );


function xt_google_ads_config_page(){
//just add the info (or in the future option) page
	if (function_exists('add_options_page')){
	add_options_page('XT Google Ads',
	'XT Google Ads',
	8,
	basename(__FILE__),
	'xt_google_ads_plugin_config'
	);
	}
}

/* Function for admin Panel */
function xt_google_ads_plugin_config(){
//we don't need at the moment any config
}


function xt_google_ads_admin_post() {
//call to the box, i no longer offer solutions for wp version < 2.5.
	if ( function_exists( 'add_meta_box' )) {
    add_meta_box( 'xt_google_ads_div','XT Google Ads', 'xt_google_ads_inner_box', 'post', 'advanced' );
    add_meta_box( 'xt_google_ads_div','XT Google Ads', 'xt_google_ads_inner_box', 'page', 'advanced' );
	}
}
function xt_google_ads_inner_box($post) {
	$ischecked = get_post_meta($post->ID, "hide_xt_google_ads", true);
	echo '<label for="desc_xt_google_ads_field">Insert here your ads code. Your default ads will replace with this.</label>';
	echo '<br/> ';
	echo '<textarea name="xt_google_ads" id="desc_xt_google_ads_field_'.$post->ID.'" style="width:100%;" rows="4">'. get_post_meta($post->ID, "_xt_google_ads", true).'</textarea>';
	echo '<br/><br/>Check this box if you want hide ads for this post. <input type="checkbox" name="hide_xt_google_ads"'.$ischecked.' value="checked"/>';
	echo '<input type="hidden" name="xt_google_ads_id" value="'.$post->ID.'" />';
}
function xt_google_ads_savedata($post_id) {
	$postID = $_POST["xt_google_ads_id"];
	$xt_google_ads = $_POST['xt_google_ads'];
	$hide_xt_google_ads = $_POST['hide_xt_google_ads'];
	if(get_post_meta($postID,"_xt_google_ads")) delete_post_meta($postID,"_xt_google_ads");
	add_post_meta($postID,"_xt_google_ads", $xt_google_ads, true);
	if(get_post_meta($postID,"hide_xt_google_ads")) delete_post_meta($postID,"hide_xt_google_ads");
	add_post_meta($postID,"hide_xt_google_ads", $hide_xt_google_ads, true);
}



function xt_google_ads_install(){
//we don't need at the moment any registration option
}


if (isset($_GET['activate']) && $_GET['activate'] == 'true') {
	xt_google_ads_install();
}

//hook for the menu
add_action('admin_menu', 'xt_google_ads_config_page');
//hook for the box in the post add/edit page
add_action('admin_init', 'xt_google_ads_admin_post', 1 );
//hook for the post saving
add_action('save_post', 'xt_google_ads_savedata');
