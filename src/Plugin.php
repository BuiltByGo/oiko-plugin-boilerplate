<?php
/**
 * Composition root — wires every subsystem together. No business logic
 * lives here; it only calls each area's own register() method.
 *
 * @package Expl
 */

namespace Expl;

use Expl\Admin\Settings_Page;
use Expl\Api\Rest_Controller;
use Expl\Frontend\Shortcodes;

defined( 'ABSPATH' ) || exit;

/**
 * Plugin class.
 *
 * Composition root that orchestrates registration of all plugin subsystems:
 * admin settings, REST API, and frontend shortcodes.
 */
final class Plugin {

	/**
	 * Boot the plugin by registering all subsystems.
	 *
	 * @return void
	 */
	public static function boot(): void {
		Settings_Page::register();
		Rest_Controller::register();
		Shortcodes::register();
	}
}
