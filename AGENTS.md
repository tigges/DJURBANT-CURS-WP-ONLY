# AGENTS.md

## Cursor Cloud specific instructions

### Overview

This is a WordPress-only development project ("DJURBANT CURS WP ONLY") running via Docker Compose. The stack consists of:

- **WordPress** (latest) on port **8080**
- **MySQL 8.0** on port **3306**

### Starting the development environment

```bash
# Start Docker daemon (required in Cloud Agent VMs)
sudo dockerd &>/tmp/dockerd.log &
sleep 5
sudo chmod 666 /var/run/docker.sock

# Start services
docker compose up -d
```

Wait for MySQL health check to pass before accessing WordPress (~10–15 seconds).

### Accessing WordPress

- **Frontend**: http://localhost:8080/
- **Admin panel**: http://localhost:8080/wp-admin/
- **Admin credentials**: `admin` / `admin123`

### WP-CLI

WP-CLI is installed inside the WordPress container. Run commands with:

```bash
docker exec workspace-wordpress-1 wp <command> --allow-root
```

### Theme & Plugin Development

Custom themes and plugins are mounted from the repo:

- `./wp-content/themes/` → `/var/www/html/wp-content/themes/`
- `./wp-content/plugins/` → `/var/www/html/wp-content/plugins/`

Changes to files in these directories are reflected immediately in the running WordPress instance (no restart needed).

### Important caveats

- Docker daemon must be started manually in Cloud Agent VMs (it is not started automatically by the update script).
- The `fuse-overlayfs` storage driver and `iptables-legacy` are required for Docker-in-Docker in the Cloud Agent VM environment.
- WordPress data is stored in Docker volumes (`db_data`, `wp_data`). These persist across `docker compose down` / `up` cycles but not across VM rebuilds. After a VM rebuild, WordPress installation must be re-run via WP-CLI.
- There are no lint checks, automated tests, or build steps defined yet — this is a fresh WordPress project scaffold.
