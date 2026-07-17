# Performance defaults

1. **`strategy => 'defer'`** on every `wp_enqueue_script()` call (native since WP 6.3) — see `Settings_Page::enqueue()` for the pattern.
2. **Native Interactivity API** (`data-wp-*` directives) over a bundled JS framework for front-end interactivity — matches "always simple," ships near-zero JS weight.
3. **Action Scheduler** for anything non-instant (email dispatch, PDF generation, CSV export, audit-log writes) — never block the request thread. Only add the dependency if the plugin actually has non-instant work to do.
4. **Autoload discipline:** any option holding more than a few KB is registered with `add_option( $key, $value, '', false )` — see `Activator::default_settings()`. Autoloaded rows over ~800KB combined are what tips a site into the object-cache-rejection/502 loop.
5. **Transients always get a finite expiry** — a week or month, never none. An expiry-less transient silently autoloads on every request.
6. **Object-cache calls (`wp_cache_get`/`wp_cache_set`) must degrade correctly with no persistent cache present** — most free-tier hosts still don't run Redis/Memcached.
7. **No unconditional admin-ajax polling on every page load** (the `wc-cart-fragments` anti-pattern) — see `Shortcodes::maybe_enqueue()` for the conditional-load pattern.
8. **Custom tables get indexes on every column used in `WHERE`/`JOIN`/`ORDER BY` at creation time**, not retrofitted later.
