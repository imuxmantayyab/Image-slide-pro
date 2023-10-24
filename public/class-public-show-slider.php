<?Php 
class ISP_Public_slider_show
{
    public function __construct()
    {
        $this->enqueue_public_scripts();
        add_action('wp_enqueue_scripts', [$this, 'enqueue_public_scripts']);
        add_shortcode('slider', [$this, 'custom_slider_shortcode']);
        add_filter('manage_image_slider_posts_columns', [$this, 'add_slider_column']);
        add_action('manage_image_slider_posts_custom_column', [$this, 'display_slider_column'], 10, 2);
    }

    public function enqueue_public_scripts()
    {
        $public_enqueue_class = 'ISP_Public_Enqueue';
        add_action('wp_enqueue_scripts', [$public_enqueue_class, 'enqueue_scripts']);
    }

    // Define the shortcode to display the slider with a specific post ID
    function custom_slider_shortcode($atts) {
        $atts = shortcode_atts(array(
            'id' => null,
            'size' => 'full', // Change 'full' to the desired image size
        ), $atts);

        if (!$atts['id']) {
            return ''; // If no post ID is provided, return an empty string
        }

        // Retrieve the stored image IDs for the specified post ID
        $image_ids = get_post_meta($atts['id'], '_image_slider_image_ids', true);

        if (!empty ($image_ids))
        {
            $slider_content = '<div class="slider-container">
                <div class="slider">';

            if (is_array($image_ids)) {
                foreach ($image_ids as $image_id) {
                    $image_url = wp_get_attachment_image_url($image_id, $atts['size']);
                    if ($image_url) {
                        $slider_content .= '<img src="' . esc_url($image_url) . '" class="slide" alt="Image ' . esc_attr($image_id) . '">';
                    }
                }
            }

            $slider_content .= '</div>
                <div class="counter-container">
                    <span id="active-count"></span> / <span id="total-count"></span>
                </div>
                <div class="dot-container">
                    <!-- Dots will be added dynamically using JavaScript -->
                </div>
                <div class="nav-buttons">
                    <button class="carousel-control-prev">&#10094;</button>
                    <button class="carousel-control-next">&#10095;</button>
                </div>
            </div>';
            return $slider_content;
        }
    }

    // Add a custom column to display the shortcode before the title
    public function add_slider_column($columns)
    {
        // Create a new array to hold the updated column order
        $new_columns = array();

        foreach ($columns as $key => $value) {
            // Add existing columns to the new array
            $new_columns[$key] = $value;

            // Add the "Shortcode" column before the "Title" column
            if ($key === 'title') {
                $new_columns['shortcode_column'] = 'Shortcode';
            }

             // Add the "Author" column before the "Title" column
            if ($key === 'title') {
                $new_columns['author'] = 'Author';
            }
        }

        return $new_columns;
    }

    // Display the shortcode content in the custom column
    public function display_slider_column($column, $post_id)
    {
        $slider_title = get_the_title($post_id);
        if ($column === 'shortcode_column') {
            echo '[slider id="' . $post_id . '" title="' . $slider_title . '"]';
        }
    }
}

// Instantiate the classes
$asp_public_slider_show = new ISP_Public_slider_show();
