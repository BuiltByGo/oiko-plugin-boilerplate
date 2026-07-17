<?php
/**
 * Uninstall handler — removes every option this plugin created. Runs
 * standalone, without loading the plugin itself.
 *
 * @package Expl
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

delete_option( 'example-plugin_settings' );
wp_clear_scheduled_hook( 'example-plugin_cron' );
