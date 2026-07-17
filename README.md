# Oiko Plugin Boilerplate

Secure-by-default WordPress plugin scaffold. `main` branch is the
plain-WP baseline; the `woocommerce` branch adds HPOS/cart-blocks
compatibility on top.

## Use this repo

Clone directly, or via the CLI once published:

```
npx create-oiko-plugin@latest my-plugin --name "My Plugin"
```

## Local development

```
composer install
composer run lint    # PHPCS
composer run stan    # PHPStan level 6
composer run test    # PHPUnit
```

See `AGENTS.md` for the rules an AI coding agent should follow when
extending a plugin built on this boilerplate. See `SECURITY.md` and
`PERFORMANCE.md` for the full detail behind those rules.
