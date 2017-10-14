define(
    [
        'ko',
        'uiComponent',
        'underscore',
        'Magento_Checkout/js/model/step-navigator',
        'Magento_Customer/js/customer-data'
    ],
    function (
        ko,
        Component,
        _,
        stepNavigator,
        customerData
    ) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Rubic_SimpleCheckout/introduction'
            },

            isVisible: ko.observable(true),

            currentlyLoggedIn: function() {
                var customer = customerData.get('customer')();
                return customer && customer.firstname;
            },

            /**
             *
             * @returns {*}
             */
            initialize: function () {
                console.log();

                this._super();
                stepNavigator.registerStep('introduction', null, 'Introduction', this.isVisible, _.bind(this.navigate, this), 5);
                return this;
            },

            /**
             * The navigate() method is responsible for navigation between checkout step
             * during checkout. You can add custom logic, for example some conditions
             * for switching to your custom step
             */
            navigate: function () {
                console.log(1);
            },

            /**
             * @returns void
             */
            navigateToNextStep: function () {
                stepNavigator.next();
            }
        });
    }
);