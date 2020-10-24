<?php

// Options
$logo = $theme->get('logo', []);
$mobile = $theme->get('mobile', []);

if ($logo['image_mobile']) {
    $logo['image'] = $logo['image_mobile'];
}

$logo = $logo['image'] ? $this->image($logo['image'], ['alt' => $logo['text'], 'class' => 'uk-responsive-height']) : $logo['text'];

if (!$logo) {
    unset($mobile['logo']);
}

if (!$this->countModules('mobile')) {
    unset($mobile['toggle']);
}

$mobile['search'] = false; // TODO

?>

<nav class="uk-navbar-container" uk-navbar>

    <?php if ($mobile['logo'] == 'left' || $mobile['toggle'] == 'left' || $mobile['search'] == 'left') : ?>
    <div class="uk-navbar-left">

        <?php if ($mobile['logo'] == 'left') : ?>
        <a class="uk-navbar-item uk-logo<?= $mobile['logo_padding_remove'] ? ' uk-padding-remove-left' : '' ?>" href="<?= $theme->get('site_url') ?>">
            <?= $logo ?>
        </a>
        <?php endif ?>

        <?php if ($mobile['toggle'] == 'left') : ?>
        <a class="uk-navbar-toggle" href="#tm-mobile" uk-toggle<?= ($mobile['animation'] == 'dropdown') ? '="animation: true"' : '' ?>>
            <div uk-navbar-toggle-icon></div>
            <?php if ($mobile['toggle_text']) : ?>
            <span class="uk-margin-small-left"><?= JText::_('TPL_YOOTHEME_MENU') ?></span>
            <?php endif ?>
        </a>
        <?php endif ?>

        <?php if ($mobile['search'] == 'left') : ?>
        <a class="uk-navbar-item"><?= JText::_('TPL_YOOTHEME_SEARCH') ?></a>
        <?php endif ?>

    </div>
    <?php endif ?>

    <?php if ($mobile['logo'] == 'center') : ?>
    <div class="uk-navbar-center">
        <a class="uk-navbar-item uk-logo" href="<?= $theme->get('site_url') ?>">
            <?= $logo ?>
        </a>
    </div>
    <?php endif ?>

    <?php if ($mobile['logo'] == 'right' || $mobile['toggle'] == 'right' || $mobile['search'] == 'right') : ?>
    <div class="uk-navbar-right">

        <?php if ($mobile['search'] == 'right') : ?>
        <a class="uk-navbar-item"><?= JText::_('TPL_YOOTHEME_SEARCH') ?></a>
        <?php endif ?>

        <?php if ($mobile['toggle'] == 'right') : ?>
        <a class="uk-navbar-toggle" href="#tm-mobile" uk-toggle<?= $mobile['animation'] == 'dropdown' ? '="animation: true"' : '' ?>>
            <?php if ($mobile['toggle_text']) : ?>
            <span class="uk-margin-small-right"><?= JText::_('TPL_YOOTHEME_MENU') ?></span>
            <?php endif ?>
            <div uk-navbar-toggle-icon></div>
        </a>
        <?php endif ?>

        <?php if ($mobile['logo'] == 'right') : ?>
        <a class="uk-navbar-item uk-logo<?= $mobile['logo_padding_remove'] ? ' uk-padding-remove-right' : '' ?>" href="<?= $theme->get('site_url') ?>">
            <?= $logo ?>
        </a>
        <?php endif ?>

    </div>
    <?php endif ?>

</nav>

<?php if ($this->countModules('mobile')) :

    $attrs_menu = [];
    $attrs_menu['class'][] = $mobile['animation'] == 'offcanvas' ? 'uk-offcanvas-bar' : '';
    $attrs_menu['class'][] = $mobile['animation'] == 'modal' ? 'uk-modal-dialog uk-modal-body' : '';
    $attrs_menu['class'][] = $mobile['animation'] == 'dropdown' ? 'uk-background-default uk-padding' : '';
    $attrs_menu['class'][] = $mobile['menu_center'] ? 'uk-text-center' : '';
    $attrs_menu['class'][] = $mobile['animation'] != 'dropdown' && $mobile['menu_center_vertical'] ? 'uk-flex' : '';

    $mobile['offcanvas']['overlay'] == true;

    ?>

    <?php if ($mobile['animation'] == 'offcanvas') : ?>
    <div id="tm-mobile" uk-offcanvas<?= $this->attrs($mobile['offcanvas'] ?: []) ?>>
        <div<?= $this->attrs($attrs_menu) ?>>

            <button class="uk-offcanvas-close" type="button" uk-close></button>

            <?php if ($mobile['menu_center_vertical']) : ?>
            <div class="uk-margin-auto-vertical uk-width-1-1">
            <?php endif ?>

                <jdoc:include type="modules" name="mobile" style="grid-stack" />

            <?php if ($mobile['menu_center_vertical']) : ?>
            </div>
            <?php endif ?>

        </div>
    </div>
    <?php endif ?>

    <?php if ($mobile['animation'] == 'modal') : ?>
    <div id="tm-mobile" class="uk-modal-full" uk-modal>
        <div<?= $this->attrs($attrs_menu) ?> uk-height-viewport>

            <button class="uk-modal-close-full" type="button" uk-close></button>

            <?php if ($mobile['menu_center_vertical']) : ?>
            <div class="uk-margin-auto-vertical uk-width-1-1">
            <?php endif ?>

                <jdoc:include type="modules" name="mobile" style="grid-stack" />

            <?php if ($mobile['menu_center_vertical']) : ?>
            </div>
            <?php endif ?>

        </div>
    </div>
    <?php endif ?>

    <?php if ($mobile['animation'] == 'dropdown') : ?>
    <div class="uk-position-relative uk-position-z-index">
        <div id="tm-mobile" class="<?= $mobile['dropdown'] == 'slide' ? 'uk-position-top' : '' ?>" hidden>
            <div<?= $this->attrs($attrs_menu) ?>>

                <jdoc:include type="modules" name="mobile" style="grid-stack" />

            </div>
        </div>
    </div>
    <?php endif ?>

<?php endif ?>
