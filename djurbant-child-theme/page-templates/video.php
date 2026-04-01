<?php
/**
 * Template Name: DJ UrbanT Video
 *
 * Renders the original djurbant.com video sub-page inside WordPress.
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
    <link rel="preconnect" href="https://www.youtube.com" />
    <style>
      body[data-page="video"] .media-section { padding-top: var(--space-3); margin-top: var(--space-4); }
      body[data-page="video"] .subpage-grid .media-card.chunk-hidden { display: none !important; }
      body[data-page="video"] .subpage-grid .media-card.chunk-force-visible[hidden] { display: flex !important; }
      body[data-page="video"] .subpage-grid .chunk-load-more-tile { display: flex; align-items: stretch; }
      body[data-page="video"] .subpage-grid .chunk-load-more-inner { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.6rem; border-radius: 8px; border: 1px solid rgba(76, 196, 255, 0.38); background: rgba(7, 12, 26, 0.9); box-shadow: inset 0 0 0 1px rgba(255,255,255,0.05), 0 8px 24px rgba(0,0,0,0.3); padding: 1rem; }
      body[data-page="video"] .subpage-grid .chunk-load-more-btn { width: 100%; }
      body[data-page="video"] .subpage-grid .chunk-load-more-hint { margin: 0; font-size: 0.78rem; letter-spacing: 0.04em; color: rgba(220,226,240,0.68); }
    </style>
</head>
<body data-page="video" <?php body_class(); ?>>
<?php wp_body_open(); ?>

    <header class="site-header">
      <a class="brand" href="<?php echo home_url('/'); ?>" aria-label="DJ UrbanT home">
        <img class="brand-logo" src="<?php echo $theme_uri; ?>/assets/images/UT_TITLE_SVG.svg" alt="DJ UrbanT" />
      </a>
      <nav class="main-nav" aria-label="Main navigation">
        <a class="main-nav-book" href="<?php echo home_url('/contact/'); ?>">Contact</a>
      </nav>
    </header>

    <main class="subpage-main">
      <section class="media-section">
        <div class="section-head">
          <h1 data-cms-text="page.title">Video</h1>
        </div>
        <p class="subpage-intro" data-cms-text="page.intro">Ranked from fetched video channel stats.</p>
        <div id="videos-rest-grid" class="subpage-grid"></div>
        <div class="section-cta">
          <a class="btn btn-outline" href="<?php echo home_url('/?bestOf=video#best-of-artist'); ?>">Top Video</a>
        </div>
      </section>
    </main>

    <?php get_template_part('footer-djurbant'); ?>

<?php // Inline chunked loading script for the video subpage grid ?>
<script>
(() => {
    const CHUNK_SIZE = 12, INITIAL_COUNT_MOBILE = 3, INITIAL_COUNT_DESKTOP = 12, FORCE_VISIBLE_FROM = 3, DESKTOP_QUERY = "(min-width: 901px)";
    const getInitialCount = () => window.matchMedia(DESKTOP_QUERY).matches ? INITIAL_COUNT_DESKTOP : INITIAL_COUNT_MOBILE;
    const isDesktop = () => window.matchMedia(DESKTOP_QUERY).matches;
    const getCards = (c) => [...c.querySelectorAll(":scope > .media-card:not(.chunk-load-more-tile)")];
    function createLoadMoreTile(r, onClick) { const a = document.createElement("article"); a.className = "media-card chunk-load-more-tile"; const i = document.createElement("div"); i.className = "chunk-load-more-inner"; const b = document.createElement("button"); b.type = "button"; b.className = "btn btn-outline chunk-load-more-btn"; b.textContent = "More"; b.addEventListener("click", onClick); const h = document.createElement("p"); h.className = "chunk-load-more-hint"; h.textContent = r + "+ more"; i.append(b, h); a.append(i); return a; }
    function initChunkedContainer(id) { const c = document.getElementById(id); if (!c) return false; const cards = getCards(c); if (!cards.length) return false; if (!c.dataset.chunkVisibleCount) c.dataset.chunkVisibleCount = String(Math.min(getInitialCount(), cards.length)); const apply = () => { const all = getCards(c); const vc = Math.min(Number(c.dataset.chunkVisibleCount) || getInitialCount(), all.length); all.forEach((card, i) => { card.classList.toggle("chunk-hidden", i >= vc); if (i >= FORCE_VISIBLE_FROM) card.classList.toggle("chunk-force-visible", i < vc); else card.classList.remove("chunk-force-visible"); }); const et = c.querySelector(":scope > .chunk-load-more-tile"); if (et) et.remove(); const rem = all.length - vc; if (!isDesktop() && rem > 0) c.appendChild(createLoadMoreTile(rem, () => { c.dataset.chunkVisibleCount = String(Math.min(all.length, vc + CHUNK_SIZE)); apply(); })); }; c._applyChunkState = apply; apply(); return true; }
    let att = 0; const iv = setInterval(() => { att++; const c = document.getElementById("videos-rest-grid"); if (!c) { if (att >= 160) clearInterval(iv); return; } if (c.dataset.chunkInit === "1") { clearInterval(iv); return; } if (initChunkedContainer("videos-rest-grid")) { c.dataset.chunkInit = "1"; clearInterval(iv); } }, 120);
    window.addEventListener("resize", () => { const c = document.getElementById("videos-rest-grid"); c?._applyChunkState?.(); }, { passive: true });
})();
</script>

<?php wp_footer(); ?>
</body>
</html>
