# Agent instructions for this plugin

This plugin is built on the Oiko boilerplate. Follow these rules for
every change you make.

## 1. Security — required on every entry point

| Entry point | Required check | Function |
|---|---|---|
| Form submit / admin-post | Capability, then nonce, then sanitize | `current_user_can()` -> `check_admin_referer()` -> `Expl\Support\Sanitizer::*()` |
| AJAX handler | Nonce + capability | `check_ajax_referer()` + `current_user_can()` |
| REST route | Explicit `permission_callback` | never `__return_true` on anything that mutates data |
| Any `$_POST`/`$_GET`/`$_REQUEST` read | Route through the sanitizer | `Expl\Support\Sanitizer::text()/::email()/::key()/::textarea()` |
| Any dynamic output | Escape at the point of output | `esc_html()` / `esc_attr()` / `esc_url()` |
| Any query with a variable | Always prepared | `$wpdb->prepare()` |

Full detail: `SECURITY.md`.

## 2. Performance — required defaults

1. `wp_enqueue_script(..., ['strategy' => 'defer'])` — never a bare enqueue.
2. Enqueue admin assets only on this plugin's own screen; enqueue frontend assets only where the shortcode/block is actually present.
3. Options over a few KB: `add_option( $key, $value, '', false )` — autoload off.
4. Transients always get a finite expiry — never none.
5. Anything non-instant (email, PDF generation, exports) goes through Action Scheduler if this plugin adds one — never blocks the request thread.

Full detail: `PERFORMANCE.md`.

## 3. File placement

New classes go under `src/<Area>/`, PSR-4 namespace root is `Expl\`. Don't add classes outside `src/`. The bootstrap file (`example-plugin.php`) never contains business logic — only the version gate, constants, and hook registration pointing into `src/`. Test code under `tests/` is excluded from the WPCS ruleset (see `phpcs.xml.dist`) — WPCS governs code that ships to WordPress, test code doesn't need to follow the same commenting/array-syntax conventions.

## 4. Before you're done

All of these must pass:

```
vendor/bin/phpcs
vendor/bin/phpstan analyse
vendor/bin/phpunit
```
