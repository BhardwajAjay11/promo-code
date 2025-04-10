<?php
add_action('admin_menu', 'customize_promotional_banner_menu', 999);
function customize_promotional_banner_menu() {
    // Remove default submenu items added by WordPress
    remove_submenu_page('edit.php?post_type=promotional_banner', 'edit.php?post_type=promotional_banner');
    remove_submenu_page('edit.php?post_type=promotional_banner', 'post-new.php?post_type=promotional_banner');

    // Add our custom menu items in the desired order

    // 1. Banner Dashboard (first item)
    add_submenu_page(
        'edit.php?post_type=promotional_banner',
        'Banner Dashboard',
        'Banner Dashboard',
        'manage_options',
        'promotional-banner-dashboard',
        'render_promotional_banner_dashboard'
    );

    // 2. List View (replace default “All Banners”)
    add_submenu_page(
        'edit.php?post_type=promotional_banner',
        'All Banners',
        'All Banners',
        'manage_options',
        'edit.php?post_type=promotional_banner',
        '', // Leave blank to let WP handle rendering
        1
    );

    // 3. Add New
    add_submenu_page(
        'edit.php?post_type=promotional_banner',
        'Add New Banner',
        'Add New Banner',
        'manage_options',
        'post-new.php?post_type=promotional_banner',
        ''
    );

    // 4. Popup Animation
    add_submenu_page(
        'edit.php?post_type=promotional_banner',
        'Popup Ticker Animation',
        'Popup Animation',
        'manage_options',
        'popup_ticker_animation',
        'render_popup_ticker_animation_settings'
    );

    // 5. Ticker Animation
    add_submenu_page(
        'edit.php?post_type=promotional_banner',
        'Ticker Animation',
        'Ticker Animation',
        'manage_options',
        'ticker_animation',
        'render_ticker_animation_settings'
    );
}
