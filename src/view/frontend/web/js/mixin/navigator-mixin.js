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

    return function (target) {
        /**
         * Disallow going back to Email step when we're logged in.
         * Stop jerky animations between steps by removing body animations.
         */
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

        /**
         * Backported handleHash from 2.2 to pass element in element.navigate call.
         */
        target.handleHash = function () {
            var hashString = window.location.hash.replace('#', ''),
                isRequestedStepVisible;

            if (hashString === '') {
                if (!customer.isLoggedIn()) {
                    target.navigateTo('email');
                }
                target.navigateTo(quote.isVirtual() ? 'payment' : 'shipping');
                return false;
            }

            if ($.inArray(hashString, this.validCodes) === -1) {
                window.location.href = window.checkoutConfig.pageNotFoundUrl;

                return false;
            }

            isRequestedStepVisible = target.steps.sort(this.sortItems).some(function (element) {
                return (element.code == hashString || element.alias == hashString) && element.isVisible(); //eslint-disable-line
            });

            //if requested step is visible, then we don't need to load step data from server
            if (isRequestedStepVisible) {
                return false;
            }

            target.steps.sort(this.sortItems).forEach(function (element) {
                if (element.code == hashString || element.alias == hashString) { //eslint-disable-line eqeqeq
                    element.navigate(element);
                } else {
                    element.isVisible(false);
                }

            });

            return false;
        };

        return target;
    };
});