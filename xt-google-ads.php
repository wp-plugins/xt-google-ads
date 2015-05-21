<?php
/*
Plugin Name: XT Google Ads
Plugin URI: http://xtrsyz.org/
Description: Just plugin to show Google Ads on every page.
Author: Satria Adhi
Version: 1.0
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
	if (stristr($content, '<!--noads-->')) {
		// no ads
	} else if (stristr($content,'<!--ads-->')) {
		$ads_code = get_option('xt_google_ads_code');
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
		$ads_code = get_option('xt_google_ads_code');
		$ads_code = $ads_code?$ads_code:'<span class="alignright"><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script><!-- serbagratisandiinternet_sidebar_AdSense2_200x200_as --><ins class="adsbygoogle" style="display:inline-block;width:200px;height:200px" data-ad-client="ca-pub-3709515531962980" data-ad-slot="9236036214"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script></span>';
		$cc = substr($content,0,$titik);
		$dd = substr($content,$titik);
		$content = $cc . $ads_code . $dd;
	}
	return $content;
}
add_action( 'the_content', 'xt_google_ads' );

?>