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
                'Rubic_CleanCheckout/js/view/shipping-mixin': true
            },
            'Magento_Checkout/js/view/payment': {
                'Rubic_CleanCheckout/js/view/payment-mixin': true
            },
            'Magento_Checkout/js/view/payment/default': {
                'Rubic_CleanCheckout/js/view/payment-default-mixin': true
            },
            'Magento_Checkout/js/view/summary/cart-items': {
                'Rubic_CleanCheckout/js/view/cart-items-mixin': true
            },
            'Magento_Checkout/js/view/summary/shipping': {
                'Rubic_CleanCheckout/js/view/summary-shipping-mixin': true
            },
            'Magento_Checkout/js/view/summary/abstract-total': {
                'Rubic_CleanCheckout/js/view/abstract-total-mixin': true
            },
            'Magento_Checkout/js/model/step-navigator': {
                'Rubic_CleanCheckout/js/model/navigator-mixin': true
            },
            'Magento_Checkout/js/model/shipping-rates-validator': {
                'Rubic_CleanCheckout/js/model/shipping-rates-validator-mixin': true
            }
        }
    }
};