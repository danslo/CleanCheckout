/**
 * Copyright © 2018 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
require(["jquery","jquery/colorpicker/js/colorpicker"], function ($) {
    $(document).ready(function () {
        $('.colorpicker-text').each(function() {
            var e = $(this);
            e.css("backgroundColor", e.val());
            e.ColorPicker({
                color: e.val(),
                onChange: function (hsb, hex, rgb) {
                    e.css("backgroundColor", "#" + hex).val("#" + hex);
                }
            });
        });
    });
});