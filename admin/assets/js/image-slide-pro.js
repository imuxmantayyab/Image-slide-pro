
jQuery(document).ready(function($) {
    var imageContainer = document.querySelector('.image-container');
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
        console.log(data);
        data.append('action', 'update_image_order');
        data.append('image_ids', JSON.stringify(imageIds));

        fetch(ajaxurl, {
            method: 'POST',
            body: data
        }).then(function(response) {
            return response.json();
        }).then(function(data) {
            if (data.success) {
                alert('Image order updated successfully.');
            } else {
                console.error('Image order update failed.');
            }
        }).catch(function(error) {
            console.error('Error:', error);
        });
    }

    var deleteButtons = document.querySelectorAll('.delete-button');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            var imageId = button.getAttribute('data-image-id');
            if (confirm('Are you sure you want to delete this image?')) {
                deleteImage(imageId, button);
            }
        });
    });

    function deleteImage(imageId, button) {
        var data = new URLSearchParams();
        console.log(data);
        data.append('action', 'delete_image');
        data.append('image_id', imageId);

        fetch(ajaxurl, {
            method: 'POST',
            body: data
        }).then(function(response) {
            return response.json();
        }).then(function(data) {
            if (data.success) {
                var deletedImage = button.parentElement;
                if (deletedImage) {
                    deletedImage.remove();
                }
                console.log('Image deleted successfully.');
            } else {
                console.error('Image deletion failed.');
            }
        }).catch(function(error) {
            console.error('Error:', error);
        });
    }

    var deleteAllButton = document.getElementById('delete-all-images');
    deleteAllButton.addEventListener('click', function() {
        if (confirm('Are you sure you want to delete all images?')) {
            deleteAllImages();
        }
    });



    function deleteAllImages() {
        var data = new URLSearchParams();
        data.append('action', 'delete_all_images');

        fetch(ajaxurl, {
            method: 'POST',
            body: data
        }).then(function(response) {
            return response.json();
        }).then(function(data) {
            if (data.success) {
                var imageContainer = document.querySelector('.image-container');
                if (imageContainer) {
                    imageContainer.innerHTML = '';
                }
                console.log('All images deleted successfully.');
            } else {
                console.error('Image deletion failed.');
            }
        }).catch(function(error) {
            console.error('Error:', error);
        });
    }
});