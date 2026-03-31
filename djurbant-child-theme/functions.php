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
 * REST API endpoint for WPForms entries (bookings)
 */
function djurbant_register_bookings_api() {
    register_rest_route('djurbant/v1', '/bookings-count', [
        'methods' => 'GET',
        'callback' => function() {
            global $wpdb;
            $count = 0;
            // Check Fluent Forms entries table
            $ff_table = $wpdb->prefix . 'fluentform_submissions';
            if ($wpdb->get_var("SHOW TABLES LIKE '$ff_table'") === $ff_table) {
                $count = (int) $wpdb->get_var("SELECT COUNT(*) FROM $ff_table WHERE form_id = 1 AND status != 'trashed'");
                $unread = (int) $wpdb->get_var("SELECT COUNT(*) FROM $ff_table WHERE form_id = 1 AND status = 'unread'");
                return rest_ensure_response(['count' => $count, 'unread' => $unread, 'source' => 'fluent_forms']);
            }
            // Fallback to WPForms
            $wp_table = $wpdb->prefix . 'wpforms_entries';
            if ($wpdb->get_var("SHOW TABLES LIKE '$wp_table'") === $wp_table) {
                $count = (int) $wpdb->get_var("SELECT COUNT(*) FROM $wp_table WHERE form_id = 54");
                return rest_ensure_response(['count' => $count, 'unread' => 0, 'source' => 'wpforms']);
            }
            return rest_ensure_response(['count' => 0, 'unread' => 0, 'source' => 'none']);
        },
        'permission_callback' => function() { return current_user_can('manage_options'); },
    ]);

    register_rest_route('djurbant/v1', '/bookings', [
        'methods' => 'GET',
        'callback' => function() {
            global $wpdb;
            $table = $wpdb->prefix . 'wpforms_entries';
            if ($wpdb->get_var("SHOW TABLES LIKE '$table'") !== $table) {
                return rest_ensure_response([]);
            }
            $entries = $wpdb->get_results(
                "SELECT entry_id, fields, status, date_created FROM $table WHERE form_id = 54 ORDER BY date_created DESC LIMIT 50",
                ARRAY_A
            );
            $result = [];
            foreach ($entries as $e) {
                $fields = json_decode($e['fields'], true) ?: [];
                $name = '';
                $email = '';
                $message = '';
                foreach ($fields as $f) {
                    $label = strtolower($f['name'] ?? '');
                    if (strpos($label, 'name') !== false && !$name) $name = $f['value'] ?? '';
                    elseif (strpos($label, 'email') !== false && !$email) $email = $f['value'] ?? '';
                    elseif (strpos($label, 'message') !== false || strpos($label, 'comment') !== false) $message = $f['value'] ?? '';
                }
                $result[] = [
                    'id' => $e['entry_id'],
                    'name' => $name,
                    'email' => $email,
                    'message' => mb_substr($message, 0, 120),
                    'status' => $e['status'] ?: 'new',
                    'date' => $e['date_created'],
                ];
            }
            return rest_ensure_response($result);
        },
        'permission_callback' => function() { return current_user_can('manage_options'); },
    ]);
}
add_action('rest_api_init', 'djurbant_register_bookings_api');

/**
 * REST API endpoint for reading/writing site content text (site-content.json)
 */
function djurbant_register_content_api() {
    register_rest_route('djurbant/v1', '/content', [
        [
            'methods' => 'GET',
            'callback' => function() {
                $file = get_stylesheet_directory() . '/site-content.json';
                if (!file_exists($file)) return new WP_Error('not_found', 'File not found', ['status' => 404]);
                return rest_ensure_response(json_decode(file_get_contents($file), true));
            },
            'permission_callback' => function() { return current_user_can('manage_options'); },
        ],
        [
            'methods' => 'POST',
            'callback' => function($request) {
                $file = get_stylesheet_directory() . '/site-content.json';
                if (!file_exists($file)) return new WP_Error('not_found', 'File not found', ['status' => 404]);
                $updates = $request->get_json_params();
                if (!is_array($updates)) return new WP_Error('invalid', 'Invalid data', ['status' => 400]);
                $data = json_decode(file_get_contents($file), true);
                foreach ($updates as $path => $value) {
                    $keys = explode('.', $path);
                    $ref = &$data;
                    foreach ($keys as $i => $key) {
                        if ($i === count($keys) - 1) {
                            $ref[$key] = $value;
                        } else {
                            if (!isset($ref[$key]) || !is_array($ref[$key])) $ref[$key] = [];
                            $ref = &$ref[$key];
                        }
                    }
                }
                file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                return rest_ensure_response(['success' => true]);
            },
            'permission_callback' => function() { return current_user_can('manage_options'); },
        ],
    ]);
}
add_action('rest_api_init', 'djurbant_register_content_api');

