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
        'Magento_Customer/js/model/customer'
    ],
    function (
        $,
        ko,
        Component,
        _,
        stepNavigator,
        customerData,
        customer
    ) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Rubic_CleanCheckout/welcome'
            },

            loginFormSelector: 'form[data-role=email-with-possible-login]',

            isVisible: ko.observable(!customer.isLoggedIn()),

            /**
             *
             * @returns {*}
             */
            initialize: function () {
                this._super();
                stepNavigator.registerStep('welcome-step', null, 'Welcome', this.isVisible, _.bind(this.navigate, this), 5);
                return this;
            },

            /**
             * The navigate() method is responsible for navigation between checkout step
             * during checkout. You can add custom logic, for example some conditions
             * for switching to your custom step
             */
            navigate: function () {},

            validateEmail: function () {
                var emailValidationResult = customer.isLoggedIn();

                if (!customer.isLoggedIn()) {
                    $(this.loginFormSelector).validation();
                    emailValidationResult = Boolean($(this.loginFormSelector + ' input[name=username]').valid());
                }
                return emailValidationResult;
            },

            /**
             * @returns void
             */
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