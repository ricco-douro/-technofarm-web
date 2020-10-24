<?php

namespace YOOtheme\Theme;

use YOOtheme\EventSubscriberInterface;
use YOOtheme\Module;
use YOOtheme\Util\Collection;

class Modules extends Module implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke($app)
    {
        $this['@types'] = function () {

            $lang = \JFactory::getLanguage();
            $types = $this['db']->fetchAll("SELECT name, element FROM @extensions WHERE client_id = 0 AND type = 'module'");

            foreach ($types as $type) {
                $lang->load("{$type['element']}.sys", JPATH_SITE, null, false, true);
                $data[$type['element']] = \JText::_($type['name']);
            }

            natsort($data);

            return $data;
        };

        $this['@modules'] = function () {
            return $this['db']->fetchAll("SELECT id, title, module, position, ordering FROM @modules WHERE client_id = 0 AND published != -2 ORDER BY position, ordering");
        };
    }

    public function onSite($theme)
    {
        require "{$this->path}/src/ModulesRenderer.php";

        $this['view']->addFunction('countModules', [$this, 'countModules']);
    }

    public function onAdmin($theme)
    {
        $this['@config']->merge(['section' => [
            'types' => $this['@types'],
            'modules' => $this['@modules'],
            'positions' => array_keys($theme->options['positions']),
            'url' => 'administrator/index.php?option='. (\JPluginHelper::isEnabled('system', 'advancedmodules') ? 'com_advancedmodules' : 'com_modules'),
        ]], true);
        $this['scripts']->add('customizer-modules', "{$this->path}/app/modules.min.js", 'customizer');
    }

    public function onModules(&$modules)
    {
        if ($this['admin']) {
            return;
        }

        $this['view']['sections']->add('breadcrumbs', function () {
            return \JModuleHelper::renderModule($this->createModule([
                'name' => 'yoo_breadcrumbs',
                'module' => 'mod_breadcrumbs',
            ]));
        });

        if ($position = $this['theme']->get('header.search')) {

            $search = $this->createModule([
                'name' => 'yoo_search',
                'module' => 'mod_search',
                'position' => $position,
            ]);

            array_push($modules, $search);

            $search = $this->createModule([
                'name' => 'yoo_search',
                'module' => 'mod_search',
                'position' => 'mobile',
            ]);

            array_push($modules, $search);
        }

        if ($position = $this['theme']->get('header.social')) {

            $social = $this->createModule([
                'name' => 'yoo_socials',
                'module' => 'mod_custom',
                'position' => $position,
                'content' => $this['view']->render('socials'),
            ]);

            strpos($position, 'left') ? array_unshift($modules, $social) : array_push($modules, $social);
        }

        foreach ($modules as $module) {

            if (!isset($positions[$module->position])) {
                $positions[$module->position] = [];
            }

            $params = json_decode($module->params);
            $config = json_decode($params && isset($params->config) ? $params->config : '{}', true);

            $module->type = str_replace('mod_', '', $module->module);
            $module->attrs = ['id' => "module-{$module->id}", 'class' => []];
            $module->config = (new Collection($this->options['config']['defaults']))->merge($config)->merge([
                'class' => [isset($params->moduleclass_sfx) ? $params->moduleclass_sfx : ''],
                'showtitle' => $module->showtitle,
                'title_tag' => isset($params->header_tag) ? $params->header_tag : 'h3',
                'is_list' => in_array($module->type, ['articles_archive', 'articles_categories', 'articles_latest', 'articles_popular', 'tags_popular', 'tags_similar'])
            ]);
        }
    }

    public function countModules($condition)
    {
        return \JFactory::getDocument()->countModules($condition);
    }

    public function createModule($module)
    {
        $module = (object) array_merge(['id' => 0, 'title' => '', 'showtitle' => 0, 'position' => '', 'params' => '{}'], (array) $module);

        if (is_array($module->params)) {
            $module->params = json_encode($module->params);
        }

        return $module;
    }

    public function editModule($form, $data)
    {
        if (!in_array($form->getName(), ['com_modules.module', 'com_advancedmodules.module'])) {
            return;
        }

        $this['@config']->set('base', $this['url']->to($this['theme']->path));
        $this['styles']->add('module-styles', 'platforms/joomla/assets/css/admin.css');

        $this['scripts']
            ->add('module-edit', "{$this->path}/app/module-edit.min.js", ['uikit', 'vue'])
            ->add('module-data', "var \$module = {$this['@config']};", '', 'string');

        $form->load('<form><fields name="params"><fieldset name="template" label="Template"><field name="config" type="hidden" default="{}" /></fieldset></fields></form>');
    }

    public static function getSubscribedEvents()
    {
        return [
            'theme.site' => 'onSite',
            'theme.admin' => 'onAdmin',
            'modules.load' => ['onModules', -10],
            'content.form' => 'editModule',
        ];
    }
}
