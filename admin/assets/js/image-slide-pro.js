jQuery(document).ready(function ($) {
    // Initialize the media uploader
    var mediaUploader;
    $('#open-media-uploader').click(function (e) {
        e.preventDefault();
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }
        // Create and open the media uploader frame
        mediaUploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Select Image'
            },
            multiple: true
        });
        // Handle selected image(s)
        mediaUploader.on('select', function () {
            var attachments = mediaUploader.state().get('selection').toJSON();
            var imageContainer = $('.slider-image-preview');
            attachments.forEach(function (attachment) {
                var imageId = attachment.id;
                var imageUrl = attachment.url;
                // Display the selected image in the meta box
                imageContainer.append('<div class="uploaded-image" data-image-id="' + imageId + '"><img class="slider-image" src="' + imageUrl + '"><button type="button" class="delete-button" data-image-id="' + imageId + '">&times;</button></div>');
                imageContainer.append('<input type="hidden" class="slider-image-id" name="image_slider_image_ids[]" value="' + imageId + '">');
            });
        });
        // Open the media uploader
        mediaUploader.open();
    });

    // Handle delete button clicks
    $('.slider-image-preview').on('click', '.delete-button', function () {
        var postID = $(this).data('image-id');
        
        // Show a confirmation dialog
        var confirmation = confirm('Are you sure you want to delete this image? If ( YES ) Then after delete image update post to confirm delete');

        if (confirmation) {
            // If user confirms, proceed with the deletion
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'delete_image_slider_images',
                    post_id: postID,
                },
                success: function (response) {
                    if (response.success) {
                        $('.uploaded-image[data-image-id="' + postID + '"]').remove();
                    } else {
                        console.error(response.data);
                    }
                },
            });
        }
    });


    var imageContainer = document.querySelector('.slider-image-preview');

    if (imageContainer) {
        new Sortable(imageContainer, {
            handle: '.uploaded-image',
            animation: 150,
            onEnd: function(event) {
                var imageIds = Array.from(imageContainer.querySelectorAll('.uploaded-image')).map(function(img) {
                    return img.getAttribute('data-image-id');
                });
                updateImageOrder(imageIds);
            }
        });
    }

    function updateImageOrder(imageIds) {
        var data = new URLSearchParams();
        data.append('action', 'update_image_order');
        data.append('image_ids', JSON.stringify(imageIds));

        fetch(ajaxurl, {
            method: 'POST',
            body: data
        }).then(function(response) {
            return response.json();
        }).then(function(data) {
            if (data.success) {
                console.log('Image order updated successfully.');
            } else {
                console.error('Image order update failed.');
            }
        }).catch(function(error) {
            console.error('Error:', error);
        });
    }

});