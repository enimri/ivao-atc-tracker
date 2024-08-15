jQuery(document).ready(function($) {
    // Add a new ATC input field
    $('#add-atc').on('click', function(e) {
        e.preventDefault();
        var newField = '<div class="atc-input"><input type="text" name="ivao_atc_list[]" class="regular-text" /><button class="remove-atc button">Remove</button></div>';
        $('#ivao-atc-list').append(newField);
    });

    // Remove an ATC input field
    $(document).on('click', '.remove-atc', function(e) {
        e.preventDefault();
        $(this).parent('.atc-input').remove();
    });
});
