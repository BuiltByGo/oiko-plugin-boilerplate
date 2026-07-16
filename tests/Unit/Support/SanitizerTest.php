<?php

namespace Expl\Tests\Support;

use Expl\Support\Sanitizer;
use WP_Mock;
use WP_Mock\Tools\TestCase;

class SanitizerTest extends TestCase {

	public function test_text_unslashes_then_sanitizes(): void {
		WP_Mock::userFunction( 'wp_unslash' )
			->once()
			->with( '<b>hi</b>' )
			->andReturn( '<b>hi</b>' );

		WP_Mock::userFunction( 'sanitize_text_field' )
			->once()
			->with( '<b>hi</b>' )
			->andReturn( 'hi' );

		$this->assertSame( 'hi', Sanitizer::text( '<b>hi</b>' ) );
	}

	public function test_email_unslashes_then_sanitizes(): void {
		WP_Mock::userFunction( 'wp_unslash' )
			->once()
			->with( 'a@b.com ' )
			->andReturn( 'a@b.com ' );

		WP_Mock::userFunction( 'sanitize_email' )
			->once()
			->with( 'a@b.com ' )
			->andReturn( 'a@b.com' );

		$this->assertSame( 'a@b.com', Sanitizer::email( 'a@b.com ' ) );
	}

	public function test_key_unslashes_then_sanitizes(): void {
		WP_Mock::userFunction( 'wp_unslash' )
			->once()
			->with( 'My Key' )
			->andReturn( 'My Key' );

		WP_Mock::userFunction( 'sanitize_key' )
			->once()
			->with( 'My Key' )
			->andReturn( 'my_key' );

		$this->assertSame( 'my_key', Sanitizer::key( 'My Key' ) );
	}

	public function test_textarea_unslashes_then_sanitizes(): void {
		WP_Mock::userFunction( 'wp_unslash' )
			->once()
			->with( "line one\nline two" )
			->andReturn( "line one\nline two" );

		WP_Mock::userFunction( 'sanitize_textarea_field' )
			->once()
			->with( "line one\nline two" )
			->andReturn( "line one\nline two" );

		$this->assertSame( "line one\nline two", Sanitizer::textarea( "line one\nline two" ) );
	}
}
