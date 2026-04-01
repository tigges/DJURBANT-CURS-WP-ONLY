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

A `docker-compose.yml` is provided for local development/testing with WordPress + MySQL (port 8080). To start the local environment:

```bash
# Start services (requires Docker)
sudo docker compose up -d

# Install WordPress (first time only)
sudo docker exec workspace-wordpress-1 bash -c "curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar && chmod +x wp-cli.phar && mv wp-cli.phar /usr/local/bin/wp"
sudo docker exec workspace-wordpress-1 wp core install --url="http://localhost:8080" --title="DJ UrbanT" --admin_user=admin --admin_password=admin --admin_email=admin@example.com --allow-root

# Install Kadence parent theme and activate child theme
sudo docker exec workspace-wordpress-1 wp theme install kadence --allow-root
sudo docker exec workspace-wordpress-1 wp theme activate djurbant-child-theme --allow-root

# Set pretty permalinks
sudo docker exec workspace-wordpress-1 wp rewrite structure '/%postname%/' --allow-root

# Create pages with custom templates
sudo docker exec workspace-wordpress-1 wp option update show_on_front page --allow-root
sudo docker exec workspace-wordpress-1 wp post create --post_type=page --post_title="DJ UrbanT" --post_name="home" --post_status=publish --page_template="page-templates/home.php" --allow-root
# Store the returned post ID and set as front page:
sudo docker exec workspace-wordpress-1 wp option update page_on_front <ID> --allow-root
sudo docker exec workspace-wordpress-1 wp post create --post_type=page --post_title="Video" --post_name="video" --post_status=publish --page_template="page-templates/video.php" --allow-root
sudo docker exec workspace-wordpress-1 wp post create --post_type=page --post_title="Audio" --post_name="audio" --post_status=publish --page_template="page-templates/audio.php" --allow-root
sudo docker exec workspace-wordpress-1 wp post create --post_type=page --post_title="Contact" --post_name="contact" --post_status=publish --page_template="page-templates/contact.php" --allow-root
```

**Local credentials**: `admin` / `admin`

#### Non-obvious local Docker caveats

- The `djurbant-child-theme/` directory must be copied into `wp-content/themes/` before starting Docker, since the docker-compose volume mounts `./wp-content/themes`. A symlink won't work because paths differ between host and container.
- The Kadence parent theme is **not** bundled in the repo. It must be installed via `wp theme install kadence` inside the container after first boot.
- Docker in this Cloud Agent VM requires `fuse-overlayfs` storage driver and `iptables-legacy` (configured via `/etc/docker/daemon.json` and `update-alternatives`).
- The `[fluentform id="1"]` shortcode on the Contact page shows as raw text locally since Fluent Forms plugin is not installed. This is expected.

### Current Site Structure (Pages)

| Page | Slug | Purpose |
|------|------|---------|
| DJ UrbanT | `/` (static homepage) | Hero + YouTube embed + stats strip + booking CTA |
| Video | `/video/` | Full YouTube video archive (5 embeds + "More on YouTube" CTA) |
| Audio | `/audio/` | Mixcloud audio archive (6 embeds + "More on Mixcloud" CTA) |
| Contact | `/contact/` | Booking info + WhatsApp button + reply SLA |
| Privacy Policy | (draft) | Standard WP privacy policy |

### Content Sources

The YouTube/Mixcloud content was sourced from `https://djurbant.com/media-data.json` which is auto-generated from the YouTube and Mixcloud APIs. When new sets are published, pages should be updated in wp-admin with fresh embed URLs.

### Additional CSS (Customizer)

The dark theme polish is stored in **Appearance → Customize → Additional CSS** and includes:
- Black backgrounds for body, header, content, footer
- Hidden default page title bars (`entry-hero-container-inner`, `h1.entry-title`)
- Rainbow gradient CTA button (`.djurbant-cta-rainbow`)
- Semi-transparent header (`rgba(3,5,9,0.95)`)

### Build Phases (for reference)

The site is being built to clone https://djurbant.com/ as a Gutenberg-managed WordPress site:

- **Phase 1** (done): Foundation — Kadence theme, dark global styles, plugins
- **Phase 2** (done): Pages — Homepage scaffold, Contact page with WhatsApp
- **Phase 3** (done): Dynamic content — YouTube/Mixcloud embeds on Video, Audio, and Homepage
- **Phase 4** (done): Polish — Rainbow gradient CTA, hidden page titles, dark header CSS
- **Phase 5** (pending): Go live — Push staging → live, domain setup, WPForms booking form creation
