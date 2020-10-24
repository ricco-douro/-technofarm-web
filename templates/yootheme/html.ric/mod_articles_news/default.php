<?php

defined('_JEXEC') or die;

foreach ($list as $item) {
	include JModuleHelper::getLayoutPath('mod_articles_news', '_item');
}
