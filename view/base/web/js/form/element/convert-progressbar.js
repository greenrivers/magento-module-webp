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
            let totalImages = 0;
            let imagesToConversion = 0;
            let convertedImages = 0;

            $.post(
                location.origin + '/admin/unexpected_webp/webp/images',
                {
                    form_key: window.FORM_KEY,
                    extensions: extensions.value(),
                    folders: folders.value()
                }
            ).done(function (data) {
                totalImages = data.images;
                imagesToConversion = data.images_to_conversion;
                that.isDone(false);

                process();
            });

            function process() {
                $.post(
                    location.origin + '/admin/unexpected_webp/webp/convert',
                    {
                        form_key: window.FORM_KEY,
                        extensions: extensions.value(),
                        converted_images: convertedImages,
                        folders: folders.value()
                    }
                ).done(function (data) {
                    convertedImages = data.converted_images;
                    const percentage = Math.round((convertedImages / imagesToConversion) * 100);
                    that.value(percentage + '%');

                    if (convertedImages < imagesToConversion) {
                        setTimeout(process, 300);
                    } else {
                        that.isDone(true);
                        that.value('Complete');
                        alert({
                            title: 'Conversion summary',
                            content: `
                                <span>Total: ${totalImages}</span><br><br>
                                <span>To conversion: ${imagesToConversion}</span><br><br>
                                <span>Converted: ${convertedImages}</span>
                            `,
                            autoOpen: true,
                            clickableOverlay: false
                        });
                    }
                });
            }
        }
    });
});