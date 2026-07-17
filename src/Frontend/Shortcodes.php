<?php
/**
 * A minimal demo shortcode, showing escaped-output + conditional-enqueue.
 *
 * @package Expl
 */

namespace Expl\Frontend;

defined( 'ABSPATH' ) || exit;

/**
 * Shortcodes class.
 *
 * Demonstrates escaped output and conditional asset enqueue based on singular post content.
 */
final class Shortcodes {

	/**
	 * Register the shortcode and enqueue hook.
	 *
	 * @return void
	 */
	public static function register(): void {
		add_shortcode( 'example_plugin_status', array( self::class, 'render' ) );
		add_action( 'wp_enqueue_scripts', array( self::class, 'maybe_enqueue' ) );
	}

	/**
	 * Only loads on singular views that actually contain the shortcode —
	 * never a global frontend-wide enqueue.
	 *
	 * @return void
	 */
	public static function maybe_enqueue(): void {
		if ( ! is_singular() ) {
			return;
		}

		global $post;

		if ( ! ( $post instanceof \WP_Post ) || ! has_shortcode( $post->post_content, 'example_plugin_status' ) ) {
			return;
		}

		wp_enqueue_style(
			'example-plugin-frontend',
			EXPL_URL . 'assets/frontend/css/style.css',
			array(),
			EXPL_VERSION
		);
	}

	/**
	 * Render the shortcode output, escaped.
	 *
	 * @return string The escaped shortcode output.
	 */
	public static function render(): string {
		return '<p class="example-plugin-status">' . esc_html__( 'Example Plugin is active.', 'example-plugin' ) . '</p>';
	}
}
