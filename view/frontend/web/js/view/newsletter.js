define(
    [
        'uiComponent'
    ],
    function (
        Component
    ) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Rubic_SimpleCheckout/newsletter'
            },

            isCheckedByDefault: function () {
                return true;
            },

            getCheckoutLabel: function () {
                return 'Subscribe to our newsletter to receive exclusive offers and the latest news on our products and services.';
            }
        });
    }
);