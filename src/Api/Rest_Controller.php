<?php
/**
 * REST routes. Every route declares an explicit permission_callback —
 * never __return_true on anything that mutates data or reveals more
 * than a public visitor should see.
 *
 * @package Expl
 */

namespace Expl\Api;

defined( 'ABSPATH' ) || exit;

/**
 * Rest_Controller class.
 */
final class Rest_Controller {

	/**
	 * Register the REST API hook.
	 */
	public static function register(): void {
		add_action( 'rest_api_init', array( self::class, 'routes' ) );
	}

	/**
	 * Register REST routes.
	 */
	public static function routes(): void {
		register_rest_route(
			'example-plugin/v1',
			'/status',
			array(
				'methods'             => 'GET',
				'callback'            => array( self::class, 'status' ),
				'permission_callback' => array( self::class, 'can_view_status' ),
			)
		);
	}

	/**
	 * Check if current user can view status.
	 *
	 * @return bool True if user can manage options, false otherwise.
	 */
	public static function can_view_status(): bool {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Get the plugin status.
	 *
	 * @return \WP_REST_Response The status response.
	 */
	public static function status(): \WP_REST_Response {
		return rest_ensure_response(
			array(
				'status'  => 'ok',
				'version' => EXPL_VERSION,
			)
		);
	}
}
