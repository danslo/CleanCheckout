/**
 * Copyright © 2017 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
define([], function () {
    'use strict';

    return function (target) {
        return target.extend({
            /**
             * Override progress bar template so we can style the step icons.
             *
             * @returns {initialize}
             */
            defaults: {
                template: 'Rubic_CleanCheckout/progress-bar',
                visible: true
            }
        });
    }
});