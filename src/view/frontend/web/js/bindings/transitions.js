/**
 * Copyright © 2018 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
define([
    'jquery',
    'ko'
], function ($, ko) {
    'use strict';

    /**
     * Hide an element immediately and fade in with a configurable value.
     */
    ko.bindingHandlers.fadeVisible.update = function (element, valueAccessor) {
        var value = valueAccessor();
        ko.unwrap(value) ? $(element).fadeIn(window.checkoutConfig.stepTransitionSpeed) : $(element).hide();
    };
});
