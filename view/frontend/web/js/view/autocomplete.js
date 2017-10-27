/**
 * Copyright © 2017 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
if (window.checkoutConfig.autoComplete.enabled) {
    define(
        [
            'uiComponent',
            'jquery',
            'async!https://maps.googleapis.com/maps/api/js?key=' + window.checkoutConfig.autoComplete.apiKey + '&libraries=places&callback=initAutocomplete',
        ],
        function (Component, $) {
            'use strict';

            return Component.extend({
                autocomplete: null,

                fields: {
                    route: "input[name='street[0]']",
                    street_number: "input[name='street[1]']",
                    postal_code: "input[name='postcode']",
                    locality: "input[name='city']",
                    country: "select[name='country_id']"
                },

                initialize: function () {
                    $(document).on('keypress', "input[name='street[0]']", function (e) {
                        if (this.autocomplete === null) {
                            this.autocomplete = new google.maps.places.Autocomplete(e.target, {types: ['geocode']});
                            this.autocomplete.addListener('place_changed', this.fillAddressFields.bind(this));
                        }
                    }.bind(this));
                    return this._super();
                },

                findComponentValue: function (place, type) {
                    for (var i = 0; i < place.address_components.length; i++) {
                        var addressType = place.address_components[i].types[0];
                        if (addressType === type) {
                            return place.address_components[i].short_name;
                        }
                    }
                    return null;
                },

                fillAddressFields: function () {
                    var place = this.autocomplete.getPlace();

                    for (var key in this.fields) {
                        if (this.fields.hasOwnProperty(key)) {
                            var field = $(this.fields[key]);
                            var value = this.findComponentValue(place, key);

                            if (field.length) {
                                if (value !== null) {
                                    field.val(value).change();
                                }
                            } else if (key === 'street_number') {
                                // Couldn't find second address field, just append it to the address.
                                if (value !== null) {
                                    $(this.fields.route).val($(this.fields.route).val() + ' ' + value).change();
                                }
                            }
                        }
                    }
                }
            });
        }
    );
}