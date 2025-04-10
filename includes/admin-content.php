<?php
// New code for admin section
function coupon_meta_box_callback($post) {
    // Get existing values from the post meta
    $discount_type = get_post_meta($post->ID, 'discount_type', true);
    $discount_value = get_post_meta($post->ID, 'discount_value', true);
    $expiry_date = get_post_meta($post->ID, 'expiry_date', true);
    $display_position = get_post_meta($post->ID, 'display_position', true);
    $popup_height = get_post_meta($post->ID, 'popup_height', true);
    $popup_width = get_post_meta($post->ID, 'popup_width', true);
    $popup_bg_color = get_post_meta($post->ID, 'popup_bg_color', true);
    $popup_text_color = get_post_meta($post->ID, 'popup_text_color', true);
    $popup_position = get_post_meta($post->ID, 'popup_position', true);
    $popup_custom_css = get_post_meta($post->ID, 'popup_custom_css', true);
    $user_limit = get_post_meta($post->ID, 'user_limit', true);
    $image_url = get_post_meta($post->ID, 'popup_image', true); 
    $video_url = get_post_meta($post->ID, 'popup_video', true);

    // Output the form fields
    echo '<div class="allfiled"><label>Discount Type: </label>
        <select name="discount_type">
            <option value="percent" '. selected($discount_type, 'percent', false) .'>Percentage</option>
            <option value="fixed_cart" '. selected($discount_type, 'fixed_cart', false) .'>Fixed Cart</option>
        </select>';

    echo '<label>Discount Value: </label><input type="number" name="discount_value" value="' . esc_attr($discount_value) . '" />';
    
    // User Limit Field
    echo '<label>User Limit: </label><input type="number" name="user_limit" value="' . esc_attr($user_limit) . '" min="0" />
          <small>0 means unlimited usage</small>';

    echo '<label>Expiry Date: </label><input type="date" name="expiry_date" value="' . esc_attr($expiry_date) . '" />';

    echo '<label>Display Position: </label>
        <select name="display_position" id="display_position">
            <option value="homepage_banner" '. selected($display_position, 'homepage_banner', false) .'>Homepage Banner</option>
            <option value="header_ticker" '. selected($display_position, 'header_ticker', false) .'>Header Ticker</option>
            <option value="popup" '. selected($display_position, 'popup', false) .'>Popup</option>
        </select>';

    
    
    echo '</div>';

}

function save_coupon_meta($post_id) {
    if (isset($_POST['discount_type'])) update_post_meta($post_id, 'discount_type', sanitize_text_field($_POST['discount_type']));
    if (isset($_POST['discount_value'])) update_post_meta($post_id, 'discount_value', sanitize_text_field($_POST['discount_value']));
    if (isset($_POST['expiry_date'])) update_post_meta($post_id, 'expiry_date', sanitize_text_field($_POST['expiry_date']));
    if (isset($_POST['display_position'])) update_post_meta($post_id, 'display_position', sanitize_text_field($_POST['display_position']));
    if (isset($_POST['user_limit'])) update_post_meta($post_id, 'user_limit', intval($_POST['user_limit']));
       
    update_woocommerce_coupon($post_id);
}
add_action('save_post', 'save_coupon_meta');






