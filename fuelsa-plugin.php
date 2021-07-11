<?php
/**
 * Plugin Name: Fuel SA Plugin
 * Plugin URI: https://fuelsa.co.za
 * Description: Fuel SA utilities
 * Version: 0.1
 * Text Domain: fuelsa-plugin
 * Author: Nailed Digital
 * Author URI: https://www.naileddigital.com
 */

include('fuelsa-client.php');


function fsa_init() {
	wp_enqueue_script('chartjs', 'https://cdn.jsdelivr.net/npm/chart.js@3.4.1/dist/chart.min.js', array(), false, true);
    wp_enqueue_script( 'fsa-chart-shortcode-script', plugins_url( '/js/chart-shortcode.js', __FILE__ ), array('chartjs'), false, true);
}

add_action('wp_enqueue_scripts','fsa_init');

function format_price($number) {
	return 'R' . number_format(floatval($number) / 100, 2);
}

 function fsa_current_fuel_prices($atts) {
	$settings = get_option('fsa_settings');
	if (!$settings) {
		echo 'You need to configure the Fuel SA settings under Settings';
		return;
	}
	if (!$settings['fsa_api_key'] || empty($settings['fsa_api_key'])) {
		echo 'You need to set the API key';
		return;
	}
	$key = 'fuelsa-cfp';
	if ( !$result = get_transient($key) ) {
		$client = new FuelSAClient($settings['fsa_api_key']);
		$result = $client->getCurrentFuelPrices();
		set_transient($key, serialize($result), '', 43200);
	 }
	 else {
		 $result = unserialize($result);
	 }

	 $result = json_decode($result, true);

	 $content = '';

	 if ($settings['fsa_theme'] == 1) {
		$content .= '<style>';
		$content .= '.fuelsa-cfp-container { width: 100%; }';
		$content .= '.fuelsa-cfp-container > div { width: 50%; display: inline-block; }';
		$content .= '.fuelsa-cfp-regions > div { width: 50%; display: inline-block; }';
		$content .= '.fuelsa-cfp-regions { font-weight: bold; font-size: 150%; }';
		$content .= '.fuelsa-cfp-prices > div { width: 50%; display: inline-block; }';
		$content .= '.fuelsa-cfp-prices span { width: 100%; display: block; }';
		$content .= '.fuelsa-cfp-title { font-weight: bold; }';
		$content .= '.fuelsa-cfp-diesel-terms { font-size: 80%; font-style: italic; opacity: .8; }';
		$content .= '</style>';
	 }

	 $petroldatechange = '';
	 $petrolreefunleaded93 = 0;
	 $petrolreefunleaded95 = 0;
	 $petrolreeflrp93 = 0;
	 $petrolcoastunleaded93 = 0;
	 $petrolcoastunleaded95 = 0;
	 $petrolcoastlrp95 = 0;

	 foreach($result['petrol'] as $item) {
		 
		 $petroldatechange = date('d M Y', strtotime($item['date']));
		 if ($item['location'] == 'Reef') {
			if ($item['octane'] == '93' && $item['type'] == 'Unleaded') {
				$petrolreefunleaded93 = format_price($item['value']);
			}
			else if ($item['octane'] == '95' && $item['type'] == 'Unleaded') {
				$petrolreefunleaded95 = format_price($item['value']);
			}
			else if ($item['octane'] == '95' && $item['type'] == 'LRP') { 
				$petrolreeflrp93 = format_price($item['value']);
			}
		 }
		 else if ($item['location'] == 'Coast') {
			 if ($item['octane'] == '93' && $item['type'] == 'Unleaded') {
				$petrolcoastunleaded93 = format_price($item['value']);
			}
			else if ($item['octane'] == '95' && $item['type'] == 'Unleaded') {
				$petrolcoastunleaded95 = format_price($item['value']);
			}
			else if ($item['octane'] == '95' && $item['type'] == 'LRP') { 
				$petrolcoastlrp95 = format_price($item['value']);
			}
		 }
	 }

	 $dieseldatechange = '';
	 $dieselreef50ppm = 0;
	 $dieselreef500ppm = 0;
	 $dieselcoast50ppm = 0;
	 $dieselcoast500ppm = 0;

	 foreach($result['diesel'] as $item) {
		 
		 $dieseldatechange = date('d M Y', strtotime($item['date']));
		 if ($item['location'] == 'Reef') {
			if ($item['ppm'] == '50') {
				$dieselreef50ppm = format_price($item['value']);
			}
			else if ($item['ppm'] == '500') {
				$dieselreef500ppm = format_price($item['value']);
			}
		 }
		 else if ($item['location'] == 'Coast') {
			if ($item['ppm'] == '50') {
				$dieselcoast50ppm = format_price($item['value']);
			}
			else if ($item['ppm'] == '500') {
				$dieselcoast500ppm = format_price($item['value']);
			}
		 }
	 }

	 
	 $content .= '<div class="fuelsa-cfp-container">';
	 $content .= '<div class="fuelsa-cfp-petrol-container">';
	 $content .= '<h2>Petrol</h2>';
	 $content .= '<div class="fuelsa-cfp-lpc">Last Price Change: ' . $petroldatechange . '</div>';
	 $content .= '<div class="fuelsa-cfp-regions"><div>Reef</div><div>Coast</div></div>';
	 $content .= '<div class="fuelsa-cfp-prices"><div><span class="fuelsa-cfp-title">Unleaded 93</span><span>' . $petrolreefunleaded93 . '</span><span class="fuelsa-cfp-title">Unleaded 95</span><span>' . $petrolreefunleaded95 . '</span><span class="fuelsa-cfp-title">LRP 93</span><span>' . $petrolreefunleaded93 . '</span></div><div><span class="fuelsa-cfp-title">Unleaded 93</span><span>' . $petrolcoastunleaded93 . '</span><span class="fuelsa-cfp-title">Unleaded 95</span><span>' . $petrolcoastunleaded95 . '</span><span class="fuelsa-cfp-title">LRP 93</span><span>' . $petrolcoastlrp95 . '</span></div></div>';
	 $content .= '</div><div class="fuelsa-cfp-diesel-container"><h2>Diesel</h2>';
	 $content .= '<div class="fuelsa-cfp-lpc">Last Price Change: ' . $dieseldatechange . '</div>';
	 $content .= '<div class="fuelsa-cfp-regions"><div>Reef</div><div>Coast</div></div>';
	 $content .= '<div class="fuelsa-cfp-prices"><div><span class="fuelsa-cfp-title">50 PPM (0.005%)</span><span>' . $dieselreef50ppm . '</span><span class="fuelsa-cfp-title">500 PPM (0.05%)</span><span>' . $dieselreef500ppm . '</span></div><div><span class="fuelsa-cfp-title">50 PPM (0.005%)</span><span>' . $dieselcoast50ppm . '</span><span class="fuelsa-cfp-title">500 PPM (0.05%)</span><span>' . $dieselcoast500ppm . '</span></div><span class="fuelsa-cfp-diesel-terms">* Wholesale Diesel price quoted. Prices may vary between fuel retailers.</span></div>';
	 $content .= '</div>';
	 $content .= '<div>Powered by <a href="https://www.fuelsa.co.za" target="_blank">Fuel SA</a>';
	 $content .= '</div>';
	 
    return $content;
}

