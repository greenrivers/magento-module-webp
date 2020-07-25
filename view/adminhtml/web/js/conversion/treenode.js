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

        let treeInstance;
        const url = `${location.origin}/admin/unexpected_webp/convert/treenode`;

        $("#folder-tree")
            .folderTree({
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

        $('#folder-tree').on('loaded.jstree', (event, data) => {
            treeInstance = data.inst;
            treeInstance.open_node($('#root'));
        });

        function addNew(url, isRoot) {
            alert(1);
        }

        // $("#folder-tree").on("check_node.jstree uncheck_node.jstree", function (e, data) {
        //     let {node} = data.rslt.obj.data();
        //     console.log(node, e.type);
        // });

        window.addNew = addNew;

    }
);