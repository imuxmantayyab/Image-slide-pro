<?php
/*
 * Plugin Name:       Image Slide Pro
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Handle the basics with this plugin.
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            M Usman Tayyab
 * Author URI:        https://author.muxmantayyab.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       isp
 * Domain Path:       /languages
 */

// Prevents the plugin file from being accessed directly.
if ( ! defined( 'WPINC' ) ) {
    die();
}

// include files.
require_once plugin_dir_path( __FILE__ ) . 'admin/class-admin-scripts.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/class-admin-settings-page.php';
require_once plugin_dir_path( __FILE__ ) . 'public/class-public-scripts.php';
require_once plugin_dir_path( __FILE__ ) . 'public/class-public-show-slider.php';
