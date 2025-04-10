jQuery(document).ready(function ($) {

    // Function to toggle popup fields based on dropdown selection
    function togglePopupFields() {
        var displayOption = localStorage.getItem("position");
        var popupField = $('#popup_fields');

        if (displayOption === 'popup') {
            popupField.show();
            $('#popup_page').select2({
                placeholder: "Select pages",
                allowClear: true,
                width: 'resolve'
            });
        } else {
            popupField.hide();
        }
    }

    togglePopupFields();

    // On change of the dropdown, update localStorage and toggle fields
    $('#display_position').on('change', function () {
        var selectedPosition = $(this).val();
        localStorage.setItem("position", selectedPosition);
        togglePopupFields();
    });

    // Initialize select2 for the popup pages multi-select
    $('select[name="popup_pages[]"]').select2({
        placeholder: "Select pages",
        allowClear: true,
        width: 'resolve'
    });

});
