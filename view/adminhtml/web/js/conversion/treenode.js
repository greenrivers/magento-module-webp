/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_Webp
 */
define([
    'jquery',
    'uiRegistry',
    'Magento_Cms/js/folder-tree',
    'domReady!'
], function ($, registry) {
    'use strict';

    let treeInstance;
    const url = `${location.origin}/admin/greenrivers_webp/conversion/treenode`;
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
            },
            'checkbox': {
                'tie_selection' : false,
                'whole_node' : false
            }
        }
    });

    $('#folder-tree')
        .on('loaded.jstree', (event, data) => {
            treeInstance = data.instance ? data.instance : data.inst;
            treeInstance.open_node($('#root'));
        })
        .on('check_node.jstree uncheck_node.jstree', (event, data) => {
            const treeInstance = data.instance ? data.instance : data.inst;
            const {id} = data.rslt ? data.rslt.obj.data().node : data.node;
            const {type} = event;

            const folders = registry.get(`index = ${index}`);
            let nodes = folders.value();

            let escapeId = id.replaceAll('/', '\\/');
            const parent = data.instance ? treeInstance.get_parent($('#' + escapeId)) :
                treeInstance._get_parent($('#' + escapeId));
            let parentId = !['root', -1, '#'].includes(parent) ? parent.prop('id') : 'root';
            const escapeParentId = parentId.replaceAll('/', '\\/');

            if (type === 'check_node') {
                let children = data.instance ? treeInstance.get_children_dom(`#${escapeParentId}`) :
                    treeInstance._get_children(`#${escapeParentId}`);
                const isAll = children.toArray().every(child => treeInstance.is_checked(child));

                if (isAll) {
                    children.toArray().forEach(el => {
                        nodes = _.without(nodes, el.id);
                    });
                }

                nodes.forEach(node => {
                    const currentNode = node;
                    const nodesSplit = node.split('/');

                    if (nodesSplit.length > 1) {
                        nodesSplit.forEach((node, i) => {
                            if (i > 0) {
                                const destNode = nodesSplit.slice(0, i + 1).join('/');

                                if (nodesSplit[0] === id || destNode === id) {
                                    nodes = _.without(nodes, currentNode);
                                }
                            }
                        })
                    }
                });
            } else {
                nodes = _.without(nodes, parentId, id);
                while (parentId !== 'root') {
                    escapeId = parentId.replaceAll('/', '\\/');
                    parentId = data.instance ? treeInstance.get_parent($('#' + escapeId)).prop('id') :
                        treeInstance._get_parent($('#' + escapeId)).prop('id');
                    nodes = _.without(nodes, parentId);
                }
            }

            const ids = data.instance ? treeInstance.get_checked() :
                treeInstance.get_checked()
                    .toArray()
                    .map(value => value.id);

            nodes = ids.includes('root') ? ['root'] : _.union(nodes.concat(ids));
            registry.get(`index = ${index}`).value(nodes);
        });

    $(document).ajaxStop(() => {
        const folders = registry.get(`index = ${index}`);

        if (folders) {
            const nodes = folders.value();

            nodes.forEach(node => {
                if (node) {
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
                }
            });
        }
    });
});
