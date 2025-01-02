(function($) {
    $(document).on("click", ".qty-minus, .qty-plus", function(event) {
        if ($(this).prop("disabled")) {
            // Prevent click if the button is disabled
            event.preventDefault();
            return;
        }
        const button = $(this);
        const input = button.closest(".wcqf-btn-wrapper").find("input.qty");
        const currentValue = parseInt(input.val()) || 1;
        const min = parseInt(input.attr("min")) || 1;
        const max = parseInt(input.attr("max")) || Infinity;
        const step = parseInt(input.attr("step")) || 1;
        let val = 1;
        if (button.hasClass("qty-minus")) {
            val = Math.max(min, currentValue - step);
        } else if (button.hasClass("qty-plus")) {
            val = Math.min(max, currentValue + step);
        }

        if ( val !== currentValue ){
            input.val(val);
            input.trigger("change");
        } else {
            // Enable/Disable the buttons based on min/max.
            button.prop("disabled", currentValue <= min);
            button.prop("disabled", currentValue >= max);
        }

    });

    $(document).on("input change", "input.qty", function() {
        const input = $(this);
        const min = parseInt(input.attr("min")) || 1;
        const max = parseInt(input.attr("max")) || Infinity;
        let value = parseInt(input.val()) || min;

        const plusBtn = input.closest(".wcqf-btn-wrapper").find(".qty-plus");
        const minusBtn = input.closest(".wcqf-btn-wrapper").find(".qty-minus");

        // Adjust value if it goes out of bounds
        if (value < min) value = min;
        if (value > max) value = max;
        input.val(value);
        input.closest(".wcqf-quantity-wrapper").find("[data-quantity]").attr("data-quantity", value);
        if ( $(document).find("#order_review").length > 0 ){
            handleOrderReviewChange( input );
        }
        // Enable/Disable the buttons based on min/max
        minusBtn.prop("disabled", value <= min);
        plusBtn.prop("disabled", value >= max);

    });

    // Function to handle order review changes
    function handleOrderReviewChange( input ) {
        const parentsSelector = input.parents('.wcqf-checkout-inner');
        let cartItemKey = parentsSelector.data('cart_item_key');
        const val = parseInt(input.val()) || 1;
        const data = {
            action: "wcqf_update_checkout_order_data",
            security: wcqf_checkout_params.update_order_review_nonce,
            cartItemKey : cartItemKey,
            quantity : val
        };
        //console.log( wcqf_checkout_params.ajax_url )
        $.post(wcqf_checkout_params.ajax_url, data, function (response) {
            if ( response.success ){
               $( document.body ).trigger( 'update_checkout' );
            }
        });
    }


})(jQuery);