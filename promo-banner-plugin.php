<?php
/**
 * Plugin Name: Promotional Banner 
 * Plugin URI: http://www.ghrix.com
 * Description: A plugin demonstrating how to add a Promotional banner with flexible positioning.
 * Author:  Ghrix.com
 * Author URI: http://www.ghrix.com
 * Version: 1.0
 */


 defined('ABSPATH') || exit;

 // Check if WooCommerce is active
 function my_plugin_check_woocommerce() {
     if (!is_plugin_active('woocommerce/woocommerce.php')) {
         // Show admin error
         add_action('admin_notices', 'my_plugin_woocommerce_missing_notice');
 
         // Deactivate this plugin
         deactivate_plugins(plugin_basename(__FILE__));
     }
 }
 add_action('admin_init', 'my_plugin_check_woocommerce');
 
 // Error message
 function my_plugin_woocommerce_missing_notice() {
     echo '<div class="notice notice-error is-dismissible">
         <p><strong>Promotional Banner:</strong> WooCommerce plugin is required. Please install and activate WooCommerce first.</p>
     </div>';
 }



 // Get all scheduled posts that missed their schedule
function publish_missed_posts() {
    global $wpdb;
    $missed_posts = $wpdb->get_results("
        SELECT ID FROM $wpdb->posts
        WHERE post_status = 'future' 
        AND post_date <= NOW()
    ");

    foreach ($missed_posts as $post) {
        wp_update_post([
            'ID'          => $post->ID,
            'post_status' => 'publish',
        ]);
    }
}
add_action('init', 'publish_missed_posts');


// enqueue the all links
function enqueue_promo_banner_scripts() {
    // Enqueue CSS
    wp_enqueue_style('promo-banner-style', plugin_dir_url(__FILE__) . 'assets/promo-frontend.css');

    // Enqueue JS
    wp_enqueue_script('promo-banner-script', plugin_dir_url(__FILE__) . 'assets/promo-frontend.js', array('jquery'), null, true);
    
    // Pass data to JS
    wp_localize_script('promo-banner-script', 'promoBannerData', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));
    
    // Pass data to JS
    wp_localize_script('promo-banner-script', 'promoBannerData', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_promo_banner_scripts');

function enqueue_promo_banner_admin_scripts() {
    // Enqueue CSS in the admin panel
    wp_enqueue_style('promo-banner-admin-style', plugin_dir_url(__FILE__) . 'assets/promo-admin.css');
    wp_enqueue_style('promo-clodflare-admin-style', "https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css");
    
    wp_enqueue_script('promo-banner-script', plugin_dir_url(__FILE__) . 'assets/promo-admin.js', array('jquery'), null, true);

    wp_enqueue_script('jquery', "https://code.jquery.com/jquery-3.6.0.min.js", array('jquery'), null, true);
    wp_enqueue_script('select2-js', "https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js", array('jquery'), null, true);

}
add_action('admin_enqueue_scripts', 'enqueue_promo_banner_admin_scripts');



include_once plugin_dir_path(__FILE__) . 'includes/popup-model.php';
include_once plugin_dir_path(__FILE__) . 'includes/admin-content.php';
include_once plugin_dir_path(__FILE__) . 'includes/woocommerce-coupon.php';
include_once plugin_dir_path(__FILE__) . 'includes/admin-menu.php';
include_once plugin_dir_path(__FILE__) . 'includes/table-expand-functions.php';
include_once plugin_dir_path(__FILE__) . 'includes/popup-settings.php';
include_once plugin_dir_path(__FILE__) . 'includes/ticker-settings.php';
include_once plugin_dir_path(__FILE__) . 'includes/admin-dashboard.php';





// Register Custom Post Type for Banners
function register_coupon_cpt() {
    register_post_type('promotional_banner', array(
        'labels' => array(
            'name' => 'Promotional Banner',
            'singular_name' => 'Promotional Banner'
        ),
        'public' => true,
        'menu_icon' => 'dashicons-megaphone',
        'supports' => array('title'),
    ));
}
add_action('init', 'register_coupon_cpt');

// Add Meta Boxes
function add_coupon_meta_boxes() {
    add_meta_box('coupon_details', 'Coupon Details', 'coupon_meta_box_callback', 'promotional_banner');
}
add_action('add_meta_boxes', 'add_coupon_meta_boxes');


add_action('edit_form_after_title', 'add_generate_code_button');
function add_generate_code_button($post) {
    if ($post->post_type !== 'promotional_banner') {
        return;
    }
    ?>
    <div id="generate-code-wrapper" style="margin: 10px 0;">
        <button type="button" class="button" id="generate-random-code">Generate Code</button>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const generateBtn = document.getElementById('generate-random-code');
            const titleInput = document.getElementById('title');

            generateBtn.addEventListener('click', function () {
                const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                let result = '';
                for (let i = 0; i < 6; i++) {
                    result += characters.charAt(Math.floor(Math.random() * characters.length));
                }

                // Set title
                titleInput.value = result;

                // Target and update only this label
                const titleLabel = document.querySelector('label#title-prompt-text');
                if (titleLabel && !titleLabel.classList.contains('screen-reader-text')) {
                    titleLabel.classList.add('screen-reader-text');
                }
            });

        });
    </script>
    <?php
}
