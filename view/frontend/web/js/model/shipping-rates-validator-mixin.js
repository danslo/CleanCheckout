/**
 * Copyright © 2017 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
define([], function () {
    'use strict';

    /**
     * Allow configuration to set shipping validator delay.
     */
    return function (target) {
        target.validateDelay = window.checkoutConfig.shippingValidateDelay;
        return target;
    };
});