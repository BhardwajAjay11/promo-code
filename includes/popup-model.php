<?php
function get_youtube_video_id($url) {
    parse_str(parse_url($url, PHP_URL_QUERY), $queryParams);
    return $queryParams['v'] ?? ''; // Extracts 'v' parameter (video ID) from URL
}

function display_homepage_banner_popup() {
    // Fetch homepage banner
    $args = array(
        'post_type' => 'promotional_banner',
        'meta_query' => array(
            array(
                'key' => 'expiry_date',
                'value' => date('Y-m-d'),
                'compare' => '>=',
                'type' => 'DATE'
            )
        )
    );
    $coupons = new WP_Query($args);
    
    if ($coupons->have_posts()) {
        while ($coupons->have_posts()) {
            
            $coupons->the_post();
            $title = get_the_title();
            $display_position = get_post_meta(get_the_ID(), 'display_position', true);
            $discount_type = get_post_meta(get_the_ID(), 'discount_type', true);
            $discount_value = get_post_meta(get_the_ID(), 'discount_value', true);
            $expiry_date = get_post_meta(get_the_ID(), 'expiry_date', true);
            $popup_page = get_post_meta(get_the_ID(), 'popup_page', true);
            $popup_width = get_option('popup_width');
            $popup_height = get_option('popup_height');
            $popup_bg_color = get_option('popup_bg_color');
            $popup_text_color = get_option('popup_text_color');
            $popup_position = get_option('popup_position');
            $popup_pages = get_option('popup_pages');
            $popup_bg_image = get_option('popup_image');
            $popup_bg_video = get_option('popup_video');
            $popup_custom_css = get_option('popup_custom_css');
            
            // for ticker
            $options = get_option('ticker_animation_settings');
            $ticker_speed = $options['ticker_speed'] ?? 5000;
            $ticker_loop = !empty($options['ticker_loop']);
            $ticker_position = $options['ticker_position'] ?? 'top_bar';
        
            $discount_text = ($discount_type === 'percent') ? "$discount_value% Off" : "$discount_value Off";
            $current_page_id = get_queried_object_id();
            
            echo '<div class="maincuoponWrapper">';
            if ($display_position === 'popup' && 
                (
                    (is_array($popup_pages) && in_array($current_page_id, $popup_pages)) ||
                    $popup_page === ''
                )
            ):

             // Output custom CSS
             if (!empty($popup_custom_css)) {
                echo '<style>' . $popup_custom_css . '</style>';
            }
                
        ?>
            <div class="bannerwrapper">
                <div id="homepageBannerPopup" class="popup-overlay" style="display:none;">
                    <div class="popup-content" style="color:<?php echo esc_attr($popup_text_color); ?>;
                        background-size: cover; 
                        /* background-color: <?php echo esc_attr($popup_bg_color); ?>; */
                        <?php if (!$popup_bg_video): ?> 
                            background-image: url('<?php echo esc_url($popup_bg_image); ?>'); 
                        <?php endif; ?> 
                        width: <?php echo esc_attr($popup_width); ?>px; 
                        height: <?php echo esc_attr($popup_height); ?>px;">
                        
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
                        <div class="coupon-popup">
                            <h2 class="coupon-title">🎁 Coupon Code</h2>
                            <div class="coupon-box copy-icon">
                                <span class="icon">📎</span>
                                <strong class="coupon-code"><?php echo esc_html($title); ?></strong>
                                <span class="copy-hint">Click to copy</span>
                            </div>
                            <p class="discount">🎉 <?php echo esc_html($discount_text); ?></p>
                            <p class="expiry">Expires on <?php echo esc_html($expiry_date); ?></p>
                        </div>
                      
                    </div>
                </div>
            </div>
            <?php
                endif;
            echo '</div>';
            
        }
    }
    wp_reset_postdata(); // Always reset post data after using WP_Query
}
add_action('wp_body_open', 'display_homepage_banner_popup');


// for header ticker or banner 

