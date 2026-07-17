<?php

namespace Expl\Tests\Lifecycle;

use Expl\Lifecycle\Deactivator;
use WP_Mock;
use WP_Mock\Tools\TestCase;

class DeactivatorTest extends TestCase {

	public function test_deactivate_clears_cron_and_flushes_rewrites(): void {
		WP_Mock::userFunction( 'wp_clear_scheduled_hook' )
			->once()
			->with( 'example-plugin_cron' );

		WP_Mock::userFunction( 'flush_rewrite_rules' )->once();

		Deactivator::deactivate();

		$this->assertConditionsMet();
	}
}
