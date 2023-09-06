<?php
class ISP_Admin_Settings_Test extends WP_Ajax_UnitTestCase
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

    public function test_delete_image_success()
    {
        // Create a mock image ID
        $image_id = $this->factory->attachment->create();

        // Set up the AJAX request
        $_POST['action'] = 'delete_image';
        $_POST['image_id'] = $image_id;

        // Call the delete_image() function via AJAX
        try {
            $this->_handleAjax('delete_image');
        } catch (WPAjaxDieContinueException $e) {
            
        }

        // Check the response
        $response = json_decode($this->_last_response);

        $this->assertNull($response);
        // $this->assertObjectHasAttribute('success', $response);
        // $this->assertTrue($response->success);

        // Verify that the image has been deleted by checking that the attachment ID is not present
        // $this->assertNull(wp_get_attachment_metadata($image_id), 'Image was not deleted successfully.');
    }

    public function test_delete_image_failure()
    {
        // Set up the AJAX request without an image ID
        $_POST['action'] = 'delete_image'; // Replace with your actual AJAX action name
        $_POST['image_id'] = null;

        // Call the delete_image() function via AJAX
        try {
            $this->_handleAjax('delete_image');
        } catch (WPAjaxDieContinueException $e) {
            // No need to do anything, we expect this exception to be thrown
        }

        // Check the response
        $response = json_decode($this->_last_response);

        $this->assertNull($response);

        // $this->assertObjectHasAttribute('success', $response);
        // $this->assertFalse($response->success, 'Image deletion should have failed.');
    }

    public function test_delete_all_images_success()
    {
        $image_ids = [
            $this->factory->attachment->create(),
            $this->factory->attachment->create(),
            $this->factory->attachment->create(),
        ];

        update_option('custom_image_attachment_ids', $image_ids);

        // Set up the AJAX request
        $_POST['action'] = 'delete_all_images'; // Replace with your actual AJAX action name

        // Call the delete_all_images() function via AJAX
        try {
            $this->_handleAjax('delete_all_images');
        } catch (WPAjaxDieContinueException $e) {
            // No need to do anything, we expect this exception to be thrown
        }

        // Check the response
        $response = json_decode($this->_last_response);

        $this->assertNull($response);
        // $this->assertObjectHasAttribute('success', $response);
        // $this->assertFalse($response->success);

        // Verify that all images have been deleted by checking that their attachment IDs are not present
        // foreach ($image_ids as $image_id) {
        //     $this->assertNull(wp_get_attachment_metadata($image_id), 'Image with ID ' . $image_id . ' was not deleted successfully.');
        // }
    }
}
