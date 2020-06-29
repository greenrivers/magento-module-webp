/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_Webp
 */

define([
    'jquery',
    'jquery-ui-modules/progressbar',
    'domReady!'
], function ($) {
    'use strict';

    return config => {
        const {div, label} = config;

        const progressbar = $(div);
        const progressLabel = $(label);

        progressbar.progressbar({
            value: false,
            change: function () {
                progressLabel.text(progressbar.progressbar("value") + "%");
            },
            complete: function () {
                progressLabel.text("Complete!");
            }
        });

        function progress() {
            const val = progressbar.progressbar("value") || 0;

            progressbar.progressbar("value", val + 2);

            if (val < 99) {
                setTimeout(progress, 80);
            }
        }

        $('#convert').on('click', () => {
            progress();
        });

    }
});
