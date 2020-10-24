<div class="joms-module--topmembers">
  <!-- link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script -->
  
  
  <link rel="stylesheet" href="libraries/accordion/jquery-ui.css">
  <link rel="stylesheet" href="libraries/accordion/style.css">
  <script src="libraries/accordion/jquery-1.12.4.js"></script>
  <script src="libraries/accordion/jquery-ui.js"></script>
  <script defer src="libraries/accordion/fontawesome/js/all.js"></script>
  
  
  <script>
  $( function() {
    var icons = {
      header: "ui-icon-circle-arrow-e",
      activeHeader: "ui-icon-circle-arrow-s"
    };
    $( "#accordion" ).accordion({
      icons: icons,
	  heightStyle: "content"
    });
    $( "#toggle" ).button().on( "click", function() {
      if ( $( "#accordion" ).accordion( "option", "icons" ) ) {
        $( "#accordion" ).accordion( "option", "icons", null );
      } else {
        $( "#accordion" ).accordion( "option", "icons", icons );
      }
    });
  } );
  </script>
  
  <style>
  

	</style>
<?php

defined( '_JEXEC' ) or die( 'Unauthorized Access' ); 

$svgPath = CFactory::getPath('template://assets/icon/joms-icon.svg');
include_once $svgPath;

JHtml::_('behavior.modal', 'a.edocman-modal');

$groupId=0;

if (JRequest::getInt('groupid', '')<>0)
{
$groupId = JRequest::getInt('groupid', '');	
}
$is_groupadmin=false;

$user = JFactory::getUser();
$usr_id = $user->get('id');

$db1 = JFactory::getDbo();
$query1 = $db1->getQuery(true);
$query1->select($db1->quoteName(array('permissions')));
$query1->from($db1->quoteName('#__community_groups_members'));
$query1->where($db1->quoteName('memberid') . ' = '. $usr_id);

$db1->setQuery($query1);
$results1 = $db1->loadRow();

if($results1['0']==1)
	{
	$is_groupadmin=true;
	}

$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select($db->quoteName(array('id', 'class', 'classtext', 'link', 'linktext', 'type', 'is_admin', 'target', 'rel')));
$query->from($db->quoteName('#__community_group_actions'));
$query->where($db->quoteName('groupid') . ' = '. $groupId);


$query->order('groupid, class, id ASC');

$db->setQuery($query);
$db->execute();
$num_rows = $db->getNumRows();

if ($num_rows>0)
	{

		$results = $db->loadObjectList();

		$acc_group="";
		$acc_link="";

		echo '<div id="accordion">';

			foreach ($results as $row) :

				if ($row->class != $acc_group )
				{
					if ($acc_group!="")
						{
							echo "</div>";
						}

					$acc_group = $row->class ;
					echo "<h3>".$row->classtext."</h3>";
					echo "<div>";
				}
		
				if ($row->is_admin==1)
				{
					if ($is_groupadmin)
					{
						echo "<p class='".$row->type."'><a href='". $row->link."'";		
						echo ($row->target=='modal') ? 'class="edocman-modal" rel="'.$row->rel.'">' : '>';	
						echo $row->linktext."</a> <i class='fas fa-audio-description' style='color:red;'></i></p>";
					}
					else
					{
						echo "<p class='not".$row->type."'>". $row->linktext." <i class='fas fa-audio-description' style='color:red;'></i></p>";
					}
				}
				else
				{
					echo "<p class='".$row->type."'><a href='". $row->link."'";		
					echo ($row->target=='modal') ? 'class="edocman-modal" rel="'.$row->rel.'">' : '>';	
					echo $row->linktext."</a></p>";
				}
		
		
			endforeach;

		echo "</div></div>";
	}
	else
	{
		echo "<p class='notlink'>Este grupo ainda não tem atividades específicas.</p>";
				
	}
	echo "</div>";

	
?>

