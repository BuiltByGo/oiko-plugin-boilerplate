<?php
/**
 * Runs once, on plugin deactivation. Never deletes user data — that's
 * uninstall.php's job, guarded by explicit uninstall, not deactivation.
 *
 * @package Expl
 */

namespace Expl\Lifecycle;

defined( 'ABSPATH' ) || exit;

/**
 * Deactivator class.
 */
final class Deactivator {

	/**
	 * Run once on plugin deactivation.
	 */
	public static function deactivate(): void {
		wp_clear_scheduled_hook( 'example-plugin_cron' );
		flush_rewrite_rules();
	}
}
