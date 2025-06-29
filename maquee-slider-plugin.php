<?php
/*
Plugin Name: Marquee Slider
Description: A customizable marquee slider for displaying multiple image sliders with configurable speed and direction.
Version: 2.0
Author: Tajwar Tajim
Author URI: https://github.com/tajwar-t
License: Personal
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Enqueue frontend scripts and styles
function ms_enqueue_scripts() {
    wp_enqueue_style('marquee-slider-style', plugin_dir_url(__FILE__) . 'css/marquee-slider.css', array(), time());
    wp_enqueue_script('marquee-slider-script', plugin_dir_url(__FILE__) . 'js/marquee-slider.js', array('jquery'), time(), true);
}
add_action('wp_enqueue_scripts', 'ms_enqueue_scripts');

// Enqueue admin scripts and styles
function ms_enqueue_admin_scripts($hook) {
    if ($hook !== 'toplevel_page_marquee-slider' && $hook !== 'marquee-slider_page_marquee-slider-add') {
        return;
    }
    wp_enqueue_media();
    wp_enqueue_script('marquee-slider-admin-script', plugin_dir_url(__FILE__) . 'js/marquee-slider-admin.js', array('jquery'), time(), '2.0', true);
    wp_enqueue_style('marquee-slider-admin-style', plugin_dir_url(__FILE__) . 'css/marquee-slider-admin.css', array(), time());
}
add_action('admin_enqueue_scripts', 'ms_enqueue_admin_scripts');

// Create admin menu
function ms_admin_menu() {
    add_menu_page(
        'Marquee Slider Settings',
        'Marquee Slider',
        'manage_options',
        'marquee-slider',
        'ms_settings_page',
        'dashicons-images-alt2'
    );
    add_submenu_page(
        'marquee-slider',
        'Add New Slider',
        'Add New',
        'manage_options',
        'marquee-slider-add',
        'ms_add_slider_page'
    );
}
add_action('admin_menu', 'ms_admin_menu');

// Register settings
function ms_register_settings() {
    register_setting('ms_settings_group', 'ms_sliders', array('sanitize_callback' => 'ms_sanitize_sliders'));
}
add_action('admin_init', 'ms_register_settings');

// Sanitize sliders array
function ms_sanitize_sliders($input) {
    if (!is_array($input)) {
        return array();
    }
    $sanitized = array();
    foreach ($input as $id => $slider) {
        $sanitized[$id] = array(
            'name' => sanitize_text_field($slider['name']),
            'images' => isset($slider['images']) ? array_map('absint', (array) $slider['images']) : array(),
            'speed' => absint($slider['speed'] ? $slider['speed'] : 50),
            'direction' => in_array($slider['direction'], array('left', 'right')) ? $slider['direction'] : 'left'
        );
    }
    return $sanitized;
}

// Main settings page
function ms_settings_page() {
    $sliders = get_option('ms_sliders', array());
    ?>
    <div class="wrap">
        <h1>Marquee Slider Settings</h1>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Slider Name</th>
                    <th>Shortcode</th>
                    <th>Images</th>
                    <th>Speed (px/s)</th>
                    <th>Direction</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($sliders)): ?>
                    <tr><td colspan="6">No sliders created yet. <a href="<?php echo admin_url('admin.php?page=marquee-slider-add'); ?>">Add a new slider</a>.</td></tr>
                <?php else: ?>
                    <?php foreach ($sliders as $id => $slider): ?>
                        <tr>
                            <td><?php echo esc_html($slider['name']); ?></td>
                            <td><code>[marquee_slider id="<?php echo esc_attr($id); ?>"]</code></td>
                            <td><?php echo count($slider['images']); ?> images</td>
                            <td><?php echo esc_html($slider['speed']); ?></td>
                            <td><?php echo esc_html(ucfirst($slider['direction'])); ?></td>
                            <td>
                                <a href="<?php echo admin_url('admin.php?page=marquee-slider-add&edit=' . $id); ?>" class="button">Edit</a>
                                <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=marquee-slider&delete=' . $id), 'ms_delete_slider_' . $id); ?>" class="button" onclick="return confirm('Are you sure you want to delete this slider?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
    // Handle slider deletion
    if (isset($_GET['delete']) && check_admin_referer('ms_delete_slider_' . $_GET['delete'])) {
        $delete_id = sanitize_text_field($_GET['delete']);
        unset($sliders[$delete_id]);
        update_option('ms_sliders', $sliders);
        wp_redirect(admin_url('admin.php?page=marquee-slider'));
        exit;
    }
}

// Add/Edit slider page
function ms_add_slider_page() {
    $sliders = get_option('ms_sliders', array());
    $edit_id = isset($_GET['edit']) ? sanitize_text_field($_GET['edit']) : '';
    $is_edit = !empty($edit_id) && isset($sliders[$edit_id]);
    $slider = $is_edit ? $sliders[$edit_id] : array('name' => '', 'images' => array(), 'speed' => 50, 'direction' => 'left');
    $new_id = $is_edit ? $edit_id : uniqid('slider_');
    
    if (isset($_POST['ms_save_slider']) && check_admin_referer('ms_save_slider')) {
        $sliders[$new_id] = array(
            'name' => sanitize_text_field($_POST['ms_name']),
            'images' => isset($_POST['ms_images']) ? array_map('absint', (array) $_POST['ms_images']) : array(),
            'speed' => absint($_POST['ms_speed'] ? $_POST['ms_speed'] : 50),
            'direction' => in_array($_POST['ms_direction'], array('left', 'right')) ? $_POST['ms_direction'] : 'left'
        );
        update_option('ms_sliders', $sliders);
        wp_redirect(admin_url('admin.php?page=marquee-slider'));
        exit;
    }
    ?>
    <div class="wrap">
        <h1><?php echo $is_edit ? 'Edit Slider' : 'Add New Slider'; ?></h1>
        <form method="post">
            <?php wp_nonce_field('ms_save_slider'); ?>
            <table class="form-table">
                <tr>
                    <th><label for="ms_name">Slider Name</label></th>
                    <td><input type="text" name="ms_name" id="ms_name" value="<?php echo esc_attr($slider['name']); ?>" required class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="ms_images">Select Images</label></th>
                    <td>
                        <div id="ms-image-container">
                            <?php foreach ($slider['images'] as $image_id): ?>
                                <div class="ms-image-preview">
                                    <?php echo wp_get_attachment_image($image_id, 'thumbnail'); ?>
                                    <input type="hidden" name="ms_images[]" value="<?php echo esc_attr($image_id); ?>">
                                    <button type="button" class="ms-remove-image">Remove</button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" id="ms-add-image" class="button">Add Image</button>
                    </td>
                </tr>
                <tr>
                    <th><label for="ms_speed">Marquee Speed (px/s)</label></th>
                    <td>
                        <input type="number" name="ms_speed" id="ms_speed" value="<?php echo esc_attr($slider['speed']); ?>" min="10" max="200">
                    </td>
                </tr>
                <tr>
                    <th><label for="ms_direction">Direction</label></th>
                    <td>
                        <select name="ms_direction" id="ms_direction">
                            <option value="left" <?php selected($slider['direction'], 'left'); ?>>Left</option>
                            <option value="right" <?php selected($slider['direction'], 'right'); ?>>Right</option>
                        </select>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="ms_save_slider" value="1">
            <?php submit_button($is_edit ? 'Update Slider' : 'Create Slider'); ?>
        </form>
    </div>
    <?php
}

// Shortcode
function ms_slider_shortcode($atts) {
    $atts = shortcode_atts(array('id' => ''), $atts);
    $sliders = get_option('ms_sliders', array());
    
    if (empty($atts['id']) || !isset($sliders[$atts['id']])) {
        return '<p>Invalid or no slider ID specified: ' . esc_html($atts['id']) . '</p>';
    }

    $slider = $sliders[$atts['id']];
    if (empty($slider['images'])) {
        return '<p>No images selected for slider ID: ' . esc_html($atts['id']) . '</p>';
    }

    $output = '<div class="marquee-slider" data-speed="' . esc_attr($slider['speed']) . '" data-direction="' . esc_attr($slider['direction']) . '" data-slider-id="' . esc_attr($atts['id']) . '">';
    $output .= '<div class="marquee-inner">';

    // Output images in individual div wrappers for seamless looping
    $image_count = count($slider['images']);
    $repeat_count = max(4, ceil(30 / $image_count)); // Increased for smoother looping
    for ($i = 0; $i < $repeat_count; $i++) {
        foreach ($slider['images'] as $image_id) {
            $image = wp_get_attachment_image_src($image_id, 'medium');
            if ($image) {
                $output .= '<div class="marquee-image-wrapper">';
                $output .= '<img src="' . esc_url($image[0]) . '" width="' . esc_attr($image[1]) . '" height="' . esc_attr($image[2]) . '" alt="">';
                $output .= '</div>';
            }
        }
    }
    
    $output .= '</div></div>';
    return $output;
}
add_shortcode('marquee_slider', 'ms_slider_shortcode');
?>