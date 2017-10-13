define([], function () {
    'use strict';

    return function (target) {
        /**
         * Always expand items block by default.
         *
         * There's no specific reason to only do this after shipping step.
         */
        return target.extend({
            isItemsBlockExpanded: function () {
                return true;
            }
        });
    };
});