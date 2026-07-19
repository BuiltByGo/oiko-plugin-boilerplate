<?php
/**
 * Plugin settings screen under Settings -> Example Plugin.
 *
 * @package Expl
 */

namespace Expl\Admin;

use Expl\Support\Sanitizer;

defined( 'ABSPATH' ) || exit;

/**
 * Settings_Page class.
 *
 * Demonstrates the house pattern for an admin-facing settings screen:
 * capability check, then nonce check, then sanitize — in that order —
 * plus a deferred, screen-scoped script enqueue.
 */
final class Settings_Page {

	/**
	 * Wire up the menu, the enqueue, and the admin-post save handler.
	 */
	public static function register(): void {
		add_action( 'admin_menu', array( self::class, 'add_menu' ) );
		add_action( 'admin_enqueue_scripts', array( self::class, 'enqueue' ) );
		add_action( 'admin_post_example_plugin_save_settings', array( self::class, 'save' ) );
	}

	/**
	 * Register the settings screen under Settings.
	 */
	public static function add_menu(): void {
		add_options_page(
			__( 'Example Plugin', 'example-plugin' ),
			__( 'Example Plugin', 'example-plugin' ),
			'manage_options',
			'example-plugin',
			array( self::class, 'render' )
		);
	}

	/**
	 * Loaded only on this plugin's own settings screen, deferred —
	 * never a global admin-wide enqueue.
	 *
	 * @param string $hook The current admin screen hook suffix.
	 */
	public static function enqueue( string $hook ): void {
		if ( 'settings_page_example-plugin' !== $hook ) {
			return;
		}

		wp_enqueue_style(
			'example-plugin-admin',
			EXPL_URL . 'assets/admin/css/oiko-admin.css',
			array(),
			EXPL_VERSION
		);

		wp_enqueue_script(
			'example-plugin-admin',
			EXPL_URL . 'assets/admin/js/settings.js',
			array(),
			EXPL_VERSION,
			array(
				'strategy'  => 'defer',
				'in_footer' => true,
			)
		);
	}

	/**
	 * Render the settings form.
	 */
	public static function render(): void {
		$settings = get_option( 'example-plugin_settings', array( 'enabled' => true ) );

		Ui::header( __( 'Example Plugin', 'example-plugin' ) );
		?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="example_plugin_save_settings" />
			<?php wp_nonce_field( 'example-plugin_save_settings' ); ?>
			<?php Ui::card_open( __( 'General', 'example-plugin' ) ); ?>
			<?php Ui::toggle_field( 'enabled', __( 'Enabled', 'example-plugin' ), ! empty( $settings['enabled'] ) ); ?>
			<?php Ui::card_close(); ?>
			<?php Ui::primary_button( __( 'Save Changes', 'example-plugin' ) ); ?>
		</form>
		<?php
		Ui::footer();
	}

	/**
	 * Capability check, then nonce, then sanitize — in that order.
	 */
	public static function save(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to do this.', 'example-plugin' ), '', array( 'response' => 403 ) );
		}

		check_admin_referer( 'example-plugin_save_settings' );

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash -- Sanitizer::text() unslashes then sanitizes; WPCS can't trace through a static method call.
		$enabled = isset( $_POST['enabled'] ) ? '1' === Sanitizer::text( $_POST['enabled'] ) : false;

		update_option( 'example-plugin_settings', array( 'enabled' => $enabled ), false );

		wp_safe_redirect( add_query_arg( 'updated', 'true', wp_get_referer() ) );
		exit;
	}
}
