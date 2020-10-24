<?php

use YOOtheme\Util\Collection;

class JDocumentRendererHtmlModules extends JDocumentRenderer
{
    public function render($position, $params = [], $content = null)
    {
        $renderer = $this->_doc->loadRenderer('module');

        $app = JFactory::getApplication();
        $user = JFactory::getUser();

        $frontEdit = $app->isSite() && $app->get('frontediting', 1) && !$user->guest;
        $menusEdit = $app->get('frontediting', 1) == 2 && $user->authorise('core.edit', 'com_menus');

        foreach ($modules = JModuleHelper::getModules($position) as $module) {

            $moduleHtml = $renderer->render($module, $params, $content);

            if (!isset($module->attrs)) {
                $module->attrs = [];
                $module->config = new Collection();
            }

            if ($frontEdit && trim($moduleHtml) != '' && $user->authorise('module.edit.frontend', 'com_modules.module.' . $module->id)) {
                $displayData = ['moduleHtml' => &$moduleHtml, 'module' => $module, 'position' => $position, 'menusediting' => $menusEdit];
                JLayoutHelper::render('joomla.edit.frontediting_modules', $displayData);
            }

            $module->content = $moduleHtml;
        }

        return JHtml::_('render', 'position', array_merge(['items' => $modules], $params));
    }
}

// Joomla < 3.5
class_alias('JDocumentRendererHtmlModules', 'JDocumentRendererModules');
