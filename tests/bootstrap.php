<?php
/**
 * PHPUnit bootstrap — wires Composer's autoloader and WP_Mock.
 *
 * @package Expl
 */

require_once dirname( __DIR__ ) . '/vendor/autoload.php';

WP_Mock::bootstrap();
