(function($) {
    $(document).on("click", ".qty-minus, .qty-plus", function() {
        const button = $(this);
        const input = button.closest(".wcqf-btn-wrapper").find("input.qty");
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

    $(document).on("input change", "input.qty", function() {
        const input = $(this);
        const min = parseInt(input.attr("min")) || 1;
        const max = parseInt(input.attr("max")) || Infinity;
        let value = parseInt(input.val()) || min;
        if (value < min) value = min;
        if (value > max) value = max;
        input.val(value);
        input.closest(".wcqf-quantity-wrapper").find("[data-quantity]").attr("data-quantity", value);
    });

    $("form.checkout").on("keypress", "input.qty", function(event) {
        return event.charCode >= 48 && event.charCode <= 57;
    });
    $("form.checkout").on("change", "input.qty", function(event) {
        const $form = $("form.checkout");
        if ($form[0].checkValidity()) {
            const data = {
                action: "wcqf_update_order_review",
                security: wcqf_checkout_params.update_order_review_nonce,
                post_data: $form.serialize(),
            };
            $.post(wcqf_checkout_params.ajax_url, data, function(response) {
                console.log('mmmmmmmmmmmmmmmmm')
                $("body").trigger("update_checkout");
            });
        }
    });
})(jQuery);