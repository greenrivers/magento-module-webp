/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_Webp
 */

define([
    'jquery',
    'domReady!'
], function ($) {
    'use strict';

    return config => {
        const {divProgressBar, divProgress} = config;
        const progressBar = $(divProgressBar);
        const progress = $(divProgress);

        $('#convert').on('click', () => {
            $.post(
                location.origin + '/admin/webp/webp/convert',
                {
                    form_key: window.FORM_KEY
                }
            ).done(function (data) {
                console.log(data);
                return true;
            });
            // const percentage = Math.round(progress.width() / progressBar.width() * 100);
            // progress.width(percentage + 1 + '%').html(percentage + 1 + '%');
        });
    }
});
