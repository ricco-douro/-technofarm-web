<?php

defined('_JEXEC') or die;

if (!$params->get('showLast', 1)) {
    array_pop($list);
}

echo JHtml::_('render', 'breadcrumbs', ['items' => $list]);

?>
