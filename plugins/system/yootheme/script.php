<?php

defined('_JEXEC') or die;

class plgSystemYOOthemeInstallerScript
{
    public function install($parent)
    {
        JFactory::getDBO()->setQuery("UPDATE `#__extensions` SET `enabled` = 1 WHERE `type` = 'plugin' AND `folder` = 'system' AND `element` = 'yootheme'")->execute();
    }

    public function uninstall($parent) {}

    public function update($parent) {}

    public function preflight($type, $parent) {}

    public function postflight($type, $parent) {}
}
