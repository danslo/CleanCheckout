/**
 * Copyright © 2018 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
define([
    'jquery',
    'Magento_Checkout/js/model/full-screen-loader',
    'Magento_Checkout/js/checkout-data'
], function ($, fullScreenLoader, checkoutData) {
    'use strict';

    /**
     * Newsletter subscription after order placement.
     */
    return function (target) {
        return target.extend({
            getEmail: function () {
                return window.isCustomerLoggedIn
                    ? window.customerData.email
                    : checkoutData.getValidatedEmailValue()
            },

            afterPlaceOrder: function () {
                if (window.checkoutConfig.newsletterEnabled) {
                    var checked = Boolean($('[name="newsletter-subscribe"]').attr('checked'));
                    if (checked) {
                        fullScreenLoader.startLoader();
                        $.ajax({
                            type: 'POST',
                            url: window.checkoutConfig.newsletterUrl,
                            data: {'email': this.getEmail()},
                            async: false
                        }).always(function () {
                            fullScreenLoader.stopLoader();
                        });
                    }
                }
                this._super();
            }
        });
    };
});