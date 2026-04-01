#!/usr/bin/env bash
#
# wp-setup.sh — idempotent first-boot provisioning for the local Docker environment.
# Run from the repo root:  ./wp-setup.sh
# Or via the Makefile:      make setup
#
set -euo pipefail

CONTAINER="${COMPOSE_PROJECT_NAME:-workspace}-wordpress-1"
WP_URL="${WP_URL:-http://localhost:8080}"
WP_ADMIN_USER="${WP_ADMIN_USER:-admin}"
WP_ADMIN_PASSWORD="${WP_ADMIN_PASSWORD:-admin}"
WP_ADMIN_EMAIL="${WP_ADMIN_EMAIL:-admin@example.com}"

wpcli() {
  sudo docker exec "$CONTAINER" wp "$@" --allow-root
}

echo "==> Waiting for WordPress container to be healthy…"
for i in $(seq 1 60); do
  if sudo docker exec "$CONTAINER" curl -sf http://localhost:80/ >/dev/null 2>&1; then
    break
  fi
  printf "."
  sleep 2
done
echo ""

# ── Install WP-CLI if missing ──────────────────────────────────────
if ! sudo docker exec "$CONTAINER" which wp >/dev/null 2>&1; then
  echo "==> Installing WP-CLI…"
  sudo docker exec "$CONTAINER" bash -c \
    "curl -sSL https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar -o /usr/local/bin/wp && chmod +x /usr/local/bin/wp"
fi

# ── Core install (idempotent — skips if already installed) ─────────
if ! wpcli core is-installed 2>/dev/null; then
  echo "==> Running WordPress core install…"
  wpcli core install \
    --url="$WP_URL" \
    --title="DJ UrbanT" \
    --admin_user="$WP_ADMIN_USER" \
    --admin_password="$WP_ADMIN_PASSWORD" \
    --admin_email="$WP_ADMIN_EMAIL"
else
  echo "==> WordPress already installed — skipping core install."
fi

# ── Install & activate Kadence parent theme ────────────────────────
if ! wpcli theme is-installed kadence 2>/dev/null; then
  echo "==> Installing Kadence parent theme…"
  wpcli theme install kadence
fi

ACTIVE_THEME=$(wpcli theme list --status=active --field=name 2>/dev/null)
if [ "$ACTIVE_THEME" != "djurbant-child-theme" ]; then
  echo "==> Activating djurbant-child-theme…"
  wpcli theme activate djurbant-child-theme
else
  echo "==> djurbant-child-theme already active."
fi

# ── Pretty permalinks ──────────────────────────────────────────────
wpcli rewrite structure '/%postname%/' 2>/dev/null || true

# ── Create pages (idempotent — only if they don't exist) ──────────
create_page() {
  local title="$1" slug="$2" template="$3"
  if wpcli post list --post_type=page --name="$slug" --field=ID 2>/dev/null | grep -q .; then
    echo "    Page '$slug' already exists — skipping."
    return
  fi
  local page_id
  page_id=$(wpcli post create --post_type=page \
    --post_title="$title" --post_name="$slug" \
    --post_status=publish --page_template="$template" \
    --porcelain)
  echo "    Created page '$slug' (ID $page_id)"
  echo "$page_id"
}

echo "==> Ensuring pages exist…"
wpcli option update show_on_front page 2>/dev/null || true

HOME_ID=$(create_page "DJ UrbanT" "home" "page-templates/home.php")
if [ -n "$HOME_ID" ]; then
  wpcli option update page_on_front "$HOME_ID"
fi

# If home page existed already, make sure it's set as front page
if [ -z "$HOME_ID" ]; then
  EXISTING_HOME=$(wpcli post list --post_type=page --name=home --field=ID 2>/dev/null)
  if [ -n "$EXISTING_HOME" ]; then
    wpcli option update page_on_front "$EXISTING_HOME" 2>/dev/null || true
  fi
fi

create_page "Video"   "video"   "page-templates/video.php"
create_page "Audio"   "audio"   "page-templates/audio.php"
create_page "Contact" "contact" "page-templates/contact.php"

echo ""
echo "✔ Local WordPress ready at $WP_URL"
echo "  Admin: $WP_URL/wp-admin/  ($WP_ADMIN_USER / $WP_ADMIN_PASSWORD)"
