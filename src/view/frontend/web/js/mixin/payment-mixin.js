/**
 * Copyright © 2018 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
define([
    'ko',
    'Magento_Checkout/js/model/quote',
    'Magento_Customer/js/model/customer'
], function (ko, quote, customer) {
    'use strict';

    /**
     * Disable visibility on billing, since it's no longer the first step.
     */
    return function (target) {
        return target.extend({
            defaults: {
                template: 'Rubic_CleanCheckout/payment',
                activeMethod: ''
            },

            isVisible: ko.observable(customer.isLoggedIn() && quote.isVirtual())
        });
    };
});