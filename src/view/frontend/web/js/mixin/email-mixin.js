/**
 * Copyright © 2018 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
define(['Magento_Checkout/js/checkout-data'], function (checkoutData) {
    'use strict';

    /**
     * - Set our own email (login) template.
     * - Reduce the check delay down from 2 seconds.
     */
    return function (target) {
        return target.extend({
            defaults: {
                template: 'Rubic_CleanCheckout/form/element/email',
                email: checkoutData.getInputFieldEmailValue(),
                emailFocused: false,
                isLoading: false,
                isPasswordVisible: false,
                listens: {
                    email: 'emailHasChanged',
                    emailFocused: 'validateEmail'
                }
            },

            initialize: function () {
                this.checkDelay = 500;
                return this._super();
            }
        });
    }
});