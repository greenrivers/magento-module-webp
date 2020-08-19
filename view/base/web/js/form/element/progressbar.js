/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_Webp
 */

define([
    'jquery',
    'ko',
    'uiRegistry',
    'Magento_Ui/js/form/element/abstract',
    'domReady!'
], function ($, ko, registry, AbstractElement) {
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
            const extensions = registry.get('index = conversion_image_formats');
            let totalFiles = 0;
            let convertedFiles = 0;

            $.post(
                location.origin + '/admin/unexpected_webp/webp/files',
                {
                    form_key: window.FORM_KEY,
                    extensions: extensions.value()
                }
            ).done(function (data) {
                totalFiles = data.files;

                recursively_ajax();
            });

            function recursively_ajax() {
                console.warn("begin");
                $.post(
                    location.origin + '/admin/unexpected_webp/webp/convert',
                    {
                        form_key: window.FORM_KEY,
                        extensions: extensions.value(),
                        converted_files: convertedFiles
                    }
                ).done(function (data) {
                    convertedFiles = data.converted_files;
                    console.log(convertedFiles);
                    if (convertedFiles < totalFiles) {
                        setTimeout(recursively_ajax, 300);
                    }
                });
            }

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