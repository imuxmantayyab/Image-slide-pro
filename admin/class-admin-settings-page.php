<?php 
class ISP_Admin_Settings
{
    public function __construct()
    {
        $this->admin_menu_enqueue_scripts();
        $this->enqueue_admin_scripts();
    }

     /**
     * Enqueue scripts for the admin menu
     */
    public function admin_menu_enqueue_scripts()
    {
        add_action('admin_menu', [$this, 'add_settings_page']);
    }

    /**
     * enqueue_admin_scripts
     */
    public function enqueue_admin_scripts()
    {
        $admin_enqueue_class = 'ISP_Admin_Enqueue';
        add_action('admin_enqueue_scripts', [$admin_enqueue_class, 'enqueue_scripts']);
    }

    public function add_settings_page()
    {
        add_menu_page(
            'Image Slide Pro Settings', // Page title
            'Image Slide Pro',          // Menu title
            'manage_options',           // Capability
            'isp-settings',             // Menu slug
            [$this, 'settings_page_content'], // Callback function
            'dashicons-images-alt2',    // Icon
            30                          // Position
        );
    }

    function settings_page_content() 
    {
            // Handle image upload and settings update here
            $upload_message = ''; // Initialize upload message

            if (isset($_POST['upload_image'])) {
                if (!empty($_FILES['image_upload']['name'])) {
                    $uploaded_image_id = media_handle_upload('image_upload', 0); // Upload the image to the media library
        
                    if (is_wp_error($uploaded_image_id)) {
                        $upload_message = '<div class="notice notice-error is-dismissible"><p>' . esc_html($uploaded_image_id->get_error_message()) . '</p></div>';
                        echo $upload_message;
                    } else {
                        $upload_message = '<div class="notice notice-success is-dismissible"><p>Image uploaded successfully.</p></div>';
                        echo $upload_message;
                        $uploaded_image_ids = get_option('custom_image_attachment_ids', array());
                        $uploaded_image_ids[] = $uploaded_image_id;
                        update_option('custom_image_attachment_ids', $uploaded_image_ids); // Store the image IDs in options
                    }
                }
            }

        // Display the settings page
        ?>
         
        <div class="wrap">
            <h1>Image Slide Pro Settings</h1>
            <div class="notice notice-warning is-dismissible" id="shortcode">
                <p><strong>Pro Tip:</strong> Use this shortcode to display a beautiful slider on your site!</p>
                <button disabled class="button button-secondary"><?php echo '[custom_slider]'; ?></button>
            </div>
            <div class="notice notice-warning is-dismissible">
                <p><strong>Pro Tip:</strong> Use drag and drop to reorder images; changes will be saved automatically!</p>
            </div>
            <form method="post" enctype="multipart/form-data">
                <div class="slider-top-header">
                    <input type="file" name="image_upload" id="image_upload">
                    <!-- Display the slider using shortcode -->
                    <button type="button" id="delete-all-images" class="button button-secondry">Delete All</button>
                    <button type="submit" name="upload_image" class="upload_image button button-primary">Upload</button>
                </div>
            </form>
            <?php
            // Display the uploaded images if available
            $uploaded_image_ids = get_option('custom_image_attachment_ids', array());
            if (!empty($uploaded_image_ids)) {
                ?>
                <div class="image-container">
                    <?php
                    foreach ($uploaded_image_ids as $image_id) {
                        echo '<div class="uploaded-image" data-image-id="' . $image_id . '">';
                        echo wp_get_attachment_image($image_id);
                        echo '<button type="button" class="delete-button" data-image-id="' . $image_id . '">&times;</button>';
                        echo '</div>';
                    }
                    ?>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
    }

    // AJAX action for deleting an individual image
    function delete_custom_image() 
    {
        if (isset($_POST['image_id'])) {
            $image_id = intval($_POST['image_id']);
            wp_delete_attachment($image_id, true);

            $uploaded_image_ids = get_option('custom_image_attachment_ids', array());
            $updated_image_ids = array_diff($uploaded_image_ids, array($image_id));
            update_option('custom_image_attachment_ids', $updated_image_ids);

            wp_send_json_success();
        } else {
            wp_send_json_error();
        }
    }

    // AJAX action for deleting all images
    function delete_all_custom_images() {
        $uploaded_image_ids = get_option('custom_image_attachment_ids', array());
        foreach ($uploaded_image_ids as $image_id) {
            wp_delete_attachment($image_id, true);
        }
        update_option('custom_image_attachment_ids', array());

        wp_send_json_success();
    }

    /**
     * ajax_delete_img
     */
    public function ajax_delete_img()
    {
        add_action('wp_ajax_delete_all_images', 'delete_all_custom_images');
        add_action('wp_ajax_delete_image', 'delete_custom_image');
    }
}

// Instantiate the ISP_Admin_Settings class
$isp_admin_settings = new ISP_Admin_Settings();
