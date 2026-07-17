# Security defaults

These are load-bearing, not suggestions — every plugin built on this
boilerplate ships with them from the first commit.

1. **Nonce + capability check on every action and REST/AJAX route.** Capability first, then nonce (`current_user_can()` before `check_admin_referer()`/`check_ajax_referer()`) — a valid nonce only proves the request is intentional, not that the user is allowed.
2. **Escape all output, sanitize all input.** Sanitization happens on the way in (`Expl\Support\Sanitizer`), escaping happens at the point of output, every time — even for values you believe are already safe.
3. **No unauthenticated AJAX/REST endpoints** unless the action is genuinely meant to be public (e.g. a newsletter signup). Every `permission_callback` is explicit; never `__return_true` on a route that mutates data.
4. **Prepared statements only.** `$wpdb->prepare()` on every query touching a variable, including custom tables you create yourself.
5. **Uploads:** MIME + extension allowlist, no PHP execution in the upload directory, randomized filenames, protected directory.
6. **Credentials:** encrypt anything stored, redact secrets from logs.
7. **`uninstall.php` always removes everything this plugin created** — options, tables, transients, cron events. See `uninstall.php` in this repo for the pattern.

WPCS's `WordPress.Security.ValidatedSanitizedInput` sniff cannot trace sanitization through static method calls (like `Sanitizer::text()`) — only bare global function names. This means every call site using `Sanitizer::*()` on raw `$_POST`/`$_GET` needs a scoped `// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized,WordPress.Security.ValidatedSanitizedInput.MissingUnslash` comment directly above it (see `src/Admin/Settings_Page.php`'s `save()` method for the exact pattern). This is expected and correct — not a suppressed real issue — because the underlying `Sanitizer` methods do genuinely sanitize; WPCS just can't see through the abstraction.
