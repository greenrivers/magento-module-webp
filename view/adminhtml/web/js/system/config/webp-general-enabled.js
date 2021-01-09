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
            template: 'Greenrivers_Webp/system/config/toggle_switch',
            text: ko.observable('Yes'),
            isChecked: ko.observable(true)
        },

        /**
         * @inheritdoc
         */
        initialize: function (config) {
            this._super();
            this.isChecked(config.isChecked);
        },

        /**
         * @inheritdoc
         */
        initObservable: function () {
            this._super()
                .observe({
                    isChecked: ko.observable(true)
                });

            this.isChecked.subscribe(function (value) {
                this.text(value ? 'Yes' : 'No');
                this.toggleElements(value);
            }, this);

            return this;
        },

        toggleElements: function () {
            $('.section-config:not(:first)').toggle();
        },

        /**
         * @returns {Number}
         */
        getValue: function () {
            return this.isChecked() | 0;
        }
    });
});
