/**
 * Copyright © 2017 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
define(
    [
        'ko',
        'uiComponent'
    ],
    function (
        ko,
        Component
    ) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Rubic_CleanCheckout/social-login'
            },

            isVisible: ko.observable(window.checkoutConfig.socialLogin.enabled),

            isProviderEnabled: function (provider) {
                return window.checkoutConfig.socialLogin[provider];
            },

            getProviderUrl: function (provider) {
                return window.checkoutConfig.socialLogin.url + '?provider=' + provider
            }
        });
    }
);