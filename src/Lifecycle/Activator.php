<?php
/**
 * Runs once, on plugin activation.
 *
 * @package Expl
 */

namespace Expl\Lifecycle;

defined( 'ABSPATH' ) || exit;

/**
 * Activator class.
 */
final class Activator {

	/**
	 * Run once on plugin activation.
	 */
	public static function activate(): void {
		add_option( 'example-plugin_settings', self::default_settings(), '', false );
		self::maybe_create_tables();
		flush_rewrite_rules();
	}

	/**
	 * Default settings. Registered with autoload off ('' , false) —
	 * this plugin's settings aren't needed on every request.
	 *
	 * @return array Default settings array.
	 */
	public static function default_settings(): array {
		return array( 'enabled' => true );
	}

	/**
	 * No custom tables by default. If a plugin built on this boilerplate
	 * needs one, call dbDelta() here — see the WP schema-change guide
	 * (https://developer.wordpress.org/reference/functions/dbdelta/)
	 * and add indexes on every column used in WHERE/JOIN/ORDER BY.
	 */
	private static function maybe_create_tables(): void {
	}
}
