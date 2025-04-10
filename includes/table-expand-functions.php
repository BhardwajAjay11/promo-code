<?php
// Add a custom column to show the shortcode in the admin panel
add_filter('manage_promotional_banner_posts_columns', 'add_promotional_banner_shortcode_column');
function add_promotional_banner_shortcode_column($columns) {
    $new_columns = array();

    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;

        // Insert our shortcode column *right after* the Title column
        if ($key === 'title') {
            $new_columns['shortcode'] = 'Shortcode';
            $new_columns['Status'] = 'Status';
            $new_columns['banner_Type'] = 'Banner Type';

        }
    }

    return $new_columns;
}

add_action('manage_promotional_banner_posts_custom_column', 'custom_promotional_banner_column_content', 10, 2);
function custom_promotional_banner_column_content($column, $post_id) {

    if ($column === 'shortcode') {
        echo '<code>[promotional_banner id="' . esc_attr($post_id) . '"]</code>';
    }

    if ($column === 'Status') {
        $expiry_date = get_post_meta($post_id, 'expiry_date', true);
        if ($expiry_date) {
            $current_date = current_time('Y-m-d');
            if ($expiry_date < $current_date) {
                echo '<span style="color:red;">Expired</span>';
            } else {
                echo '<span style="color:green;">Active</span>';
            }
        } else {
            echo '<span style="color:gray;">No Expiry Date</span>';
        }
    }

    if ($column === 'banner_Type') {
        $display_position = get_post_meta($post_id, 'display_position', true);
        echo esc_html($display_position ? ucfirst($display_position) : '—');
    }
}


function render_promotional_banner_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id' => '',
    ), $atts, 'promotional_banner');

    $post_id = intval($atts['id']);
    if (!$post_id) {
        return '⚠️ Invalid Banner ID';
    }

    // Assume you're querying a custom post type 'coupons'
    $coupons = new WP_Query(array(
        'post_type' => 'promotional_banner',
        'p' => $post_id
    ));

    if ($coupons->have_posts()) {
        ob_start(); // Start buffering

        while ($coupons->have_posts()) {
            $coupons->the_post();
            $title = get_the_title();
            $display_position = get_post_meta(get_the_ID(), 'display_position', true);
            $discount_type = get_post_meta(get_the_ID(), 'discount_type', true);
            $discount_value = get_post_meta(get_the_ID(), 'discount_value', true);
            $expiry_date = get_post_meta(get_the_ID(), 'expiry_date', true);
            $popup_page = get_post_meta(get_the_ID(), 'popup_page', true);
            $popup_height = get_post_meta(get_the_ID(), 'popup_height', true);
            $popup_width = get_post_meta(get_the_ID(), 'popup_width', true);
            $popup_bg_color = get_post_meta(get_the_ID(), 'popup_bg_color', true);
            $popup_text_color = get_post_meta(get_the_ID(), 'popup_text_color', true);
            $popup_bg_image = get_post_meta(get_the_ID(), 'popup_image', true);
            $popup_bg_video = get_post_meta(get_the_ID(), 'popup_video', true);

            $discount_text = ($discount_type === 'percent') ? "$discount_value% Off" : "$$discount_value Off";

            if ($display_position === 'popup'): ?>
                <div class="bannerwrapper">
                    <div id="homepageBannerPopup" class="popup-overlay" style="display:none;">
                        <div class="popup-content" style="
                            color:<?php echo esc_attr($popup_text_color); ?>;
                            background-size: cover;
                            <?php if (!$popup_bg_video): ?>
                                background-image: url('<?php echo esc_url($popup_bg_image); ?>');
                            <?php endif; ?>
                            width: <?php echo esc_attr($popup_width); ?>px;
                            height: <?php echo esc_attr($popup_height); ?>px;
                        ">
                            <?php if ($popup_bg_video): ?>
                                <div class="popup-video-wrapper">
                                    <iframe 
                                        class="popup-bg-video"
                                        src="https://www.youtube.com/embed/<?php echo esc_attr(get_youtube_video_id($popup_bg_video)); ?>?autoplay=1&loop=1&mute=1&playlist=<?php echo esc_attr(get_youtube_video_id($popup_bg_video)); ?>"
                                        frameborder="0"
                                        allow="autoplay; encrypted-media"
                                        allowfullscreen>
                                    </iframe>
                                </div>
                            <?php endif; ?>

                            <span class="close-btn">&times;</span>
                            <h2>Coupon Code <?php echo esc_html($title); ?></h2>
                            <p>🎉 <?php echo esc_html($discount_text); ?></p>
                            <p>Expires on <?php echo esc_html($expiry_date); ?></p>
                        </div>
                    </div>
                </div>
            <?php endif;

            if ($display_position === 'homepage_banner' && is_front_page()) {
                echo '<div class="homepage-banner">Coupon Code ' . esc_html($title) . ' 🚀 ' . esc_html($discount_text) . ' - Expires on ' . esc_html($expiry_date) . '</div>';
            }

            if ($display_position === 'header_ticker') {
                echo '<div class="header-ticker">Coupon Code ' . esc_html($title) . ' 🔥 ' . esc_html($discount_text) . ' - Expires on ' . esc_html($expiry_date) . '</div>';
            }
        }

        wp_reset_postdata();
        $content = ob_get_clean(); // Get everything printed above
        return do_shortcode($content); // Return content for shortcode
    }

    return '⚠️ No banner found';
}
add_shortcode('promotional_banner', 'render_promotional_banner_shortcode');