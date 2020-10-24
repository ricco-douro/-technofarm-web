<?php
/**
* @copyright (C) 2015 iJoomla, Inc. - All rights reserved.
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author iJoomla.com <webmaster@ijoomla.com>
* @url https://www.jomsocial.com/license-agreement
* The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
* More info at https://www.jomsocial.com/license-agreement
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

$percentage = "";
$totalWidth = 630;

if($user->id) {
    $percentage = $stats->completePercentage;
}

// Calculate a percentage of a circle
$dashoffset = ($percentage / 100) * $totalWidth;

?>

<div class="joms-module">

<?php if($user->id){?>

    <div class="joms-module--profilecompleteness">

        <!-- data-percent will define the percentage value in the middle of pie -->
        <div class="joms-pc__pgbar--outer">
            <div class="joms-pc__pgbar" data-percent="<?php echo $stats->completePercentage; ?>%">
                <svg viewBox="-10 -10 220 220">
                  <g fill="none" stroke-width="8" transform="translate(100,100)">
                    <path d="M 0,-100 A 100,100 0 0,1 86.6,-50" stroke="#5F7EFC" />
                    <path d="M 86.6,-50 A 100,100 0 0,1 86.6,50" stroke="#5F7EFC" />
                    <path d="M 86.6,50 A 100,100 0 0,1 0,100" stroke="#5F7EFC" />
                    <path d="M 0,100 A 100,100 0 0,1 -86.6,50" stroke="#5F7EFC" />
                    <path d="M -86.6,50 A 100,100 0 0,1 -86.6,-50" stroke="#5F7EFC" />
                    <path d="M -86.6,-50 A 100,100 0 0,1 0,-100" stroke="#5F7EFC" />
                  </g>
                </svg>
                <svg viewBox="-10 -10 220 220">
                <!--
                    the lenght of the progress bar is based on stroke-dashoffset value
                    100% = 630
                    50% = 315
                -->
                  <path d="M200,100 C200,44.771525 155.228475,0 100,0 C44.771525,0 0,44.771525 0,100 C0,155.228475 44.771525,200 100,200 C155.228475,200 200,155.228475 200,100 Z" stroke-dashoffset="<?php echo $dashoffset; ?>"></path>
                </svg>
            </div>
            <div class="joms-gap"></div>

            <?php if($stats->completePercentage == 100) {?>
            <p class="joms-text--centre"><?php echo JText::_('MOD_COMMUNITY_PROFILECOMPLETENESS_COMPLETE'); ?></p>
            <?php }?>
        </div>
        <!--  Defining Angle Gradient Colors  -->
        <svg width="0" height="0">
            <defs>
                <linearGradient id="cl1" gradientUnits="objectBoundingBox" x1="0" y1="0" x2="1" y2="1">
                    <stop class="cl1" />
                    <stop class="cl2" offset="100%" />
                </linearGradient>
                <linearGradient id="cl2" gradientUnits="objectBoundingBox" x1="0" y1="0" x2="0" y2="1">
                    <stop class="cl3" />
                    <stop class="cl4" offset="100%" />
                </linearGradient>
                <linearGradient id="cl3" gradientUnits="objectBoundingBox" x1="1" y1="0" x2="0" y2="1">
                    <stop class="cl5" />
                    <stop class="cl6" offset="100%" />
                </linearGradient>
                <linearGradient id="cl4" gradientUnits="objectBoundingBox" x1="1" y1="1" x2="0" y2="0">
                    <stop class="cl7" />
                    <stop class="cl8" offset="100%" />
                </linearGradient>
                <linearGradient id="cl5" gradientUnits="objectBoundingBox" x1="0" y1="1" x2="0" y2="0">
                    <stop class="cl9" />
                    <stop class="cl10" offset="100%" />
                </linearGradient>
                <linearGradient id="cl6" gradientUnits="objectBoundingBox" x1="0" y1="1" x2="1" y2="0">
                    <stop class="cl11" />
                    <stop class="cl12" offset="100%" />
                </linearGradient>
            </defs>
        </svg>

        <!-- only list uncompleted profile -->
        <ul class="joms-list joms-list--pc">
            <?php foreach($stats->completionMessages as $message){ ?>
            <li><a href="<?php echo (isset($message['link']) ? $message['link'] : 'javascript:void(0)') ?>"><?php echo $message['msg']; ?></a><span><?php echo floor($message['incomplete']/$stats->total*100) ?>%</span></li>
            <?php } ?>
        </ul>

    </div>

<?php }else{?>

    <p class="joms-blankslate">
        <?php echo JText::_('MOD_COMMUNITY_PROFILECOMPLETENESS_LOGIN'); ?>
    </p>               

<?php } ?>

</div>
