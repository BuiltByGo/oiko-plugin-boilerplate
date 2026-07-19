<?php
/**
 * Shared render helpers for every Oiko plugin's admin screens: the
 * `.oiko-admin` token-scope wrapper, card sections, and form fields.
 * This class only controls how a field/card/toggle is drawn — each
 * plugin's own settings-page class still owns its field list, values,
 * and save logic.
 *
 * @package Expl
 */

namespace Expl\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * Ui class.
 */
final class Ui {

	/**
	 * Open the wp-admin `.wrap`, the `.oiko-admin` token scope, and
	 * render the header (logo mark + page title). Pair with footer().
	 *
	 * @param string $title Plugin display name.
	 * @return void
	 */
	public static function header( string $title ): void {
		?>
		<div class="wrap">
		<?php
		self::section_open();
		?>
			<div class="oiko-header">
				<?php self::render_mark(); ?>
				<h1><?php echo esc_html( $title ); ?></h1>
			</div>
		<?php
	}

	/**
	 * Close the `.oiko-admin` token scope and the `.wrap` opened by header().
	 *
	 * @return void
	 */
	public static function footer(): void {
		self::section_close();
		?>
		</div>
		<?php
	}

	/**
	 * Open the bare `.oiko-admin` token-scope wrapper, without a page
	 * title or `.wrap` — for embedding Oiko cards inside a page
	 * WordPress core (or another plugin) already owns, e.g. profile.php.
	 *
	 * @return void
	 */
	public static function section_open(): void {
		?>
		<div class="oiko-admin">
		<?php
	}

	/**
	 * Close the wrapper opened by section_open().
	 *
	 * @return void
	 */
	public static function section_close(): void {
		?>
		</div>
		<?php
	}

	/**
	 * Open a card section with a heading.
	 *
	 * @param string $heading Section heading text.
	 * @return void
	 */
	public static function card_open( string $heading ): void {
		?>
		<div class="oiko-card">
			<h2><?php echo esc_html( $heading ); ?></h2>
		<?php
	}

	/**
	 * Close a card section opened by card_open().
	 *
	 * @return void
	 */
	public static function card_close(): void {
		?>
		</div>
		<?php
	}

	/**
	 * Render a labeled text/password field. Password fields default to
	 * `autocomplete="off"` unless a different value is explicitly given.
	 *
	 * @param string $name         Field name attribute.
	 * @param string $label        Field label text.
	 * @param string $value        Current field value.
	 * @param string $type         Input type, e.g. 'text' or 'password'.
	 * @param string $autocomplete Explicit autocomplete value; defaults to 'off' for password fields, unset otherwise.
	 * @return void
	 */
	public static function text_field( string $name, string $label, string $value, string $type = 'text', string $autocomplete = '' ): void {
		if ( '' === $autocomplete && 'password' === $type ) {
			$autocomplete = 'off';
		}
		?>
		<div class="oiko-field">
			<label for="<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $label ); ?></label>
			<input
				type="<?php echo esc_attr( $type ); ?>"
				name="<?php echo esc_attr( $name ); ?>"
				id="<?php echo esc_attr( $name ); ?>"
				value="<?php echo esc_attr( $value ); ?>"
				<?php echo '' !== $autocomplete ? 'autocomplete="' . esc_attr( $autocomplete ) . '"' : ''; ?>
			/>
		</div>
		<?php
	}

	/**
	 * Render a CSS-only toggle switch bound to a checkbox field. No JS —
	 * a real `<input type="checkbox">` behind a styled `<span>` track,
	 * wrapped in a `<label>` so clicking anywhere toggles it natively.
	 *
	 * @param string $name    Field name attribute.
	 * @param string $label   Field label text.
	 * @param bool   $checked Whether the toggle is currently on.
	 * @param string $value   Checkbox value attribute — defaults to '1' for a
	 *                        simple on/off field; pass a distinct value (e.g.
	 *                        a role key) when this toggle is one of several
	 *                        sharing the same array-notation `$name`.
	 * @return void
	 */
	public static function toggle_field( string $name, string $label, bool $checked, string $value = '1' ): void {
		?>
		<label class="oiko-toggle">
			<input type="checkbox" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" <?php checked( $checked ); ?> />
			<span class="oiko-toggle-track"></span><?php echo esc_html( $label ); ?>
		</label>
		<?php
	}

	/**
	 * Render the primary submit button.
	 *
	 * @param string $label Button label text.
	 * @return void
	 */
	public static function primary_button( string $label ): void {
		?>
		<p>
			<button type="submit" class="button oiko-btn-primary"><?php echo esc_html( $label ); ?></button>
		</p>
		<?php
	}

	/**
	 * Render the inline SVG logo mark from its static asset file.
	 *
	 * @return void
	 */
	private static function render_mark(): void {
		$svg_path = EXPL_DIR . 'assets/admin/images/oiko-mark.svg';

		if ( ! file_exists( $svg_path ) ) {
			return;
		}

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- static, version-controlled SVG asset shipped with the plugin itself (fixed path, not user input), not a remote or user-supplied file.
		$svg = file_get_contents( $svg_path );

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the SVG file's own contents, not user input; nothing here is dynamic.
		echo $svg;
	}
}
