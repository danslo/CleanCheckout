define(
    [
        'ko'
    ], function (ko) {
        'use strict';

        /**
         * Disable visibility on shipping/billing, since they're no longer the first step.
         */
        return function (target) {
            return target.extend({
                initialize: function () {
                    this.visible = ko.observable(false);
                    this._super();
                    return this;
                }
            });
        };
    }
);