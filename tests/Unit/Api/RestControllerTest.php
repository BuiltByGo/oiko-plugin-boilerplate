<?php

namespace Expl\Tests\Api;

use Expl\Api\Rest_Controller;
use WP_Mock;
use WP_Mock\Tools\TestCase;

class RestControllerTest extends TestCase {

	public function test_register_hooks_rest_api_init(): void {
		WP_Mock::expectActionAdded( 'rest_api_init', [ Rest_Controller::class, 'routes' ] );

		Rest_Controller::register();

		$this->assertConditionsMet();
	}

	public function test_routes_registers_status_route_with_explicit_permission_callback(): void {
		WP_Mock::userFunction( 'register_rest_route' )
			->once()
			->with(
				'example-plugin/v1',
				'/status',
				[
					'methods'             => 'GET',
					'callback'            => [ Rest_Controller::class, 'status' ],
					'permission_callback' => [ Rest_Controller::class, 'can_view_status' ],
				]
			);

		Rest_Controller::routes();

		$this->assertConditionsMet();
	}

	public function test_can_view_status_requires_manage_options(): void {
		WP_Mock::userFunction( 'current_user_can' )
			->once()
			->with( 'manage_options' )
			->andReturn( true );

		$this->assertTrue( Rest_Controller::can_view_status() );
	}
}
