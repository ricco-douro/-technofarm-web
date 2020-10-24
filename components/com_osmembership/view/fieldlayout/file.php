<?php
/**
 * @package        Joomla
 * @subpackage     OSMembership
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die;

JFactory::getDocument()->addScript(JUri::root(true) . '/media/com_osmembership/assets/js/ajaxupload.js');
?>
<input type="button" value="<?php echo JText::_('OSM_SELECT_FILE'); ?>" id="button-file-<?php echo $name; ?>" class="btn btn-primary" />
<span class="osm-uploaded-file" id="uploaded-file-<?php echo $name; ?>">
<?php
    if ($value && file_exists(JPATH_ROOT . '/media/com_osmembership/upload/' . $value))
    {
    ?>
        <a href="<?php echo JRoute::_('index.php?option=com_osmembership&task=controller.download_file&file_name=' . $value); ?>"><i class="fa fa-donwload"></i><strong><?php echo $value; ?></strong></a>
    <?php
    }
?>
</span>
<input type="hidden" id="<?php echo $name; ?>" name="<?php echo $name; ?>"  value="<?php echo $value; ?>" />
<script type="text/javascript">
    new AjaxUpload('#button-file-<?php echo $name; ?>', {
        action: siteUrl + 'index.php?option=com_osmembership&task=upload_file',
        name: 'file',
        autoSubmit: true,
        responseType: 'json',
        onSubmit: function (file, extension) {
            jQuery('#button-file-<?php echo $name; ?>').after('<span class="wait">&nbsp;<img src="<?php echo JUri::root(true);?>/media/com_osmembership/ajax-loadding-animation.gif" alt="" /></span>');
            jQuery('#button-file-<?php echo $name; ?>').attr('disabled', true);
        },
        onComplete: function (file, json) {
            jQuery('#button-file-<?php echo $name; ?>').attr('disabled', false);
            jQuery('.error').remove();
            if (json['success']) {
                jQuery('#uploaded-file-<?php echo $name; ?>').html(file);
                jQuery('input[name="<?php echo $name; ?>"]').attr('value', json['file']);
            }
            if (json['error']) {
                jQuery('#button-file-<?php echo $name; ?>').after('<span class="error">' + json['error'] + '</span>');
            }

            jQuery('.wait').remove();
        }
    });
</script>