/**
 * REST API endpoint for Koko Analytics stats
 */
function djurbant_register_analytics_api() {
    register_rest_route('djurbant/v1', '/analytics', [
        'methods' => 'GET',
        'callback' => function($request) {
            global $wpdb;
            $table_stats = $wpdb->prefix . 'koko_analytics_site_stats';
            $table_posts = $wpdb->prefix . 'koko_analytics_post_stats';
            $result = ['today' => 0, 'today_visitors' => 0, 'week' => 0, 'week_visitors' => 0, 'month' => 0, 'month_visitors' => 0, 'top_pages' => [], 'top_referrers' => [], 'daily' => []];

            if ($wpdb->get_var("SHOW TABLES LIKE '$table_stats'") !== $table_stats) {
                return rest_ensure_response($result);
            }

            $today = date('Y-m-d');
            $week_ago = date('Y-m-d', strtotime('-7 days'));
            $month_ago = date('Y-m-d', strtotime('-30 days'));

            $today_row = $wpdb->get_row($wpdb->prepare("SELECT visitors, pageviews FROM $table_stats WHERE date = %s", $today), ARRAY_A);
            $result['today'] = (int)($today_row['pageviews'] ?? 0);
            $result['today_visitors'] = (int)($today_row['visitors'] ?? 0);

            $week_row = $wpdb->get_row($wpdb->prepare("SELECT SUM(visitors) as visitors, SUM(pageviews) as pageviews FROM $table_stats WHERE date >= %s", $week_ago), ARRAY_A);
            $result['week'] = (int)($week_row['pageviews'] ?? 0);
            $result['week_visitors'] = (int)($week_row['visitors'] ?? 0);

            $month_row = $wpdb->get_row($wpdb->prepare("SELECT SUM(visitors) as visitors, SUM(pageviews) as pageviews FROM $table_stats WHERE date >= %s", $month_ago), ARRAY_A);
            $result['month'] = (int)($month_row['pageviews'] ?? 0);
            $result['month_visitors'] = (int)($month_row['visitors'] ?? 0);

            $daily = $wpdb->get_results($wpdb->prepare("SELECT date, visitors, pageviews FROM $table_stats WHERE date >= %s ORDER BY date ASC", $month_ago), ARRAY_A);
            $result['daily'] = $daily ?: [];

            if ($wpdb->get_var("SHOW TABLES LIKE '$table_posts'") === $table_posts) {
                $top = $wpdb->get_results($wpdb->prepare("SELECT id as post_id, SUM(visitors) as visitors, SUM(pageviews) as pageviews FROM $table_posts WHERE date >= %s GROUP BY id ORDER BY pageviews DESC LIMIT 8", $week_ago), ARRAY_A);
                foreach ($top as &$p) {
                    $title = get_the_title($p['post_id']);
                    $p['title'] = $title ?: '(ID: ' . $p['post_id'] . ')';
                    $p['url'] = get_permalink($p['post_id']) ?: '';
                }
                $result['top_pages'] = $top ?: [];
            }

            $table_ref = $wpdb->prefix . 'koko_analytics_referrer_stats';
            $table_ref_urls = $wpdb->prefix . 'koko_analytics_referrer_urls';
            if ($wpdb->get_var("SHOW TABLES LIKE '$table_ref'") === $table_ref && $wpdb->get_var("SHOW TABLES LIKE '$table_ref_urls'") === $table_ref_urls) {
                $refs = $wpdb->get_results($wpdb->prepare("SELECT r.url, SUM(s.visitors) as visitors, SUM(s.pageviews) as pageviews FROM $table_ref s JOIN $table_ref_urls r ON s.id = r.id WHERE s.date >= %s GROUP BY s.id ORDER BY visitors DESC LIMIT 6", $week_ago), ARRAY_A);
                $result['top_referrers'] = $refs ?: [];
            }

            return rest_ensure_response($result);
        },
        'permission_callback' => function() { return current_user_can('manage_options'); },
    ]);
}
add_action('rest_api_init', 'djurbant_register_analytics_api');

