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
    'Magento_Ui/js/modal/alert',
    'domReady!'
], function ($, ko, registry, AbstractElement, alert) {
    'use strict';

    return AbstractElement.extend({
        defaults: {
            template: 'Unexpected_Webp/form/element/progressbar',
            value: ko.observable(0),
            isDone: ko.observable(true)
        },

        /**
         * @inheritdoc
         */
        initialize: function () {
            this._super();
        },

        onClick: function () {
            const that = this;
            const extensions = registry.get('index = conversion_image_formats');
            const folders = registry.get('index = conversion_folders');
            let totalFiles = 0;
            let convertedFiles = 0;

            $.post(
                location.origin + '/admin/unexpected_webp/webp/files',
                {
                    form_key: window.FORM_KEY,
                    extensions: extensions.value(),
                    folders: folders.value()
                }
            ).done(function (data) {
                totalFiles = data.files;
                that.isDone(false);

                process();
            });

            function process() {
                $.post(
                    location.origin + '/admin/unexpected_webp/webp/convert',
                    {
                        form_key: window.FORM_KEY,
                        extensions: extensions.value(),
                        converted_files: convertedFiles,
                        folders: folders.value()
                    }
                ).done(function (data) {
                    convertedFiles = data.converted_files;
                    const percentage = Math.round((convertedFiles / totalFiles) * 100);
                    that.value(percentage + '%');

                    if (convertedFiles < totalFiles) {
                        setTimeout(process, 300);
                    } else {
                        that.isDone(true);
                        that.value('Complete');
                        alert({
                            title: 'Conversion summary',
                            content: `<span>Total: ${totalFiles}</span><br><br><span>Converted: ${convertedFiles}</span>`,
                            autoOpen: true,
                            clickableOverlay: false
                        });
                    }
                });
            }
        }
    });
});