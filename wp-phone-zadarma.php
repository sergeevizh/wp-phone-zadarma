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

include_once 'inc/options.php';

//Print Shortcode and call phone number
function sc_zadarma_callback($atts) {

	extract( shortcode_atts( array(
			  'key' => '',
			  'secret' => ''
		 ), $atts ) );

	 	$from = $_REQUEST['from'];
		$to = $_REQUEST['to'];


	 if(isset($_REQUEST['action'])):
		 $action = $_REQUEST['action'];
	 	 if($action != 'sip_zadarma_callback') return "Нет вызова звонка";
	 	 $respond = zadarma_callback_s($from, $to);
	 endif;


	ob_start();
	?>
		<div class="sip_zadarma_callback">
			<?php
				if(isset($respond) and ($respond->status == 'success')) {
					echo "<strong class='label label-success'>Звонок запущен</strong>";
				}
			?>
			<p>Укажите номера в международном формате без +. Например: 78002000000</p>
			<form type="GET">
				<div class="form-wrapper">
					<label for="from_input">Кто звонит</label><br/>
					<input id="from_input" type="number" name="from" value="<?php echo $from ?>" />
				</div>
				<div class="to-wrapper">
					<label for="to_input">Кому звоним</label><br/>
					<input id="to_input" type="number" name="to" value="<?php echo $to ?>"/>
				</div>
				<input type="hidden" name="action" value="sip_zadarma_callback" />
				<br/>
				<input type="submit" class="btn btn-default" value="Позвонить" />
			</form>
		</div>
	<?php

	$html = ob_get_contents();
	ob_get_clean();

	return $html;

}
add_shortcode('sip_zadarma', 'sc_zadarma_callback');


//Zadarma Callback
function zadarma_callback_s($from, $to){

	include_once 'inc/zadarma-user-api-v1/lib/Client.php';
	$key = get_option( 'zadarma_api_key_s' );
	define('KEY', $key);

	$secret = get_option('zadarma_api_secret_s');
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
	    return $answerObject;
	} else {
	    return $answerObject->message;
	}
	return;
}

//Rule for check API Zadarma and generate key
function chek_zadarma(){
	if (isset($_GET['zd_echo'])) exit($_GET['zd_echo']);
}
add_action('init', 'chek_zadarma');
