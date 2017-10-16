var config = {
    'config': {
        'mixins': {
            'Magento_Checkout/js/view/shipping': {
                'Rubic_SimpleCheckout/js/view/shipping-mixin': true
            },
            'Magento_Checkout/js/view/payment': {
                'Rubic_SimpleCheckout/js/view/payment-mixin': true
            },
            'Magento_Checkout/js/view/summary/cart-items': {
                'Rubic_SimpleCheckout/js/view/cart-items-mixin': true
            },
            'Magento_Checkout/js/view/summary/abstract-total': {
                'Rubic_SimpleCheckout/js/view/abstract-total-mixin': true
            },
            'Magento_Checkout/js/model/step-navigator': {
                'Rubic_SimpleCheckout/js/model/navigator-mixin': true
            }
        }
    }
};