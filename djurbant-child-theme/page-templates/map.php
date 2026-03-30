<?php
/**
 * Template Name: DJ UrbanT Map
 *
 * Renders the djurbant.com site-map page inside WordPress.
 */
defined('ABSPATH') || exit;
$theme_uri = get_stylesheet_directory_uri();
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php wp_head(); ?>
    <link rel="icon" type="image/x-icon" href="<?php echo $theme_uri; ?>/assets/images/favicon.ico" />
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $theme_uri; ?>/assets/images/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $theme_uri; ?>/assets/images/favicon-16x16.png" />
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $theme_uri; ?>/assets/images/apple-touch-icon.png" />
    <style>
      :root {
        --map-bg: #0a0c14;
        --map-surface: #12151f;
        --map-border: rgba(255,255,255,0.10);
        --map-text: #e2e6ef;
        --map-muted: #8a90a2;
        --map-accent: #22bfff;
        --map-accent-soft: rgba(34,191,255,0.18);
        --map-radius: 12px;
      }
      body[data-page="map"] { margin: 0; background: var(--map-bg); color: var(--map-text); font-family: "Syne", system-ui, -apple-system, sans-serif; }
      .map-wrap { max-width: 1100px; margin: 0 auto; padding: 3rem 1.5rem 4rem; }
      .map-wrap h1 { font-size: clamp(1.6rem, 4vw, 2.4rem); font-weight: 700; margin: 0 0 0.5rem; }
      .map-wrap .map-lead { color: var(--map-muted); font-size: 1rem; max-width: 64ch; margin: 0 0 2.5rem; line-height: 1.55; }
      .map-wrap h2 { font-size: 1.15rem; font-weight: 600; margin: 2.2rem 0 1rem; letter-spacing: 0.03em; color: var(--map-accent); }
      .map-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; }
      .map-card { background: var(--map-surface); border: 1px solid var(--map-border); border-radius: var(--map-radius); overflow: hidden; text-decoration: none; color: inherit; transition: border-color 0.2s, box-shadow 0.2s; }
      .map-card:hover { border-color: var(--map-accent); box-shadow: 0 0 0 1px var(--map-accent-soft), 0 8px 24px rgba(0,0,0,0.35); }
      .map-card-preview { width: 100%; aspect-ratio: 16/10; border: none; pointer-events: none; background: var(--map-bg); }
      .map-card-body { padding: 0.75rem 1rem; }
      .map-card-title { margin: 0 0 0.25rem; font-size: 0.95rem; font-weight: 600; }
      .map-card-url { margin: 0; font-size: 0.78rem; color: var(--map-muted); word-break: break-all; }
      .map-routes { margin-top: 2.5rem; }
      .map-routes table { width: 100%; border-collapse: collapse; }
      .map-routes th, .map-routes td { text-align: left; padding: 0.55rem 0.75rem; border-bottom: 1px solid var(--map-border); font-size: 0.9rem; }
      .map-routes th { color: var(--map-muted); font-weight: 500; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.06em; }
      .map-routes td a { color: var(--map-accent); text-decoration: none; }
      .map-routes td a:hover { text-decoration: underline; }
      .status { display: inline-block; font-size: 0.72rem; font-weight: 600; letter-spacing: 0.04em; text-transform: uppercase; padding: 0.18rem 0.55rem; border-radius: 999px; }
      .status-open { background: rgba(75,229,72,0.16); color: #4be548; }
      .status-locked { background: rgba(255,93,87,0.14); color: #ff5d57; }
      @media (max-width: 768px) {
        .map-grid { grid-template-columns: repeat(2, 1fr); }
      }
      @media (max-width: 480px) {
        .map-grid { grid-template-columns: 1fr; }
        .map-wrap { padding: 2rem 1rem 3rem; }
      }
    </style>
</head>
<body data-page="map" <?php body_class(); ?>>
<?php wp_body_open(); ?>

    <main class="map-wrap">
      <h1>DJ UrbanT &mdash; Map</h1>
      <p class="map-lead">Visual overview of every route on djurbant.com. Use this page to quickly jump between sections or check the status of each route.</p>

      <h2>Layer 1 &mdash; Key Pages</h2>
      <div class="map-grid">
        <a class="map-card" href="<?php echo home_url('/'); ?>">
          <iframe class="map-card-preview" src="<?php echo home_url('/'); ?>" tabindex="-1" loading="lazy" title="Home preview"></iframe>
          <div class="map-card-body">
            <p class="map-card-title">Home</p>
            <p class="map-card-url">/</p>
          </div>
        </a>
        <a class="map-card" href="<?php echo home_url('/video/'); ?>">
          <iframe class="map-card-preview" src="<?php echo home_url('/video/'); ?>" tabindex="-1" loading="lazy" title="Video preview"></iframe>
          <div class="map-card-body">
            <p class="map-card-title">Video</p>
            <p class="map-card-url">/video/</p>
          </div>
        </a>
        <a class="map-card" href="<?php echo home_url('/audio/'); ?>">
          <iframe class="map-card-preview" src="<?php echo home_url('/audio/'); ?>" tabindex="-1" loading="lazy" title="Audio preview"></iframe>
          <div class="map-card-body">
            <p class="map-card-title">Audio</p>
            <p class="map-card-url">/audio/</p>
          </div>
        </a>
        <a class="map-card" href="<?php echo home_url('/contact/'); ?>">
          <iframe class="map-card-preview" src="<?php echo home_url('/contact/'); ?>" tabindex="-1" loading="lazy" title="Contact preview"></iframe>
          <div class="map-card-body">
            <p class="map-card-title">Contact</p>
            <p class="map-card-url">/contact/</p>
          </div>
        </a>
      </div>

      <h2>Layer 2 &mdash; Admin</h2>
      <div class="map-grid" style="grid-template-columns: repeat(2, 1fr);">
        <a class="map-card" href="<?php echo home_url('/map/'); ?>">
          <iframe class="map-card-preview" src="<?php echo home_url('/map/'); ?>" tabindex="-1" loading="lazy" title="Map preview"></iframe>
          <div class="map-card-body">
            <p class="map-card-title">Map</p>
            <p class="map-card-url">/map/</p>
          </div>
        </a>
        <a class="map-card" href="<?php echo home_url('/admin/'); ?>">
          <div class="map-card-preview" style="display:flex;align-items:center;justify-content:center;color:var(--map-muted);font-size:0.85rem;">Requires login</div>
          <div class="map-card-body">
            <p class="map-card-title">Admin</p>
            <p class="map-card-url">/admin/</p>
          </div>
        </a>
      </div>

      <div class="map-routes">
        <h2>All Routes</h2>
        <table>
          <thead>
            <tr>
              <th>Page</th>
              <th>Path</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Home</td>
              <td><a href="<?php echo home_url('/'); ?>">/</a></td>
              <td><span class="status status-open">Open</span></td>
            </tr>
            <tr>
              <td>Video</td>
              <td><a href="<?php echo home_url('/video/'); ?>">/video/</a></td>
              <td><span class="status status-open">Open</span></td>
            </tr>
            <tr>
              <td>Audio</td>
              <td><a href="<?php echo home_url('/audio/'); ?>">/audio/</a></td>
              <td><span class="status status-open">Open</span></td>
            </tr>
            <tr>
              <td>Contact</td>
              <td><a href="<?php echo home_url('/contact/'); ?>">/contact/</a></td>
              <td><span class="status status-open">Open</span></td>
            </tr>
            <tr>
              <td>Map</td>
              <td><a href="<?php echo home_url('/map/'); ?>">/map/</a></td>
              <td><span class="status status-open">Open</span></td>
            </tr>
            <tr>
              <td>Admin</td>
              <td><a href="<?php echo home_url('/admin/'); ?>">/admin/</a></td>
              <td><span class="status status-locked">Locked</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>

<?php wp_footer(); ?>
</body>
</html>
