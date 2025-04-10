<?php
function render_popup_ticker_animation_settings() {
    // Get saved options
    $popup_width = get_option('popup_width', '');
    $popup_height = get_option('popup_height', '');
    $popup_bg_color = get_option('popup_bg_color', '#ffffff');
    $popup_text_color = get_option('popup_text_color', '#000000');
    $popup_position = get_option('popup_position', 'center');
    $popup_custom_css = get_option('popup_custom_css', '');
    $display_position = get_option('display_position', '');
    $image_url = get_option('popup_image', '');
    $video_url = get_option('popup_video', '');
    $selected_pages = get_option('popup_pages', []);

    ?>
    <div class="wrap">
        <h1>Popup Ticker Animation Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('popup_ticker_animation_options'); ?>
            <?php do_settings_sections('popup_ticker_animation'); ?>

            <!-- Popup specific fields -->
            <div class="postbox ">
                <div id="popup_fields" style="display: <?php echo ($display_position == 'popup' ? 'block' : 'none'); ?>;" class="allfiled">
                    <label>Popup Width: </label>
                    <input type="text" name="popup_width" value="<?php echo esc_attr($popup_width); ?>" />

                    <label>Popup Height: </label>
                    <input type="text" name="popup_height" value="<?php echo esc_attr($popup_height); ?>" />

                    <label>Popup Background Color: </label>
                    <input type="color" name="popup_bg_color" value="<?php echo esc_attr($popup_bg_color); ?>" />

                    <label>Popup Text Color: </label>
                    <input type="color" name="popup_text_color" value="<?php echo esc_attr($popup_text_color); ?>" />

                    <label>Show Popup On Pages:</label>
                    <select name="popup_pages[]" multiple style="width: 100%; height: 150px;">
                        <option value="">All Site</option>
                        <?php
                        $pages = get_pages();
                        foreach ($pages as $page) {
                            $selected = in_array($page->ID, (array)$selected_pages) ? 'selected' : '';
                            echo '<option value="' . esc_attr($page->ID) . '" ' . $selected . '>' . esc_html($page->post_title) . '</option>';
                        }
                        ?>
                    </select>

                    <label>
                        <input type="radio" name="media_type" value="image" <?php checked($image_url !== '', true); ?> onclick="toggleMedia('image');"> Upload Image
                    </label>
                    <label>
                        <input type="radio" name="media_type" value="video" <?php checked($video_url !== '', true); ?> onclick="toggleMedia('video');"> Video URL
                    </label>

                    <div id="image_upload_section" style="display: <?php echo ($image_url !== '' ? 'block' : 'none'); ?>;">
                        <label>Popup Image:</label>
                        <input type="button" class="upload_image_button" value="Upload Image" />
                        <input type="hidden" name="popup_image" value="<?php echo esc_attr($image_url); ?>" />
                    </div>

                    <div id="video_upload_section" style="display: <?php echo ($video_url !== '' ? 'block' : 'none'); ?>;">
                        <label>Popup Video URL:</label>
                        <input type="text" name="popup_video" placeholder="Enter video URL" value="<?php echo esc_attr($video_url); ?>" />
                    </div>

                    <label>Popup Position:</label>
                    <select name="popup_position">
                        <option value="center" <?php selected($popup_position, 'center'); ?>>Center</option>
                        <option value="right" <?php selected($popup_position, 'right'); ?>>Right</option>
                        <option value="left" <?php selected($popup_position, 'left'); ?>>Left</option>
                    </select>

                    <label>Custom CSS:</label>
                    <textarea name="popup_custom_css" rows="4" cols="50"><?php echo esc_textarea($popup_custom_css); ?></textarea>
                </div>
            </div>   

            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

    function popup_ticker_register_settings() {
    register_setting('popup_ticker_animation_options', 'popup_width');
    register_setting('popup_ticker_animation_options', 'popup_height');
    register_setting('popup_ticker_animation_options', 'popup_bg_color');
    register_setting('popup_ticker_animation_options', 'popup_text_color');
    register_setting('popup_ticker_animation_options', 'popup_position');
    register_setting('popup_ticker_animation_options', 'popup_custom_css');
    register_setting('popup_ticker_animation_options', 'popup_pages');
    register_setting('popup_ticker_animation_options', 'popup_image');
    register_setting('popup_ticker_animation_options', 'popup_video');
    register_setting('popup_ticker_animation_options', 'popup_banner_clicks');
    register_setting('popup_ticker_animation_options', 'header_ticker_clicks');
    register_setting('popup_ticker_animation_options', 'header_banner_clicks');

}
add_action('admin_init', 'popup_ticker_register_settings');


function enqueue_media_uploader_script() {
    wp_enqueue_media(); // Load the media uploader

    ?>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            function toggleMedia(type) {
                document.getElementById('image_upload_section').style.display = (type === 'image') ? 'block' : 'none';
                document.getElementById('video_upload_section').style.display = (type === 'video') ? 'block' : 'none';
            }

            var mediaInputs = document.querySelectorAll('input[name="media_type"]');
            mediaInputs.forEach(function (input) {
                input.addEventListener('change', function () {
                    toggleMedia(this.value);
                });
            });

            var image_frame;
            var uploadButton = document.querySelector('.upload_image_button');

            if (uploadButton) {
                uploadButton.addEventListener('click', function (e) {
                    e.preventDefault();

                    if (image_frame) {
                        image_frame.open();
                        return;
                    }

                    image_frame = wp.media({
                        title: 'Choose an Image',
                        button: {
                            text: 'Use this image'
                        },
                        multiple: false
                    });

                    image_frame.on('select', function () {
                        var attachment = image_frame.state().get('selection').first().toJSON();
                        var inputField = document.querySelector('input[name="popup_image"]');
                        if (inputField) {
                            inputField.value = attachment.url;
                        }

                        var imgElement = document.querySelector('img');
                        if (imgElement) {
                            imgElement.src = attachment.url;
                        }
                    });

                    image_frame.open();
                });
            }
        });
    </script>
    <?php
}
add_action('admin_enqueue_scripts', 'enqueue_media_uploader_script');