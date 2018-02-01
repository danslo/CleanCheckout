/**
 * Copyright © 2018 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
define([
    'jquery',
    'Magento_Customer/js/model/customer',
    'Magento_Checkout/js/model/quote'
], function ($, customer, quote) {
    'use strict';

    /**
     * - Disallow going back to Email step when we're logged in.
     * - Stop jerky animations between steps by removing body animations.
     */
    return function (target) {
        var getDefaultStep = function () {
            if (!customer.isLoggedIn()) {
                return 'email';
            }
            return quote.isVirtual() ? 'payment' : 'shipping';
        };

        target.navigateTo = function (code, scrollToElementId) {
            if (customer.isLoggedIn() && code === 'email') {
                return;
            }
            var sortedItems = target.steps.sort(this.sortItems);
            if (!this.isProcessed(code)) {
                return;
            }

            window.location = window.checkoutConfig.checkoutUrl + '#' + code;
            sortedItems.forEach(function (element) {
                element.isVisible(element.code === code);
            });
        };

        var originalHandleHash = target.handleHash;
        target.handleHash = function () {
            var hashString = window.location.hash.replace('#', '');
            if (hashString === '') {
                target.navigateTo(getDefaultStep());
                return true;
            }
            return originalHandleHash.bind(target)();
        };

        return target;
    };
});