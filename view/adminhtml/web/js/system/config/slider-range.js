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
        // const {divProgressBar, divProgress} = config;
        // const progressBar = $(divProgressBar);
        // const progress = $(divProgress);

        $("#slider-range-max").slider({
            range: "max",
            min: 0,
            max: 100,
            value: 75,
            slide: function (event, ui) {
                $("#value").html(ui.value);
            }
        });
        $("#value").html($("#slider-range-max").slider("value"));
    }
});
