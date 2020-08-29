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
            let totalFiles = 0;
            let removedFiles = 0;

            $.post(
                location.origin + '/admin/unexpected_webp/webp/files',
                {
                    form_key: window.FORM_KEY,
                    extensions: '*.webp'
                }
            ).done(function (data) {
                totalFiles = data.files;
                that.isDone(false);

                process();
            });

            function process() {
                $.post(
                    location.origin + '/admin/unexpected_webp/webp/clear',
                    {
                        form_key: window.FORM_KEY
                    }
                ).done(function (data) {
                    removedFiles += data.removed_files;
                    const percentage = Math.round((removedFiles / totalFiles) * 100);
                    that.value(percentage + '%');

                    if (removedFiles < totalFiles) {
                        setTimeout(process, 300);
                    } else {
                        that.isDone(true);
                        that.value('Complete');
                        alert({
                            title: 'Clear summary',
                            content: `<span>Total: ${totalFiles}</span><br><br><span>Removed: ${removedFiles}</span>`,
                            autoOpen: true,
                            clickableOverlay: false
                        });
                    }
                });
            }
        }
    });
});