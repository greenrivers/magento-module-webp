/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_Webp
 */
define([
        'jquery',
        'Magento_Ui/js/modal/alert',
        'Magento_Ui/js/modal/confirm',
        'Magento_Cms/js/folder-tree'
    ], function ($, mAlert, mConfirm) {
        'use strict';

        const url = `${location.origin}/admin/unexpected_webp/convert/treenode`;

        $("#folder-tree").folderTree({
            rootName: 'media',
            url: url,
            currentPath: ['media'],
            tree: {
                'plugins': ['themes', 'json_data', 'ui', 'hotkeys', 'checkbox'],
                'themes': {
                    'theme': 'default',
                    'dots': false,
                    'icons': true
                }
            }
        });
    }
);