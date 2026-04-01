<?php
/**
 * Template Name: DJ UrbanT Home
 * 
 * Renders the original djurbant.com homepage inside WordPress.
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
    <link rel="preconnect" href="https://www.mixcloud.com" />
    <style>
      body[data-page="home"] #best-of-artist .media-card.chunk-hidden { display: none !important; }
      body[data-page="home"] #best-of-artist .media-card.chunk-force-visible[hidden] { display: flex !important; }
      body[data-page="home"] #best-of-artist .chunk-load-more-tile { display: flex; align-items: stretch; }
      body[data-page="home"] #best-of-artist .chunk-load-more-inner { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.6rem; border-radius: 8px; border: 1px solid rgba(76, 196, 255, 0.38); background: rgba(7, 12, 26, 0.9); box-shadow: inset 0 0 0 1px rgba(255,255,255,0.05), 0 8px 24px rgba(0,0,0,0.3); padding: 1rem; }
      body[data-page="home"] #best-of-artist .chunk-load-more-btn { width: 100%; }
      body[data-page="home"] #best-of-artist .chunk-load-more-hint { margin: 0; font-size: 0.78rem; letter-spacing: 0.04em; color: rgba(220,226,240,0.68); }
      body[data-page="home"] #best-of-artist .section-cta-best-of-toggle .btn { border: 1px solid transparent; background: linear-gradient(rgba(5,9,17,0.95), rgba(5,9,17,0.95)) padding-box, var(--rainbow-divider-gradient) border-box; color: #f4f7ff; }
      body[data-page="home"] #best-of-artist .section-cta-best-of-toggle .btn.is-active { border: 1px solid transparent; background: linear-gradient(rgba(15,78,115,0.92), rgba(9,56,90,0.92)) padding-box, linear-gradient(135deg, #35ceff 0%, #2a8bff 100%) border-box; color: #ebfbff; box-shadow: 0 0 0 1px rgba(34,191,255,0.34), 0 8px 18px rgba(0,0,0,0.26); }
      body[data-page="home"] #best-of-artist .home-rail-shell { position: relative; }
      body[data-page="home"] #best-of-artist .home-rail-controls { display: none; }
      body[data-page="home"] #videos-grid, body[data-page="home"] #audio-grid { display: flex; flex-direction: row; overflow-x: auto; gap: 12px; padding-bottom: 12px; scroll-snap-type: x mandatory; scroll-behavior: smooth; scroll-padding-inline: 4px; overscroll-behavior-x: contain; scrollbar-width: none; }
      body[data-page="home"] #videos-grid::-webkit-scrollbar, body[data-page="home"] #audio-grid::-webkit-scrollbar { display: none; }
      body[data-page="home"] #best-of-artist .media-card, body[data-page="home"] #best-of-artist .chunk-load-more-tile { flex: 0 0 75vw; max-width: 280px; scroll-snap-align: start; }
      @media (min-width: 901px) {
        body[data-page="home"] #best-of-artist .home-rail-controls { display: flex; position: absolute; top: 50%; left: -28px; right: -28px; transform: translateY(-50%); justify-content: space-between; align-items: center; pointer-events: none; z-index: 5; }
        body[data-page="home"] #best-of-artist .home-rail-controls[data-scrollable="false"] { display: none; }
        body[data-page="home"] #best-of-artist .chunk-load-more-tile { display: none !important; }
        body[data-page="home"] #best-of-artist .home-rail-arrow { pointer-events: auto; width: 54px; height: 54px; border-radius: 999px; padding: 0; display: inline-flex; align-items: center; justify-content: center; font-size: 1.32rem; background: rgba(7,9,16,0.86); border: 1px solid rgba(255,255,255,0.34); box-shadow: 0 10px 20px rgba(0,0,0,0.34); transition: background-color 0.2s ease, border-color 0.2s ease, transform 0.2s ease, opacity 0.2s ease; }
        body[data-page="home"] #best-of-artist .home-rail-arrow:hover:not([disabled]) { background: rgba(12,17,33,0.92); border-color: rgba(34,191,255,0.76); transform: translateY(-1px); }
        body[data-page="home"] #best-of-artist .home-rail-arrow[disabled] { opacity: 0.45; cursor: not-allowed; }
        body[data-page="home"] #videos-grid, body[data-page="home"] #audio-grid { gap: 16px; padding-bottom: 16px; }
        body[data-page="home"] #best-of-artist .media-card, body[data-page="home"] #best-of-artist .chunk-load-more-tile { flex: 0 0 340px; max-width: 340px; }
      }
    </style>
</head>
<body data-page="home" <?php body_class(); ?>>
<?php wp_body_open(); ?>

    <header class="site-header">
      <a class="brand" href="<?php echo home_url('/'); ?>" aria-label="DJ UrbanT home">
        <img class="brand-logo" src="<?php echo $theme_uri; ?>/assets/images/UT_TITLE_SVG.svg" alt="DJ UrbanT" />
      </a>
      <nav class="main-nav" aria-label="Main navigation">
        <a class="main-nav-book" href="<?php echo home_url('/contact/'); ?>">Book</a>
      </nav>
    </header>

    <section id="live" class="hero">
      <div class="hero-overlay" aria-hidden="true">
        <h2 class="hero-overlay-title">DJ <span class="urbant-wordmark"><span class="urbant-u">U</span>rban<span class="urbant-t">T</span></span> - Live</h2>
        <h2 class="hero-overlay-title hero-overlay-title-alt">DJ <span class="urbant-wordmark"><span class="urbant-u">U</span>rban<span class="urbant-t">T</span></span> - Live</h2>
      </div>
      <p class="hero-tagline" data-cms-text="page.hero.tagline">Bass House. Tech House. Live Sets.</p>
      <div class="diamond-stage" aria-hidden="true">
        <img class="hero-proto" src="<?php echo $theme_uri; ?>/assets/images/djurbant-proto-1.png" alt="" />
      </div>
      <a class="btn btn-live" href="#best-of-artist">Watch Latest Video</a>
    </section>

    <main>
      <section id="best-of-artist" class="media-section">
        <div class="section-head">
          <h2 data-cms-text="page.bestOf.title">The Best of Artist</h2>
        </div>
        <div class="section-cta section-cta-best-of-toggle">
          <button id="best-of-toggle-video" class="btn btn-outline is-active" type="button" aria-pressed="true">Video</button>
          <button id="best-of-toggle-audio" class="btn btn-outline" type="button" aria-pressed="false">Audio</button>
        </div>
        <div class="home-rail-shell">
          <div class="home-rail-controls" aria-label="Best of Artist rail controls">
            <button id="best-of-rail-prev" class="btn btn-outline home-rail-arrow" type="button" aria-label="Scroll left">&larr;</button>
            <button id="best-of-rail-next" class="btn btn-outline home-rail-arrow" type="button" aria-label="Scroll right">&rarr;</button>
          </div>
          <div id="videos-grid" class="media-grid"></div>
          <div id="audio-grid" class="media-grid"></div>
        </div>
      </section>

      <section class="stats-strip" aria-label="DJ UrbanT highlights" data-cms-visible-if="page.sections.showStatsBand">
        <div class="stats-strip-grid">
          <article class="stats-card">
            <p class="stats-card-value">434<span class="accent-plus">+</span></p>
            <p class="stats-card-label">Sets</p>
          </article>
          <article class="stats-card">
            <p class="stats-card-value gradient">Bass<br/>House</p>
            <p class="stats-card-label">&amp; Tech House</p>
          </article>
          <article class="stats-card">
            <p class="stats-card-value">5<span class="accent-plus">+</span></p>
            <p class="stats-card-label">Platforms</p>
          </article>
          <article class="stats-card">
            <p class="stats-card-value">On<br/>Demand</p>
            <p class="stats-card-label">Full Archive</p>
          </article>
        </div>
      </section>

      <section class="about-cta-section" aria-label="Booking CTA" data-cms-visible-if="page.sections.showBookingBand">
        <div class="about-cta-bar">
          <h2 class="about-cta-title" data-cms-text="page.bookingBand.title">Bring the set to you.</h2>
          <a class="about-cta-book" href="<?php echo home_url('/contact/'); ?>" aria-label="Book DJ UrbanT">
            <span data-cms-text="page.bookingBand.buttonLabel">Book</span>
          </a>
        </div>
      </section>
    </main>

    <?php get_template_part('footer-djurbant'); ?>

<?php // Inline the home-specific chunked loading script ?>
<script>
(() => {
    const CHUNK_SIZE = 12, INITIAL_COUNT_MOBILE = 3, INITIAL_COUNT_DESKTOP = 6, FORCE_VISIBLE_FROM = 3, AUTOLOAD_THRESHOLD = 0.78, RAIL_DESKTOP_QUERY = "(min-width: 901px)";
    const getInitialCount = () => window.matchMedia(RAIL_DESKTOP_QUERY).matches ? INITIAL_COUNT_DESKTOP : INITIAL_COUNT_MOBILE;
    const isDesktopRail = () => window.matchMedia(RAIL_DESKTOP_QUERY).matches;
    const getCards = (c) => [...c.querySelectorAll(":scope > .media-card:not(.chunk-load-more-tile)")];
    function createLoadMoreTile(r, onClick) { const a = document.createElement("article"); a.className = "media-card chunk-load-more-tile"; const i = document.createElement("div"); i.className = "chunk-load-more-inner"; const b = document.createElement("button"); b.type = "button"; b.className = "btn btn-outline chunk-load-more-btn"; b.textContent = "More"; b.addEventListener("click", onClick); const h = document.createElement("p"); h.className = "chunk-load-more-hint"; h.textContent = r + "+ more"; i.append(b, h); a.append(i); return a; }
    function ensureViewportMinimum(c) { const cards = getCards(c); if (!cards.length) return; const cv = Number(c.dataset.chunkVisibleCount) || 0; const mv = Math.min(cards.length, getInitialCount()); if (cv < mv) c.dataset.chunkVisibleCount = String(mv); }
    function maybeAutoLoadChunk(c, force) { if (!c || c.dataset.chunkAutoLoading === "1" || !isDesktopRail()) return; const cards = getCards(c); if (!cards.length) return; const vc = Math.min(Number(c.dataset.chunkVisibleCount) || getInitialCount(), cards.length); if (vc >= cards.length) return; if (!force) { const ms = Math.max(0, c.scrollWidth - c.clientWidth); if (ms <= 0 || c.scrollLeft < ms * AUTOLOAD_THRESHOLD) return; } c.dataset.chunkAutoLoading = "1"; setTimeout(() => { const cv = Math.min(Number(c.dataset.chunkVisibleCount) || getInitialCount(), cards.length); const nv = Math.min(cards.length, cv + CHUNK_SIZE); if (nv !== cv) { c.dataset.chunkVisibleCount = String(nv); c._applyChunkState?.(); } c.dataset.chunkAutoLoading = "0"; }, 90); }
    function initChunkedContainer(id) { const c = document.getElementById(id); if (!c) return false; const cards = getCards(c); if (!cards.length) return false; if (!c.dataset.chunkVisibleCount) c.dataset.chunkVisibleCount = String(Math.min(getInitialCount(), cards.length)); ensureViewportMinimum(c); const apply = () => { const all = getCards(c); const vc = Math.min(Number(c.dataset.chunkVisibleCount) || getInitialCount(), all.length); all.forEach((card, i) => { card.classList.toggle("chunk-hidden", i >= vc); if (i >= FORCE_VISIBLE_FROM) card.classList.toggle("chunk-force-visible", i < vc); else card.classList.remove("chunk-force-visible"); }); const et = c.querySelector(":scope > .chunk-load-more-tile"); if (et) et.remove(); const rem = all.length - vc; if (!isDesktopRail() && rem > 0) c.appendChild(createLoadMoreTile(rem, () => { c.dataset.chunkVisibleCount = String(Math.min(all.length, vc + CHUNK_SIZE)); apply(); })); requestAnimationFrame(() => { maybeAutoLoadChunk(c, false); syncRailControls(); }); }; c._applyChunkState = apply; apply(); return true; }
    const targets = ["videos-grid", "audio-grid"]; let att = 0; const iv = setInterval(() => { att++; let init = 0; targets.forEach(id => { const c = document.getElementById(id); if (!c) return; if (c.dataset.chunkInit === "1") { init++; return; } if (initChunkedContainer(id)) { c.dataset.chunkInit = "1"; init++; } }); if (init === targets.length || att >= 160) clearInterval(iv); }, 120);
    const reapplyAll = () => { targets.forEach(id => { const c = document.getElementById(id); if (c) ensureViewportMinimum(c); c?._applyChunkState?.(); }); syncRailControls(); };
    const vt = document.getElementById("best-of-toggle-video"), at = document.getElementById("best-of-toggle-audio"), pb = document.getElementById("best-of-rail-prev"), nb = document.getElementById("best-of-rail-next"), rc = document.querySelector("#best-of-artist .home-rail-controls");
    const isDesktop = () => window.matchMedia(RAIL_DESKTOP_QUERY).matches;
    const getActiveRail = () => document.getElementById(at?.classList.contains("is-active") ? "audio-grid" : "videos-grid");
    const getRailGap = r => { if (!r) return 12; const s = getComputedStyle(r); const g = parseFloat(s.columnGap || s.gap || "12"); return isFinite(g) ? g : 12; };
    const getRailStep = r => { if (!r) return 0; const fc = r.querySelector(":scope > .media-card:not(.chunk-hidden)"); const g = getRailGap(r); if (fc) { const cs = fc.getBoundingClientRect().width + g; const cpv = Math.max(1, Math.floor((r.clientWidth + g) / cs)); return Math.max(220, Math.round(cs * Math.max(1, cpv - 1))); } return Math.max(220, Math.round(r.clientWidth * 0.86)); };
    const syncRailControls = () => { if (!pb || !nb) return; if (!isDesktop()) { pb.disabled = nb.disabled = true; rc?.setAttribute("data-scrollable", "false"); return; } const r = getActiveRail(); if (!r) { pb.disabled = nb.disabled = true; rc?.setAttribute("data-scrollable", "false"); return; } const ms = Math.max(0, r.scrollWidth - r.clientWidth); const scrollable = ms > 8; rc?.setAttribute("data-scrollable", scrollable ? "true" : "false"); if (!scrollable) { pb.disabled = nb.disabled = true; return; } pb.disabled = r.scrollLeft <= 2; nb.disabled = r.scrollLeft >= ms - 2; };
    const syncAfterScroll = () => { let fc = 0, ll = -1; const tick = () => { fc++; const r = getActiveRail(); if (!r) { syncRailControls(); return; } syncRailControls(); if (fc > 24 || (fc > 3 && Math.abs(r.scrollLeft - ll) < 0.5)) return; ll = r.scrollLeft; requestAnimationFrame(tick); }; requestAnimationFrame(tick); };
    const scrollRail = d => { const r = getActiveRail(); if (!r) return; if (d > 0) { const ms = Math.max(0, r.scrollWidth - r.clientWidth); if (ms < 8 || r.scrollLeft >= ms * AUTOLOAD_THRESHOLD) maybeAutoLoadChunk(r, true); } r.scrollBy({ left: d * getRailStep(r), behavior: "smooth" }); syncAfterScroll(); };
    pb?.addEventListener("click", () => scrollRail(-1)); nb?.addEventListener("click", () => scrollRail(1));
    targets.forEach(id => { document.getElementById(id)?.addEventListener("scroll", () => { maybeAutoLoadChunk(document.getElementById(id), false); syncRailControls(); }, { passive: true }); });
    vt?.addEventListener("click", () => setTimeout(() => { reapplyAll(); syncRailControls(); }, 0));
    at?.addEventListener("click", () => setTimeout(() => { reapplyAll(); syncRailControls(); }, 0));
    window.addEventListener("resize", reapplyAll, { passive: true }); syncRailControls();
})();
</script>

<?php wp_footer(); ?>
</body>
</html>
