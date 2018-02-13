/**
 * Copyright © 2018 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
define([

], function () {
    'use strict';

    /**
     * Allow cart items to always be shown through configuration.
     */
    return function (target) {
        return target.extend({
            isItemsBlockExpanded: function () {
                return window.checkoutConfig.alwaysShowCartItems ? true : this._super();
            }
        });
    };
});