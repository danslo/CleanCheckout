/**
 * Copyright © 2017 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
var config = {
    map: {
        '*': {
            async: 'Rubic_CleanCheckout/js/async'
        }
    },
    config: {
        mixins: {
            'Magento_Checkout/js/view/shipping': {
                'Rubic_CleanCheckout/js/mixin/shipping-mixin': true
            },
            'Magento_Checkout/js/view/payment': {
                'Rubic_CleanCheckout/js/mixin/payment-mixin': true
            },
            'Magento_Checkout/js/view/payment/default': {
                'Rubic_CleanCheckout/js/mixin/payment-default-mixin': true
            },
            'Magento_Checkout/js/view/summary/cart-items': {
                'Rubic_CleanCheckout/js/mixin/cart-items-mixin': true
            },
            'Magento_Checkout/js/view/summary/shipping': {
                'Rubic_CleanCheckout/js/mixin/summary-shipping-mixin': true
            },
            'Magento_Checkout/js/view/summary/abstract-total': {
                'Rubic_CleanCheckout/js/mixin/abstract-total-mixin': true
            },
            'Magento_Checkout/js/view/form/element/email': {
                'Rubic_CleanCheckout/js/mixin/email-mixin': true
            },
            'Magento_Checkout/js/model/step-navigator': {
                'Rubic_CleanCheckout/js/mixin/navigator-mixin': true
            },
            'Magento_Checkout/js/model/shipping-rates-validator': {
                'Rubic_CleanCheckout/js/mixin/shipping-rates-validator-mixin': true
            }
        }
    }
};