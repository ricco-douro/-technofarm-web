<?php

return [

    'name' => 'yootheme/builder-description-list',

    'builder' => 'description_list',

    'render' => function ($element) {

        // Deprecated
        if ($element['title_style'] == 'muted') {
            $element['title_style'] = '';
            $element['title_color'] = 'muted';
        }

        // Deprecated
        switch ($element['layout']) {
            case '':
                $element['width'] = 'auto';
                $element['layout'] = 'grid-2';
                break;
            case 'width-small':
                $element['width'] = 'small';
                $element['layout'] = 'grid-2';
                break;
            case 'width-medium':
                $element['width'] = 'medium';
                $element['layout'] = 'grid-2';
                break;
            case 'space-between':
                $element['width'] = 'expand';
                $element['layout'] = 'grid-2';
                break;

        }

        return $this['view']->render('@builder/description-list/template', compact('element'));
    },

    'config' => [

        'title' => 'Description List',
        'width' => 500,
        'element' => true,
        'mixins' => ['element', 'container'],
        'tabs' => [

            [

                'title' => 'Content',
                'fields' => [

                    'content' => [
                        'label' => 'Items',
                        'type' => 'content-items',
                        'item' => 'description_list_item',
                    ],

                    'show_title' => [
                        'label' => 'Display',
                        'type' => 'checkbox',
                        'text' => 'Show the title',
                    ],

                    'show_meta' => [
                        'type' => 'checkbox',
                        'text' => 'Show the meta text',
                    ],

                    'show_content' => [
                        'type' => 'checkbox',
                        'text' => 'Show the content',
                    ],

                    'show_link' => [
                        'description' => 'Show or hide content fields without the need to delete the content itself.',
                        'type' => 'checkbox',
                        'default' => true,
                        'text' => 'Show the link',
                    ],

                ],

            ],

            [

                'title' => 'Basic',
                'fields' => [

                    'list_style' => [
                        'label' => 'Style',
                        'type' => 'select',
                        'default' => '',
                        'options' => [
                            'Default' => '',
                            'Divider' => 'divider',
                            'Striped' => 'striped',
                        ],
                    ],

                    'list_size' => [
                        'type' => 'checkbox',
                        'description' => 'Select the list style and add larger padding between items.',
                        'text' => 'Larger padding',
                    ],

                    'layout' => [
                        'label' => 'Layout',
                        'description' => 'Define the layout of the title, meta and content.',
                        'type' => 'select',
                        'default' => '',
                        'options' => [
                            '2 Column Grid' => 'grid-2',
                            '2 Column Grid (Meta only)' => 'grid-2-m',
                            'Stacked' => 'stacked',
                        ],
                    ],

                    'width' => [
                        'label' => 'Width',
                        'type' => 'select',
                        'default' => '',
                        'options' => [
                            'Auto' => 'auto',
                            'Small' => 'small',
                            'Medium' => 'medium',
                            'Expand' => 'expand',
                        ],
                        'show' => 'layout != "stacked"',
                    ],

                    'leader' => [
                        'type' => 'checkbox',
                        'text' => 'Add a leader',
                        'show' => 'layout == "grid-2-m" && width == "expand"',
                    ],

                    'width_description' => [
                        'description' => 'Define the width of the title within the grid.',
                        'type' => 'description',
                        'show' => 'layout != "stacked"',
                    ],

                    'gutter' => [
                        'label' => 'Gutter',
                        'description' => 'Select the gutter width between the title and content.',
                        'type' => 'select',
                        'default' => '',
                        'options' => [
                            'Small' => 'small',
                            'Medium' => 'medium',
                            'Default' => '',
                            'Large' => 'large',
                        ],
                        'show' => 'layout == "grid-2" || (layout == "grid-2-m" && !(width == "expand" && leader))',
                    ],

                    'breakpoint' => [
                        'label' => 'Breakpoint',
                        'description' => 'Set the breakpoint from which the layout will stack.',
                        'type' => 'select',
                        'default' => '',
                        'options' => [
                            'Always' => '',
                            'Small (Phone)' => 's',
                            'Medium (Tablet)' => 'm',
                            'Large (Desktop)' => 'l',
                            'X-Large (Large Screens)' => 'xl',
                        ],
                        'show' => 'layout != "stacked"',
                    ],

                ],

            ],

            [

                'title' => 'Advanced',
                'fields' => [

                    'title_style' => [
                        'label' => 'Title Style',
                        'type' => 'select',
                        'default' => '',
                        'options' => [
                            'Default' => '',
                            'Strong' => 'strong',
                            'H1' => 'h1',
                            'H2' => 'h2',
                            'H3' => 'h3',
                            'H4' => 'h4',
                            'H5' => 'h5',
                            'H6' => 'h6',
                        ],
                        'show' => 'show_title',
                    ],

                    'title_colon' => [
                        'type' => 'checkbox',
                        'description' => 'Select the title style and add an optional colon at the end of the title.',
                        'text' => 'Add a colon',
                        'show' => 'show_title',
                    ],

                    'title_color' => [
                        'label' => 'Title Color',
                        'description' => 'Select the text color. If the background option is selected, styles that don\'t apply a background image use the primary color instead.',
                        'type' => 'select',
                        'default' => '',
                        'options' => [
                            'Default' => '',
                            'Muted' => 'muted',
                            'Primary' => 'primary',
                            'Success' => 'success',
                            'Warning' => 'warning',
                            'Danger' => 'danger',
                            'Background' => 'background',
                        ],
                        'show' => 'show_title',
                    ],

                    'meta_style' => [
                        'label' => 'Meta Style',
                        'description' => 'Select a predefined meta text style, including color, size and font-family.',
                        'type' => 'select',
                        'default' => '',
                        'options' => [
                            'Default' => '',
                            'Meta' => 'meta',
                            'Muted' => 'muted',
                            'Primary' => 'primary',
                            'H4' => 'h4',
                            'H5' => 'h5',
                            'H6' => 'h6',
                        ],
                        'show' => 'show_meta',
                    ],

                    'meta_align' => [
                        'label' => 'Meta Alignment',
                        'description' => 'Align the meta text above or below the title.',
                        'type' => 'select',
                        'options' => [
                            'Above Title' => 'top-title',
                            'Below Title' => 'bottom-title',
                            'Above Content' => 'top-content',
                            'Below Content' => 'bottom-content',
                        ],
                        'show' => 'show_meta && layout != "grid-2-m"',
                    ],

                    'content_style' => [
                        'label' => 'Content Style',
                        'description' => 'Select a predefined text style, including color, size and font-family.',
                        'type' => 'select',
                        'default' => '',
                        'options' => [
                            'Default' => '',
                            'Lead' => 'lead',
                            'Meta' => 'meta',
                        ],
                        'show' => 'show_content',
                    ],

                    'link_style' => [
                        'label' => 'Link Style',
                        'description' => 'This option doesn\'t apply unless a URL has been added to the item. Only the item\'s content will be linked.',
                        'type' => 'select',
                        'default' => '',
                        'options' => [
                            'Default' => '',
                            'Muted' => 'muted',
                            'Reset' => 'reset',
                        ],
                        'show' => 'show_link',
                    ],

                ],

            ],

            [

                'title' => 'General',
                'fields' => [

                    'text_align' => '{text_align_justify}',

                    'text_align_breakpoint' => '{text_align_breakpoint}',

                    'text_align_fallback' => '{text_align_justify_fallback}',

                    'maxwidth' => '{maxwidth}',

                    'maxwidth_align' => '{maxwidth_align}',

                    'margin' => '{margin}',

                    'margin_remove_top' => '{margin_remove_top}',

                    'margin_remove_bottom' => '{margin_remove_bottom}',

                    'animation' => '{animation}',

                    'visibility' => '{visibility}',

                    'id' => '{id}',

                    'class' => '{class}',

                    'name' => '{name}',

                    'css' => [
                        'label' => 'CSS',
                        'description' => 'Enter your own custom CSS. The following selectors will be prefixed automatically for this element: <code>.el-element</code>, <code>.el-item</code>, <code>.el-title</code>, <code>.el-content</code>',
                        'type' => 'editor',
                        'editor' => 'code',
                        'mode' => 'css',
                        'attrs' => [
                            'debounce' => 500
                        ],
                    ],

                ],

            ],

        ],

        'defaults' => [

            'show_title' => true,
            'show_meta' => true,
            'show_content' => true,
            'show_link' => true,

            'layout' => 'grid-2',
            'width' => 'auto',
            'gutter' => 'small',

            'meta_style' => 'meta',
            'meta_align' => 'bottom-content',

        ],

    ],

    'default' => [

        'children' => array_fill(0, 3, [
            'type' => 'description_list_item',
        ]),

    ],

    'include' => [

        'yootheme/builder-description-list-item' => [

            'builder' => 'description_list_item',

            'config' => [

                'title' => 'Item',
                'width' => 600,
                'mixins' => ['element', 'item'],
                'fields' => [

                    'title' => [
                        'label' => 'Title',
                    ],

                    'meta' => [
                        'label' => 'Meta',
                    ],

                    'content' => [
                        'label' => 'Content',
                        'type' => 'editor',
                    ],

                    'link' => '{link}',

                    'link_target' => '{link_target}',

                ],

            ],

            'default' => [

                'props' => [
                    'content' => 'Lorem ipsum dolor sit amet.',
                    'title' => 'Item',
                ],

            ],

        ],

    ],

];