/**
 * YouTube/Mixcloud auto-refresh pipeline
 */
define('DJURBANT_YT_CHANNEL_ID', 'UCWVZzuD7wttYjaJpHxy0iiA');
define('DJURBANT_MC_USER', 'urbant');
define('DJURBANT_FEATURED_BONUS', 150);

function djurbant_get_yt_api_key() {
    return get_option('djurbant_youtube_api_key', '');
}

function djurbant_fetch_youtube_videos($api_key) {
    if (!$api_key) return [];
    $search_url = 'https://www.googleapis.com/youtube/v3/search?part=snippet&channelId=' . DJURBANT_YT_CHANNEL_ID . '&type=video&order=date&maxResults=20&key=' . $api_key;
    $search_resp = wp_remote_get($search_url, ['timeout' => 15]);
    if (is_wp_error($search_resp)) return [];
    $search_data = json_decode(wp_remote_retrieve_body($search_resp), true);
    $video_ids = [];
    foreach ($search_data['items'] ?? [] as $item) {
        $video_ids[] = $item['id']['videoId'] ?? '';
    }
    $video_ids = array_filter($video_ids);
    if (empty($video_ids)) return [];

    $stats_url = 'https://www.googleapis.com/youtube/v3/videos?part=snippet,statistics&id=' . implode(',', $video_ids) . '&key=' . $api_key;
    $stats_resp = wp_remote_get($stats_url, ['timeout' => 15]);
    if (is_wp_error($stats_resp)) return [];
    $stats_data = json_decode(wp_remote_retrieve_body($stats_resp), true);

    $videos = [];
    foreach ($stats_data['items'] ?? [] as $item) {
        $videos[] = [
            'id' => $item['id'],
            'title' => $item['snippet']['title'] ?? '',
            'genres' => ['bass-house'],
            'url' => 'https://www.youtube.com/watch?v=' . $item['id'],
            'embedUrl' => 'https://www.youtube.com/embed/' . $item['id'],
            'thumbnailUrl' => $item['snippet']['thumbnails']['high']['url'] ?? '',
            'viewCount' => (int)($item['statistics']['viewCount'] ?? 0),
            'publishedAt' => substr($item['snippet']['publishedAt'] ?? '', 0, 10),
        ];
    }
    return $videos;
}

function djurbant_fetch_mixcloud_cloudcasts() {
    $url = 'https://api.mixcloud.com/' . DJURBANT_MC_USER . '/cloudcasts/?limit=30';
    $resp = wp_remote_get($url, ['timeout' => 15]);
    if (is_wp_error($resp)) return [];
    $data = json_decode(wp_remote_retrieve_body($resp), true);

    $audio = [];
    foreach ($data['data'] ?? [] as $item) {
        $audio[] = [
            'key' => $item['key'] ?? '',
            'title' => $item['name'] ?? '',
            'genres' => ['bass-house'],
            'url' => $item['url'] ?? ('https://www.mixcloud.com' . ($item['key'] ?? '')),
            'embedUrl' => 'https://www.mixcloud.com/widget/iframe/?hide_cover=0&mini=0&light=0&feed=' . urlencode($item['key'] ?? ''),
            'playCount' => (int)($item['play_count'] ?? 0),
            'favoriteCount' => (int)($item['favorite_count'] ?? 0),
            'publishedAt' => $item['created_time'] ?? '',
        ];
    }
    return $audio;
}

