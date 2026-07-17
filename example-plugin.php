<?php
/**
 * Plugin Name:       Example Plugin
 * Description:       Oiko plugin boilerplate — secure-by-default WordPress plugin scaffold.
 * Version:           0.1.0
 * Requires at least: 6.7
 * Requires PHP:      8.3
 * Author:            BuiltByGo
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       example-plugin
 * Domain Path:       /languages
 *
 * @package Expl
 */

defined( 'ABSPATH' ) || exit;

define( 'EXPL_VERSION', '0.1.0' );
define( 'EXPL_FILE', __FILE__ );
define( 'EXPL_DIR', plugin_dir_path( __FILE__ ) );
define( 'EXPL_URL', plugin_dir_url( __FILE__ ) );

if ( version_compare( PHP_VERSION, '8.3', '<' ) ) {
	add_action(
		'admin_notices',
		function () {
			echo '<div class="notice notice-error"><p>' .
				esc_html__( 'Example Plugin requires PHP 8.3 or higher.', 'example-plugin' ) .
				'</p></div>';
		}
	);
	return;
}

require_once EXPL_DIR . 'vendor/autoload.php';

register_activation_hook( EXPL_FILE, array( \Expl\Lifecycle\Activator::class, 'activate' ) );
register_deactivation_hook( EXPL_FILE, array( \Expl\Lifecycle\Deactivator::class, 'deactivate' ) );

add_action( 'plugins_loaded', array( \Expl\Plugin::class, 'boot' ) );
