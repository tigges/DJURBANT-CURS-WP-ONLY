<?php
/**
 * Template Name: DJ UrbanT Contact
 *
 * Renders the original djurbant.com contact page inside WordPress.
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
      body[data-page="contact"] .media-section { position: relative; border-top: 1px solid rgba(255,255,255,0.14); border-bottom: 1px solid rgba(255,255,255,0.14); background: #000; padding: clamp(1.5rem,3vw,2.5rem) clamp(1rem,3vw,2.2rem); margin-top: 0; }
      body[data-page="contact"] .media-section::before { content:""; position:absolute; left:0; right:0; bottom:-1px; height:1px; background:var(--rainbow-divider-gradient); pointer-events:none; }
      body[data-page="contact"] .subpage-main { background: #000; padding-top: 0; }
      .wpforms-container { background: transparent !important; }
      .wpforms-form .wpforms-field-label { color: rgba(220,226,240,0.85) !important; font-family: "Syne",sans-serif !important; font-weight: 500 !important; }
      .wpforms-form input[type="text"], .wpforms-form input[type="email"], .wpforms-form input[type="tel"], .wpforms-form input[type="date"], .wpforms-form input[type="url"], .wpforms-form textarea, .wpforms-form select { background: rgba(255,255,255,0.06) !important; border: 1px solid rgba(255,255,255,0.18) !important; color: #f1f1f1 !important; border-radius: 8px !important; padding: 0.7rem 0.85rem !important; font-family: "Syne",sans-serif !important; }
      .wpforms-form input::placeholder, .wpforms-form textarea::placeholder { color: rgba(170,176,190,0.5) !important; }
      .wpforms-form input:focus, .wpforms-form textarea:focus, .wpforms-form select:focus { border-color: rgba(0,198,255,0.5) !important; outline: none !important; box-shadow: 0 0 0 2px rgba(0,198,255,0.15) !important; }
      .wpforms-form .wpforms-submit-container button { background: var(--cta-gradient, linear-gradient(118deg, #20d5ff, #2f7bff, #7a2cff, #ff2f8a)) !important; color: #fff !important; border: none !important; border-radius: 10px !important; padding: 0.8rem 2rem !important; font-weight: 700 !important; text-transform: uppercase !important; letter-spacing: 0.08em !important; cursor: pointer !important; }
      .wpforms-confirmation-container-full { background: rgba(0,198,255,0.1) !important; border: 1px solid rgba(0,198,255,0.3) !important; border-radius: 10px !important; color: #dff7ff !important; }
    </style>
</head>
<body data-page="contact" <?php body_class(); ?>>
<?php wp_body_open(); ?>

    <header class="site-header">
      <a class="brand" href="<?php echo home_url('/'); ?>" aria-label="DJ UrbanT home">
        <img class="brand-logo" src="<?php echo $theme_uri; ?>/assets/images/UT_TITLE_SVG.svg" alt="DJ UrbanT" />
      </a>
      <nav class="main-nav" aria-label="Main navigation">
        <a class="main-nav-book" href="<?php echo home_url('/'); ?>">Home</a>
      </nav>
    </header>

    <main class="subpage-main">
      <section class="media-section">
        <div class="section-head">
          <h1 data-cms-text="page.title">Contact DJ UrbanT</h1>
        </div>
        <p class="subpage-intro" data-cms-text="page.introText">Use this form for bookings, event inquiries, collaborations, and press.</p>
        <p class="subpage-intro">We usually reply within 24 hours.</p>

        <?php
        if (function_exists('wpforms_display')) {
            wpforms_display(54, true, true);
        } else {
            echo do_shortcode('[wpforms id="54"]');
        }
        ?>

        <div class="section-cta" style="margin-top:1.5rem;">
          <a class="btn btn-outline" href="<?php echo home_url('/'); ?>" data-cms-text="page.backButtonLabel">Back Home</a>
        </div>
      </section>
    </main>

    <?php get_template_part('footer-djurbant'); ?>

<?php wp_footer(); ?>
</body>
</html>
