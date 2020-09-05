/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_Webp
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
            template: 'Unexpected_Webp/system/config/toggle_switch',
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
            }, this);

            return this;
        },

        /**
         * @returns {Number}
         */
        getValue: function () {
            return this.isChecked() | 0;
        }
    });
});
