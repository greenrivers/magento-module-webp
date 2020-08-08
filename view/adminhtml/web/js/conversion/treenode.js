/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_Webp
 */
define([
    'jquery',
    'uiRegistry',
    'Magento_Cms/js/folder-tree',
    'domReady!'
], function ($, registry) {
    'use strict';

    let treeInstance;
    const url = `${location.origin}/admin/unexpected_webp/conversion/treenode`;
    const index = location.pathname.includes('cron') ? 'cron_folders' : 'conversion_folders';

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

    $('#folder-tree')
        .on('loaded.jstree', (event, data) => {
            treeInstance = data.inst;
            treeInstance.open_node($('#root'));
        })
        .on('check_node.jstree uncheck_node.jstree', (event, data) => {
            const {inst} = data;
            const {id} = data.rslt.obj.data().node;
            const {type} = event;
            const escapeId = id.replaceAll('/', '\\/');
            const parentId = inst._get_parent($('#' + escapeId))
                .prop('id')
            const escapeParentId = parentId.replaceAll('/', '\\/');
            const folders = registry.get(`index = ${index}`);
            let nodes = folders.value();

            if (type === 'check_node') {
                let children = inst._get_children(`#${escapeParentId}`);
                const isAll = children.toArray().every(child => inst.is_checked(child));

                if (isAll) {
                    nodes = _.without(nodes, parentId);
                }
            } else {
                nodes = _.without(nodes, id, parentId);
            }

            const ids = inst.get_checked()
                .toArray()
                .map(value => value.id);

            ids.forEach(id => {
                id = id.replaceAll('/', '\\/')
                const children = inst._get_children(`#${id}`);
                const isAll = children.toArray().every(child => inst.is_checked(child));
                if (isAll && children.length) {
                    children.each((i, el) => {
                        nodes = _.without(nodes, $(el).prop('id'));
                    });
                }
            });

            nodes = _.union(nodes.concat(ids));
            registry.get(`index = ${index}`).value(nodes);

            console.log(registry.get(`index = ${index}`).value())
        });

    $(document).ajaxStop(() => {
        const folders = registry.get(`index = ${index}`);

        if (folders) {
            const nodes = folders.value();

            nodes.forEach(node => {
                node = node.replaceAll('/', '\\/');
                treeInstance.check_node(`#${node}`);

                const nodesSplit = node.split('\\/');

                if (nodesSplit.length > 1) {
                    nodesSplit.forEach((node, i) => {
                        const destNode = nodesSplit.slice(0, i + 1).join('\\/');

                        if (i < nodesSplit.length - 1) {
                            $(`#${destNode}`)
                                .removeClass('jstree-unchecked jstree-checked')
                                .addClass('jstree-undetermined');
                        }
                    })
                }
            });
        }
    });
});