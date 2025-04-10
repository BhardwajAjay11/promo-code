<?php
// Register settings
add_action('admin_init', 'register_ticker_animation_settings');
function register_ticker_animation_settings() {
    register_setting('ticker_animation_options', 'ticker_animation_settings');

    add_settings_section(
        'ticker_animation_main_section',
        'Main Settings',
        null,
        'ticker_animation'
    );
}

// Render the settings page
function render_ticker_animation_settings() {
    $options = get_option('ticker_animation_settings');
    ?>
    <div class="wrap">
        <h1>Ticker Animation Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('ticker_animation_options');
            do_settings_sections('ticker_animation');
            ?>
            <table class="form-table">
            <tr valign="top">
                <th scope="row">Ticker Animation Type</th>
                <td>
                    <select name="ticker_animation_settings[ticker_animation_type]">
                        <?php
                        $animation_types = [
                            'slide' => 'Slide',
                            'fade' => 'Fade',
                            'bounce' => 'Bounce',
                        ];
                        foreach ($animation_types as $value => $label): ?>
                            <option value="<?php echo esc_attr($value); ?>" <?php selected($options['ticker_animation_type'] ?? 'slide', $value); ?>>
                                <?php echo esc_html($label); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="description">Choose the animation style for the ticker text.</p>
                </td>
            </tr>

                <tr valign="top">
                    <th scope="row">Ticker Speed</th>
                    <td>
                        <input type="number" name="ticker_animation_settings[ticker_speed]" value="<?php echo esc_attr($options['ticker_speed'] ?? 5000); ?>" />
                        <p class="description">Set the speed of the ticker scroll in milliseconds.</p>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Enable Looping</th>
                    <td>
                        <input type="checkbox" name="ticker_animation_settings[ticker_loop]" value="1" <?php checked(1, $options['ticker_loop'] ?? 0); ?> />
                        <label for="ticker_loop">Loop the animation infinitely.</label>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Ticker Position</th>
                    <td>
                        <select name="ticker_animation_settings[ticker_position]">
                            <?php
                            $positions = [
                                'top_bar' => 'Top Bar',
                                'below_header' => 'Below Header'
                            ];
                            foreach ($positions as $value => $label): ?>
                                <option value="<?php echo esc_attr($value); ?>" <?php selected($options['ticker_position'] ?? '', $value); ?>>
                                    <?php echo esc_html($label); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <p class="description">Select where to display the ticker.</p>
                    </td>
                    <!-- New Fields Start -->
                        <tr valign="top">
                            <th scope="row">Ticker Padding</th>
                            <td>
                                <input type="text" name="ticker_animation_settings[ticker_padding]" value="<?php echo esc_attr($options['ticker_padding'] ?? '10px'); ?>" placeholder="e.g., 10px 20px" />
                                <p class="description">CSS padding for the ticker area (e.g., 10px 20px).</p>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">Text Color</th>
                            <td>
                                <input type="color" name="ticker_animation_settings[ticker_text_color]" value="<?php echo esc_attr($options['ticker_text_color'] ?? '#000000'); ?>" />
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">Background Color</th>
                            <td>
                                <input type="color" name="ticker_animation_settings[ticker_bg_color]" value="<?php echo esc_attr($options['ticker_bg_color'] ?? '#ffffff'); ?>" />
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">Custom Ticker Text</th>
                            <td>
                                <textarea name="ticker_animation_settings[ticker_custom_text]" rows="3" cols="50"><?php echo esc_textarea($options['ticker_custom_text'] ?? ''); ?></textarea>
                                <p class="description">Enter custom text to display in the ticker.</p>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">Button Label</th>
                            <td>
                                <input type="text" name="ticker_animation_settings[ticker_button_label]" value="<?php echo esc_attr($options['ticker_button_label'] ?? 'Learn More'); ?>" />
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">Button URL</th>
                            <td>
                                <input type="url" name="ticker_animation_settings[ticker_button_url]" value="<?php echo esc_attr($options['ticker_button_url'] ?? '#'); ?>" />
                                <p class="description">Enter the URL the button should link to.</p>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">Button Background Color</th>
                            <td>
                                <input type="color" name="ticker_animation_settings[button_bg_color]" value="<?php echo esc_attr($options['button_bg_color'] ?? '#0073aa'); ?>" />
                                <p class="description">Background color of the button.</p>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">Button Text Color</th>
                            <td>
                                <input type="color" name="ticker_animation_settings[button_text_color]" value="<?php echo esc_attr($options['button_text_color'] ?? '#ffffff'); ?>" />
                                <p class="description">Text color of the button.</p>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">Button Border</th>
                            <td>
                                <input type="text" name="ticker_animation_settings[button_border]" value="<?php echo esc_attr($options['button_border'] ?? '1px solid #0073aa'); ?>" placeholder="e.g., 1px solid #0073aa" />
                                <p class="description">CSS border property for the button.</p>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">Button Border Radius</th>
                            <td>
                                <input type="text" name="ticker_animation_settings[button_border_radius]" value="<?php echo esc_attr($options['button_border_radius'] ?? '4px'); ?>" placeholder="e.g., 4px" />
                                <p class="description">Rounded corners for the button.</p>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">Button Padding</th>
                            <td>
                                <input type="text" name="ticker_animation_settings[button_padding]" value="<?php echo esc_attr($options['button_padding'] ?? '8px 16px'); ?>" placeholder="e.g., 8px 16px" />
                                <p class="description">Padding inside the button (top/bottom and left/right).</p>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">Button Margin</th>
                            <td>
                                <input type="text" name="ticker_animation_settings[button_margin]" value="<?php echo esc_attr($options['button_margin'] ?? '10px'); ?>" placeholder="e.g., 10px" />
                                <p class="description">Spacing around the button.</p>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">Button Font Size</th>
                            <td>
                                <input type="text" name="ticker_animation_settings[button_font_size]" value="<?php echo esc_attr($options['button_font_size'] ?? '14px'); ?>" placeholder="e.g., 14px" />
                                <p class="description">Font size of the button text.</p>
                            </td>
                        </tr>

                        <!-- New Fields End -->
               
            </table>

            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
