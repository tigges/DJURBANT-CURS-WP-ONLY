<?php
/**
 * Template Name: DJ UrbanT Admin
 *
 * Renders the original djurbant.com admin dashboard inside WordPress.
 * Uses WordPress authentication instead of Google auth gate.
 */
defined('ABSPATH') || exit;

if (!is_user_logged_in()) {
    wp_redirect(wp_login_url(get_permalink()));
    exit;
}

$theme_uri = get_stylesheet_directory_uri();
$current_user = wp_get_current_user();
$user_email = $current_user->user_email;
$user_initials = strtoupper(substr($current_user->first_name ?: $current_user->display_name, 0, 1) . substr($current_user->last_name ?: '', 0, 1));
if (strlen($user_initials) < 2) $user_initials = strtoupper(substr($user_email, 0, 2));
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DJ UrbanT | Admin</title>
    <?php wp_head(); ?>
    <link rel="stylesheet" href="<?php echo $theme_uri; ?>/admin.css" />
</head>
<body data-page="admin" <?php body_class(); ?>>
<?php wp_body_open(); ?>

    <div id="admin-app" class="admin-app">
<?php
$sc_file = get_stylesheet_directory() . '/site-content.json';
$sc_data = file_exists($sc_file) ? json_decode(file_get_contents($sc_file), true) : [];
$socials = $sc_data['global']['socialLinks'] ?? [];
$content_fields = [
    ['path' => 'pages.home.hero.tagline', 'label' => 'Hero tagline'],
    ['path' => 'pages.home.bestOf.title', 'label' => '"Best of Artist" heading'],
    ['path' => 'pages.home.bookingBand.title', 'label' => 'Booking band title'],
    ['path' => 'pages.home.bookingBand.buttonLabel', 'label' => 'Booking button label'],
    ['path' => 'pages.contact.title', 'label' => 'Contact page title'],
    ['path' => 'pages.contact.introText', 'label' => 'Contact intro text', 'type' => 'textarea'],
    ['path' => 'global.meta.replySlaText', 'label' => 'Reply SLA text'],
    ['path' => 'global.ctaDefaults.bookLabel', 'label' => 'Header "Book" button label'],
];
function djurbant_get_nested($arr, $path) {
    $keys = explode('.', $path);
    $val = $arr;
    foreach ($keys as $k) { $val = $val[$k] ?? null; if ($val === null) return ''; }
    return is_string($val) ? $val : '';
}
?>
      <header class="admin-topbar">
        <div class="admin-topbar-left">
          <button id="sidebar-toggle-btn" class="admin-icon-btn mobile-only" type="button" aria-label="Open menu">☰</button>
          <a class="admin-brand" href="<?php echo home_url('/admin/'); ?>" aria-label="Admin home">
            <img src="<?php echo $theme_uri; ?>/assets/images/UT_TITLE_SVG.svg" alt="" />
            <span class="admin-brand-wordmark">DJ URBANT</span>
          </a>
          <span class="admin-divider" aria-hidden="true"></span>
          <span class="admin-topbar-label">ADMIN</span>
          <button id="settings-toggle-btn" class="admin-icon-btn" type="button" aria-label="Open settings">⚙</button>
        </div>
        <div class="admin-topbar-right">
          <a class="admin-home-link" href="<?php echo home_url('/'); ?>">← Home</a>
          <a class="admin-home-link" href="<?php echo admin_url(); ?>" target="_blank" rel="noopener noreferrer">WP Admin</a>
          <span class="admin-divider" aria-hidden="true"></span>
          <div class="admin-user-block">
            <span class="admin-avatar"><?php echo esc_html($user_initials); ?></span>
            <span id="admin-user-email"><?php echo esc_html($user_email); ?></span>
          </div>
        </div>
      </header>

      <div id="sidebar-scrim" class="sidebar-scrim"></div>

      <div class="admin-shell">
        <aside id="admin-sidebar" class="admin-sidebar">
          <nav aria-label="Admin navigation">
            <button class="admin-nav-item is-active" type="button" data-view="home">
              <span class="admin-nav-icon">⌂</span><span>Admin Home</span>
            </button>
            <p class="admin-nav-group-label">Site</p>
            <button class="admin-nav-item" type="button" data-view="dashboard">
              <span class="admin-nav-icon">▦</span><span>Dashboard</span>
            </button>
            <button class="admin-nav-item" type="button" data-view="analytics">
              <span class="admin-nav-icon">▤</span><span>Analytics</span>
            </button>
            <button class="admin-nav-item" type="button" data-view="settings">
              <span class="admin-nav-icon">⚙</span><span>Settings</span>
            </button>
            <p class="admin-nav-group-label">Content</p>
            <button class="admin-nav-item" type="button" data-view="pages">
              <span class="admin-nav-icon">▣</span><span>Pages</span>
            </button>
            <button class="admin-nav-item" type="button" data-view="uploads">
              <span class="admin-nav-icon">⇪</span><span>Uploads</span>
            </button>
            <button class="admin-nav-item" type="button" data-view="socials">
              <span class="admin-nav-icon">⎔</span><span>Socials</span>
            </button>
            <button class="admin-nav-item" type="button" data-view="youtube">
              <span class="admin-nav-icon">▶</span><span>YouTube</span>
              <span id="youtube-live-badge" class="admin-badge admin-badge-amber">Live</span>
            </button>
            <button class="admin-nav-item" type="button" data-view="bookings">
              <span class="admin-nav-icon">⌕</span><span>Bookings</span>
              <span id="bookings-unread-badge" class="admin-badge admin-badge-danger">0</span>
            </button>
            <p class="admin-nav-group-label">Reference</p>
            <button class="admin-nav-item" type="button" data-view="management">
              <span class="admin-nav-icon">☰</span><span>Management</span>
            </button>
            <button class="admin-nav-item" type="button" data-view="sitemap">
              <span class="admin-nav-icon">◫</span><span>Site Map</span>
            </button>
          </nav>
        </aside>

        <main class="admin-main">
          <section class="admin-view" data-view-panel="home">
            <div class="admin-view-head">
              <h1>Admin Home</h1>
              <p>Fast overview and quick actions for site and content operations.</p>
            </div>
            <section class="admin-stats-grid">
              <article class="admin-card admin-stat-card">
                <p class="admin-stat-label">Site visits today</p>
                <p class="admin-stat-value">142</p>
                <p class="admin-stat-meta admin-stat-up">↑ 18% vs yesterday</p>
              </article>
              <article class="admin-card admin-stat-card">
                <p class="admin-stat-label">Site visits (7d)</p>
                <p class="admin-stat-value">892</p>
                <p class="admin-stat-meta admin-stat-up">↑ 12% vs last week</p>
              </article>
              <article class="admin-card admin-stat-card">
                <p class="admin-stat-label">Social stats</p>
                <div class="admin-social-mini-grid">
                  <p><span>▶</span> 4.8k</p><p><span>☁</span> 2.1k</p><p><span>◎</span> 7.4k</p><p><span>◉</span> 5.9k</p>
                </div>
              </article>
              <article id="unread-stat-card" class="admin-card admin-stat-card">
                <p class="admin-stat-label">Unread messages</p>
                <p id="unread-stat-value" class="admin-stat-value">0</p>
                <p class="admin-stat-meta">booking form</p>
              </article>
            </section>
            <section class="admin-quick-grid">
              <button class="admin-card admin-quick-card admin-quick-live" type="button" data-open-view="dashboard">
                <div class="admin-quick-head"><span class="admin-quick-icon">▶</span><span id="home-youtube-badge" class="admin-badge admin-badge-amber">● Live now</span></div>
                <h2>YouTube</h2><p>Channel status and latest stream controls.</p><span class="admin-quick-link">View dashboard →</span>
              </button>
              <button class="admin-card admin-quick-card admin-quick-alert" type="button" data-open-view="bookings">
                <div class="admin-quick-head"><span class="admin-quick-icon">⌕</span><span id="home-bookings-badge" class="admin-badge admin-badge-danger">0</span></div>
                <h2>Booking requests</h2><p id="home-bookings-meta">No unread venue requests.</p><span class="admin-quick-link">Open inbox →</span>
              </button>
              <button class="admin-card admin-quick-card" type="button" data-open-view="analytics">
                <div class="admin-quick-head"><span class="admin-quick-icon">▤</span></div>
                <h2>Site stats</h2><p>7-day trend, traffic and engagement.</p><span class="admin-quick-link">Open analytics →</span>
              </button>
              <button class="admin-card admin-quick-card" type="button" data-open-view="pages">
                <div class="admin-quick-head"><span class="admin-quick-icon">▣</span></div>
                <h2>Pages</h2><p><?php echo wp_count_posts('page')->publish; ?> pages published.</p><span class="admin-quick-link">Manage pages →</span>
              </button>
              <button class="admin-card admin-quick-card" type="button" data-open-view="uploads">
                <div class="admin-quick-head"><span class="admin-quick-icon">⇪</span></div>
                <h2>Uploads</h2><p>Last upload: Set #434 • Mar 15, 2026.</p><span class="admin-quick-link">Open uploads →</span>
              </button>
              <button class="admin-card admin-quick-card" type="button" data-open-view="socials">
                <div class="admin-quick-head"><span class="admin-quick-icon">⎔</span></div>
                <h2>Social links</h2><p>6 platforms active in navigation.</p><span class="admin-quick-link">Edit socials →</span>
              </button>
            </section>
            <section id="home-activity-section" class="admin-card admin-block">
              <div class="admin-block-head"><h2>Recent activity</h2></div>
              <ul id="recent-activity-list" class="admin-activity-list"></ul>
            </section>
            <section id="home-socials-section" class="admin-card admin-block">
              <div class="admin-block-head"><h2>Social links management</h2>
                <button id="add-platform-btn" class="admin-btn admin-btn-outline" type="button">Add platform</button>
              </div>
              <div class="admin-table-wrap">
                <table class="admin-table"><thead><tr><th>Platform</th><th>URL</th><th>In nav</th><th>Order</th></tr></thead>
                <tbody id="social-links-table-body"></tbody></table>
              </div>
              <form id="add-platform-form" class="admin-inline-form" hidden>
                <input id="add-platform-name" type="text" placeholder="Platform name" />
                <input id="add-platform-url" type="url" placeholder="https://example.com" />
                <label class="admin-checkbox"><input id="add-platform-in-nav" type="checkbox" checked /><span>In nav</span></label>
                <button class="admin-btn admin-btn-solid" type="submit">Add</button>
                <button id="cancel-platform-btn" class="admin-btn admin-btn-outline" type="button">Cancel</button>
              </form>
            </section>
            <section class="admin-card admin-customize" aria-label="Customise this page">
              <div class="admin-customize-left">✎ Customise this page</div>
              <div class="admin-customize-chips">
                <button class="admin-chip is-on" type="button" data-home-chip="activity">Activity</button>
                <button class="admin-chip is-on" type="button" data-home-chip="socials">Socials</button>
              </div>
              <button id="home-customize-reset" class="admin-reset-btn" type="button">Reset</button>
            </section>
          </section>

          <section class="admin-view" data-view-panel="dashboard" hidden>
            <div class="admin-view-head"><h1>Dashboard</h1><p>Operational dashboard with toggleable modules.</p></div>
            <section id="dashboard-site-stats" class="admin-stats-grid admin-stats-grid-compact">
              <article class="admin-card admin-stat-card"><p class="admin-stat-label">Site visits today</p><p class="admin-stat-value">142</p><p class="admin-stat-meta admin-stat-up">↑ 18%</p></article>
              <article class="admin-card admin-stat-card"><p class="admin-stat-label">Site visits (7d)</p><p class="admin-stat-value">892</p><p class="admin-stat-meta admin-stat-up">↑ 12%</p></article>
              <article class="admin-card admin-stat-card"><p class="admin-stat-label">Booking form views</p><p class="admin-stat-value">67</p><p class="admin-stat-meta">last 7 days</p></article>
              <article class="admin-card admin-stat-card"><p class="admin-stat-label">Booking requests</p><p id="dashboard-bookings-value" class="admin-stat-value">0</p><p class="admin-stat-meta">inbox total</p></article>
            </section>
            <section id="dashboard-platforms" class="admin-stats-grid admin-stats-grid-compact">
              <article class="admin-card admin-stat-card"><p class="admin-stat-label">YouTube</p><p class="admin-stat-value">4.8k</p><p class="admin-stat-meta">subscribers • live</p></article>
              <article class="admin-card admin-stat-card"><p class="admin-stat-label">Mixcloud</p><p class="admin-stat-value">2.1k</p><p class="admin-stat-meta">followers • 124k plays</p></article>
              <article class="admin-card admin-stat-card"><p class="admin-stat-label">Instagram</p><p class="admin-stat-value">7.4k</p><p class="admin-stat-meta">followers • 7d reach</p></article>
              <article class="admin-card admin-stat-card"><p class="admin-stat-label">TikTok</p><p class="admin-stat-value">5.9k</p><p class="admin-stat-meta">followers • 32 videos</p></article>
            </section>
            <section class="admin-dashboard-grid">
              <article id="dashboard-pages" class="admin-card admin-block">
                <div class="admin-block-head"><h2>Pages</h2></div>
                <ul class="admin-simple-list">
                  <?php
                  $pages = get_pages(['sort_column' => 'menu_order', 'sort_order' => 'ASC']);
                  foreach ($pages as $p) {
                      $status = $p->post_status === 'publish' ? 'Published' : ucfirst($p->post_status);
                      echo '<li>' . esc_html($p->post_title) . ' <span>' . esc_html($status) . '</span></li>';
                  }
                  ?>
                </ul>
              </article>
              <article id="dashboard-uploads" class="admin-card admin-block">
                <div class="admin-block-head"><h2>Uploads</h2></div>
                <p>Drop zone and recent upload tracking module.</p>
              </article>
              <article id="dashboard-youtube" class="admin-card admin-block">
                <div class="admin-block-head"><h2>YouTube Channel</h2></div>
                <p>DJ UrbanT channel controls and live shortcuts.</p>
                <div class="admin-inline-actions">
                  <a class="admin-btn admin-btn-outline" href="https://studio.youtube.com" target="_blank" rel="noopener noreferrer">Open YouTube Studio</a>
                  <a class="admin-btn admin-btn-outline" href="https://www.youtube.com/@djurbant/live" target="_blank" rel="noopener noreferrer">View Live</a>
                </div>
              </article>
              <article id="dashboard-bookings" class="admin-card admin-block">
                <div class="admin-block-head"><h2>Bookings</h2></div>
                <p>Inbox and routing controls.</p>
                <button class="admin-btn admin-btn-outline" type="button" data-open-view="bookings">Open bookings</button>
              </article>
            </section>
            <section id="dashboard-socials" class="admin-card admin-block">
              <div class="admin-block-head"><h2>Social links</h2></div>
              <div class="admin-table-wrap">
                <table class="admin-table"><thead><tr><th>Platform</th><th>URL</th><th>In nav</th></tr></thead>
                <tbody id="dashboard-social-links-body"></tbody></table>
              </div>
            </section>
            <section class="admin-card admin-customize" aria-label="Customise dashboard">
              <div class="admin-customize-left">✎ Customise dashboard</div>
              <div class="admin-customize-chips admin-customize-chips-wrap">
                <button class="admin-chip is-on" type="button" data-dashboard-chip="siteStats">Site stats</button>
                <button class="admin-chip is-on" type="button" data-dashboard-chip="platforms">Platforms</button>
                <button class="admin-chip is-on" type="button" data-dashboard-chip="pages">Pages</button>
                <button class="admin-chip is-on" type="button" data-dashboard-chip="uploads">Uploads</button>
                <button class="admin-chip is-on" type="button" data-dashboard-chip="youtube">YouTube</button>
                <button class="admin-chip is-on" type="button" data-dashboard-chip="bookings">Bookings</button>
                <button class="admin-chip is-on" type="button" data-dashboard-chip="socials">Socials</button>
              </div>
              <button id="dashboard-customize-reset" class="admin-reset-btn" type="button">Reset</button>
            </section>
          </section>

          <section class="admin-view" data-view-panel="bookings" hidden>
            <div class="admin-view-head"><h1>Bookings</h1><p>Inbox for booking requests and routing controls.</p></div>
            <section class="admin-card admin-block">
              <div class="admin-table-wrap">
                <table class="admin-table"><thead><tr><th>Status</th><th>Venue / Event</th><th>Date</th><th>Type</th><th>Budget</th><th>Received</th><th>Actions</th></tr></thead>
                <tbody id="bookings-table-body"></tbody></table>
              </div>
            </section>
          </section>

          <section class="admin-view" data-view-panel="pages" hidden>
            <div class="admin-view-head"><h1>Pages</h1><p>Manage WordPress pages.</p></div>
            <section class="admin-card admin-block">
              <ul class="admin-simple-list">
                <?php foreach ($pages as $p): ?>
                <li>
                  <a href="<?php echo get_edit_post_link($p->ID); ?>" target="_blank"><?php echo esc_html($p->post_title); ?></a>
                  <span><?php echo $p->post_status === 'publish' ? 'Published' : ucfirst($p->post_status); ?></span>
                </li>
                <?php endforeach; ?>
              </ul>
              <div class="admin-inline-actions" style="margin-top:1rem;">
                <a class="admin-btn admin-btn-outline" href="<?php echo admin_url('edit.php?post_type=page'); ?>" target="_blank">Manage in WP Admin</a>
              </div>
            </section>
          </section>

          <section class="admin-view" data-view-panel="uploads" hidden>
            <div class="admin-view-head"><h1>Uploads</h1><p>Media library management.</p></div>
            <section class="admin-card admin-block">
              <div class="admin-inline-actions">
                <a class="admin-btn admin-btn-outline" href="<?php echo admin_url('upload.php'); ?>" target="_blank">Open Media Library</a>
              </div>
            </section>
          </section>

          <section class="admin-view" data-view-panel="socials" hidden>
            <div class="admin-view-head"><h1>Content &amp; Socials</h1><p>Edit site text and social links. Preview shows where each field appears on the live site.</p></div>

            <section class="admin-card admin-block">
              <div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;align-items:start">
                <div>
                  <h3 style="margin:0 0 1rem;font-size:1rem">Site Text Content</h3>
                  <div style="display:grid;gap:0.7rem">
                    <?php foreach ($content_fields as $i => $f):
                      $val = djurbant_get_nested($sc_data, $f['path']);
                      $type = $f['type'] ?? 'text';
                      $num = $i + 1;
                    ?>
                    <label style="display:block">
                      <span style="font-size:0.8rem;font-weight:600;color:var(--admin-text)"><span style="display:inline-flex;align-items:center;justify-content:center;width:1.3rem;height:1.3rem;border-radius:999px;background:linear-gradient(135deg,#00c8ff,#7b5cf0);color:#fff;font-size:0.65rem;font-weight:700;margin-right:0.35rem"><?php echo $num; ?></span><?php echo esc_html($f['label']); ?></span>
                      <?php if ($type === 'textarea'): ?>
                      <textarea class="content-field" data-path="<?php echo esc_attr($f['path']); ?>" rows="2" style="width:100%;margin-top:0.25rem;background:var(--admin-surface);border:1px solid var(--admin-border);color:var(--admin-text);border-radius:6px;padding:0.4rem 0.55rem;font-size:0.85rem;font-family:inherit;resize:vertical"><?php echo esc_textarea($val); ?></textarea>
                      <?php else: ?>
                      <input type="text" class="content-field" data-path="<?php echo esc_attr($f['path']); ?>" value="<?php echo esc_attr($val); ?>" style="width:100%;margin-top:0.25rem;background:var(--admin-surface);border:1px solid var(--admin-border);color:var(--admin-text);border-radius:6px;padding:0.38rem 0.55rem;font-size:0.85rem" />
                      <?php endif; ?>
                    </label>
                    <?php endforeach; ?>
                  </div>
                  <div style="margin-top:0.8rem;display:flex;align-items:center;gap:0.8rem">
                    <button id="save-content-btn" class="admin-btn admin-btn-solid" type="button">Save content</button>
                    <span id="content-save-status" style="font-size:0.82rem;color:var(--admin-muted)"></span>
                  </div>
                </div>

                <div>
                  <h3 style="margin:0 0 0.6rem;font-size:1rem">Live Preview</h3>
                  <div style="position:relative;border:1px solid var(--admin-border);border-radius:10px;overflow:hidden;background:#000;height:420px">
                    <iframe src="<?php echo home_url('/'); ?>" style="width:200%;height:840px;transform:scale(0.5);transform-origin:top left;pointer-events:none;display:block;border:0" title="Live site preview"></iframe>
                    <div style="position:absolute;top:0;left:0;width:50%;height:420px;pointer-events:none">
                      <span style="position:absolute;top:48%;left:28%;display:inline-flex;align-items:center;justify-content:center;width:1.3rem;height:1.3rem;border-radius:999px;background:linear-gradient(135deg,#00c8ff,#7b5cf0);color:#fff;font-size:0.65rem;font-weight:700;box-shadow:0 0 0 2px #fff,0 0 8px rgba(0,200,255,0.5)">1</span>
                      <span style="position:absolute;top:62%;left:10%;display:inline-flex;align-items:center;justify-content:center;width:1.3rem;height:1.3rem;border-radius:999px;background:linear-gradient(135deg,#00c8ff,#7b5cf0);color:#fff;font-size:0.65rem;font-weight:700;box-shadow:0 0 0 2px #fff,0 0 8px rgba(0,200,255,0.5)">2</span>
                      <span style="position:absolute;top:87%;left:8%;display:inline-flex;align-items:center;justify-content:center;width:1.3rem;height:1.3rem;border-radius:999px;background:linear-gradient(135deg,#00c8ff,#7b5cf0);color:#fff;font-size:0.65rem;font-weight:700;box-shadow:0 0 0 2px #fff,0 0 8px rgba(0,200,255,0.5)">3</span>
                      <span style="position:absolute;top:87%;right:12%;display:inline-flex;align-items:center;justify-content:center;width:1.3rem;height:1.3rem;border-radius:999px;background:linear-gradient(135deg,#00c8ff,#7b5cf0);color:#fff;font-size:0.65rem;font-weight:700;box-shadow:0 0 0 2px #fff,0 0 8px rgba(0,200,255,0.5)">4</span>
                      <span style="position:absolute;top:4%;right:8%;display:inline-flex;align-items:center;justify-content:center;width:1.3rem;height:1.3rem;border-radius:999px;background:linear-gradient(135deg,#00c8ff,#7b5cf0);color:#fff;font-size:0.65rem;font-weight:700;box-shadow:0 0 0 2px #fff,0 0 8px rgba(0,200,255,0.5)">8</span>
                      <span style="position:absolute;bottom:4%;left:35%;display:inline-flex;align-items:center;justify-content:center;width:1.3rem;height:1.3rem;border-radius:999px;background:linear-gradient(135deg,#00c8ff,#7b5cf0);color:#fff;font-size:0.65rem;font-weight:700;box-shadow:0 0 0 2px #fff,0 0 8px rgba(0,200,255,0.5)">9</span>
                    </div>
                  </div>
                  <p style="margin:0.5rem 0 0;font-size:0.78rem;color:var(--admin-muted)">Numbers match the edit fields on the left. <strong>⑨</strong> = Social links in footer (edited below).</p>
                </div>
              </div>
            </section>

            <section class="admin-card admin-block" style="margin-top:1rem">
              <div class="admin-block-head"><h2>⑨ Social Links</h2></div>
              <div class="admin-table-wrap">
                <table class="admin-table">
                  <thead><tr><th>Platform</th><th>URL</th><th>Enabled</th></tr></thead>
                  <tbody id="real-socials-tbody">
                    <?php foreach ($socials as $key => $s): ?>
                    <tr>
                      <td><strong><?php echo esc_html($s['label'] ?? $key); ?></strong></td>
                      <td><input type="url" class="socials-url-input" data-key="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($s['url'] ?? ''); ?>" style="width:100%;background:var(--admin-surface);border:1px solid var(--admin-border);color:var(--admin-text);border-radius:6px;padding:0.35rem 0.5rem;font-size:0.85rem" /></td>
                      <td><label style="cursor:pointer"><input type="checkbox" class="socials-enabled-input" data-key="<?php echo esc_attr($key); ?>"<?php echo ($s['enabled'] !== false) ? ' checked' : ''; ?> /> On</label></td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
              <div style="margin-top:0.8rem;display:flex;align-items:center;gap:0.8rem">
                <button id="save-socials-btn" class="admin-btn admin-btn-solid" type="button">Save social links</button>
                <span id="socials-save-status" style="font-size:0.82rem;color:var(--admin-muted)"></span>
              </div>
            </section>
          </section>

          <section class="admin-view" data-view-panel="youtube" hidden>
            <div class="admin-view-head"><h1>YouTube</h1><p>YouTube channel management.</p></div>
            <section class="admin-card admin-block">
              <div class="admin-inline-actions">
                <a class="admin-btn admin-btn-outline" href="https://studio.youtube.com" target="_blank" rel="noopener noreferrer">Open YouTube Studio</a>
                <a class="admin-btn admin-btn-outline" href="https://www.youtube.com/@DJ_UrbanT" target="_blank" rel="noopener noreferrer">View Channel</a>
              </div>
            </section>
          </section>

          <section class="admin-view" data-view-panel="analytics" hidden>
            <div class="admin-view-head"><h1>Analytics</h1><p>Real-time site traffic from Koko Analytics.</p></div>

            <section id="analytics-stats" class="admin-stats-grid admin-stats-grid-compact">
              <article class="admin-card admin-stat-card"><p class="admin-stat-label">Visitors today</p><p id="ana-today-visitors" class="admin-stat-value">—</p><p class="admin-stat-meta">unique visitors</p></article>
              <article class="admin-card admin-stat-card"><p class="admin-stat-label">Page views (7d)</p><p id="ana-week-views" class="admin-stat-value">—</p><p class="admin-stat-meta">last 7 days</p></article>
              <article class="admin-card admin-stat-card"><p class="admin-stat-label">Visitors (7d)</p><p id="ana-week-visitors" class="admin-stat-value">—</p><p class="admin-stat-meta">unique</p></article>
              <article class="admin-card admin-stat-card"><p class="admin-stat-label">Page views (30d)</p><p id="ana-month-views" class="admin-stat-value">—</p><p class="admin-stat-meta">last 30 days</p></article>
            </section>

            <section class="admin-card admin-block" style="margin-top:1rem">
              <div class="admin-block-head"><h2>Visitors — Last 30 Days</h2></div>
              <div id="analytics-chart" style="height:180px;display:flex;align-items:flex-end;gap:2px;padding:0.5rem 0">
                <p style="color:var(--admin-muted);font-size:0.85rem">Loading chart…</p>
              </div>
            </section>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:1rem">
              <section class="admin-card admin-block">
                <div class="admin-block-head"><h2>Top Pages (7d)</h2></div>
                <ul id="analytics-top-pages" class="admin-simple-list">
                  <li style="color:var(--admin-muted)">Loading…</li>
                </ul>
              </section>
              <section class="admin-card admin-block">
                <div class="admin-block-head"><h2>Traffic Sources (7d)</h2></div>
                <ul id="analytics-referrers" class="admin-simple-list">
                  <li style="color:var(--admin-muted)">Loading…</li>
                </ul>
              </section>
            </div>

            <section class="admin-card admin-block" style="margin-top:1rem">
              <div class="admin-block-head"><h2>Advanced Analytics</h2></div>
              <p style="color:var(--admin-muted);margin:0 0 0.8rem">For deeper insights, use one of these tools:</p>
              <div class="admin-inline-actions" style="flex-wrap:wrap;gap:0.5rem">
                <a class="admin-btn admin-btn-outline" href="<?php echo admin_url('admin.php?page=koko-analytics'); ?>" target="_blank">📊 Koko Analytics Dashboard</a>
                <a class="admin-btn admin-btn-outline" href="<?php echo admin_url('admin.php?page=googlesitekit-splash'); ?>" target="_blank" style="opacity:0.7">🔮 Google Site Kit (install later)</a>
                <a class="admin-btn admin-btn-outline" href="https://analytics.google.com/" target="_blank" rel="noopener noreferrer" style="opacity:0.7">📈 Google Analytics (external)</a>
              </div>
            </section>
          </section>

          <section class="admin-view" data-view-panel="settings" hidden>
            <div class="admin-view-head"><h1>Settings</h1><p>Global admin settings.</p></div>
            <section class="admin-card admin-block">
              <div class="admin-inline-actions">
                <a class="admin-btn admin-btn-outline" href="<?php echo admin_url('options-general.php'); ?>" target="_blank">WordPress Settings</a>
                <a class="admin-btn admin-btn-outline" href="<?php echo admin_url('customize.php'); ?>" target="_blank">Theme Customizer</a>
              </div>
            </section>
          </section>

          <section class="admin-view" data-view-panel="management" hidden>
            <div class="admin-view-head">
              <h1>Complete Management Reference</h1>
              <p>Direct links to manage every aspect of the DJ UrbanT site.</p>
            </div>

            <section class="admin-card admin-block">
              <div class="admin-block-head"><h2>Content (text, videos, audio data)</h2></div>
              <div class="admin-table-wrap">
                <table class="admin-table">
                  <thead><tr><th>What</th><th>Where</th><th>Direct Link</th><th>How</th></tr></thead>
                  <tbody>
                    <tr><td>Hero tagline, CTA text, booking band text</td><td><code>site-content.json</code> in theme</td><td><a href="<?php echo admin_url('theme-editor.php?file=site-content.json&theme=djurbant-child'); ?>" target="_blank">Theme File Editor</a></td><td>Edit JSON fields directly</td></tr>
                    <tr><td>YouTube/Mixcloud feed data</td><td><code>media-data.json</code> in theme</td><td><a href="<?php echo admin_url('theme-editor.php?file=media-data.json&theme=djurbant-child'); ?>" target="_blank">Theme File Editor</a></td><td>Edit JSON — or auto-generate from APIs</td></tr>
                    <tr><td>Social links (URLs, platforms, visibility)</td><td><code>site-content.json</code> → <code>global.socialLinks</code></td><td><a href="<?php echo admin_url('theme-editor.php?file=site-content.json&theme=djurbant-child'); ?>" target="_blank">Theme File Editor</a></td><td>Also via REST API: <code>/wp-json/djurbant/v1/socials</code></td></tr>
                    <tr><td>Page titles, slugs, publish status</td><td>WordPress Pages</td><td><a href="<?php echo admin_url('edit.php?post_type=page'); ?>" target="_blank">Pages list</a></td><td>Standard WP page management</td></tr>
                    <tr><td>Booking form fields</td><td>WPForms Builder</td><td><a href="<?php echo admin_url('admin.php?page=wpforms-builder&view=fields&form_id=54'); ?>" target="_blank">Edit Form 54</a></td><td>Visual drag-and-drop</td></tr>
                    <tr><td>Booking submissions / inbox</td><td>WPForms Entries</td><td><a href="<?php echo admin_url('admin.php?page=wpforms-entries&view=list&form_id=54'); ?>" target="_blank">View Entries</a></td><td>Read, star, export</td></tr>
                  </tbody>
                </table>
              </div>
            </section>

            <section class="admin-card admin-block">
              <div class="admin-block-head"><h2>Design (colors, fonts, spacing)</h2></div>
              <div class="admin-table-wrap">
                <table class="admin-table">
                  <thead><tr><th>What</th><th>Where</th><th>Direct Link</th><th>How</th></tr></thead>
                  <tbody>
                    <tr><td>Global colors (brand cyan, violet, gradients)</td><td>CSS variables in <code>djurbant-styles.css</code></td><td><a href="<?php echo admin_url('theme-editor.php?file=djurbant-styles.css&theme=djurbant-child'); ?>" target="_blank">Theme File Editor</a></td><td>Edit <code>:root</code> variables at the top</td></tr>
                    <tr><td>Fonts</td><td>Font files + <code>functions.php</code></td><td><a href="<?php echo admin_url('theme-editor.php?file=functions.php&theme=djurbant-child'); ?>" target="_blank">functions.php</a></td><td>Replace TTF files in <code>assets/fonts/</code>, update <code>@font-face</code></td></tr>
                    <tr><td>Quick CSS tweaks</td><td>Customizer Additional CSS</td><td><a href="<?php echo admin_url('customize.php?autofocus[section]=custom_css'); ?>" target="_blank">Additional CSS</a></td><td>Override any style without touching theme files</td></tr>
                    <tr><td>Admin page colors</td><td><code>admin.css</code> variables</td><td><a href="<?php echo admin_url('theme-editor.php?file=admin.css&theme=djurbant-child'); ?>" target="_blank">Theme File Editor</a></td><td>Edit <code>:root</code> at top</td></tr>
                    <tr><td>Kadence global styles (fallback pages)</td><td>Kadence Customizer</td><td><a href="<?php echo admin_url('customize.php?autofocus[section]=kadence_customizer_general_colors'); ?>" target="_blank">Colors &amp; Fonts</a></td><td>For pages not using custom templates</td></tr>
                  </tbody>
                </table>
              </div>
            </section>

            <section class="admin-card admin-block">
              <div class="admin-block-head"><h2>Layout &amp; Structure</h2></div>
              <div class="admin-table-wrap">
                <table class="admin-table">
                  <thead><tr><th>What</th><th>Where</th><th>Direct Link</th><th>How</th></tr></thead>
                  <tbody>
                    <tr><td>Homepage structure (hero, carousel, stats, CTA)</td><td><code>page-templates/home.php</code></td><td><a href="<?php echo admin_url('theme-editor.php?file=page-templates/home.php&theme=djurbant-child'); ?>" target="_blank">Theme File Editor</a></td><td>Edit HTML/PHP directly</td></tr>
                    <tr><td>Video page layout</td><td><code>page-templates/video.php</code></td><td><a href="<?php echo admin_url('theme-editor.php?file=page-templates/video.php&theme=djurbant-child'); ?>" target="_blank">Theme File Editor</a></td><td>Edit HTML</td></tr>
                    <tr><td>Contact page layout</td><td><code>page-templates/contact.php</code></td><td><a href="<?php echo admin_url('theme-editor.php?file=page-templates/contact.php&theme=djurbant-child'); ?>" target="_blank">Theme File Editor</a></td><td>Edit HTML</td></tr>
                    <tr><td>Footer (social icons, logo)</td><td><code>footer-djurbant.php</code></td><td><a href="<?php echo admin_url('theme-editor.php?file=footer-djurbant.php&theme=djurbant-child'); ?>" target="_blank">Theme File Editor</a></td><td>Edit HTML/SVG icons</td></tr>
                    <tr><td>Navigation menu items</td><td>Hardcoded in templates</td><td>Each template's <code>&lt;header&gt;</code> section</td><td>Edit the <code>&lt;nav&gt;</code> HTML in each template</td></tr>
                  </tbody>
                </table>
              </div>
            </section>

            <section class="admin-card admin-block">
              <div class="admin-block-head"><h2>Media &amp; Assets</h2></div>
              <div class="admin-table-wrap">
                <table class="admin-table">
                  <thead><tr><th>What</th><th>Where</th><th>Direct Link</th><th>How</th></tr></thead>
                  <tbody>
                    <tr><td>Logo SVG</td><td><code>assets/images/UT_TITLE_SVG.svg</code></td><td>Theme directory on server</td><td>Replace file via SFTP or Git</td></tr>
                    <tr><td>Diamond image</td><td><code>assets/images/djurbant-proto-1.png</code></td><td>Theme directory</td><td>Replace file</td></tr>
                    <tr><td>Favicons</td><td><code>assets/images/favicon*</code></td><td>Theme directory</td><td>Replace files</td></tr>
                    <tr><td>WordPress media library</td><td>WP Media</td><td><a href="<?php echo admin_url('upload.php'); ?>" target="_blank">Media Library</a></td><td>Upload/manage images</td></tr>
                  </tbody>
                </table>
              </div>
            </section>

            <section class="admin-card admin-block">
              <div class="admin-block-head"><h2>Features &amp; Plugins</h2></div>
              <div class="admin-table-wrap">
                <table class="admin-table">
                  <thead><tr><th>What</th><th>Where</th><th>Direct Link</th><th>How</th></tr></thead>
                  <tbody>
                    <tr><td>Caching (Breeze)</td><td>Breeze Settings</td><td><a href="<?php echo admin_url('options-general.php?page=breeze'); ?>" target="_blank">Breeze</a></td><td>Purge cache after changes</td></tr>
                    <tr><td>WPForms (contact form)</td><td>WPForms</td><td><a href="<?php echo admin_url('admin.php?page=wpforms-overview'); ?>" target="_blank">All Forms</a></td><td>Manage forms</td></tr>
                    <tr><td>Kadence Blocks</td><td>Kadence</td><td><a href="<?php echo admin_url('admin.php?page=kadence-blocks'); ?>" target="_blank">Kadence Settings</a></td><td>Block settings</td></tr>
                    <tr><td>All plugins</td><td>Plugins page</td><td><a href="<?php echo admin_url('plugins.php'); ?>" target="_blank">Plugins</a></td><td>Enable/disable</td></tr>
                  </tbody>
                </table>
              </div>
            </section>

            <section class="admin-card admin-block">
              <div class="admin-block-head"><h2>Site Settings</h2></div>
              <div class="admin-table-wrap">
                <table class="admin-table">
                  <thead><tr><th>What</th><th>Where</th><th>Direct Link</th><th>How</th></tr></thead>
                  <tbody>
                    <tr><td>Site title &amp; tagline</td><td>General Settings</td><td><a href="<?php echo admin_url('options-general.php'); ?>" target="_blank">General</a></td><td>Edit title, tagline, timezone</td></tr>
                    <tr><td>Homepage setting (which page is front page)</td><td>Reading Settings</td><td><a href="<?php echo admin_url('options-reading.php'); ?>" target="_blank">Reading</a></td><td>Set static page</td></tr>
                    <tr><td>Permalinks</td><td>Permalink Settings</td><td><a href="<?php echo admin_url('options-permalink.php'); ?>" target="_blank">Permalinks</a></td><td>URL structure</td></tr>
                    <tr><td>Users &amp; roles</td><td>Users</td><td><a href="<?php echo admin_url('users.php'); ?>" target="_blank">Users</a></td><td>Manage access</td></tr>
                  </tbody>
                </table>
              </div>
            </section>
          </section>

          <section class="admin-view" data-view-panel="sitemap" hidden>
            <div class="admin-view-head">
              <h1>Site Map</h1>
              <p>All pages and their status. Also accessible at <a href="<?php echo home_url('/map/'); ?>" target="_blank" style="color:var(--map-accent,#0078d4)">/map/</a></p>
            </div>
            <?php
            $all_pages = get_pages(['sort_column' => 'menu_order', 'sort_order' => 'ASC']);
            $site_url = home_url('/');
            ?>
            <section class="admin-card admin-block">
              <div class="admin-block-head"><h2>Published Pages</h2></div>
              <div class="admin-table-wrap">
                <table class="admin-table">
                  <thead><tr><th>Page</th><th>URL</th><th>Template</th><th>Status</th><th>Actions</th></tr></thead>
                  <tbody>
                    <?php foreach ($all_pages as $p):
                      $tmpl = get_page_template_slug($p->ID) ?: 'default';
                      $tmpl_short = str_replace('page-templates/', '', $tmpl);
                      $status = $p->post_status === 'publish' ? 'Published' : ucfirst($p->post_status);
                      $url = get_permalink($p->ID);
                    ?>
                    <tr>
                      <td><strong><?php echo esc_html($p->post_title); ?></strong></td>
                      <td><a href="<?php echo esc_url($url); ?>" target="_blank" style="color:var(--map-accent,#0078d4);font-size:0.85rem"><?php echo esc_html(str_replace($site_url, '/', $url)); ?></a></td>
                      <td><code style="font-size:0.78rem;background:var(--admin-surface);padding:0.15rem 0.4rem;border-radius:4px"><?php echo esc_html($tmpl_short); ?></code></td>
                      <td><span style="display:inline-block;font-size:0.72rem;font-weight:600;letter-spacing:0.04em;text-transform:uppercase;padding:0.18rem 0.55rem;border-radius:999px;<?php echo $p->post_status === 'publish' ? 'background:rgba(46,160,67,0.12);color:#1a7f37;border:1px solid rgba(46,160,67,0.3)' : 'background:rgba(207,34,46,0.1);color:#cf222e;border:1px solid rgba(207,34,46,0.3)'; ?>"><?php echo esc_html($status); ?></span></td>
                      <td style="white-space:nowrap">
                        <a class="admin-btn admin-btn-outline" style="font-size:0.75rem;padding:0.2rem 0.5rem" href="<?php echo get_edit_post_link($p->ID); ?>" target="_blank">Edit</a>
                        <a class="admin-btn admin-btn-outline" style="font-size:0.75rem;padding:0.2rem 0.5rem" href="<?php echo esc_url($url); ?>" target="_blank">View</a>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </section>

            <section class="admin-card admin-block" style="margin-top:1rem">
              <div class="admin-block-head"><h2>Quick Links</h2></div>
              <div class="admin-inline-actions" style="flex-wrap:wrap;gap:0.5rem">
                <a class="admin-btn admin-btn-outline" href="<?php echo home_url('/'); ?>" target="_blank">Homepage</a>
                <a class="admin-btn admin-btn-outline" href="<?php echo home_url('/video/'); ?>" target="_blank">Video</a>
                <a class="admin-btn admin-btn-outline" href="<?php echo home_url('/audio/'); ?>" target="_blank">Audio</a>
                <a class="admin-btn admin-btn-outline" href="<?php echo home_url('/contact/'); ?>" target="_blank">Contact</a>
                <a class="admin-btn admin-btn-outline" href="<?php echo home_url('/map/'); ?>" target="_blank">Full Map Page</a>
                <a class="admin-btn admin-btn-outline" href="<?php echo admin_url(); ?>" target="_blank">WP Admin</a>
              </div>
            </section>
          </section>
        </main>
      </div>
    </div>

    <div id="settings-panel-scrim" class="settings-panel-scrim" hidden></div>
    <aside id="settings-panel" class="settings-panel" hidden>
      <div class="settings-panel-head">
        <h2>Settings</h2>
        <button id="settings-close-btn" class="admin-icon-btn" type="button" aria-label="Close settings">✕</button>
      </div>
      <label class="settings-field"><span>Font size</span>
        <select id="setting-font-size"><option value="small">Small</option><option value="medium">Medium</option><option value="large">Large</option></select>
      </label>
      <label class="settings-field"><span>Timezone</span>
        <select id="setting-timezone"><option value="Europe/Zurich">Europe/Zurich</option><option value="Europe/London">Europe/London</option><option value="UTC">UTC</option></select>
      </label>
      <label class="settings-field settings-checkbox-row">
        <input id="setting-notifications" type="checkbox" /><span>Notifications enabled</span>
      </label>
      <a class="admin-btn admin-btn-outline" href="<?php echo wp_logout_url(home_url('/')); ?>">Logout</a>
    </aside>

    <script>
    window.__djurbantAdmin = {
      restBase: '<?php echo esc_url(rest_url('djurbant/v1')); ?>',
      restNonce: '<?php echo wp_create_nonce('wp_rest'); ?>',
      wpAdminUrl: '<?php echo esc_url(admin_url()); ?>',
      formId: 54
    };
    </script>
    <script src="<?php echo $theme_uri; ?>/admin.js"></script>
    <?php wp_footer(); ?>
</body>
</html>
