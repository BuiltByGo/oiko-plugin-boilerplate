<?php

namespace Expl\Tests\Frontend;

use Expl\Frontend\Shortcodes;
use WP_Mock;
use WP_Mock\Tools\TestCase;

class ShortcodesTest extends TestCase {

	public function test_register_adds_shortcode_and_enqueue_hook(): void {
		WP_Mock::userFunction( 'add_shortcode' )
			->once()
			->with( 'example_plugin_status', array( Shortcodes::class, 'render' ) );

		WP_Mock::expectActionAdded( 'wp_enqueue_scripts', array( Shortcodes::class, 'maybe_enqueue' ) );

		Shortcodes::register();

		$this->assertConditionsMet();
	}

	public function test_render_escapes_output(): void {
		WP_Mock::passthruFunction( 'esc_html__' );

		$this->assertSame(
			'<p class="example-plugin-status">Example Plugin is active.</p>',
			Shortcodes::render()
		);

		$this->assertConditionsMet();
	}
}
