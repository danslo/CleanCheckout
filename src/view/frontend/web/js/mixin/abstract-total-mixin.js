/**
 * Copyright © 2018 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
define([
    'Magento_Checkout/js/model/step-navigator'
], function (stepNavigator) {
    'use strict';

    /**
     * Allow configuration to force total full mode.
     */
    return function (target) {
        return target.extend({
            isFullMode: function () {
                if (!this.getTotals()) {
                    return false;
                }

                return window.checkoutConfig.forceTotalsFullMode ?
                    true : stepNavigator.isProcessed('shipping');
            }
        });
    };
});