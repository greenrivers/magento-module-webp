/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_Webp
 */

define([
    'jquery',
    'ko',
    'uiComponent',
    'domReady!'
], function ($, ko, Component) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Greenrivers_Webp/system/config/slider_range',
            sliderId: null,
            sliderHandleId: null,
            inputId: null,
            value: ko.observable(0)
        },

        /**
         * @inheritdoc
         */
        initialize: function (config) {
            const {sliderId, sliderHandleId, inputId, value} = config;
            this._super();

            this.sliderId = sliderId;
            this.sliderHandleId = sliderHandleId;
            this.inputId = inputId;
            this.value(value);
        },

        /**
         * @inheritdoc
         */
        initObservable: function () {
            const {sliderId, sliderHandleId, inputId, value} = this;
            const handle = $(`#${sliderHandleId}`);
            const input = $(`#${inputId}`);

            this._super()
                .observe({
                    value: ko.observable(value)
                });

            $(`#${sliderId}`)
                .slider({
                    create: function () {
                        handle.text(value);
                        input.val(value);
                    },
                    slide: function (event, ui) {
                        const {value} = ui;

                        handle.text(value);
                        input.val(value);
                        this.value(value);
                    }.bind(this)
                })
                .slider('value', value);

            return this;
        },

        /**
         * @returns {Number}
         */
        getValue: function () {
            return this.value() | 0;
        }
    });
});
