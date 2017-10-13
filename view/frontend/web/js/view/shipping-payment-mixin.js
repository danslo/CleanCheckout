define(
    [
        'ko'
    ], function (ko) {
        'use strict';

        var mixin = {
            initialize: function () {
                this.visible = ko.observable(false);
                this._super();
                return this;
            }
        };

        return function (target) {
            return target.extend(mixin);
        };
    }
);