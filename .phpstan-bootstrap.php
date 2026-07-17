<?php
/**
 * PHPStan-only constant stubs. Never loaded by WordPress itself —
 * loaded only via phpstan.neon.dist's bootstrapFiles.
 *
 * example-plugin.php defines EXPL_VERSION/EXPL_FILE/EXPL_DIR/EXPL_URL
 * at runtime, but EXPL_DIR and EXPL_URL are computed from WordPress
 * functions (plugin_dir_path()/plugin_dir_url()) whose return values
 * PHPStan cannot resolve across files. Without this stub, any src/
 * file referencing those two constants raises a false "Constant not
 * found" error the moment it's added — as it already does for
 * EXPL_URL in Settings_Page.php and Shortcodes.php. Defining literal
 * stand-ins here lets static analysis resolve all four constants
 * consistently, regardless of which ones a future task references.
 *
 * @package Expl
 */

defined( 'EXPL_VERSION' ) || define( 'EXPL_VERSION', '0.1.0' );
defined( 'EXPL_FILE' ) || define( 'EXPL_FILE', __FILE__ );
defined( 'EXPL_DIR' ) || define( 'EXPL_DIR', __DIR__ . '/' );
defined( 'EXPL_URL' ) || define( 'EXPL_URL', 'https://example.test/wp-content/plugins/example-plugin/' );
