<?php 
/**
 * jSecure Lite components for Joomla!
 * jSecure Lite extention prevents access to administration (back end)
 * login page without appropriate access key.
 *
 * @author      $Author: Ajay Lulia $
 * @copyright   Joomla Service Provider - 2012
 * @package     jSecure Lite 1.0
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version     $Id: default.php  $
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<h3><?php echo JText::_('HELP');?></h3>
<table>
	<tr>
		<td>
			<p><b>Drawback:</b></p>
			<p>Joomla has one drawback, any web user can easily know the site is created in Joomla! by typing the URL to access the administration area <b>(i.e. www.site name.com/administration).</b> This makes hackers hack the site easily once they crack id and password for Joomla!. </p>
		</td>
	</tr>
</table>

<table class="table-striped-jsecure" width="100%">
	<tr>
		<td>
			<p><b>Instructions</b></p>
			<p>jSecure Lite module prevents access to administration (back end) login page without appropriate access key.</p>
		</td>
	</tr>
</table>
<br/>
<table style="border:1px solid #0088CC;">
	<tr>
		<td style="background-color:#F5FAFD; line-height:18px; padding:8px;">
			<p><b>Important!</b></p>
			<p style="color:#0088CC;">In order for jSecure Lite to work the jSecure Lite <b>plugin</b> must be enabled. Go to Extensions>Plugin Manager and look for the "<b>System - jSecure Lite plugin</b>". Make sure this plug in is enabled.</p>
		</td>
	</tr>
</table>

<div>
<p>&nbsp;</p>
</div>

<div>
	<ul class="nav nav-tabs">
	<li class="active"><a href="#basic_config_tab" data-toggle="tab"><?php echo JText::_('Basic Configuration');?></a></li>
	<li><a href="#license_tab" data-toggle="tab"><?php echo JText::_('License');?></a></li>
	</ul>

	<div class="tab-content">
	<div class="tab-pane active" id="basic_config_tab">
		<table class="table-striped-jsecure" width="100%">
		<tr>
		<td>
			<p>The basic configuration will hide your administrator URL from public access. This is all most people need.</p>
		</td>
		</tr>
		</table>
		<table>
		<tr>
			<td style="line-height:18px; padding:8px;">
			1) Set <b>"enable"</b> to <b>"yes"</b>.<br/><br/>
			2) Set the <b>"Pass Key"</b> to <b>"URL"</b> This will hide the administrator URL.<br/><br/>
			3) In the <b>"Key"</b> field enter the key that will be part of your new administrator URL. For example, if you enter <b>"test"</b> into the key field, then the administrator URL will be <a href="#">http://www.yourwebsite/administrator/?test</a>. Please note that you cannot have a key that is only numbers.<br/>
			   <br/>If you do not enter a key, but enable the jSecure Lite component, then the URL to access the administrator area is /?jSecure <a href="#">(http://www.yourwebsite/administrator/?jSecure)</a>.<br/><br/>
			4) Set the <b>"Redirect Options"</b> field. By default, if someone tries to access you /administrator URL without the correct key, they will be redirected to the home page of your Joomla site. You can also set up a <b>"Custom Path"</b> is you would like the user to be redirected somewhere else, such as a <b>404 error</b> page.</li>
			</td>
		</tr>
		</table>
	</div>


	
	
	
	<div class="tab-pane" id="license_tab">
		<table class="table-striped-jsecure" width="100%">
		<tr>
		<td>
			<p>This is free software and you may redistribute it under the GPL. jSecure Lite comes with absolutely no warranty. Use at your own risk. For details, see the license at <a href="http://www.gnu.org/licenses/gpl.txt" target="_blank">http://www.gnu.org/licenses/gpl.txt</a> Other licenses can be found in LICENSES folder. </p>
		</td>
		</tr>
		</table>
	</div>
	
	</div>
</div>