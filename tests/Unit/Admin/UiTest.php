<?php

namespace Expl\Tests\Admin;

use Expl\Admin\Ui;
use WP_Mock;
use WP_Mock\Tools\TestCase;

class UiTest extends TestCase {

	public function setUp(): void {
		parent::setUp();

		if ( ! defined( 'EXPL_DIR' ) ) {
			define( 'EXPL_DIR', dirname( __DIR__, 3 ) . '/' );
		}

		WP_Mock::userFunction( 'esc_html' )->andReturnUsing(
			function ( $text ) {
				return htmlspecialchars( $text, ENT_QUOTES, 'UTF-8' );
			}
		);
		WP_Mock::userFunction( 'esc_attr' )->andReturnUsing(
			function ( $text ) {
				return htmlspecialchars( $text, ENT_QUOTES, 'UTF-8' );
			}
		);
	}

	public function test_header_opens_the_wrap_and_token_scope_and_renders_the_title(): void {
		ob_start();
		Ui::header( 'Example Plugin' );
		$html = ob_get_clean();

		$this->assertStringContainsString( '<div class="wrap">', $html );
		$this->assertStringContainsString( '<div class="oiko-admin">', $html );
		$this->assertStringContainsString( '<div class="oiko-header">', $html );
		$this->assertStringContainsString( '<h1>Example Plugin</h1>', $html );
		$this->assertStringContainsString( 'class="oiko-mark"', $html );
	}

	public function test_header_escapes_the_title(): void {
		ob_start();
		Ui::header( '<script>alert(1)</script>' );
		$html = ob_get_clean();

		$this->assertStringNotContainsString( '<script>alert(1)</script>', $html );
	}

	public function test_footer_closes_both_wrappers_opened_by_header(): void {
		ob_start();
		Ui::footer();
		$html = ob_get_clean();

		$this->assertSame( 2, substr_count( $html, '</div>' ) );
	}

	public function test_section_open_and_close_render_only_the_bare_token_scope(): void {
		ob_start();
		Ui::section_open();
		$open = ob_get_clean();

		ob_start();
		Ui::section_close();
		$close = ob_get_clean();

		$this->assertStringContainsString( '<div class="oiko-admin">', $open );
		$this->assertStringNotContainsString( '<h1>', $open );
		$this->assertStringContainsString( '</div>', $close );
	}

	public function test_card_open_renders_the_card_wrapper_and_heading(): void {
		ob_start();
		Ui::card_open( 'Login CAPTCHA' );
		$html = ob_get_clean();

		$this->assertStringContainsString( 'class="oiko-card"', $html );
		$this->assertStringContainsString( '<h2>Login CAPTCHA</h2>', $html );
	}

	public function test_card_close_renders_the_closing_div(): void {
		ob_start();
		Ui::card_close();
		$html = ob_get_clean();

		$this->assertStringContainsString( '</div>', $html );
	}

	public function test_text_field_renders_label_and_input_with_the_given_type_and_value(): void {
		ob_start();
		Ui::text_field( 'turnstile_site_key', 'Turnstile site key', 'abc123', 'text' );
		$html = ob_get_clean();

		$this->assertStringContainsString( 'class="oiko-field"', $html );
		$this->assertStringContainsString( 'for="turnstile_site_key"', $html );
		$this->assertStringContainsString( '>Turnstile site key</label>', $html );
		$this->assertStringContainsString( 'type="text"', $html );
		$this->assertStringContainsString( 'name="turnstile_site_key"', $html );
		$this->assertStringContainsString( 'value="abc123"', $html );
	}

	public function test_text_field_defaults_password_fields_to_autocomplete_off(): void {
		ob_start();
		Ui::text_field( 'turnstile_secret_key', 'Turnstile secret key', '', 'password' );
		$html = ob_get_clean();

		$this->assertStringContainsString( 'type="password"', $html );
		$this->assertStringContainsString( 'autocomplete="off"', $html );
	}

	public function test_text_field_honors_an_explicit_autocomplete_override(): void {
		ob_start();
		Ui::text_field( 'oiko_guard_2fa_code', 'Code', '', 'text', 'one-time-code' );
		$html = ob_get_clean();

		$this->assertStringContainsString( 'autocomplete="one-time-code"', $html );
	}

	public function test_toggle_field_renders_a_checked_toggle_when_true(): void {
		WP_Mock::userFunction( 'checked' )->andReturnUsing(
			function ( $checked, $current = true, $echo = true ) {
				$result = ( (string) $checked === (string) $current ) ? ' checked="checked"' : '';
				if ( $echo ) {
					echo $result;
				}
				return $result;
			}
		);

		ob_start();
		Ui::toggle_field( 'enabled', 'Enabled', true );
		$html = ob_get_clean();

		$this->assertStringContainsString( 'class="oiko-toggle"', $html );
		$this->assertStringContainsString( 'type="checkbox"', $html );
		$this->assertStringContainsString( 'name="enabled"', $html );
		$this->assertStringContainsString( 'checked="checked"', $html );
		$this->assertStringContainsString( 'class="oiko-toggle-track"', $html );
		$this->assertStringContainsString( '>Enabled', $html );
		$this->assertStringContainsString( 'value="1"', $html );
	}

	public function test_toggle_field_renders_unchecked_when_false(): void {
		WP_Mock::userFunction( 'checked' )->andReturnUsing(
			function ( $checked, $current = true, $echo = true ) {
				$result = ( (string) $checked === (string) $current ) ? ' checked="checked"' : '';
				if ( $echo ) {
					echo $result;
				}
				return $result;
			}
		);

		ob_start();
		Ui::toggle_field( 'enabled', 'Enabled', false );
		$html = ob_get_clean();

		$this->assertStringNotContainsString( 'checked="checked"', $html );
	}

	public function test_toggle_field_uses_a_custom_value_when_given(): void {
		WP_Mock::userFunction( 'checked' )->andReturnUsing(
			function ( $checked, $current = true, $echo = true ) {
				$result = ( (string) $checked === (string) $current ) ? ' checked="checked"' : '';
				if ( $echo ) {
					echo $result;
				}
				return $result;
			}
		);

		ob_start();
		Ui::toggle_field( 'alert_roles[]', 'Administrator', true, 'administrator' );
		$html = ob_get_clean();

		$this->assertStringContainsString( 'name="alert_roles[]"', $html );
		$this->assertStringContainsString( 'value="administrator"', $html );
	}

	public function test_primary_button_renders_a_submit_button_with_the_given_label(): void {
		ob_start();
		Ui::primary_button( 'Save Changes' );
		$html = ob_get_clean();

		$this->assertStringContainsString( 'type="submit"', $html );
		$this->assertStringContainsString( 'class="button oiko-btn-primary"', $html );
		$this->assertStringContainsString( '>Save Changes<', $html );
	}
}
