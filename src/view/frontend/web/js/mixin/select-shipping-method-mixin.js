/**
 * Copyright © 2018 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
define([
    'underscore',
    'uiRegistry',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/resource-url-manager',
    'mage/storage',
    'Magento_Checkout/js/model/full-screen-loader'
], function (_, registry, quote, resourceUrlManager, storage, fullScreenLoader) {
    'use strict';

    /**
     * Save shipping information (and thus update order summary) on selecting shipping method.
     */
    return function (target) {
        return function (shippingMethod) {
            /**
             * No need to update if shipping estimate is already accurate.
             */
            if (_.isEqual(quote.shippingMethod(), shippingMethod)) {
                return;
            }

            /**
             * Update the quote.
             */
            target(shippingMethod);

            /**
             * No need to update shipping information when we're only dealing with a single rate.
             *
             * We are forced to use registry here because requiring shippingService would create a circular dependency.
             */
            var shippingAddress = registry.get('checkout.steps.shipping-step.shippingAddress');
            if (typeof shippingAddress === 'undefined' || shippingAddress.rates().length <= 1) {
                return;
            }

            var payload = {
                addressInformation: {
                    'shipping_address': quote.shippingAddress(),
                    'shipping_method_code': shippingMethod['method_code'],
                    'shipping_carrier_code': shippingMethod['carrier_code']
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