<?php
// Define a class named "ISP_Public_Enqueue"
class ISP_Public_Enqueue
{
    // Define a public static method named "enqueue_scripts"
    public static function enqueue_scripts()
    {
        wp_enqueue_script('js-image-slide-pro', plugin_dir_url(__FILE__) . 'assets/js/public-image-slide-pro.js', array('jquery'), '1.0', true);
        wp_enqueue_style('css-image-slide-pro', plugin_dir_url(__FILE__) . 'assets/css/public-image-slide-pro.css');
    }
}