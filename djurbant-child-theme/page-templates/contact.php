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
      body[data-page="contact"] .media-section { padding-top: var(--space-3); margin-top: var(--space-4); }
    </style>
</head>
<body data-page="contact" <?php body_class(); ?>>
<?php wp_body_open(); ?>

    <header class="site-header">
      <a class="brand" href="<?php echo home_url('/'); ?>" aria-label="DJ UrbanT home">
        <img class="brand-logo" src="<?php echo $theme_uri; ?>/assets/images/UT_TITLE_SVG.svg" alt="DJ UrbanT" />
      </a>
      <nav class="main-nav" aria-label="Main navigation">
        <a class="main-nav-book is-active" href="<?php echo home_url('/contact/'); ?>">Contact</a>
      </nav>
    </header>

    <main class="subpage-main">
      <section class="media-section">
        <div class="section-head">
          <h1 data-cms-text="page.title">Contact DJ UrbanT</h1>
        </div>
        <p class="subpage-intro" data-cms-text="page.introText">Use this form for bookings, event inquiries, collaborations, and press.</p>
        <p class="subpage-intro">We usually reply within 24 hours.</p>

        <form class="contact-form" action="mailto:booking@djurbant.com" method="post" enctype="text/plain" data-cms-action="page.formAction">
          <div class="contact-grid">
            <div class="contact-field">
              <span>Name</span>
              <input type="text" name="name" placeholder="Your name" required />
            </div>
            <div class="contact-field">
              <span>Email</span>
              <input type="email" name="email" placeholder="you@example.com" required />
            </div>
            <div class="contact-field">
              <span>Phone</span>
              <input type="tel" name="phone" placeholder="+1 (555) 000-0000" />
            </div>
            <div class="contact-field">
              <span>Event date</span>
              <input type="date" name="event_date" />
            </div>
            <div class="contact-field">
              <span>Subject</span>
              <input type="text" name="subject" placeholder="Booking / Collaboration / Press" />
            </div>
            <div class="contact-field">
              <span>Message</span>
              <textarea name="message" placeholder="Tell us about your event or inquiry…" rows="6" required></textarea>
            </div>
          </div>

          <div class="section-cta">
            <a class="btn btn-outline" href="<?php echo home_url('/'); ?>" data-cms-text="page.backButtonLabel">Back Home</a>
            <button class="btn btn-cta" type="submit" data-cms-text="page.submitButtonLabel">Send Inquiry</button>
          </div>
        </form>
      </section>
    </main>

    <?php get_template_part('footer-djurbant'); ?>

<?php wp_footer(); ?>
</body>
</html>