function display_homepage_banner() {
  
    $args = array(
        'post_type' => 'promotional_banner',
        'posts_per_page' => -1, // Ensure all banners are loaded
        'meta_query' => array(
            array(
                'key' => 'expiry_date',
                'value' => date('Y-m-d'),
                'compare' => '>=',
                'type' => 'DATE'
            )
        )
    );

    $banners = new WP_Query($args);

    if ($banners->have_posts()) {
        $output = '';

        $options = get_option('ticker_animation_settings');
        $ticker_speed = $options['ticker_speed'] ?? 5000;
        $ticker_loop = !empty($options['ticker_loop']);
        $ticker_position = $options['ticker_position'] ?? 'top_bar';

        // for button style
        $button_bg_color = $options['button_bg_color'] ?? '#000000';
        $button_text_color = $options['button_text_color'] ?? '#ffffff';
        $button_border = $options['button_border'] ?? 'none';
        $button_border_radius = $options['button_border_radius'] ?? '3px';
        $button_padding = $options['button_padding'] ?? '5px 10px';
        $button_margin = $options['button_margin'] ?? '15px';
        $button_font_size = $options['button_font_size'] ?? '14px';
        $ticker_speed = $options['ticker_speed'] ?? 5000;
        $ticker_loop = !empty($options['ticker_loop']);
        $ticker_position = $options['ticker_position'] ?? 'top_bar';
        $ticker_padding = $options['ticker_padding'] ?? '10px';
        $text_color = $options['ticker_text_color'] ?? '#000';
        $bg_color = $options['ticker_bg_color'] ?? '#fff';
        $custom_text = $options['ticker_custom_text'] ?? '';
        $button_label = $options['ticker_button_label'] ?? '';
        $button_url = $options['ticker_button_url'] ?? '';
        $options = get_option('ticker_animation_settings');
        $ticker_animation_type = $options['ticker_animation_type'] ?? 'slide';
        $padding_left = ($ticker_animation_type === 'slide') ? 'padding-left: 100%; ' : '';
        $margin_top = ($ticker_animation_type === 'bounce') ? '10px;' : '';

        while ($banners->have_posts()) {
            $banners->the_post();
            $title = get_the_title();
            $post_id = get_the_ID();

            $display_position = get_post_meta($post_id, 'display_position', true);
            $discount_type = get_post_meta($post_id, 'discount_type', true);
            $discount_value = get_post_meta($post_id, 'discount_value', true);
            $expiry_date = get_post_meta($post_id, 'expiry_date', true);

            $discount_text = ($discount_type === 'percent') ? "$discount_value% Off" : "$$discount_value Off";

            // Homepage Banner
            if ($display_position === 'homepage_banner' && is_front_page()) {
                // $output .= '<div class="homepage-banner">🎉 Coupon Code <span class="copy-icon">📎<strong >' . esc_html($title) . '</strong></span> - ' . esc_html($discount_text) . ' (Expires on ' . esc_html($expiry_date) . ')</div>';
               $output .= '<div class="header-ticker ticker-' . esc_attr($ticker_position) . '" ';
                $output .= 'data-animation="' . esc_attr($ticker_animation_type) . '" ';
                $output .= 'data-speed="' . esc_attr($ticker_speed) . '" ';
                $output .= 'data-loop="' . ($ticker_loop ? 'true' : 'false') . '" ';
                $output .= 'style="background-color: ' . esc_attr($bg_color) . '; padding: ' . esc_attr($ticker_padding) . ';">';
            
                $output .= '<div class="ticker-text" style="white-space: nowrap; overflow: hidden; color: ' . esc_attr($text_color) . ';">';
              
                $output .= '<span style="display: inline-block;' . $padding_left . '">';
                
                if (!empty($custom_text)) {
                    $output .= esc_html($custom_text);
                } else {
                    $output .= '🔥 Coupon Code <span class="copy-icon" style="animation: none;">📎<strong>' . esc_html($title) . '</strong></span> - ' . esc_html($discount_text) . ' (Expires on ' . esc_html($expiry_date) . ')';
                }
            
                if (!empty($button_label) && !empty($button_url)) {
                    $button_style = 'background: ' . esc_attr($button_bg_color) . ';';
                    $button_style .= ' color: ' . esc_attr($button_text_color) . ';';
                    $button_style .= ' border: ' . esc_attr($button_border) . ';';
                    $button_style .= ' border-radius: ' . esc_attr($button_border_radius) . ';';
                    $button_style .= ' padding: ' . esc_attr($button_padding) . ';';
                    $button_style .= ' margin-left: ' . esc_attr($button_margin) . ';';
                    $button_style .= ' margin-top:' . esc_attr($margin_top) . ';';
                    $button_style .= ' font-size: ' . esc_attr($button_font_size) . ';';
                    $button_style .= ' text-decoration: none; display: inline-block;';
            
                    $output .= ' <a href="' . esc_url($button_url) . '" style="' . $button_style . '">' . esc_html($button_label) . '</a>';
                }
            
                $output .= '</span></div></div>';
            }

            // Header Ticker
            if ($display_position === 'header_ticker') {

            
                $output .= '<div class="header-ticker ticker-' . esc_attr($ticker_position) . '" ';
                $output .= 'data-animation="' . esc_attr($ticker_animation_type) . '" ';
                $output .= 'data-speed="' . esc_attr($ticker_speed) . '" ';
                $output .= 'data-loop="' . ($ticker_loop ? 'true' : 'false') . '" ';
                $output .= 'style="background-color: ' . esc_attr($bg_color) . '; padding: ' . esc_attr($ticker_padding) . ';">';
            
                $output .= '<div class="ticker-text" style="white-space: nowrap; overflow: hidden; color: ' . esc_attr($text_color) . ';">';
              
                $output .= '<span style="display: inline-block;' . $padding_left . '">';
                
                if (!empty($custom_text)) {
                    $output .= esc_html($custom_text);
                } else {
                    $output .= '🔥 Coupon Code <span class="copy-icon" style="animation: none;">📎<strong>' . esc_html($title) . '</strong></span> - ' . esc_html($discount_text) . ' (Expires on ' . esc_html($expiry_date) . ')';
                }
            
                if (!empty($button_label) && !empty($button_url)) {
                    $button_style = 'background: ' . esc_attr($button_bg_color) . ';';
                    $button_style .= ' color: ' . esc_attr($button_text_color) . ';';
                    $button_style .= ' border: ' . esc_attr($button_border) . ';';
                    $button_style .= ' border-radius: ' . esc_attr($button_border_radius) . ';';
                    $button_style .= ' padding: ' . esc_attr($button_padding) . ';';
                    $button_style .= ' margin-left: ' . esc_attr($button_margin) . ';';
                    $button_style .= ' margin-top:' . esc_attr($margin_top) . ';';
                    $button_style .= ' font-size: ' . esc_attr($button_font_size) . ';';
                    $button_style .= ' text-decoration: none; display: inline-block;';
            
                    $output .= ' <a href="' . esc_url($button_url) . '" style="' . $button_style . '">' . esc_html($button_label) . '</a>';
                }
            
                $output .= '</span></div></div>';
            }
            
            
        }

        echo '<div class="headcuoponWrapper">' . $output . '</div>';
    }

    wp_reset_postdata();
}
add_action('wp_head', 'display_homepage_banner');
?>
