<?php
/**
 * Pages tab data - section definitions for each page
 */
$page_tabs = [
    'home' => [
        'label' => 'Home',
        'url' => home_url('/'),
        'template' => 'page-templates/home.php',
        'preview' => 'homepage-preview.png',
        'sections' => [
            ['num' => 1, 'name' => 'Header', 'desc' => 'Fixed top bar — logo, nav, Book button', 'pos' => 'top:1%;left:2%'],
            ['num' => 2, 'name' => 'Hero Banner', 'desc' => '3D diamond, "DJ URBANT - LIVE", tagline, rainbow CTA', 'pos' => 'top:15%;left:38%'],
            ['num' => 3, 'name' => 'Best of Artist', 'desc' => 'Video/Audio toggle, horizontal card carousel', 'pos' => 'top:40%;left:2%'],
            ['num' => 4, 'name' => 'Stats Strip', 'desc' => '434+ Sets | Bass House | 5+ Platforms | On Demand', 'pos' => 'top:56%;left:2%'],
            ['num' => 5, 'name' => 'Booking CTA', 'desc' => '"Bring the set to you." + Book button', 'pos' => 'top:72%;left:2%'],
            ['num' => 6, 'name' => 'Footer', 'desc' => 'Logo, 6 social icons, copyright', 'pos' => 'top:90%;left:2%'],
        ],
    ],
    'video' => [
        'label' => 'Video',
        'url' => home_url('/video/'),
        'template' => 'page-templates/video.php',
        'preview' => 'video-preview.png',
        'extra_links' => [
            ['label' => 'Open YouTube Studio', 'url' => 'https://studio.youtube.com', 'external' => true],
            ['label' => 'View Channel', 'url' => 'https://www.youtube.com/@DJ_UrbanT', 'external' => true],
            ['label' => 'Edit media-data.json', 'url' => 'THEME_EDITOR:media-data.json', 'external' => false],
        ],
        'sections' => [
            ['num' => 1, 'name' => 'Header', 'desc' => 'Logo + Contact nav link', 'pos' => 'top:1%;left:2%'],
            ['num' => 2, 'name' => 'Page Title', 'desc' => '"Video" heading + intro text', 'pos' => 'top:12%;left:5%'],
            ['num' => 3, 'name' => 'Video Grid', 'desc' => 'YouTube embeds — populated from media-data.json', 'pos' => 'top:28%;left:5%'],
            ['num' => 4, 'name' => 'Footer', 'desc' => 'Social icons + copyright', 'pos' => 'top:92%;left:2%'],
        ],
    ],
    'audio' => [
        'label' => 'Audio',
        'url' => home_url('/audio/'),
        'template' => 'page-templates/audio.php',
        'preview' => 'audio-preview.png',
        'sections' => [
            ['num' => 1, 'name' => 'Header', 'desc' => 'Logo + Contact nav link', 'pos' => 'top:1%;left:2%'],
            ['num' => 2, 'name' => 'Page Title', 'desc' => '"Audio" heading + intro text', 'pos' => 'top:15%;left:5%'],
            ['num' => 3, 'name' => 'Audio Grid', 'desc' => 'Mixcloud embeds — populated from media-data.json', 'pos' => 'top:35%;left:5%'],
            ['num' => 4, 'name' => 'Footer', 'desc' => 'Social icons + copyright', 'pos' => 'top:92%;left:2%'],
        ],
    ],
    'contact' => [
        'label' => 'Contact',
        'url' => home_url('/contact/'),
        'template' => 'page-templates/contact.php',
        'preview' => 'contact-preview.png',
        'extra_links' => [
            ['label' => 'Edit form fields', 'url' => admin_url('admin.php?page=fluent_forms&form_id=1&route=editor'), 'external' => false],
            ['label' => 'View messages', 'url' => admin_url('admin.php?page=fluent_forms&route=entries&form_id=1'), 'external' => false],
            ['label' => 'All forms', 'url' => admin_url('admin.php?page=fluent_forms'), 'external' => false],
        ],
        'sections' => [
            ['num' => 1, 'name' => 'Header', 'desc' => 'Logo + Home nav button', 'pos' => 'top:1%;left:2%'],
            ['num' => 2, 'name' => 'Page Title', 'desc' => '"Contact DJ UrbanT" heading', 'pos' => 'top:12%;left:5%'],
            ['num' => 3, 'name' => 'Intro Text', 'desc' => 'Booking description + reply SLA', 'pos' => 'top:20%;left:5%'],
            ['num' => 4, 'name' => 'Booking Form', 'desc' => 'Fluent Forms (ID 1) — Name, Email, Message', 'pos' => 'top:40%;left:5%'],
            ['num' => 5, 'name' => 'Footer', 'desc' => 'Social icons + copyright', 'pos' => 'top:92%;left:2%'],
        ],
    ],
];
?>
