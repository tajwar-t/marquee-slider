jQuery(document).ready(function($) {
    // Add image button
    $('#ms-add-image').on('click', function(e) {
        e.preventDefault();
        var frame = wp.media({
            title: 'Select Images',
            button: { text: 'Add Images' },
            multiple: true
        });

        frame.on('select', function() {
            var attachments = frame.state().get('selection').toJSON();
            var container = $('#ms-image-container');
            attachments.forEach(function(attachment) {
                var imgHtml = '<div class="ms-image-preview">' +
                    '<img src="' + attachment.url + '" style="max-width: 100px; height: auto;" />' +
                    '<input type="hidden" name="ms_images[]" value="' + attachment.id + '">' +
                    '<button type="button" class="ms-remove-image">Remove</button>' +
                    '</div>';
                container.append(imgHtml);
            });
        });

        frame.open();
    });

    // Remove image button
    $(document).on('click', '.ms-remove-image', function() {
        $(this).closest('.ms-image-preview').remove();
    });
});