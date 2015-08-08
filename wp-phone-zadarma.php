<?php
/*
Plugin Name: WP Phone Zadarma
Plugin URI: https://github.com/systemo-biz/wp-phone-zadarma
Description: Shortcode [sip_zadarma key="" secret=""] for callback via Zadarma API https://zadarma.com/ru/support/api/
Version: 20150808
License: GPL
Author: Systemo
Author URI: http://systemo.biz
GitHub Plugin URI: https://github.com/systemo-biz/wp-phone-zadarma
GitHub Branch: master
*/



function sc_zadarma_callback($atts) {

	extract( shortcode_atts( array(
			  'key' => '',
			  'secret' => ''
		 ), $atts ) );

	ob_start();
	?>
		<div class="sip_zadarma_callback">
			<form action="">

			</form>
		</div>
	<?php

	$html = ob_get_contents();
	ob_get_clean();

	return $html;

}
add_shortcode('sip_zadarma', 'sc_zadarma_callback');


function zadarma_callback_s($key, $secret, $from, $to){

	include_once 'inc/zadarma-user-api-v1/lib/Client.php';

	define('KEY', $key);
	define('SECRET', $secret);

	$params = array(
    'from' => $from,
    'to' => $to,
	//    'sip' => 'YOURSIP'
	);
	$zd = new \Zadarma_API\Client(KEY, SECRET);
	$answer = $zd->call('/v1/request/callback/', $params);
	$answerObject = json_decode($answer);
	if ($answerObject->status == 'success') {
	    print_r($answerObject);
	} else {
	    echo $answerObject->message;
	}

}

//Rule for check API Zadarma and generate key
function chek_zadarma(){
	if (isset($_GET['zd_echo'])) exit($_GET['zd_echo']);
}
add_action('init', 'chek_zadarma');
