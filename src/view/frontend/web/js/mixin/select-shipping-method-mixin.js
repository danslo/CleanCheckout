/**
 * Copyright © 2018 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
define([
    'uiRegistry',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/resource-url-manager',
    'mage/storage',
    'Magento_Checkout/js/model/full-screen-loader'
], function (registry, quote, resourceUrlManager, storage, fullScreenLoader) {
    'use strict';

    /**
     * Save shipping information (and thus update order summary) on selecting shipping method.
     */
    return function (target) {
        return function (shippingMethod) {
            target(shippingMethod);

            /**
             * No need to update shipping information when we're only dealing with a single rate.
             *
             * We are forced to use registry here because requiring shippingService would create a circular dependency.
             */
            var rates = registry.get('checkout.steps.shipping-step.shippingAddress').rates();
            if (rates.length <= 1) {
                return;
            }

            var payload = {
                addressInformation: {
                    'shipping_address': quote.shippingAddress(),
                    'shipping_method_code': quote.shippingMethod()['method_code'],
                    'shipping_carrier_code': quote.shippingMethod()['carrier_code']
                }
            };

            fullScreenLoader.startLoader();

            return storage.post(
                resourceUrlManager.getUrlForSetShippingInformation(quote),
                JSON.stringify(payload)
            ).done(
                function (response) {
                    quote.setTotals(response.totals);
                    fullScreenLoader.stopLoader();
                }
            ).fail(
                function () {
                    fullScreenLoader.stopLoader();
                }
            );
        };
    }
});