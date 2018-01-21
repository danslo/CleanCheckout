/**
 * Copyright © 2018 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
define([], function () {
    'use strict';

    /**
     * Override progress bar template so we can style the step icons.
     */
    return function (target) {
        return target.extend({
            defaults: {
                template: 'Rubic_CleanCheckout/progress-bar',
                visible: true
            }
        });
    }
});