add_shortcode('fuelsa_current_fuel_prices', 'fsa_current_fuel_prices');

function fsa_fuel_prices_chart($atts) {
	$settings = get_option('fsa_settings');
	if (!$settings) {
		echo 'You need to configure the Fuel SA settings under Settings';
		return;
	}
	if (!$settings['fsa_api_key'] || empty($settings['fsa_api_key'])) {
		echo 'You need to set the API key';
		return;
	}
	$key = 'fuelsa-fpc';
	if ( !$result = get_transient($key) ) {
		$client = new FuelSAClient($settings['fsa_api_key']);
		$result = $client->getFuelPricesByYear(date("Y"));
		set_transient($key, serialize($result), '', 43200);
	 }
	 else {
		 $result = unserialize($result);
	 }

	 $content = '<script>let fsafuelchartdata = ' . $result . ';</script>';
	 
	 if ($settings['fsa_theme'] == 1) {
		$content .= '<style>';
		$content .= '.fuelsa-cfp-container { width: 100%; }';
		$content .= '.fuelsa-cfp-container > div { width: 100%; }';
		$content .= '.fsa-chart { width: 100%; min-height: 200px; }';
		$content .= '</style>';
	 }

	 $content .= '<div class="fuelsa-cfp-container">';
	 $content .= '<div class="fuelsa-cfp-petrol-container">';
	 $content .= '<h2>Petrol</h2>';
	 $content .= '<canvas id="fsa-petrol-chart" class="fsa-chart"></canvas>';
	 $content .= '</div><div class="fuelsa-cfp-diesel-container">';
	 $content .= '<h2>Diesel</h2>';
	 $content .= '<canvas id="fsa-diesel-chart" class="fsa-chart"></canvas>';
	 $content .= '</div>';


	 return $content;
}

add_shortcode('fuelsa_fuel_prices_chart', 'fsa_fuel_prices_chart');

add_action( 'admin_menu', 'fsa_add_admin_menu' );
add_action( 'admin_init', 'fsa_settings_init' );


function fsa_add_admin_menu(  ) { 

	add_options_page( 'Fuel SA', 'Fuel SA', 'manage_options', 'fuel_sa_plugin', 'fsa_options_page' );

}


function fsa_settings_init(  ) { 

	register_setting( 'pluginPage', 'fsa_settings' );

	add_settings_section(
		'fsa_pluginPage_section',
		'', 
		'fsa_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'fsa_api_key', 
		__( 'API Key', 'fuelsa-plugin' ), 
		'fsa_api_key_render', 
		'pluginPage', 
		'fsa_pluginPage_section' 
	);

	add_settings_field( 
		'fsa_theme', 
		__( 'Theme', 'fuelsa-plugin' ), 
		'fsa_theme_render', 
		'pluginPage', 
		'fsa_pluginPage_section' 
	);


}


function fsa_api_key_render(  ) { 

	$options = get_option( 'fsa_settings' );
	?>
	<input type='text' name='fsa_settings[fsa_api_key]' value='<?php echo $options['fsa_api_key']; ?>'>
	<?php

}


function fsa_theme_render(  ) { 

	$options = get_option( 'fsa_settings' );
	?>
	<select name='fsa_settings[fsa_theme]'>
		<option value='1' <?php selected( $options['fsa_theme'], 1 ); ?>>Default</option>
		<option value='2' <?php selected( $options['fsa_theme'], 2 ); ?>>Custom</option>
	</select>

<?php

}


function fsa_settings_section_callback(  ) { 

	echo __( 'Set your API key and short code theme.', 'fuelsa-plugin' );

}


function fsa_options_page(  ) { 

		?>
		<form action='options.php' method='post'>

			<h2>Fuel SA Plugin</h2>

			<?php
			settings_fields( 'pluginPage' );
			do_settings_sections( 'pluginPage' );
			submit_button();
			?>

		</form>
		<?php

}