/**
 * Copyright © 2018 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
define([
    'Magento_Checkout/js/action/set-shipping-information'
], function (setShippingInformationAction) {
    'use strict';

    /**
     * Save shipping information (and thus update order summary) on selecting shipping method.
     */
    return function (target) {
        return function (shippingMethod) {
            target(shippingMethod);
            setShippingInformationAction();
        };
    }
});