/**
 * Copyright © 2018 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
define([
    'uiComponent',
    'jquery',
    'async'
], function (Component, $, async) {
    'use strict';

    return Component.extend({
        autocomplete: [],

        streetFieldSelector: "input[name='street[0]']",

        fields: {
            postal_code: { short_name: "input[name='postcode']" },
            locality:    { long_name:  "input[name='city']" },
            postal_town: { short_name: "input[name='city']" },
            country:     { short_name: "select[name='country_id']" },
            administrative_area_level_1: { long_name: "input[name='region']:visible" }
        },

        initialize: function () {
            if (window.checkoutConfig.autoComplete.enabled) {
                async.load(
                    'https://maps.googleapis.com/maps/api/js?key=' + window.checkoutConfig.autoComplete.apiKey + '&libraries=places&callback=initAutocomplete',
                    requirejs,
                    function () {
                        $(document).on('keypress', this.streetFieldSelector, function (e) {
                            if (typeof this.autocomplete[e.target.id] === 'undefined') {
                                var autocomplete = new google.maps.places.Autocomplete(e.target, {types: ['geocode']});
                                autocomplete.addListener('place_changed', this.fillAddressFields);
                                autocomplete.e = e.target;
                                autocomplete.c = this;
                                this.autocomplete[e.target.id] = autocomplete;
                            }
                        }.bind(this));
                    }.bind(this),
                    {isBuild: false}
                );
            }
            return this._super();
        },

        findComponentValue: function (place, type, subtype) {
            for (var i = 0; i < place.address_components.length; i++) {
                for (var j = 0; j < place.address_components[i].types.length; j++) {
                    var addressType = place.address_components[i].types[j];
                    if (addressType === type) {
                        return place.address_components[i][subtype];
                    }
                }
            }
            return null;
        },

        shouldSplitStreetFields: function () {
            return window.checkoutConfig.autoComplete.splitStreetFields;
        },

        fillStreetFields: function (element, place) {
            var streetNumberElement = $(element).closest('fieldset').find("input[name='street[1]']");
            if (streetNumberElement.length && this.shouldSplitStreetFields()) {
                var route  = this.findComponentValue(place, 'route', 'long_name');
                var number = this.findComponentValue(place, 'street_number', 'short_name');
                $(element).val(route).change();
                streetNumberElement.val(number).change();
            } else {
                $(element).val(place.name).change();
            }
        },

        fillOtherFields: function (element, place) {
            for (var type in this.fields) {
                if (this.fields.hasOwnProperty(type)) {
                    for (var subtype in this.fields[type]) {
                        if (this.fields[type].hasOwnProperty(subtype)) {
                            var selector = this.fields[type][subtype];
                            var form = $(element).closest('form');
                            var field = form.find(selector);
                            var value = this.findComponentValue(place, type, subtype);

                            if (value !== null) {
                                if (field.length) {
                                    field.val(value).change();
                                } else if (type === 'administrative_area_level_1') {
                                    // Couldn't find visible region input, dealing with a dropdown.
                                    var regionSelector = "select[name='region_id'] option";
                                    form.find(regionSelector).filter(function () {
                                        return $(this).text() === value;
                                    }).prop('selected', true).change();
                                }
                            }
                        }
                    }
                }
            }
        },

        fillAddressFields: function () {
            var place = this.getPlace();
            if (typeof place === 'undefined') {
                return;
            }
            this.c.fillStreetFields(this.e, place);
            this.c.fillOtherFields(this.e, place);
        }
    });
});