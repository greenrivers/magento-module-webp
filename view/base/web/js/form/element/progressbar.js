/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_Webp
 */

define([
    'jquery',
    'ko',
    'Magento_Ui/js/form/element/abstract',
    'domReady!'
], function ($, ko, AbstractElement) {
    'use strict';

    return AbstractElement.extend({
        defaults: {
            template: 'Unexpected_Webp/form/element/progressbar',
            value: ko.observable(0)
        },

        /**
         * @inheritdoc
         */
        initialize: function () {
            this._super();
        },

        onClick: function () {
            // $.post(
            //     location.origin + '/admin/unexpected_webp/webp/convert',
            //     {
            //         form_key: window.FORM_KEY
            //     }
            // ).done(function (data) {
            //     console.log(data);
            //     return true;
            // });

            const percentage = this.value();

            if (percentage < 100) {
                this.value(percentage + 1);
            }
        },

        /**
         * @inheritdoc
         */
        initObservable: function () {
            this._super()
                .observe({
                    value: ko.observable(0)
                });

            this.value.subscribe(function (value) {
                if (value > 100) {
                    alert(1);
                }
            }, this);

            return this;
        }
    });
});