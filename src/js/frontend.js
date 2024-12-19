(function($) {
    $(".sc-quantity-wrapper").on("input change", "input.qty", function() {
        let val = $(this).val();
        // Ensure value is a positive number greater than 0
        if (val <= 0 || isNaN(val)) {
            val = 1; // Set default value if 0, negative, or not a number
            $(this).val(val); // Update the input field to reflect the corrected value
        }
        // Update the data-quantity attribute
        $(this).closest(".sc-quantity-wrapper").find("[data-quantity]").attr("data-quantity", val);
    });
})(jQuery);