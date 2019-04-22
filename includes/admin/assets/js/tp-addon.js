(function($) {
    "use strict";

    $( "[data-tp-tabs]" ).tabs({
        create: function( event, ui ) {
            $(ui.tab).find("a").addClass("nav-tab-active");
        },
        activate: function( event, ui ) {
            $(ui.newTab).find("a").addClass("nav-tab-active");
            $(ui.oldTab).find("a").removeClass("nav-tab-active");
        }
    });
})(jQuery);