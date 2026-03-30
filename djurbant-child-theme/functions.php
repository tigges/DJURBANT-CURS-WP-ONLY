<?php
/**
 * DJ UrbanT Child Theme
 *
 * Loads the original djurbant.com CSS/JS assets and provides
 * custom page templates that render the original site's HTML
 * structure inside WordPress.
 */

defined('ABSPATH') || exit;

/**
 * Dequeue parent theme styles on pages using our custom templates,
 * and enqueue the original djurbant.com assets instead.
 */
function djurbant_enqueue_assets() {
    $theme_uri = get_stylesheet_directory_uri();
    $page_template = get_page_template_slug();

    $is_admin_or_map = in_array($page_template, [
        'page-templates/admin.php',
        'page-templates/map.php',
    ], true);

    if (!$is_admin_or_map) {
        wp_enqueue_style(
            'djurbant-main',
            $theme_uri . '/djurbant-styles.css',
            [],
            '1.0.0'
        );
    }

    wp_enqueue_style(
        'djurbant-wp-overrides',
        $theme_uri . '/wp-overrides.css',
        $is_admin_or_map ? [] : ['djurbant-main'],
        '1.0.0'
    );
}
add_action('wp_enqueue_scripts', 'djurbant_enqueue_assets', 20);

/**
 * Enqueue the original JS files in footer (skip on admin/map pages)
 */
