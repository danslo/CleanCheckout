define([
    'ko',
    'Magento_Customer/js/model/customer',
    'Magento_Checkout/js/model/address-converter',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/action/select-shipping-address',
    'mage/translate'
], function (ko, customer, addressConverter, quote, selectShippingAddress, $t) {
    'use strict';

    return function (target) {
        return target.extend({
            /**
             * Override shipping template so we can hide rates when there's only 1 available.
             *
             * @returns {initialize}
             */
            defaults: {
                template: 'Rubic_SimpleCheckout/shipping',
                shippingFormTemplate: 'Magento_Checkout/shipping-address/form',
                shippingMethodListTemplate: 'Magento_Checkout/shipping-address/shipping-method-list',
                shippingMethodItemTemplate: 'Magento_Checkout/shipping-address/shipping-method-item'
            },

            initialize: function () {
                this.visible = ko.observable(customer.isLoggedIn());
                this._super();
                return this;
            },

            shouldHideShipping: function() {
                return window.checkoutConfig.hideShippingMethods && this.rates().length === 1;
            },

            /**
             * Removed email validation as we do that in a previous step.
             */
            validateShippingInformation: function () {
                var shippingAddress,
                    addressData,
                    field;

                if (!quote.shippingMethod()) {
                    this.errorValidationMessage($t('Please specify a shipping method.'));

                    return false;
                }

                if (this.isFormInline) {
                    this.source.set('params.invalid', false);
                    this.triggerShippingDataValidateEvent();

                    if (this.source.get('params.invalid') ||
                        !quote.shippingMethod()['method_code'] ||
                        !quote.shippingMethod()['carrier_code']
                    ) {
                        this.focusInvalid();

                        return false;
                    }

                    shippingAddress = quote.shippingAddress();
                    addressData = addressConverter.formAddressDataToQuoteAddress(
                        this.source.get('shippingAddress')
                    );

                    //Copy form data to quote shipping address object
                    for (field in addressData) {
                        if (addressData.hasOwnProperty(field) &&  //eslint-disable-line max-depth
                            shippingAddress.hasOwnProperty(field) &&
                            typeof addressData[field] != 'function' &&
                            _.isEqual(shippingAddress[field], addressData[field])
                        ) {
                            shippingAddress[field] = addressData[field];
                        } else if (typeof addressData[field] != 'function' &&
                            !_.isEqual(shippingAddress[field], addressData[field])) {
                            shippingAddress = addressData;
                            break;
                        }
                    }

                    if (customer.isLoggedIn()) {
                        shippingAddress['save_in_address_book'] = 1;
                    }
                    selectShippingAddress(shippingAddress);
                }
                return true;
            }
        });
    }
});