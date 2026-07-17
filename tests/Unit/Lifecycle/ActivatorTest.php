<?php

namespace Expl\Tests\Lifecycle;

use Expl\Lifecycle\Activator;
use WP_Mock;
use WP_Mock\Tools\TestCase;

class ActivatorTest extends TestCase {

	public function test_activate_adds_default_settings_with_autoload_off(): void {
		$defaults = Activator::default_settings();
		$this->assertSame( [ 'enabled' => true ], $defaults );

		WP_Mock::userFunction( 'add_option' )
			->once()
			->with( 'example-plugin_settings', $defaults, '', false );

		WP_Mock::userFunction( 'flush_rewrite_rules' )->once();

		Activator::activate();

		$this->assertConditionsMet();
	}

	public function test_default_settings_shape(): void {
		$this->assertSame( [ 'enabled' => true ], Activator::default_settings() );
	}
}
