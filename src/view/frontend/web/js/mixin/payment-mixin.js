/**
 * Copyright © 2018 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
define([
    'ko',
    'Magento_Checkout/js/model/quote',
    'Magento_Customer/js/model/customer',
    'Magento_Checkout/js/checkout-data'
], function (ko, quote, customer, checkoutData) {
    'use strict';

    return function (target) {
        return target.extend({
            defaults: {
                template: 'Rubic_CleanCheckout/payment',
                activeMethod: ''
            },

            /**
             * Disable visibility on billing, since it's no longer the first step.
             */
            isVisible: ko.observable(customer.isLoggedIn() && quote.isVirtual()),

            /**
             * Set the default payment method if not set in local storage yet.
             */
            initialize: function() {
                if (checkoutData.getSelectedPaymentMethod() === null) {
                    checkoutData.setSelectedPaymentMethod(window.checkoutConfig.defaultPaymentMethod);
                }
                return this._super();
            }
        });
    };
});