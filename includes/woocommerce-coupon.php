<?php
// Update WooCommerce Coupon
function update_woocommerce_coupon($post_id, $coupon_args = []) {
    if (get_post_type($post_id) !== 'promotional_banner') return;

    $coupon_code = get_the_title($post_id);
    $discount_type = get_post_meta($post_id, 'discount_type', true) ?: 'percent';
    $amount = get_post_meta($post_id, 'discount_value', true) ?: 0;
    $expiry_date = get_post_meta($post_id, 'expiry_date', true);

    $existing_coupon = get_page_by_title($coupon_code, OBJECT, 'shop_coupon');

    if ($existing_coupon) {
        $coupon_id = $existing_coupon->ID;
        wp_update_post(['ID' => $coupon_id, 'post_title' => $coupon_code]);
    } else {
        $coupon_id = wp_insert_post(['post_title' => $coupon_code, 'post_status' => 'publish', 'post_type' => 'shop_coupon']);
    }

    update_post_meta($coupon_id, 'discount_type', $discount_type);
    update_post_meta($coupon_id, 'coupon_amount', $amount);
    update_post_meta($coupon_id, 'individual_use', 'yes');
    update_post_meta($coupon_id, 'usage_limit', '1');

    if (!empty($expiry_date)) update_post_meta($coupon_id, 'date_expires', strtotime($expiry_date));
}
