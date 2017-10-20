/**
 * Copyright © 2017 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
define(
    [
        'Magento_Checkout/js/model/quote'
    ], function (quote) {
        'use strict';

        /**
         * Hides shipping method title if required by configuration.
         */
        return function (target) {
            return target.extend({
                getShippingMethodTitle: function () {
                    if (window.checkoutConfig.hideShippingTitle || !this.isCalculated()) {
                        return '';
                    }
                    var shippingMethod = quote.shippingMethod();

                    return shippingMethod ? shippingMethod['carrier_title'] + ' - ' + shippingMethod['method_title'] : '';
                }
            });
        };
    }
);