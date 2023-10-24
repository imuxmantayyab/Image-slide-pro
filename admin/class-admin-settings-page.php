<?php
class ISP_Admin_Settings
{
    public function __construct()
    {
        $this->enqueue_admin_scripts();
        add_action('init', [$this, 'create_image_slider_cpt']);
        add_action('add_meta_boxes', [$this, 'add_image_slider_custom_meta_box']);
        add_action('wp_ajax_delete_image_slider_images', [$this, 'delete_image_slider_images']);
        add_action('wp_ajax_nopriv_delete_image_slider_images', [$this, 'delete_image_slider_images']);
         add_action('save_post', [$this, 'save_image_slider_meta_data']);
    }

    /**
     * enqueue_admin_scripts
     */
    public function enqueue_admin_scripts()
    {
        $admin_enqueue_class = 'ISP_Admin_Enqueue';
        add_action('admin_enqueue_scripts', [$admin_enqueue_class, 'enqueue_scripts']);
    }

    function create_image_slider_cpt() 
    {
        $labels = array(
            'name' => _x('Image Slider Pro', 'Post Type General Name', 'isp'),
            'singular_name' => _x('Slider', 'Post Type Singular Name', 'isp'),
            'menu_name' => _x('Image Slider Pro', 'Admin Menu text', 'isp'),
            'name_admin_bar' => _x('Slider', 'Add New on Toolbar', 'isp'),
            'archives' => __('Slider Archives', 'isp'),
            'attributes' => __('Slider Attributes', 'isp'),
            'parent_item_colon' => __('Parent Slider:', 'isp'),
            'all_items' => __('All Image Slider Pro', 'isp'),
            'add_new_item' => __('Add New Slider', 'isp'),
            'add_new' => __('Add New', 'isp'),
            'new_item' => __('New Slider', 'isp'),
            'edit_item' => __('Edit Slider', 'isp'),
            'update_item' => __('Update Slider', 'isp'),
            'view_item' => __('View Slider', 'isp'),
            'view_items' => __('View Image Slider Pro', 'isp'),
            'search_items' => __('Search Slider', 'isp'),
            'not_found' => __('Not found', 'isp'),
            'not_found_in_trash' => __('Not found in Trash', 'isp'),
            'featured_image' => __('Featured Image', 'isp'),
            'set_featured_image' => __('Set featured image', 'isp'),
            'remove_featured_image' => __('Remove featured image', 'isp'),
            'use_featured_image' => __('Use as featured image', 'isp'),
            'insert_into_item' => __('Insert into Slider', 'isp'),
            'uploaded_to_this_item' => __('Uploaded to this Slider', 'isp'),
            'items_list' => __('Image Slider Pro list', 'isp'),
            'items_list_navigation' => __('Image Slider Pro list navigation', 'isp'),
            'filter_items_list' => __('Filter Image Slider Pro list', 'isp'),
        );
        $args = array(
            'label' => __('Slider', 'isp'),
            'description' => __('', 'isp'),
            'labels' => $labels,
            'menu_icon' => 'dashicons-images-alt2',
            'supports' => array('title'),
            'taxonomies' => array(),
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 20,
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'hierarchical' => false,
            'exclude_from_search' => false,
            'show_in_rest' => true,
            'publicly_queryable' => true,
            'capability_type' => 'page',
        );
            register_post_type('image_slider', $args);
    }

    function add_image_slider_custom_meta_box() 
    {
        add_meta_box(
            'image_slider_meta_box',
            'Slider Media Gallery',
            [$this, 'render_image_slider_meta_box'],
            'image_slider',
            'normal',
            'high'
        );
    }
    
    function render_image_slider_meta_box($post) 
    {
        // Retrieve the stored image IDs
        $slider_image_ids = get_post_meta($post->ID, '_image_slider_image_ids', true);
        $slider_image_urls = array();

        if (is_array($slider_image_ids)) {
            foreach ($slider_image_ids as $image_id) {
                $slider_image_urls[] = wp_get_attachment_url($image_id);
            }
        }
        ?>
        <div class="wrap">
            <div class="slider-top-header">
                <button type="button" id="open-media-uploader" class="button button-secondary"><span class="dashicons-before dashicons-admin-media"></span> Add Media</button>
            </div>
            <div class="slider-image-preview image-container" style="margin-top: 10px;">
                <?php
                foreach ($slider_image_urls as $image_url) {
                    // Get a unique image ID for each image
                    $image_id = attachment_url_to_postid($image_url);
                    ?>
                    <div class="uploaded-image" data-image-id="<?php echo esc_attr($image_id); ?>">
                        <img class="slider-image" src="<?php echo esc_url($image_url); ?>">
                        <button type="button" class="delete-button" data-image-id="<?php echo esc_attr($image_id); ?>">&times;</button>
                        <input type="hidden" class="slider-image-id" name="image_slider_image_ids[]" value="<?php echo esc_attr($image_id); ?>">
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
    }

    function save_image_slider_meta_data($post_id) 
    {
        if (isset($_POST['image_slider_image_ids'])) {
            $image_ids = array_map('intval', $_POST['image_slider_image_ids']);
            
            // Check if there are image IDs to update
            if (!empty($image_ids)) {
                update_post_meta($post_id, '_image_slider_image_ids', $image_ids);
            } else {
                // If there are no image IDs, set the post meta data to an empty array
                update_post_meta($post_id, '_image_slider_image_ids', array());
            }
        } else {
            // If image IDs were not provided, set the post meta data to an empty array
            update_post_meta($post_id, '_image_slider_image_ids', array());
        }
    }
    

    function delete_image_slider_images() 
    {

        if (isset($_POST['post_id'])) {
            $post_id = $_POST['post_id'];
            delete_post_meta($post_id, '_image_slider_image_ids');
            wp_send_json_success();
        } else {
            wp_send_json_error('Post ID not provided.');
        }
    } 
}

// Instantiate the classes
$isp_admin_settings = new ISP_Admin_Settings();
