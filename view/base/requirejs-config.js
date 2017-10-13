var config = {
    'config': {
        'mixins': {
            'Magento_Checkout/js/view/shipping': {
                'Rubic_SimpleCheckout/js/view/shipping-payment-mixin': true
            },
            'Magento_Checkout/js/view/payment': {
                'Rubic_SimpleCheckout/js/view/shipping-payment-mixin': true
            },
            'Magento_Checkout/js/view/summary/cart-items': {
                'Rubic_SimpleCheckout/js/view/cart-items-mixin': true
            }
        }
    }
};