function djurbant_enqueue_scripts() {
    $theme_uri = get_stylesheet_directory_uri();
    $page_template = get_page_template_slug();

    if (in_array($page_template, ['page-templates/admin.php', 'page-templates/map.php'], true)) {
        return;
    }

    wp_enqueue_script(
        'djurbant-cms-content',
        $theme_uri . '/cms-content.js',
        [],
        '1.0.0',
        true
    );

    wp_enqueue_script(
        'djurbant-main-script',
        $theme_uri . '/djurbant-script.js',
        ['djurbant-cms-content'],
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'djurbant_enqueue_scripts', 20);

/**
 * Fix asset paths in the original CSS (./assets/ → theme URI)
 */
function djurbant_fix_asset_paths() {
    $theme_uri = get_stylesheet_directory_uri();
    echo '<style>
    @font-face { font-family: "Syne"; font-weight: 400; font-display: swap; src: url("' . $theme_uri . '/assets/fonts/Syne-Regular.ttf") format("truetype"); }
    @font-face { font-family: "Syne"; font-weight: 500; font-display: swap; src: url("' . $theme_uri . '/assets/fonts/Syne-Medium.ttf") format("truetype"); }
    @font-face { font-family: "Syne"; font-weight: 600; font-display: swap; src: url("' . $theme_uri . '/assets/fonts/Syne-SemiBold.ttf") format("truetype"); }
    @font-face { font-family: "Syne"; font-weight: 700; font-display: swap; src: url("' . $theme_uri . '/assets/fonts/Syne-Bold.ttf") format("truetype"); }
    @font-face { font-family: "Montserrat"; font-weight: 900; font-display: swap; src: url("' . $theme_uri . '/assets/fonts/Montserrat-Black.ttf") format("truetype"); }
    @font-face { font-family: "Russo One"; font-weight: 400; font-display: swap; src: url("' . $theme_uri . '/assets/fonts/RussoOne-Regular.ttf") format("truetype"); }
    @font-face { font-family: "Barlow Condensed"; font-weight: 700; font-display: swap; src: url("' . $theme_uri . '/assets/fonts/BarlowCondensed-Bold.ttf") format("truetype"); }
    </style>';
}
add_action('wp_head', 'djurbant_fix_asset_paths', 5);

/**
 * Provide the site-content.json and media-data.json URLs to JS
 */
function djurbant_localize_content_urls() {
    $theme_uri = get_stylesheet_directory_uri();
    echo '<script>
    window.__DJURBANT_THEME_URI = "' . esc_url($theme_uri) . '";
    </script>';
}
add_action('wp_head', 'djurbant_localize_content_urls', 6);

/**
 * Add body classes for the original page identification
 */
function djurbant_body_data_page($classes) {
    if (is_front_page()) {
        $classes[] = 'djurbant-home';
    } elseif (is_page('video')) {
        $classes[] = 'djurbant-video';
    } elseif (is_page('audio')) {
        $classes[] = 'djurbant-audio';
    } elseif (is_page('contact')) {
        $classes[] = 'djurbant-contact';
    }
    return $classes;
}
add_filter('body_class', 'djurbant_body_data_page');

/**
 * Add data-page attribute to body tag
 */
function djurbant_body_attributes() {
    $page = 'home';
    if (is_page('video')) $page = 'video';
    elseif (is_page('audio') || is_page('audio-more')) $page = 'audio-more';
    elseif (is_page('contact')) $page = 'contact';
    elseif (!is_front_page()) $page = '';

    if ($page) {
        echo ' data-page="' . esc_attr($page) . '"';
    }
}

/**
 * Register custom page templates
 */
function djurbant_register_templates($templates) {
    $templates['page-templates/home.php'] = 'DJ UrbanT Home';
    $templates['page-templates/video.php'] = 'DJ UrbanT Video';
    $templates['page-templates/audio.php'] = 'DJ UrbanT Audio';
    $templates['page-templates/contact.php'] = 'DJ UrbanT Contact';
    $templates['page-templates/map.php'] = 'DJ UrbanT Map';
    $templates['page-templates/admin.php'] = 'DJ UrbanT Admin';
    return $templates;
}
add_filter('theme_page_templates', 'djurbant_register_templates');

/**
 * Load custom template files
 */
function djurbant_load_template($template) {
    $page_template = get_page_template_slug();
    if ($page_template && file_exists(get_stylesheet_directory() . '/' . $page_template)) {
        return get_stylesheet_directory() . '/' . $page_template;
    }
    return $template;
}
add_filter('page_template', 'djurbant_load_template');

/**
 * REST API endpoint for reading/writing social links from site-content.json
 */
function djurbant_register_social_api() {
    register_rest_route('djurbant/v1', '/socials', [
        [
            'methods' => 'GET',
            'callback' => 'djurbant_get_socials',
            'permission_callback' => '__return_true',
        ],
        [
            'methods' => 'POST',
            'callback' => 'djurbant_update_socials',
            'permission_callback' => function() {
                return current_user_can('manage_options');
            },
        ],
    ]);
}
add_action('rest_api_init', 'djurbant_register_social_api');

function djurbant_get_socials() {
    $file = get_stylesheet_directory() . '/site-content.json';
    if (!file_exists($file)) return new WP_Error('not_found', 'site-content.json not found', ['status' => 404]);
    $data = json_decode(file_get_contents($file), true);
    return rest_ensure_response($data['global']['socialLinks'] ?? []);
}

function djurbant_update_socials($request) {
    $file = get_stylesheet_directory() . '/site-content.json';
    if (!file_exists($file)) return new WP_Error('not_found', 'site-content.json not found', ['status' => 404]);
    $data = json_decode(file_get_contents($file), true);
    $new_socials = $request->get_json_params();
    if (!is_array($new_socials)) return new WP_Error('invalid', 'Invalid data', ['status' => 400]);
    $data['global']['socialLinks'] = $new_socials;
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    return rest_ensure_response(['success' => true, 'socials' => $new_socials]);
}

/**
 * REST API endpoint for WPForms entries count (bookings)
 */
function djurbant_register_bookings_api() {
    register_rest_route('djurbant/v1', '/bookings-count', [
        'methods' => 'GET',
        'callback' => function() {
            if (function_exists('wpforms_get_entries_count')) {
                return rest_ensure_response(['count' => wpforms_get_entries_count(54)]);
            }
            $entries = get_posts(['post_type' => 'wpforms_entry', 'post_status' => 'publish', 'numberposts' => -1, 'meta_query' => [['key' => 'form_id', 'value' => '54']]]);
            return rest_ensure_response(['count' => count($entries)]);
        },
        'permission_callback' => function() { return current_user_can('manage_options'); },
    ]);
}
add_action('rest_api_init', 'djurbant_register_bookings_api');
function djurbant_maybe_remove_kadence_wrappers() {
    $page_template = get_page_template_slug();
    if ($page_template && strpos($page_template, 'page-templates/') === 0) {
        remove_action('kadence_header', 'Kadence\header_markup');
        remove_action('kadence_footer', 'Kadence\footer_markup');
    }
}
add_action('wp', 'djurbant_maybe_remove_kadence_wrappers');
