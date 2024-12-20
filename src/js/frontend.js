(function($) {
    $(".sc-quantity-wrapper").on("input change", "input.qty", function() {
        let val = $(this).val();
        if (val <= 0 || isNaN(val)) {
            val = 1;
            $(this).val(val);
        }
        $(this).closest(".sc-quantity-wrapper").find("[data-quantity]").attr("data-quantity", val);
    });

    $(document).on("click", ".qty-minus, .qty-plus", function() {
        const button = $(this);
        const input = button.closest(".quantity-buttons").find("input.qty");
        let currentValue = parseInt(input.val()) || 1;
        const min = parseInt(input.attr("min")) || 1;
        const max = parseInt(input.attr("max")) || Infinity;
        const step = parseInt(input.attr("step")) || 1;
        if (button.hasClass("qty-minus")) {
            currentValue = Math.max(min, currentValue - step);
        } else if (button.hasClass("qty-plus")) {
            currentValue = Math.min(max, currentValue + step);
        }
        input.val(currentValue).trigger("change");
    });

    $(document).on("input", "input.qty", function() {
        const input = $(this);
        const min = parseInt(input.attr("min")) || 1;
        const max = parseInt(input.attr("max")) || Infinity;
        let value = parseInt(input.val()) || min;
        if (value < min) value = min;
        if (value > max) value = max;
        input.val(value);
    });
})(jQuery);