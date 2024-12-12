(function($) {
    $(".sc-quantity-wrapper").on("change", "input.qty", function() {
        $(this).closest(".sc-quantity-wrapper").find("[data-quantity]").attr("data-quantity", $(this).val());
    });
})(jQuery);