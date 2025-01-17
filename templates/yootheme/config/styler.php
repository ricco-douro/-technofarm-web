<?php

return [

    'section' => [

        'ignore' => [
            '@breakpoint-*-max',
            '@internal*',
        ],

        'groups' => [

            'global' => [

                'typography' => [
                    '*-font-*',
                    '*-line-height',
                ],

                'primary' => '@global-primary-*',

                'secondary' => '@global-secondary-*',

                'colors' => '*-color',

                'backgrounds' => '*-background',

                'borders' => '*-border*',

                'box shadows' => '*-box-shadow',

                'spacings' => [
                    '*-margin',
                    '*-gutter',
                ],

                'controls' => '*-control-*',

                'z index' => '*-z-index',

                'breakpoints' => '@breakpoint-*',

            ],

            'theme' => [

                'page boxed' => '@theme-page-boxed-*',

                'toolbar' => '@theme-toolbar-*',

                'headerbar top' => '@theme-headerbar-top-*',

                'headerbar bottom' => '@theme-headerbar-bottom-*',

                'headerbar stacked' => '@theme-headerbar-stacked-*',

                'sidebar' => '@theme-sidebar-*',

            ],

            'accordion' => [

                'item' => '@accordion-item-*',
                'title' => '@accordion-title-*',
                'content' => '@accordion-content-*',

            ],

            'alert' => [

                'primary' => '@alert-primary-*',
                'success' => '@alert-success-*',
                'warning' => '@alert-warning-*',
                'danger' => '@alert-danger-*',
                'close' => '@alert-close-*',

            ],

            'animation' => [

                'slide' => '@animation-slide-*',

            ],

            'article' => [

                'title' => '@article-title-*',
                'meta' => '@article-meta-*',
                'meta link' => '@article-meta-link-*',

            ],

            'background' => [

                'default' => '@background-default-*',
                'muted' => '@background-muted-*',
                'primary' => '@background-primary-*',
                'secondary' => '@background-secondary-*',

            ],

            'badge' => [

                'badge' => '@badge-*',

            ],

            'base' => [

                'body' => '@base-body-*',
                'link' => '@base-link-*',
                'code' => '@base-code-*',
                'em' => '@base-em-*',
                'ins' => '@base-ins-*',
                'mark' => '@base-mark-*',
                'quote' => '@base-quote-*',
                'small' => '@base-small-*',
                'margin' => '@base-margin-*',
                'heading' => '@base-heading-*',
                'h1' => '@base-h1-*',
                'h2' => '@base-h2-*',
                'h3' => '@base-h3-*',
                'h4' => '@base-h4-*',
                'h5' => '@base-h5-*',
                'h6' => '@base-h6-*',
                'list' => '@base-list-*',
                'hr' => '@base-hr-*',
                'blockquote' => '@base-blockquote-*',
                'blockquote footer' => '@base-blockquote-footer-*',
                'pre' => '@base-pre-*',
                'selection' => '@base-selection-*',

            ],

            'breadcrumb' => [

                'item' => '@breadcrumb-item-*',
                'divider' => '@breadcrumb-divider*',

            ],

            'button' => [

                'default' => '@button-default-*',
                'primary' => '@button-primary-*',
                'secondary' => '@button-secondary-*',
                'danger' => '@button-danger-*',
                'disabled' => '@button-disabled-*',
                'text' => [
                    '@button-text-mode',
                    '@button-text-(?!transform)*'
                ],
                'link' => '@button-link-*',
                'small' => '@button-small-*',
                'large' => '@button-large-*',

            ],

            'card' => [

                'body' => '@card-body-*',
                'header' => '@card-header-*',
                'footer' => '@card-footer-*',
                'title' => '@card-title-*',
                'badge' => '@card-badge-*',
                'hover' => '@card-hover*',
                'default' => '@card-default-*',
                'default title' => '@card-default-title-*',
                'default header' => '@card-default-header-*',
                'default footer' => '@card-default-footer-*',
                'primary' => '@card-primary-*',
                'primary title' => '@card-primary-title-*',
                'secondary' => '@card-secondary-*',
                'secondary title' => '@card-secondary-title-*',
                'small' => '@card-small-*',
                'large' => '@card-large-*',

            ],

            'column' => [

                'divider' => '@column-divider-*',

            ],

            'comment' => [

                'header' => '@comment-header-*',
                'title' => '@comment-title-*',
                'meta' => '@comment-meta-*',
                'list' => '@comment-list-*',
                'primary' => '@comment-primary-*',

            ],

            'container' => [

                'small' => '@container-small-*',
                'large' => '@container-large-*',

            ],

            'description list' => [

                'divider' => '@description-list-divider-*',

            ],

            'divider' => [

                'divider icon' => '@divider-icon-*',
                'divider small' => '@divider-small-*',

            ],

            'dotnav' => [

                'item' => '@dotnav-item-*',

            ],

            'dropdown' => [

                'nav' => '@dropdown-nav-*',
                'nav item' => '@dropdown-nav-item-*',
                'nav header' => '@dropdown-nav-header-*',
                'nav divider' => '@dropdown-nav-divider-*',
                'nav sublist' => '@dropdown-nav-sublist-*',

            ],

            'form' => [

                'danger' => '@form-danger-*',
                'success' => '@form-success-*',
                'blank' => '@form-blank-*',
                'select' => '@form-select-*',
                'small' => '@form-small-*',
                'large' => '@form-large-*',
                'width' => '@form-width-*',
                'radio' => '@form-radio-*',
                'legend' => '@form-legend-*',
                'label' => '@form-label-*',
                'stacked' => '@form-stacked-*',
                'horizontal' => '@form-horizontal-*',
                'icon' => '@form-icon-*',

            ],

            'grid' => [

                'small' => '@grid-small-*',
                'medium' => '@grid-medium-*',
                'large' => '@grid-large-*',
                'divider' => '@grid-divider-*',

            ],

            'heading' => [

                'heading primary' => '@heading-primary-*',
                'heading hero' => '@heading-hero-*',
                'heading divider' => '@heading-divider-*',
                'heading bullet' => '@heading-bullet-*',
                'heading line' => '@heading-line-*',

            ],

            'icon' => [

                'link' => '@icon-link-*',
                'button' => '@icon-button-*',
                'image' => '@icon-image-*',

            ],

            'iconnav' => [

                'item' => '@iconnav-item-*',

            ],

            'inverse' => [

                'global' => [
                    '@inverse-global-color-mode',
                    '@inverse-global-*'
                ],
                'accordion' => '@inverse-accordion-*',
                'article' => '@inverse-article-*',
                'badge' => '@inverse-badge-*',
                'base' => '@inverse-base-*',
                'breadcrumb' => '@inverse-breadcrumb-*',
                'button default' => '@inverse-button-default-*',
                'button primary' => '@inverse-button-primary-*',
                'button secondary' => '@inverse-button-secondary-*',
                'button danger' => '@inverse-button-danger-*',
                'button disabled' => '@inverse-button-disabled-*',
                'button text' => '@inverse-button-text-(?!transform)*',
                'button link' => '@inverse-button-link-*',
                'card' => '@inverse-card-*',
                'column' => '@inverse-column-*',
                'divider' => '@inverse-divider-*',
                'dotnav' => '@inverse-dotnav-*',
                'form' => '@inverse-form-*',
                'form danger' => '@inverse-form-danger-*',
                'form success' => '@inverse-form-success-*',
                'form blank' => '@inverse-form-blank-*',
                'form select' => '@inverse-form-select-*',
                'form radio' => '@inverse-form-radio-*',
                'form legend' => '@inverse-form-legend-*',
                'form label' => '@inverse-form-label-*',
                'form icon' => '@inverse-form-icon-*',
                'grid divider' => '@inverse-grid-divider-*',
                'heading' => '@inverse-heading-*',
                'icon link' => '@inverse-icon-link-*',
                'icon button' => '@inverse-icon-button-*',
                'iconnav' => '@inverse-iconnav-*',
                'label' => '@inverse-label-*',
                'link' => '@inverse-link-*',
                'list' => '@inverse-list-*',
                'nav' => '@inverse-nav-*',
                'nav default' => '@inverse-nav-default-*',
                'nav primary' => '@inverse-nav-primary-*',
                'navbar' => '@inverse-navbar-*',
                'navbar nav item' => '@inverse-navbar-nav-item-*',
                'navbar nav item line' => '@inverse-navbar-nav-item-line-*',
                'pagination' => '@inverse-pagination-*',
                'search' => '@inverse-search-*',
                'search default' => '@inverse-search-default-*',
                'search navbar' => '@inverse-search-navbar-*',
                'search large' => '@inverse-search-large-*',
                'search toggle' => '@inverse-search-toggle-*',
                'slidenav' => '@inverse-slidenav-*',
                'subnav' => '@inverse-subnav-*',
                'subnav divider' => '@inverse-subnav-divider-*',
                'subnav pill' => '@inverse-subnav-pill-*',
                'tab' => '@inverse-tab-*',
                'text' => '@inverse-text-*',
                'totop' => '@inverse-totop-*',
                'utility' => [
                    '@inverse-dropcap-*',
                    '@inverse-leader-*',
                    '@inverse-logo-*',
                ],
            ],

            'label' => [

                'success' => '@label-success-*',
                'warning' => '@label-warning-*',
                'danger' => '@label-danger-*',

            ],

            'link' => [

                'link muted' => '@link-muted-*',

            ],

            'list' => [

                'divider' => '@list-divider-*',
                'striped' => '@list-striped-*',
                'bullet' => '@list-bullet-*',
                'large' => '@list-large-*',
                'large divider' => '@list-large-divider-*',
                'large striped' => '@list-large-striped-*',

            ],

            'margin' => [

                'small' => '@margin-small-*',
                'medium' => '@margin-medium-*',
                'large' => '@margin-large-*',
                'xlarge' => '@margin-xlarge-*',

            ],

            'modal' => [

                'dialog' => '@modal-dialog-*',
                'container' => '@modal-container-*',
                'body' => '@modal-body-*',
                'header' => '@modal-header-*',
                'footer' => '@modal-footer-*',
                'title' => '@modal-title-*',
                'close' => '@modal-close-*',
                'close outside' => '@modal-close-outside-*',
                'caption' => '@modal-caption-*',

            ],

            'nav' => [

                'item' => '@nav-item-*',
                'sublist' => '@nav-sublist-*',
                'parent icon' => '@nav-parent-icon-*',
                'header' => '@nav-header-*',
                'divider' => '@nav-divider-*',
                'default' => '@nav-default-*',
                'default item' => '@nav-default-item-*',
                'default header' => '@nav-default-header-*',
                'default divider' => '@nav-default-divider-*',
                'default sublist' => '@nav-default-sublist-*',
                'primary' => '@nav-primary-*',
                'primary item' => '@nav-primary-item-*',
                'primary header' => '@nav-primary-header-*',
                'primary divider' => '@nav-primary-divider-*',
                'primary sublist' => '@nav-primary-sublist-*',

            ],

            'navbar' => [

                'nav' => '@navbar-nav-*',
                'nav item' => '@navbar-nav-item-*',
                'nav item line' => [
                    '@navbar-nav-item-line-mode',
                    '@navbar-nav-item-line-position-mode',
                    '@navbar-nav-item-line-slide-mode',
                    '@navbar-nav-item-line-*'
                ],
                'item' => '@navbar-item-*',
                'toggle' => '@navbar-toggle-*',
                'subtitle' => '@navbar-subtitle-*',
                'sticky' => '@navbar-sticky-*',
                'dropdown' => '@navbar-dropdown-*',
                'dropdown grid' => '@navbar-dropdown-grid-*',
                'dropdown nav' => '@navbar-dropdown-nav-*',
                'dropdown nav item' => '@navbar-dropdown-nav-item-*',
                'dropdown nav header' => '@navbar-dropdown-nav-header-*',
                'dropdown nav divider' => '@navbar-dropdown-nav-divider-*',
                'dropdown nav sublist' => '@navbar-dropdown-nav-sublist-*',
                'dropbar' => '@navbar-dropbar-*',

            ],

            'notification' => [

                'message' => '@notification-message-*',
                'close' => '@notification-close-*',
                'message primary' => '@notification-message-primary-*',
                'message success' => '@notification-message-success-*',
                'message warning' => '@notification-message-warning-*',
                'message danger' => '@notification-message-danger-*',

            ],

            'offcanvas' => [

                'bar' => '@offcanvas-bar-*',
                'close' => '@offcanvas-close-*',
                'overlay' => '@offcanvas-overlay-*',

            ],

            'overlay' => [

                'default' => '@overlay-default-*',
                'primary' => '@overlay-primary-*',

            ],

            'padding' => [

                'small' => '@padding-small-*',
                'large' => '@padding-large-*',

            ],

            'pagination' => [

                'item' => '@pagination-item-*',

            ],

            'progress' => [

                'bar' => '@progress-bar-*',

            ],

            'search' => [

                'default' => '@search-default-*',
                'navbar' => '@search-navbar-*',
                'large' => '@search-large-*',
                'toggle' => '@search-toggle-*',

            ],

            'section' => [

                'default' => '@section-default-*',
                'muted' => '@section-muted-*',
                'primary' => '@section-primary-*',
                'secondary' => '@section-secondary-*',
                'xsmall' => '@section-xsmall-*',
                'small' => '@section-small-*',
                'large' => '@section-large-*',
                'xlarge' => '@section-xlarge-*',

            ],

            'subnav' => [

                'item' => '@subnav-item-*',
                'divider' => '@subnav-divider-*',
                'pill' => '@subnav-pill-*',
                'pill item' => '@subnav-pill-item-*',

            ],

            'tab' => [

                'item' => '@tab-item-*',
                'vertical' => '@tab-vertical-*',
                'vertical item' => '@tab-vertical-item-*',

            ],

            'table' => [

                'cell' => '@table-cell-*',
                'header cell' => '@table-header-cell-*',
                'footer' => '@table-footer-*',
                'caption' => '@table-caption-*',
                'row' => '@table-row-*',
                'striped' => '@table-striped-*',
                'hover' => '@table-hover-*',
                'small' => '@table-small-*',
                'expand' => '@table-expand-*',

            ],

            'text' => [

                'lead' => '@text-lead-*',
                'meta' => '@text-meta-*',
                'small' => '@text-small-*',
                'large' => '@text-large-*',

            ],

            'tile' => [

                'default' => '@tile-default-*',
                'muted' => '@tile-muted-*',
                'primary' => '@tile-primary-*',
                'secondary' => '@tile-secondary-*',

            ],

            'transition' => [

                'slide' => '@transition-slide-*',

            ],

            'utility' => [

                'panel scrollable' => '@panel-scrollable-*',
                'height' => '@height-*',
                'border rounded' => '@border-rounded-*',
                'box shadow' => '@box-shadow-*',
                'dropcap' => '@dropcap-*',
                'leader' => '@leader-*',
                'logo' => '@logo-*',
                'dragover' => '@dragover-*',

            ],

        ],

        'types' => [

            [
                'type' => 'color',
                'vars' => [
                    '*-color',
                    '*-border',
                    '*-border-top',
                    '*-border-bottom',
                    '*-border-left',
                    '*-border-right',
                    '*-text-shadow',
                    '*-background',
                    '*-gradient-top',
                    '*-gradient-bottom',
                ],
                'allowEmpty' => false
            ],

            [
                'type' => 'boxshadow',
                'vars' => [
                    '*-box-shadow',
                ],
                'allowEmpty' => false
            ],

            [
                'type' => 'select-custom',
                'vars' => [
                    '*-border-style',
                ],
                'options' => [
                    'Solid' => 'solid',
                    'Dashed' => 'dashed',
                    'Dotted' => 'dotted',
                    'None' => 'none',
                ],
            ],

            [
                'type' => 'font',
                'vars' => [
                    '*-font-family',
                ],
                'attrs' => [
                    'class' => 'yo-style-field',
                ],
            ],

            [
                'type' => 'select-custom',
                'vars' => [
                    '*-font-weight',
                ],
                'options' => [
                    'Normal' => 'normal',
                    'Bold' => 'bold',
                    'Lighter' => 'lighter',
                    'Bolder' => 'bolder',
                    '100 (Thin)' => '100',
                    '200 (Extra Light)' => '200',
                    '300 (Light)' => '300',
                    '400 (Normal)' => 'normal',
                    '500 (Medium)' => '500',
                    '600 (Semi Bold)' => '600',
                    '700 (Bold)' => 'bold',
                    '800 (Extra Bold)' => '800',
                    '900 (Black)' => '900',
                    'Inherit' => 'inherit',
                ],
            ],

            [
                'type' => 'select-custom',
                'vars' => [
                    '*-font-style',
                ],
                'options' => [
                    'Normal' => 'normal',
                    'Italic' => 'italic',
                    'Inherit' => 'inherit',
                ],
            ],

            [
                'type' => 'select-custom',
                'vars' => [
                    '*-text-align',
                ],
                'options' => [
                    'Left' => 'left',
                    'Right' => 'right',
                    'Center' => 'center',
                    'Justify' => 'justify',
                    'Inherit' => 'inherit',
                ],
            ],

            [
                'type' => 'select-custom',
                'vars' => [
                    '*-text-transform',
                ],
                'options' => [
                    'None' => 'none',
                    'Lowercase' => 'lowercase',
                    'Uppercase' => 'uppercase',
                    'Capitalize' => 'capitalize',
                    'Inherit' => 'inherit',
                ],
            ],

            [
                'type' => 'select-custom',
                'vars' => [
                    '*-text-decoration',
                ],
                'options' => [
                    'None' => 'none',
                    'Underline' => 'underline',
                ],
            ],

            [
                'type' => 'select-custom',
                'vars' => [
                    '*-color-mode',
                ],
                'options' => [
                    'Light' => 'light',
                    'Dark' => 'dark',
                    'None' => 'none',
                ],
            ],

            [
                'type' => 'select-custom',
                'vars' => [
                    '*-border-mode',
                ],
                'options' => [
                    'Full' => '',
                    'Top' => '-top',
                    'Bottom' => '-bottom',
                    'Left' => '-left',
                    'Right' => '-right',
                ],
            ],

            [
                'type' => 'select-custom',
                'vars' => [
                    '*-position-mode',
                ],
                'options' => [
                    'Top' => 'top',
                    'Bottom' => 'bottom',
                ],
            ],

            [
                'type' => 'select-custom',
                'vars' => [
                    '*-nav-item-line-mode',
                    '*-nav-item-line-slide-mode',
                    '*-nav-item-line-active-mode',
                ],
                'options' => [
                    'Enable' => 'true',
                    'Disable' => 'false',
                ],
            ],

            [
                'type' => 'select-custom',
                'vars' => [
                    '*button-text-mode',
                ],
                'options' => [
                    'None' => 'none',
                    'Line' => 'line',
                    'Arrow' => 'arrow',
                    'Em Dash' => 'em-dash',
                ],
            ],

            [
                'type' => 'checkbox',
                'vars' => [
                    '*-em-dash',
                ],
                'attrs' => [
                    'true-value' => 'true',
                ],
            ],

        ],

        'hover' => [
            'theme' => '.tm-page, .tm-toolbar, .tm-sidebar, .tm-headerbar-top, .tm-headerbar-bottom, .tm-headerbar-stacked',
            'accordion' => '.uk-accordion',
            'alert' => '.uk-alert',
            'align' => '[class*=\'uk-align\']',
            'animation' => '[class*=\'uk-animation-\']',
            'article' => '.uk-article',
            'background' => '.uk-background-default, .uk-background-muted, .uk-background-primary, .uk-background-secondary',
            'badge' => '.uk-badge',
            'base' => 'a, code, kbd, samp, em, ins, mark, q, small, p, ul, ol, dl, pre, address, fieldset, figure, h1, h2, h3, h4, h5, h6, hr, blockquote, .uk-h1, .uk-h2, .uk-h3, .uk-h4, .uk-h5, .uk-h6, .uk-link',
            'breadcrumb' => '.uk-breadcrumb',
            'button' => '.uk-button',
            'card' => '.uk-card',
            'close' => '.uk-close',
            'column' => '[class*=\'uk-column-\']',
            'comment' => '.uk-comment',
            'container' => '.uk-container',
            'description-list' => '.uk-description-list',
            'divider' => '.uk-divider-icon, .uk-divider-small',
            'dotnav' => '.uk-dotnav',
            'drop' => '.uk-drop',
            'dropdown' => '.uk-dropdown',
            'form' => '.uk-input, .uk-select, .uk-textarea, .uk-radio, .uk-checkbox, .uk-legend, .uk-fieldset, [class*=\'uk-form\']',
            'grid' => '.uk-grid',
            'heading' => '.uk-heading-primary, .uk-heading-hero, .uk-heading-divider, .uk-heading-bullet, .uk-heading-line',
            'icon' => '.uk-icon',
            'iconnav' => '.uk-iconnav',
            'label' => '.uk-label',
            'link' => '.uk-link-muted, .uk-link-reset',
            'list' => '.uk-list',
            'margin' => '[class*=\'uk-margin\']',
            'modal' => '.uk-modal',
            'nav' => '.uk-nav',
            'navbar' => '.uk-navbar-container',
            'notification' => '.uk-notification',
            'offcanvas' => '.uk-offcanvas',
            'overlay' => '.uk-overlay',
            'padding' => '[class*=\'uk-padding\']',
            'pagination' => '.uk-pagination',
            'placeholder' => '.uk-placeholder',
            'position' => '[class*=\'uk-position\']',
            'progress' => '.uk-progress',
            'search' => '.uk-search',
            'section' => '.uk-section',
            'slidenav' => '.uk-slidenav',
            'sortable' => '.uk-sortable',
            'spinner' => '.uk-spinner',
            'sticky' => '[uk-sticky]',
            'subnav' => '.uk-subnav',
            'tab' => '.uk-tab',
            'table' => '.uk-table',
            'text' => '[class*=\'uk-text-\']',
            'tile' => '.uk-tile',
            'tooltip' => '.uk-tooltip',
            'totop' => '.uk-totop',
            'transition' => '[class*=\'uk-transition-\']',
            'utility' => '.uk-panel-scrollable, .uk-height-small, .uk-height-medium, .uk-height-large, .uk-dropcap, .uk-leader, .uk-logo',
            'width' => '.uk-width-small, .uk-width-medium, .uk-width-large, .uk-width-xlarge, .uk-width-xxlarge',
        ],

        'inspect' => [
            'theme' => '.tm-page, tm-page > *, .tm-toolbar, .tm-toolbar > *, .tm-sidebar, .tm-sidebar > *, .tm-headerbar-top, .tm-headerbar-top > *, .tm-headerbar-bottom, .tm-headerbar-bottom > *, .tm-headerbar-stacked, .tm-headerbar-stacked > *',
            'accordion' => '.uk-accordion, .uk-accordion-title, .uk-accordion-title > *, .uk-accordion-content, .uk-accordion-content > *',
            'alert' => '.uk-alert, .uk-alert > *, .uk-alert-close',
            'align' => '[class*=\'uk-align\'], [class*=\'uk-align\'] > *',
            'animation' => '[class*=\'uk-animation-\'], [class*=\'uk-animation-\'] > *',
            'article' => '.uk-article, .uk-article > *, .uk-article-title, .uk-article-title > *, .uk-article-meta, .uk-article-meta > *',
            'background' => '.uk-background-default, .uk-background-muted, .uk-background-primary, .uk-background-secondary',
            'badge' => '.uk-badge, .uk-badge > *',
            'base' => ':not(.uk-nav):not(.uk-nav-sub):not(.uk-navbar-nav):not(.uk-subnav):not(.uk-breadcrumb):not(.uk-pagination):not(.uk-tab) > * > a:not([class]), > a:not([class]), > * > a:not([class]), :not(pre) > code, :not(pre) > kbd, :not(pre) > samp, em, ins, mark, q, small, h1, h2, h3, h4, h5, h6, h1 > *, h2 > *, h3 > *, h4 > *, h5 > *, h6 > *, hr:not(.uk-divider-icon), blockquote, blockquote > *, pre, pre > *, .uk-h1, .uk-h2, .uk-h3, .uk-h4, .uk-h5, .uk-h6, .uk-h1 > *, .uk-h2 > *, .uk-h3 > *, .uk-h4 > *, .uk-h5 > *, .uk-h6 > *, .uk-link',
            'breadcrumb' => '.uk-breadcrumb, .uk-breadcrumb > *, .uk-breadcrumb > * > *',
            'button' => '.uk-button, .uk-button-group',
            'card' => '.uk-card, .uk-card-body, .uk-card-body > *, .uk-card-header, .uk-card-header > *, .uk-card-footer, .uk-card-footer > *, [class*=\'uk-card-media\'], .uk-card-title, .uk-card-title > *, .uk-card-badge',
            'close' => '.uk-close, .uk-close svg',
            'column' => '[class*=\'uk-column-\'], [class*=\'uk-column-\'] > *',
            'comment' => '.uk-comment, .uk-comment-body, .uk-comment-body > *, .uk-comment-header, .uk-comment-header > *, .uk-comment-title, .uk-comment-title > *, .uk-comment-meta, .uk-comment-meta > *, .uk-comment-avatar, .uk-comment-avatar > *, .uk-comment-list, .uk-comment-list ul, .uk-comment-list li, .uk-comment-primary, uk-comment-primary > *',
            'container' => '.uk-container, .uk-container > *',
            'description-list' => '.uk-description-list, .uk-description-list > dt, .uk-description-list > dd',
            'divider' => '.uk-divider-icon, .uk-divider-small',
            'dotnav' => '.uk-dotnav, .uk-dotnav > *, .uk-dotnav > * > *',
            'drop' => '.uk-drop, .uk-drop > *',
            'dropdown' => '.uk-dropdown, .uk-dropdown > *, .uk-dropdown-nav, .uk-dropdown-nav > *, .uk-dropdown-nav > * > *, .uk-dropdown-grid, .uk-dropdown-grid > *',
            'form' => '.uk-input, .uk-select, .uk-textarea, .uk-radio, .uk-checkbox, .uk-legend, .uk-fieldset, [class*=\'uk-form\'], [class*=\'uk-form\'] > *',
            'grid' => '.uk-grid, .uk-grid > *, .uk-grid > * > *',
            'heading' => '.uk-heading-primary, .uk-heading-primary > *, .uk-heading-hero, .uk-heading-hero > *, .uk-heading-divider, .uk-heading-divider > *, .uk-heading-bullet, .uk-heading-bullet > *, .uk-heading-line, .uk-heading-line > *',
            'icon' => '.uk-icon, .uk-icon svg',
            'iconnav' => '.uk-iconnav, .uk-iconnav > *, .uk-iconnav > * > *, .uk-iconnav > * > * > *',
            'label' => '.uk-label, .uk-label > *',
            'link' => '.uk-link-muted, .uk-link-reset',
            'list' => '.uk-list, .uk-list > *, .uk-list > * > *',
            'margin' => '[class*=\'uk-margin\'], [class*=\'uk-margin\'] > *',
            'modal' => '.uk-modal, .uk-modal-dialog, .uk-modal-container, .uk-modal-body, .uk-modal-body > *, .uk-modal-header, .uk-modal-header > *, .uk-modal-footer, .uk-modal-footer > *, .uk-modal-title, .uk-modal-title > *, [class*=\'uk-modal-close-\']',
            'nav' => '.uk-nav, .uk-nav > *, .uk-nav > * > *, .uk-nav-sub, .uk-nav-sub > *, .uk-nav-sub > * > *',
            'navbar' => '.uk-navbar-container, .uk-navbar, .uk-navbar-left, .uk-navbar-right, .uk-navbar-center, .uk-navbar-nav, .uk-navbar-nav > *, .uk-navbar-nav > * > *, .uk-navbar-item, .uk-navbar-item > *, .uk-navbar-toggle, .uk-navbar-toggle > *, .uk-navbar-subtitle, .uk-navbar-dropbar, .uk-navbar-dropdown, .uk-navbar-dropdown-nav, .uk-navbar-dropdown-nav > *, .uk-navbar-dropdown-nav > * > *, .uk-navbar-dropdown-grid, .uk-navbar-dropdown-grid > *, .uk-navbar-toggle-icon',
            'notification' => '.uk-notification, .uk-notification-message, .uk-notification-message > *, .uk-notification-close',
            'offcanvas' => '.uk-offcanvas, .uk-offcanvas-overlay, .uk-offcanvas-bar, .uk-offcanvas-bar > *',
            'overlay' => '.uk-overlay, .uk-overlay > *, .uk-overlay-icon, .uk-overlay-icon svg',
            'padding' => '[class*=\'uk-padding\'], [class*=\'uk-padding\'] > *',
            'pagination' => '.uk-pagination, .uk-pagination > *, .uk-pagination > * > *, .uk-pagination-next, .uk-pagination-next svg, .uk-pagination-previous, .uk-pagination-previous svg',
            'placeholder' => '.uk-placeholder, .uk-placeholder > *',
            'position' => '[class*=\'uk-position\'], [class*=\'uk-position\'] > *',
            'progress' => '.uk-progress',
            'search' => '.uk-search, .uk-search-input, .uk-search-toggle, .uk-search-icon, .uk-search-icon svg',
            'section' => '.uk-section, .uk-section > *',
            'slidenav' => '.uk-slidenav, .uk-slidenav svg',
            'sortable' => '.uk-sortable, .uk-sortable > *, .uk-sortable > * > *, .uk-sortable-drag, .uk-sortable-drag > *, .uk-sortable-placeholder, .uk-sortable-placeholder > *, .uk-sortable-empty, .uk-sortable-empty > *',
            'spinner' => '.uk-spinner, .uk-spinner svg',
            'sticky' => '[uk-sticky], [uk-sticky] > *',
            'subnav' => '.uk-subnav, .uk-subnav > *, .uk-subnav > * > *',
            'tab' => '.uk-tab, .uk-tab > *, .uk-tab > * > *',
            'table' => '.uk-table, .uk-table th, .uk-table th > *, .uk-table td, .uk-table td > *,  .uk-table tr, .uk-table tbody, .uk-table thead, .uk-table tfoot, .uk-table caption',
            'text' => '[class*=\'uk-text-\'], [class*=\'uk-text-\'] > *',
            'tile' => '.uk-tile, .uk-tile > *',
            'tooltip' => '.uk-tooltip',
            'totop' => '.uk-totop, .uk-totop svg',
            'transition' => '[class*=\'uk-transition-\'], [class*=\'uk-transition-\'] > *',
            'utility' => '.uk-panel-scrollable, .uk-panel-scrollable > *, .uk-height-small, .uk-height-small > *, .uk-height-medium, .uk-height-medium > *, .uk-height-large, .uk-height-large > *, .uk-dropcap, .uk-leader, .uk-logo, .uk-logo > *',
            'width' => '.uk-width-small, .uk-width-small > *, .uk-width-medium, .uk-width-medium > *, .uk-width-large, .uk-width-large > *, .uk-width-xlarge, .uk-width-xlarge > *, .uk-width-xxlarge, .uk-width-xxlarge > *',
        ],

    ],

    'defaults' => [

        'style' => 'minimal',

    ],

];
