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
        const handle = $("#slider-handle");
        $("#slider").slider({
            create: function () {
                const value = $(this).slider('value');
                handle.text(value);
                $('#webp_advanced_quality').val(value);
            },
            slide: function (event, ui) {
                const {value} = ui;
                handle.text(value);
                $('#webp_advanced_quality').val(value);
            }
        });
    }
});
