define([], function () {
    'use strict';

    /**
     * Always expand items block by default.
     *
     * There's no specific reason to only do this after shipping step.
     */
    return function (target) {
        return target.extend({
            isItemsBlockExpanded: function () {
                return true;
            }
        });
    };
});