function equalheight() {
    var maxHeight = 0;
    jQuery('.calender-flex').each(function (index) {
        jQuery(this).find('.vc_column-inner').height('auto');
        jQuery(this).find('.vc_column-inner').each(function (index) {
            if (jQuery(this).height() > maxHeight)
                maxHeight = jQuery(this).outerHeight();
        });

    });
    jQuery('.calender-flex .vc_column-inner').height(maxHeight);
}

jQuery(document).ready(function () {
    equalheight();
});
jQuery(window).bind("resize", equalheight);