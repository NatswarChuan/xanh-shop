<?php
/**
 * Plugin Name: Ibtana - Ecommerce Product Addons
 * Plugin URI:
 * Description: With the Ibtana - Ecommerce Product Addons, you get to explore so many options for editing the product page by simple drag and drop functionality. You just need to use the add ons to create a unique product page in a manner that it resembles your brand in the most promising way. Woocommerce add ons will help you to get a distinct online store than those with the conventional design that your customers are tired of seeing.
 * Author: VowelWeb
 * Author URI: https://www.vowelweb.com/
 * Version: 0.1.5
 * Requires at least: 5.2
 * Requires PHP: 7.2
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: ibtana-ecommerce-product-addons
 * Domain Path:       /languages
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


define( 'IEPA_PLUGIN_FILE', __FILE__ );
define( 'IEPA_PLUGIN_URI', plugins_url( '/', IEPA_PLUGIN_FILE ) );
define( 'IEPA_PLUGIN_THEME', 'ibtana' );
define( 'IEPA_DESKTOP_STARTPOINT', '1025' );
define( 'IEPA_TABLET_BREAKPOINT', '1024' );
define( 'IEPA_MOBILE_BREAKPOINT', '767' );
define( 'IEPA_BASE', plugin_basename( IEPA_PLUGIN_FILE ) );
define( 'IEPA_DIR', plugin_dir_path( IEPA_PLUGIN_FILE ) );
define( 'IEPA_WP_PLUGINS_DIR', str_replace( 'ibtana-ecommerce-product-addons/', '', plugin_dir_path( IEPA_PLUGIN_FILE ) ) );
define( 'IEPA_URL', plugins_url( '/', IEPA_PLUGIN_FILE ) );
define( 'IEPA_ABSPATH', dirname(__FILE__) . '/' );

if( ! function_exists('get_plugin_data') ) {
  require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}
$plugin_data = get_plugin_data( __FILE__ );
define( 'IEPA_VER', $plugin_data['Version'] );
define( 'IEPA_TEXT_DOMAIN', $plugin_data['TextDomain'] );


require_once 'classes/class-iepa-loader.php';
require_once IEPA_DIR . 'IEPA_Whizzie/config.php';


function iepa_upgrader_process_complete( $upgrader_object, $options ) {
	$our_plugin = plugin_basename( __FILE__ );
	if( $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {
	 // Iterate through the plugins being updated and check if ours is there
	 foreach( $options['plugins'] as $plugin ) {
		if( $plugin == $our_plugin ) {
			// Your action if it is your plugin
			wp_remote_post(
				'https://vwthemes.com/wp-json/ibtana-licence/v2/' . 'ibtana_woo_analytics',
				array(
						'method'      => 'POST',
						'body'        => wp_json_encode( array(
								'site_url'	=>  site_url(),
							)
						),
					'headers'     => [
						'Content-Type' => 'application/json',
					],
					'data_format' => 'body'
				)
			);
		 break;
		}
	 }
	}
}
add_action( 'upgrader_process_complete', 'iepa_upgrader_process_complete', 10, 2 );


/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/init.php';

require_once IEPA_DIR . 'inc/class-admin.php';
require_once IEPA_DIR . 'inc/class-pro.php';
require_once IEPA_DIR . 'iepa_addon.php';

require_once IEPA_DIR . 'classes/class-iepa-config.php';
require_once IEPA_DIR . 'classes/class-iepa-block-helper.php';
require_once IEPA_DIR . 'classes/class-iepa-block-js.php';
require_once IEPA_DIR . 'classes/class-iepa-helper.php';
