<?php
use PHPUnit\Framework\TestCase;
class CustomSliderShortcodeTest extends TestCase
{
    public $sliderInstance;
    protected $factory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->factory = new WP_UnitTest_Factory();
        $this->sliderInstance = new ISP_Public_slider_show();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->sliderInstance);
    }

    public function testCustomSliderShortcode()
    {
        // Set up some example image IDs
        $imageIds = $this->factory->attachment->create_many(10);
    
        // Store the example image IDs in the 'custom_image_attachment_ids' option
        update_option('custom_image_attachment_ids', $imageIds);
    
        // Call the custom_slider_shortcode method using $this->sliderInstance
        $sliderContent = $this->sliderInstance->custom_slider_shortcode();
    
        // Check if the generated slider content contains expected HTML elements
        $this->assertStringContainsString('<div class="slider-container">', $sliderContent);
        
        $this->assertStringContainsString('<div class="counter-container">', $sliderContent);
        $this->assertStringContainsString('<div class="dot-container">', $sliderContent);
        $this->assertStringContainsString('<div class="nav-buttons">', $sliderContent);
    
        // Clean up by removing the 'custom_image_attachment_ids' option
        delete_option('custom_image_attachment_ids');
    }
    
    
}
