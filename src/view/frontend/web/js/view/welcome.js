/**
 * Copyright © 2017 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
define(
    [
        'jquery',
        'ko',
        'uiComponent',
        'underscore',
        'Magento_Checkout/js/model/step-navigator',
        'Magento_Customer/js/customer-data',
        'Magento_Customer/js/model/customer',
        'mage/translate',
        'Rubic_CleanCheckout/js/bindings/transitions'
    ],
    function (
        $,
        ko,
        Component,
        _,
        stepNavigator,
        customerData,
        customer,
        $t
    ) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Rubic_CleanCheckout/welcome'
            },

            loginFormSelector: 'form[data-role=email-with-possible-login]',

            isVisible: ko.observable(!customer.isLoggedIn()),

            initialize: function () {
                this._super();
                stepNavigator.registerStep('welcome', null, $t('Welcome'), this.isVisible, _.bind(this.navigate, this), 5);
                return this;
            },

            navigate: function () {
                if (customer.isLoggedIn()) {
                    this.navigateToNextStep();
                }
            },

            validateEmail: function () {
                var emailValidationResult = customer.isLoggedIn();

                if (!customer.isLoggedIn()) {
                    $(this.loginFormSelector).validation();
                    emailValidationResult = Boolean($(this.loginFormSelector + ' input[name=username]').valid());
                }
                return emailValidationResult;
            },

            navigateToNextStep: function () {
                if (this.validateEmail()) {
                    stepNavigator.next();
                } else {
                    $(this.loginFormSelector + ' input[name=username]').focus();
                }
            }
        });
    }
);