# AGENTS.md

## Cursor Cloud specific instructions

### Overview

This is a WordPress project ("DJ UrbanT") hosted on **Cloudways**. The primary development workflow is **no-code via the WordPress block editor (Gutenberg)** on the Cloudways staging site. This GitHub repo serves as version control backup and is not the primary development environment.

### Staging Site

- **Frontend**: https://wordpress-1344959-6315794.cloudwaysapps.com/
- **Admin panel**: https://wordpress-1344959-6315794.cloudwaysapps.com/wp-admin/
- **Credentials**: Stored as secrets `WP_ADMIN_USER` and `WP_ADMIN_PASSWORD`

### Installed Stack

| Component | Version/Details |
|-----------|-----------------|
| **Theme** | Kadence (free, block-based) |
| **Kadence Blocks** | Advanced Gutenberg blocks plugin |
| **WPForms Lite** | Contact/booking form with in-WP entries inbox |
| **Breeze** | Cloudways caching plugin (pre-installed) |
| **Object Cache Pro** | Redis object cache (pre-installed by Cloudways) |

### Authenticating with the WordPress REST API

WordPress on Cloudways does **not** have Basic Auth enabled. To make authenticated REST API calls:

1. Log in via `wp-login.php` using curl with cookie jar (`-c` / `-b` flags)
2. Obtain a REST nonce from `admin-ajax.php?action=rest-nonce`
3. Pass the nonce via `X-WP-Nonce` header on subsequent API calls

Example:
```bash
curl -s -L -c /tmp/wp_cookies.txt -d "log=${WP_ADMIN_USER}&pwd=${WP_ADMIN_PASSWORD}&wp-submit=Log+In&redirect_to=%2Fwp-admin%2F&testcookie=1" -o /dev/null "https://wordpress-1344959-6315794.cloudwaysapps.com/wp-login.php"
WP_NONCE=$(curl -s -b /tmp/wp_cookies.txt "https://wordpress-1344959-6315794.cloudwaysapps.com/wp-admin/admin-ajax.php?action=rest-nonce")
curl -s -b /tmp/wp_cookies.txt -H "X-WP-Nonce: ${WP_NONCE}" "https://wordpress-1344959-6315794.cloudwaysapps.com/wp-json/wp/v2/pages"
```

### Important Caveats

- **Breeze caching**: After making changes, you may need to flush the Breeze cache from wp-admin (Settings → Breeze → Purge All Cache) for changes to appear on the frontend.
- **Customizer save via API**: The `customize_save` AJAX action may return a critical error on this Cloudways setup. Use the browser-based Customizer (via computerUse subagent) for theme customizer changes like Additional CSS, header/footer settings, and global colors.
- **Page creation works well via REST API**: Creating/updating pages and posts via the REST API with Gutenberg block markup works reliably. Use Python scripts to avoid shell quoting issues with complex block HTML.
- **Additional CSS is set via the Customizer**: The dark theme overrides (black backgrounds, light text for header/footer) are stored in the WordPress Customizer → Additional CSS, not in theme files.

### Local Docker Environment

A `docker-compose.yml` is provided for local development/testing with WordPress + MySQL. See the `docker-compose.yml` file for details. This is secondary to the Cloudways staging site.

### Current Site Structure (Pages)

| Page | Slug | Purpose |
|------|------|---------|
| DJ UrbanT | `/` (static homepage) | Hero + stats + booking CTA |
| Contact | `/contact/` | Booking form + WhatsApp button |
| Privacy Policy | (draft) | Standard WP privacy policy |

### Build Phases (for reference)

The site is being built to clone https://djurbant.com/ as a Gutenberg-managed WordPress site:

- **Phase 1** (done): Foundation — Kadence theme, dark global styles, plugins
- **Phase 2** (done): Pages — Homepage scaffold, Contact page with WhatsApp
- **Phase 3** (pending): Dynamic content — YouTube/Mixcloud embeds, carousel
- **Phase 4** (pending): Polish — Custom CSS gradients, animations, responsive tweaks
- **Phase 5** (pending): Go live — Push staging → live, domain setup
