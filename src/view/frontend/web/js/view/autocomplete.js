/**
 * Copyright © 2017 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
define(
    [
        'uiComponent',
        'jquery',
        'async'
    ],
    function (Component, $, async) {
        'use strict';

        return Component.extend({
            autocomplete: [],

            streetFieldSelector: "input[name='street[0]']",

            fields: {
                route: { long_name: "input[name='street[0]']" },
                street_number: { short_name: "input[name='street[1]']"},
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

            fillAddressFields: function () {
                var place = this.getPlace();
                var streetNumber = false;
                var hasAddress = false;
                if (typeof place === 'undefined') {
                    return;
                }

                $(this.e).val(place.name).change();
                for (var type in this.c.fields) {
                    if (this.c.fields.hasOwnProperty(type)) {
                        for (var subtype in this.c.fields[type]) {
                            if (this.c.fields[type].hasOwnProperty(subtype)) {
                                var selector = this.c.fields[type][subtype];
                                var form = $(this.e).closest('form');
                                var field = form.find(selector);
                                var value = this.c.findComponentValue(place, type, subtype);

                                if (selector == this.c.fields.route.long_name && value !== null) {
                                    var address = value;
                                    hasAddress = true;
                                }

                                if (selector == this.c.fields.street_number.short_name) {
                                    if (field.length) {
                                        streetNumber = true;
                                    } else {
                                        address += ' ' + value;
                                    }

                                    if (value !== null) {
                                        hasAddress == true;
                                    }
                                }

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

                if (!streetNumber && hasAddress) {
                    form.find(this.c.fields.route.long_name).val(address).change();
                }

                if (!hasAddress) {
                    form.find(this.c.fields.route.long_name).val('').change();
                    if (streetNumber) {
                        form.find(this.c.fields.street_number.short_name).val('').change();
                        form.find(this.c.fields.postal_code.short_name).val('').change();
                    }
                }
            }
        });
    }
);
