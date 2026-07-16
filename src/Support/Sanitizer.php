<?php
/**
 * Single chokepoint for sanitizing raw request input, matched to type.
 *
 * @package Expl
 */

namespace Expl\Support;

defined( 'ABSPATH' ) || exit;

/**
 * Every $_POST/$_GET/$_REQUEST read in this plugin routes through here,
 * so a sanitize-function/data-type mismatch is a one-place-to-check
 * problem instead of one-per-handler.
 */
final class Sanitizer {

	/**
	 * Sanitize plain text input.
	 *
	 * @param mixed $value Raw input value.
	 * @return string Sanitized text.
	 */
	public static function text( $value ): string {
		return sanitize_text_field( wp_unslash( $value ) );
	}

	/**
	 * Sanitize textarea input.
	 *
	 * @param mixed $value Raw input value.
	 * @return string Sanitized textarea content.
	 */
	public static function textarea( $value ): string {
		return sanitize_textarea_field( wp_unslash( $value ) );
	}

	/**
	 * Sanitize email input.
	 *
	 * @param mixed $value Raw input value.
	 * @return string Sanitized email address.
	 */
	public static function email( $value ): string {
		return sanitize_email( wp_unslash( $value ) );
	}

	/**
	 * Sanitize key input.
	 *
	 * @param mixed $value Raw input value.
	 * @return string Sanitized key.
	 */
	public static function key( $value ): string {
		return sanitize_key( wp_unslash( $value ) );
	}
}
