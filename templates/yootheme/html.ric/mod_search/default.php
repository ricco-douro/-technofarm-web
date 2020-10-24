<?php

defined('_JEXEC') or die;

echo JHtml::_('render', 'search', [

    'position' => $module->position,
    'attrs' => [

        'id' => "search-{$module->id}",
        'action' => JRoute::_('index.php'),
        'method' => 'post',
        'role' => 'search',
        'class' => ($class = $params->get('moduleclass_sfx')) ? [$class] : '',

    ],
    'fields' => [

        ['tag' => 'input', 'name' => 'searchword', 'placeholder' => JText::_('MOD_SEARCH')],
        ['tag' => 'input', 'type' => 'hidden', 'name' => 'task', 'value' => 'search'],
        ['tag' => 'input', 'type' => 'hidden', 'name' => 'option', 'value' => 'com_search'],
        ['tag' => 'input', 'type' => 'hidden', 'name' => 'Itemid', 'value' => $params->get('set_itemid', 0) ?: $app->input->getInt('Itemid')],

    ]

]);
