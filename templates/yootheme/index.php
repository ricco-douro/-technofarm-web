<?php

defined('_JEXEC') or die();

JHtml::_('behavior.modal', 'a.edocman-modal');

$theme = JHtml::_('theme');

$site = $theme->get('site', []);

// Boxed Page Layout
$boxed = $theme->get('site.boxed', []);
$boxed_class = ['tm-page'];
$boxed_class[] = $boxed['padding'] ? 'tm-page-padding' : '';
$boxed_style[] = $boxed['media'] ? "background-image: url('{$boxed['media']}');" : '';

?>
<!DOCTYPE html>
<html lang="<?= $this->language ?>" dir="<?= $this->direction ?>" vocab="http://schema.org/">
    <head>
        <meta charset="<?= $this->getCharset() ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="<?= $theme->get('favicon') ?>">
        <link rel="apple-touch-icon-precomposed" href="<?= $theme->get('touchicon') ?>">
        <jdoc:include type="head" />
		
    </head>
    <body class="<?= $theme->get('body_class')->join(' ') ?>">

        <?php if (strpos($theme->get('header.layout'), 'offcanvas') === 0 || $theme->get('mobile.animation') == 'offcanvas') : ?>
        <div class="uk-offcanvas-content">
        <?php endif ?>

        <?php if ($site['layout'] == 'boxed') : ?>
        <div<?= JHtml::_('attrs', ['class' => $boxed_class, 'style' => $boxed_style]) ?>>
            <div <?= $boxed['alignment'] ? 'class="uk-margin-auto"' : '' ?>>
        <?php endif ?>

            <div class="tm-header-mobile uk-hidden@<?= $theme->get('mobile.breakpoint') ?>">
            <?= JHtml::_('render', 'header-mobile') ?>
            </div>

            <?php if ($this->countModules('toolbar-left') || $this->countModules('toolbar-right')) : ?>
            <div class="tm-toolbar uk-visible@<?= $theme->get('mobile.breakpoint') ?>">
                <div class="uk-container uk-flex uk-flex-middle <?= $site['toolbar_fullwidth'] ? 'uk-container-expand' : '' ?>">

                    <?php if ($this->countModules('toolbar-left')) : ?>
                    <div>
                        <div class="uk-grid-medium uk-child-width-auto uk-flex-middle" uk-grid="margin: uk-margin-small-top">
                            <jdoc:include type="modules" name="toolbar-left" style="cell" />
                        </div>
                    </div>
                    <?php endif ?>

                    <?php if ($this->countModules('toolbar-right')) : ?>
                    <div class="uk-margin-auto-left">
                        <div class="uk-grid-medium uk-child-width-auto uk-flex-middle" uk-grid="margin: uk-margin-small-top">
                            <jdoc:include type="modules" name="toolbar-right" style="cell" />
                        </div>
                    </div>
                    <?php endif ?>

                </div>
            </div>
            <?php endif ?>

            <?= JHtml::_('render', 'header') ?>

            <?= JHtml::_('render', 'section', ['name' => 'top']) ?>


			<?php include("components/com_jumi/files/header/inc_header.php");?>
            
			<?php if (!$theme->get('builder')) : ?>
			
			


            <div id="tm-main" class="tm-main uk-section uk-section-default" uk-height-viewport="mode: expand">
                <div class="uk-container">

                    <?php
                        $grid = ['uk-grid']; $sidebar = $theme->get('sidebar', []);
                        $grid[] = $sidebar['gutter'] ? "uk-grid-{$sidebar['gutter']}" : '';
                        $grid[] = $sidebar['divider'] ? "uk-grid-divider" : '';
                    ?>

                    <div<?= JHtml::_('attrs', ['class' => $grid, 'uk-grid' => true]) ?>>
                        <div class="uk-width-expand@<?= $theme->get('sidebar.breakpoint') ?>">

                            <?php if ($site['breadcrumbs']) : ?>
                            <div class="uk-margin-medium-bottom">
                                <?= JHtml::_('section', 'breadcrumbs') ?>
                            </div>
                            <?php endif ?>

            <?php endif ?>

            <jdoc:include type="message" />
            <jdoc:include type="component" />

            <?= JHtml::_('section', 'builder') ?>

            <?php if (!$theme->get('builder')) : ?>

                        </div>

                        <?php if ($this->countModules('sidebar')) : ?>
                        <?= JHtml::_('render', 'sidebar') ?>
                        <?php endif ?>

                    </div>

                </div>
            </div>
            <?php endif ?>

            <?= JHtml::_('render', 'section', ['name' => 'bottom']) ?>
            <?= JHtml::_('builder', $theme->get('footer.content'), 'footer') ?>
			<?php include("components/com_jumi/files/header/inc_footer.php");?>
            

        <?php if ($site['layout'] == 'boxed') : ?>
            </div>
        </div>
        <?php endif ?>

        <?php if (strpos($theme->get('header.layout'), 'offcanvas') === 0 || $theme->get('mobile.animation') == 'offcanvas') : ?>
        </div>
        <?php endif ?>

        <?php if ($css = $theme->get('css')) : ?>
        <style><?= preg_replace('/[\r\n\t]+/', ' ', $css) ?></style>
        <?php endif ?>

        <jdoc:include type="modules" name="debug" />

    </body>
</html>
