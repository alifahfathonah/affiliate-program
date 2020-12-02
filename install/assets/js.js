(function ($) {
    $.fn.btn = function (action) {
        var self = $(this);
        if (action == 'loading') {
            $(self).addClass("btn-loading");
        }
        if (action == 'reset') {
            $(self).removeClass("btn-loading");
        }
    }
})(jQuery); 