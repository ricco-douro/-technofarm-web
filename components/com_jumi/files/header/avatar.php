<!-- <link src="/components/com_community/templates/jomsocial/assets/css/style.css">
<link href="/components/com_jumi/files/css/ip-custom.css?v=1.5.0" rel="stylesheet" type="text/css" id="ip-custom-css">





</head>

<body> -->

	<style>
		.divtexto{

		text-transform: none!important;
		}

		.user{
			color: #196433 !important;
			background-color: #7CBA43 !important;
			display: inline-block!important;
			line-height: 16px!important;
			font-size: 18px!important;	
			font-weight: 800!important;
			padding: 5px!important;
			text-align: left!important;

		}

		.user_plano{
			color: #ffffff !important;
			display: inline-block!important;
			line-height: 16px!important;
			font-size: 18px!important;	
			padding: 5px!important;
		}		

		.user_icon{
			color: #ffffff !important;
			font-size: 16px!important;	
		}

</style>
<?php
/**
* @copyright (c) 2019 Ayeda Inovação - Todos os direitos reservados 

* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author Ricardo Carvalho <carvalhorj@gmail.com>
*
* Todo o código incluido neste arquivo está distribuído sob a licença GNU GPL v2 ou superiores* Mais informações em http://www.ayeda.com.br/index.php/sobre-a-ayeda/uso-de-codigo-livre
*/

		$user = JFactory::getUser();
		$usr_id = $user->get('id');
			
$host = 'localhost';
$rootid = 'root';
$password = '';
$db = 'plaas1707';


		$mysqli = mysqli_connect($host, $rootid, $password, $db);

		$query="
				select u.id, u.name, u.email, v1.value, cu. avatar as thumb  from  tchnfrm_users u
				left join  tchnfrm_community_fields_values v1 on u.id = v1.user_id and v1.field_id=18
				left join  tchnfrm_community_users cu on  u.id = cu.userid
				where u.id=$usr_id
				order by u.name ASC;";

		$result = $mysqli->query($query) or die($mysqli->error.__LINE__);


		$query2= "SELECT * FROM get_activesubscribers WHERE user_id=".$usr_id.";";
		$stmt2 = $mysqli->query($query2);

		$ups = 0;
		$doc = 0;
		$car = 0;
		$geo = 0;
		$title="Free";
		$name=("Usuário Anônimo");
		$thumb="components/com_community/assets/user-Male.png";
		
	foreach ($stmt2 as $row2) {
		$title= $row2['title'];
		$ups = $row2['bizobj_ups'];
		$doc = $row2['bizobj_doc'];
		$car = $row2['bizobj_car'];
		$geo = $row2['bizobj_geo'];
	}





?>

<!-- <div id="container" >  
<div class="menu row3"> 

	<div class="column column-24 text-center" ><br/><br/> -->
			<?php
/**
* @copyright (c) 2019 Ayeda Inovação - Todos os direitos reservados 

* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author Ricardo Carvalho <carvalhorj@gmail.com>
*
* Todo o código incluido neste arquivo está distribuído sob a licença GNU GPL v2 ou superiores* Mais informações em http://www.ayeda.com.br/index.php/sobre-a-ayeda/uso-de-codigo-livre
*/ 
			
			if($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
						$name=utf8_encode($row["name"]);
						$thumb=utf8_encode($row["thumb"]);
				}
				
			}

			echo'<div class="uk-flex-middle" uk-grid>';
				

				
				$timestamp=date('Ymdhms');			
				
				echo'<div class="uk-width-expand@m uk-text-right">';

					echo'<div class="uk-width-1-1 user_icon" title="Unidades Produtivas">'.$ups.' <span class=""'."?timestamp=".$timestamp.'" uk-icon="location"></span></div>';	
					echo'<div class="uk-width-1-1 user_icon" title="Documentos">'.$doc.' <span class=""'."?timestamp=".$timestamp.'" uk-icon="copy"></span></div>';
					echo'<div class="uk-width-1-1 user_icon" title="CARs">'.$car.' <span class=""'."?timestamp=".$timestamp.'" uk-icon="git-fork"></span></div>';
					echo'<div class="uk-width-1-1 user_icon" title="GEOs">'.$geo.' <span class=""'."?timestamp=".$timestamp.'" uk-icon="world"></span></div>';

					// echo'	<img src="/images/svgs/ups.svg'."?timestamp=".$timestamp.'" uk-tooltip="Unidades Produtivas" alt="Unidades Produtivas" title="Unidades Produtivas"> '.$ups.' 
							// <img src="/images/svgs/doc.svg'."?timestamp=".$timestamp.'" uk-tooltip="Documentos"  alt="Documentos" title="Documentos"> '.$doc.' 
							// <img src="/images/svgs/car.svg'."?timestamp=".$timestamp.'" uk-tooltip="CARs"  alt="CARs" title="CARs"> '.$car.' 
							// <img src="/images/svgs/geo.svg'."?timestamp=".$timestamp.'" uk-tooltip="GEOs"  alt="GEOs" title=""> '.$geo.'';					

				echo'</div>';

				echo'<div class="uk-width-expand@m uk-text-left">';
				
					echo'<img src="'.utf8_encode($thumb).'" style="border: 2px solid #ffffff; width:120px; margin-bottom:10px; -webkit-border-radius: 100%; inline-block; -moz-border-radius: 100%; -o-border-radius: 100%; border-radius: 100%; ">';
					echo'<div class="user">'.($name).'</div><br>';
					echo'<div class="user_plano"> Plano: '.$title.'</div>';	


				echo'</div>';				
				
			echo'</div>';

			?>
			
<!-- 	 </div>
</div>
</div> -->