jQuery(document).ready(function($) {
    // Get the "Upload Logo" button and the "logo-image" input field
    var uploadButton = $('#upload-btn');
    var logoImageInput = $('#logo-image');
    var logoImagePreview = $('#logo-image-preview img');

    // Show preview on page load if URL exists
    if (logoImageInput.val()) {
        logoImagePreview.show();
    } else {
        logoImagePreview.hide();
    }

    // Add a click event listener to the "Upload Logo" button
    uploadButton.on('click', function(e) {
        e.preventDefault();

        // Create a media frame
        var file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Choose Logo',
            button: {
                text: 'Use this image'
            },
            multiple: false  // Set to true to allow multiple files to be selected
        });

        // When an image is selected, run a callback.
        file_frame.on('select', function() {
            // We attach the image data to the variable
            var attachment = file_frame.state().get('selection').first().toJSON();

            // Update the "logo-image" input field with the image URL
            logoImageInput.val(attachment.url);

            // Update the image preview and make sure it's visible
            logoImagePreview.attr('src', attachment.url).show();
        });

        // Finally, open the modal
        file_frame.open();
    });

    // Initialize color pickers
    $('.color-pick').wpColorPicker();
});
