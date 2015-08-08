<?php
/*
Добавляем страницу настроек WordPress
*/
add_action('admin_menu', 'zadarma_api_s_add_menu');
function zadarma_api_s_add_menu(){
add_options_page(
	$page_title = 'Zadarma SIP API',
	$menu_title='Zadarma SIP API',
	$capability='manage_options',
	$menu_slug='zadarma_api_s',
	$function='zadarma_api_s_callback');
}
/*
Регистрируем опции, секции и поля
*/
add_action('admin_init', 'cp_hybridauth_init_options');
function cp_hybridauth_init_options(){

    /*
  	Добавляем секцию на страницу настроек
  	*/
  	add_settings_section(
  		$id = 'zadarma_api_section_s',
  		$title = 'Опции API Zadarma',
  		$callback = 'zadarma_api_section_s_callback',
  		$page = 'zadarma_api_s'
  	);

    //Опция Key
    register_setting( 'zadarma_api_s', 'zadarma_api_key_s' );
  	add_settings_field(
  		$id = 'zadarma_api_key_s',
  		$title = 'Ключ (Key)',
  		$callback = 'zadarma_api_key_s_callback',
  		$page = "zadarma_api_s",
  		$section = "zadarma_api_section_s"
		);

    //Опция Secret
    register_setting( 'zadarma_api_s', 'zadarma_api_secret_s' );
    add_settings_field(
  		$id = 'zadarma_api_secret_s',
  		$title = 'Секрет (Secret)',
  		$callback = 'zadarma_api_secret_s_callback',
  		$page = "zadarma_api_s",
  		$section = "zadarma_api_section_s"
		);

}
function zadarma_api_section_s_callback(){
?>
<p>Данные ключей и приложений следует брать на соответствующей странице https://ss.zadarma.com/api/</p>
<?php
}


function zadarma_api_key_s_callback(){
	$setting_name = 'zadarma_api_key_s';
	$setting_value = get_option( $setting_name );

	?>
  	<div class="zadarma_key_wrapper">
      <input id="<?php echo $setting_name; ?>" type="text" name="<?php echo $setting_name ?>" value="<?php echo $setting_value ?>">
  	</div>
	<?php
}


function zadarma_api_secret_s_callback(){
	$setting_name = 'zadarma_api_secret_s';
	$setting_value = get_option( $setting_name );

	?>
  	<div class="zadarma_secret_wrapper">
      <input id="<?php echo $setting_name; ?>" type="text" name="<?php echo $setting_name ?>" value="<?php echo $setting_value ?>">
  	</div>
	<?php
}


//Print form for menu page
function zadarma_api_s_callback(){
?>
    <div class="wrap">
        <h1>Настройки</h1>
        <form action="options.php" method="POST">
            <?php settings_fields( 'zadarma_api_s' ); ?>
            <?php do_settings_sections( 'zadarma_api_s' ); ?>
            <?php submit_button(); ?>
        </form>
    </div>
<?php
}
