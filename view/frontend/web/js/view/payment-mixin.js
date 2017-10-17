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
                defaults: {
                    template: 'Rubic_CleanCheckout/payment',
                    activeMethod: ''
                },

                isVisible: ko.observable(false)
            });
        };
    }
);