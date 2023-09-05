<?php
// Define a class named "ISP_Admin_Enqueue"
class ISP_Admin_Enqueue
{
    // Define a public static method named "enqueue_scripts"
    public static function enqueue_scripts()
    {
        // Enqueue SortableJS library
        wp_enqueue_script('sortable-js', plugin_dir_url(dirname(__FILE__)) . 'lib/sortable-js/Sortable.min.js', array('jquery'), null, true);
        wp_enqueue_script('js-image-slide-pro', plugin_dir_url(__FILE__) . 'assets/js/image-slide-pro.js', array('jquery'), '1.0', true);
        wp_enqueue_style('css-image-slide-pro', plugin_dir_url(__FILE__) . 'assets/css/image-slide-pro.css');
    }
}