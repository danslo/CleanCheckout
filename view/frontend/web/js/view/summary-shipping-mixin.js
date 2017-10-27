/**
 * Copyright © 2017 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
define(
    [
        'Magento_Checkout/js/model/quote'
    ],
    function (quote) {
        'use strict';

        /**
         * Hides shipping method title if required by configuration.
         */
        return function (target) {
            return target.extend({
                getShippingMethodTitle: function () {
                    return window.checkoutConfig.hideShippingTitle ? '' : this._super();
                }
            });
        };
    }
);