function djurbant_rank_media($items, $count_key = 'viewCount') {
    if (empty($items)) return ['top3' => [], 'rest' => []];

    usort($items, function($a, $b) use ($count_key) {
        return ($b[$count_key] ?? 0) - ($a[$count_key] ?? 0);
    });

    if (!empty($items)) {
        $newest_idx = 0;
        $newest_date = '';
        foreach ($items as $i => $item) {
            $d = $item['publishedAt'] ?? '';
            if ($d > $newest_date) { $newest_date = $d; $newest_idx = $i; }
        }
        if ($newest_idx > 0) {
            $newest = $items[$newest_idx];
            $newest['baseViewCount'] = $newest[$count_key] ?? 0;
            $newest['featuredBonus'] = DJURBANT_FEATURED_BONUS;
            $newest['isFeaturedRecent'] = true;
            $newest[$count_key] = ($newest[$count_key] ?? 0) + DJURBANT_FEATURED_BONUS;
            array_splice($items, $newest_idx, 1);
            array_unshift($items, $newest);
            usort($items, function($a, $b) use ($count_key) {
                return ($b[$count_key] ?? 0) - ($a[$count_key] ?? 0);
            });
        }
    }

    return [
        'top3' => array_slice($items, 0, 3),
        'rest' => array_slice($items, 3),
    ];
}

function djurbant_refresh_media_data() {
    $api_key = djurbant_get_yt_api_key();
    $videos = djurbant_fetch_youtube_videos($api_key);
    $audio = djurbant_fetch_mixcloud_cloudcasts();

    $file = get_stylesheet_directory() . '/media-data.json';
    $existing = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

    if (empty($videos) && empty($audio)) return false;

    $latest_vid_id = !empty($videos) ? $videos[0]['id'] : ($existing['youtubeLive']['latestVideoId'] ?? '');

    $media_data = [
        'generatedAt' => gmdate('Y-m-d\TH:i:s\Z'),
        'strategy' => [
            'videos' => 'Auto-generated from YouTube Data API v3. Ranked by viewCount desc, most recent pinned to slot #1.',
            'audio' => 'Auto-generated from Mixcloud API. Ranked by playCount desc, most recent pinned to slot #1.',
        ],
        'youtubeLive' => [
            'isLive' => false,
            'liveVideoId' => '',
            'liveUrl' => '',
            'latestVideoId' => $latest_vid_id,
            'latestUrl' => $latest_vid_id ? 'https://www.youtube.com/watch?v=' . $latest_vid_id : '',
        ],
        'videos' => !empty($videos) ? djurbant_rank_media($videos, 'viewCount') : ($existing['videos'] ?? ['top3' => [], 'rest' => []]),
        'audio' => !empty($audio) ? djurbant_rank_media($audio, 'playCount') : ($existing['audio'] ?? ['top3' => [], 'rest' => []]),
    ];

    file_put_contents($file, json_encode($media_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    return true;
}

// Register custom cron schedule
function djurbant_cron_schedules($schedules) {
    $schedules['sixhourly'] = ['interval' => 6 * HOUR_IN_SECONDS, 'display' => 'Every 6 hours'];
    return $schedules;
}
add_filter('cron_schedules', 'djurbant_cron_schedules');

// Schedule the refresh
if (!wp_next_scheduled('djurbant_media_refresh')) {
    wp_schedule_event(time(), 'sixhourly', 'djurbant_media_refresh');
}
add_action('djurbant_media_refresh', 'djurbant_refresh_media_data');

// REST endpoint to trigger manual refresh + store API key
function djurbant_register_feed_api() {
    register_rest_route('djurbant/v1', '/refresh-feed', [
        'methods' => 'POST',
        'callback' => function() {
            $result = djurbant_refresh_media_data();
            return rest_ensure_response(['success' => $result, 'time' => gmdate('Y-m-d H:i:s')]);
        },
        'permission_callback' => function() { return current_user_can('manage_options'); },
    ]);
    register_rest_route('djurbant/v1', '/youtube-key', [
        'methods' => 'POST',
        'callback' => function($request) {
            $key = $request->get_param('key');
            if ($key) {
                update_option('djurbant_youtube_api_key', sanitize_text_field($key));
                return rest_ensure_response(['success' => true]);
            }
            return rest_ensure_response(['success' => false]);
        },
        'permission_callback' => function() { return current_user_can('manage_options'); },
    ]);
}
add_action('rest_api_init', 'djurbant_register_feed_api');
function djurbant_maybe_remove_kadence_wrappers() {
    $page_template = get_page_template_slug();
    if ($page_template && strpos($page_template, 'page-templates/') === 0) {
        remove_action('kadence_header', 'Kadence\header_markup');
        remove_action('kadence_footer', 'Kadence\footer_markup');
    }
}
add_action('wp', 'djurbant_maybe_remove_kadence_wrappers');
