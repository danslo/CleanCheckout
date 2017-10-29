/**
 * Copyright © 2017 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
define([], function () {
    'use strict';

    /**
     * We do two things here:
     * - Set our own email (login) template.
     * - Reduce the check delay down from the ridiculous 2 seconds.
     */
    return function (target) {
        target.template = 'Rubic_CleanCheckout/form/element/email';
        return target.extend({
            initialize: function() {
                this.checkDelay = 500;
                return this._super();
            }
        });
    }
});