/**
 * Copyright © 2017 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
define([
    'jquery',
    'Magento_Customer/js/model/customer'
], function ($, customer) {
    'use strict';

    /**
     * Disallow going back to Welcome step when we're logged in.
     * Stop jerky animations between steps by removing body animations.
     */
    return function (target) {
        target.navigateTo = function (code, scrollToElementId) {
            if (customer.isLoggedIn() && code === 'welcome-step') {
                return;
            }
            var sortedItems = target.steps.sort(this.sortItems);
            if (!this.isProcessed(code)) {
                return;
            }

            window.location = window.checkoutConfig.checkoutUrl + '#' + code;
            sortedItems.forEach(function (element) {
                if (element.code === code) {
                    element.isVisible(true);
                } else {
                    element.isVisible(false);
                }
            });
        };
        return target;
    };
});