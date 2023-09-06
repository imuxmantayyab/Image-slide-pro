<?php 
class ISP_Public_slider_show
{
    public function __construct()
    {
        $this->enqueue_public_scripts();

        // Register the shortcode [custom_slider]
        add_shortcode('custom_slider', [$this, 'custom_slider_shortcode']);
    }

    public function enqueue_public_scripts()
    {
        $public_enqueue_class = 'ISP_Public_Enqueue';
        add_action('wp_enqueue_scripts', [$public_enqueue_class, 'enqueue_scripts']);
    }

    public function custom_slider_shortcode()
    {
        // Retrieve the stored image attachment IDs
        $uploaded_image_ids = get_option('custom_image_attachment_ids', array());

        // Initialize the slider content
        $slider_content = '<div class="slider-container">
            <div class="slider">';

        // Loop through the image IDs and generate the slider HTML
        foreach ($uploaded_image_ids as $image_id) {
            $image_url = wp_get_attachment_image_url($image_id, 'full'); // Change 'full' to the desired image size
            if ($image_url) {
                $slider_content .= '<img src="' . esc_url($image_url) . '" class="slide" alt="Image ' . esc_attr($image_id) . '">';
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

$asp_public_slider_show = new ISP_Public_slider_show();
