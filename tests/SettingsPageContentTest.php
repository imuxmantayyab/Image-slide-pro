<?php 
class SettingsPageContentTest extends WP_UnitTestCase
{
    protected $plugin;
    protected $factory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->factory = new WP_UnitTest_Factory();
        $this->plugin = new ISP_Admin_Settings();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->plugin);
    }

    public function test_settings_page_content()
    {
        // Create a fake HTTP request to the settings page
        $_SERVER['REQUEST_URI'] = '/wp-admin/admin.php?page=image-slide-pro-settings'; // Replace with the actual page URL
        $_SERVER['REQUEST_METHOD'] = 'GET';

        ob_start(); // Start output buffering to capture the page content
        $this->plugin->settings_page_content(); // Call the function

        $output = ob_get_clean(); // Get the buffered output

        // Check if the page content contains specific elements or text you expect
        $this->assertStringContainsString('<h1>Image Slide Pro Settings</h1>', $output);
        $this->assertStringContainsString('<button type="submit" name="upload_image" class="upload_image button button-primary">Upload</button>', $output);
        $this->assertStringContainsString('<button type="button" id="delete-all-images" class="button button-secondry">Delete All</button>', $output);
        $this->assertStringNotContainsString('<div class="uploaded-image"', $output); // Check for the absence of uploaded images container

        // You can add more assertions based on the specific content you expect on the settings page
    }
}

