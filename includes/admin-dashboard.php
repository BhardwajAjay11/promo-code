<?php
function render_promotional_banner_dashboard() {
    $popup_clicks = get_option('popup_banner_clicks', 0);
    $ticker_clicks = get_option('header_ticker_clicks', 0);
    $bannerHeader = get_option('header_banner_clicks', 0);
    // Get view stats
    $popup_views      = get_option('popup_banner_views', 0);
    $ticker_views     = get_option('header_ticker_views', 0);
    $bannerHeader_views = get_option('header_banner_views', 0);
    ?>
    <div class="wrap promo-banner-dashboard">
        <h1>📢 Promotional Banner Dashboard</h1>

        <div class="promo-banner-card promo-banner-instructions">
            <h2>📋 Getting Started with Promotional Banners</h2>
            <ul>
                <li>Go to the <strong>Add New Banner</strong> section to create and manage your banners.</li>
                <li>Choose from <strong>Popup Banner</strong>, <strong>Header Ticker</strong>, or <strong>Header Banner</strong> display types.</li>
                <li>Add your <strong>WooCommerce coupon codes</strong> to promote special deals or limited-time offers.</li>
                <li>Go to the <strong>All Banner</strong> section Copy and use the shortcode <code>[promotional_banner id="POST_ID"]</code> to display banners anywhere on your site.</li>
                <li>Track impressions and click-through rates using real-time stats below to measure performance.</li>
            </ul>
        </div>

         <!-- ✅ Active Banners Section -->
         <div class="promo-banner-card promo-active-banners">
            <h2>✅ Active Banners & Promotions</h2>
            <ul class="active-banner-list">
                <?php
                $today = current_time('Y-m-d');
                $args = array(
                    'post_type'      => 'promotional_banner',
                    'post_status'    => 'publish',
                    'posts_per_page' => -1,
                    'meta_query'     => array(
                        array(
                            'key'     => 'expiry_date',
                            'value'   => $today,
                            'compare' => '>=',
                            'type'    => 'DATE'
                        )
                    )
                );

                $active_banners = new WP_Query($args);

                if ($active_banners->have_posts()) {
                    while ($active_banners->have_posts()) {
                        $active_banners->the_post();
                        $position = get_post_meta(get_the_ID(), 'display_position', true);
                        $expiry = get_post_meta(get_the_ID(), 'expiry_date', true);
                        ?>
                        <li>
                            <strong>Cuopon Name</strong> <?php the_title(); ?>
                        </li>
                        <li> 
                            Type: <code><?php echo esc_html(ucfirst($position)); ?></code> 
                            | Expires: <?php echo esc_html($expiry); ?> 
                            | Shortcode: <code>[promotional_banner id="<?php echo get_the_ID(); ?>"]</code>
                        </li>
                        <?php
                    }
                    wp_reset_postdata();
                } else {
                    echo '<li>No active banners found.</li>';
                }
                ?>
            </ul>
        </div>

        <div class="promo-banner-card promo-banner-stats">
            <h2>📊 Click & View Statistics</h2>
            <div class="promo-stats-columns">

                <!-- Popup Banner -->
                <div class="promo-stat-item">
                    <div class="promo-stat-text">
                        <h3>📢 Popup Banner</h3>
                        <p class="click-count"><?php echo esc_html($popup_clicks); ?> Clicks</p>
                        <p class="view-count"><?php echo esc_html($popup_views); ?> Views</p>
                    </div>
                </div>

                <!-- banner header -->
                <div class="promo-stat-item">
                    <div class="promo-stat-text">
                        <h3>📰 Banner Header</h3>
                        <p class="click-count"><?php echo esc_html($bannerHeader); ?> Clicks</p>
                        <p class="view-count"><?php echo esc_html($bannerHeader_views); ?> Views</p>
                    </div>
                </div>

                <!-- Header Ticker -->
                <div class="promo-stat-item">
                    <div class="promo-stat-text">
                        <h3>📰 Header Ticker</h3>
                        <p class="click-count"><?php echo esc_html($ticker_clicks); ?> Clicks</p>
                        <p class="view-count"><?php echo esc_html($ticker_views); ?> Views</p>
                    </div>
                </div>

            </div>
        </div>     
    </div>
    <?php
}

add_action('wp_ajax_track_banner_click', 'track_banner_click');
add_action('wp_ajax_nopriv_track_banner_click', 'track_banner_click');

function track_banner_click() {
    $bannerType = isset($_GET['type']) ? sanitize_text_field($_GET['type']) : '';


    if ($bannerType === 'popup') {
        $clicks = get_option('banner_clicks', 0);
        update_option('banner_clicks', $clicks + 1);
        $views = get_option('popup_banner_views', 0);
        update_option('popup_banner_views', $views + 1);
    }

    if ($bannerType === 'header-ticker') {
        $clicks = get_option('header_ticker_clicks', 0);
        update_option('header_ticker_clicks', $clicks + 1);
        $views = get_option('header_ticker_views', 0);   
        update_option('header_ticker_views', $views + 1);
    }

    if ($bannerType === 'header-banner') {
        $clicks = get_option('header_banner_clicks', 0);
        update_option('header_banner_clicks', $clicks + 1);
        $views = get_option('header_banner_views', 0);   
        update_option('header_banner_views', $views + 1);
    }


    wp_die();
}

function ticker_initialize_click_options() {
    add_option('banner_clicks', 0);
    add_option('header_ticker_clicks', 0);
    add_option('header_banner_clicks', 0);
}
add_action('admin_init', 'ticker_initialize_click_options');
