<?php

namespace Expl\Tests;

use Expl\Admin\Settings_Page;
use Expl\Api\Rest_Controller;
use Expl\Frontend\Shortcodes;
use Expl\Plugin;
use WP_Mock;
use WP_Mock\Tools\TestCase;

class PluginTest extends TestCase {

	public function test_boot_registers_admin_api_and_frontend(): void {
		WP_Mock::expectActionAdded( 'admin_menu', array( Settings_Page::class, 'add_menu' ) );
		WP_Mock::expectActionAdded( 'admin_enqueue_scripts', array( Settings_Page::class, 'enqueue' ) );
		WP_Mock::expectActionAdded( 'admin_post_example_plugin_save_settings', array( Settings_Page::class, 'save' ) );
		WP_Mock::expectActionAdded( 'rest_api_init', array( Rest_Controller::class, 'routes' ) );
		WP_Mock::userFunction( 'add_shortcode' )
			->once()
			->with( 'example_plugin_status', array( Shortcodes::class, 'render' ) );
		WP_Mock::expectActionAdded( 'wp_enqueue_scripts', array( Shortcodes::class, 'maybe_enqueue' ) );

		Plugin::boot();

		$this->assertConditionsMet();
	}
}
