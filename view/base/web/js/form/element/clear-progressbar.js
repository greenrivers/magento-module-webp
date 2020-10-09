/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_Webp
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
            template: 'GreenRivers_Webp/form/element/progressbar',
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
            let totalImages = 0;
            let removedImages = 0;

            $.post(
                location.origin + '/admin/greenrivers_webp/webp/images',
                {
                    form_key: window.FORM_KEY
                }
            ).done(function (data) {
                totalImages = data.images;
                that.isDone(false);

                process();
            });

            function process() {
                $.post(
                    location.origin + '/admin/greenrivers_webp/webp/clear',
                    {
                        form_key: window.FORM_KEY
                    }
                ).done(function (data) {
                    removedImages += data.removed_images;
                    const percentage = Math.round((removedImages / totalImages) * 100);
                    that.value(percentage + '%');

                    if (removedImages < totalImages) {
                        setTimeout(process, 300);
                    } else {
                        that.isDone(true);
                        that.value('Complete');
                        alert({
                            title: 'Clear summary',
                            content: `<span>Total: ${totalImages}</span><br><br><span>Removed: ${removedImages}</span>`,
                            autoOpen: true,
                            clickableOverlay: false
                        });
                    }
                });
            }
        }
    });
});