define([
    'jquery',
    'Magento_Customer/js/model/customer'
], function ($, customer) {
    'use strict';

    /**
     * Disallow going back to Welcome step when we're logged in.
     */
    return function (target) {
        target.navigateTo = function (code, scrollToElementId) {
            if (customer.isLoggedIn() && code === 'welcome-step') {
                return;
            }

            var sortedItems = target.steps.sort(this.sortItems),
                bodyElem = $.browser.safari || $.browser.chrome ? $('body') : $('html');

            scrollToElementId = scrollToElementId || null;

            if (!this.isProcessed(code)) {
                return;
            }
            sortedItems.forEach(function (element) {
                if (element.code === code) {
                    element.isVisible(true);
                    bodyElem.animate({
                        scrollTop: $('#' + code).offset().top
                    }, 0, function () {
                        window.location = window.checkoutConfig.checkoutUrl + '#' + code;
                    });

                    if (scrollToElementId && $('#' + scrollToElementId).length) {
                        bodyElem.animate({
                            scrollTop: $('#' + scrollToElementId).offset().top
                        }, 0);
                    }
                } else {
                    element.isVisible(false);
                }

            });
        };
        return target;
    };
});