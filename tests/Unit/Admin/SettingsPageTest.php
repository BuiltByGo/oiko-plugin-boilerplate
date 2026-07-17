<?php

namespace Expl\Tests\Admin;

use Expl\Admin\Settings_Page;
use WP_Mock;
use WP_Mock\Tools\TestCase;

class SettingsPageTest extends TestCase {

	public function test_register_wires_up_menu_assets_and_save_handler(): void {
		WP_Mock::expectActionAdded( 'admin_menu', [ Settings_Page::class, 'add_menu' ] );
		WP_Mock::expectActionAdded( 'admin_enqueue_scripts', [ Settings_Page::class, 'enqueue' ] );
		WP_Mock::expectActionAdded( 'admin_post_example_plugin_save_settings', [ Settings_Page::class, 'save' ] );

		Settings_Page::register();

		$this->assertConditionsMet();
	}

	public function test_enqueue_skips_other_admin_screens(): void {
		WP_Mock::userFunction( 'wp_enqueue_script' )->never();

		Settings_Page::enqueue( 'edit.php' );

		$this->assertConditionsMet();
	}

	public function test_enqueue_loads_deferred_script_on_its_own_screen(): void {
		if ( ! defined( 'EXPL_URL' ) ) {
			define( 'EXPL_URL', 'https://example.test/wp-content/plugins/example-plugin/' );
		}
		if ( ! defined( 'EXPL_VERSION' ) ) {
			define( 'EXPL_VERSION', '0.1.0' );
		}

		WP_Mock::userFunction( 'wp_enqueue_script' )
			->once()
			->with(
				'example-plugin-admin',
				EXPL_URL . 'assets/admin/js/settings.js',
				[],
				EXPL_VERSION,
				[
					'strategy'  => 'defer',
					'in_footer' => true,
				]
			);

		Settings_Page::enqueue( 'settings_page_example-plugin' );

		$this->assertConditionsMet();
	}

	public function test_save_rejects_without_capability(): void {
		WP_Mock::userFunction( 'current_user_can' )
			->once()
			->with( 'manage_options' )
			->andReturn( false );

		WP_Mock::userFunction( 'wp_die' )
			->once()
			->andReturnUsing(
				function () {
					throw new \RuntimeException( 'wp_die called' );
				}
			);

		$this->expectException( \RuntimeException::class );
		$this->expectExceptionMessage( 'wp_die called' );

		Settings_Page::save();
	}

	public function test_save_updates_option_when_capability_and_nonce_pass(): void {
		$_POST['enabled'] = '1';

		WP_Mock::userFunction( 'current_user_can' )
			->once()
			->with( 'manage_options' )
			->andReturn( true );

		WP_Mock::userFunction( 'check_admin_referer' )
			->once()
			->with( 'example-plugin_save_settings' )
			->andReturn( true );

		WP_Mock::userFunction( 'wp_unslash' )
			->once()
			->with( '1' )
			->andReturn( '1' );

		WP_Mock::userFunction( 'sanitize_text_field' )
			->once()
			->with( '1' )
			->andReturn( '1' );

		WP_Mock::userFunction( 'update_option' )
			->once()
			->with( 'example-plugin_settings', array( 'enabled' => true ), false );

		WP_Mock::userFunction( 'wp_get_referer' )->once()->andReturn( 'https://example.test/wp-admin/options-general.php?page=example-plugin' );
		WP_Mock::userFunction( 'add_query_arg' )->once()->andReturn( 'https://example.test/wp-admin/options-general.php?page=example-plugin&updated=true' );

		WP_Mock::userFunction( 'wp_safe_redirect' )
			->once()
			->andReturnUsing(
				function () {
					throw new \RuntimeException( 'redirect called' );
				}
			);

		$this->expectException( \RuntimeException::class );
		$this->expectExceptionMessage( 'redirect called' );

		Settings_Page::save();
	}

	public function tearDown(): void {
		unset( $_POST['enabled'] );

		parent::tearDown();
	}
}
