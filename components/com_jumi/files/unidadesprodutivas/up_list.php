<script type="text/javascript" src="/components/com_jumi/files/js/jquery.1.8.2.min.js"></script>


	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- <link rel="shortcut icon" type="image/x-icon" href="docs/images/favicon.ico" /> -->

	<link rel="stylesheet" href="/components/com_jumi/files/leaflet/leaflet.css" integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ==" crossorigin=""/>
	<script src="/components/com_jumi/files/leaflet/leaflet.js"></script>   
	<script src="/components/com_jumi/files/leaflet/shapefile/shp.js"></script>
    <script src="/components/com_jumi/files/leaflet/shapefile/leaflet.shpfile.js"></script>
	
	
	  <link rel="stylesheet" href="/libraries/leaflet/css/Leaflet.PolylineMeasure.css" />
      <script src="/libraries/leaflet/js/Leaflet.PolylineMeasure.js"></script>
	  
	  <link  rel="stylesheet" href="/components/com_jumi/files/leaflet/draw/leaflet.draw-src.css" />
	   <script src="/components/com_jumi/files/leaflet/draw/leaflet.draw-src.js"></script>

	<?php
/**
* @copyright (c) 2019 Ayeda Inovação - Todos os direitos reservados 

* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author Ricardo Carvalho <carvalhorj@gmail.com>
*
* Todo o código incluido neste arquivo está distribuído sob a licença GNU GPL v2 ou superiores* Mais informações em http://www.ayeda.com.br/index.php/sobre-a-ayeda/uso-de-codigo-livre
*/ include 'inc_up_alerts.php';?>



<style>
	
	@import url('https://fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap');


	.titulosUnidadesCor1{
		color:#196433!important;
	}

	.titulosUnidadesCor2{
		color:#7CBA43!important;
	}

	.titulosUnidadesCor3{
		color:#D45500!important;
	}

	.titulosUnidadesFont1{
		font-family: 'Raleway', sans-serif!important;
		font-weight: 700!important;
		text-decoration: none!important;
	}

	.maiusculosS{
		text-transform:uppercase;!important;
	}

	.maiusculosN{
		text-transform: none!important;
	}

	.fontUnidades1{
		font-size: 18px!important;
		line-height: 18px!important;
		height: 100px!important;
	}

	.fontUnidadesbt1{
		font-size: 16px!important;
		line-height: 16px!important;
	}



	.uk-section {
		padding: 0!important
	}
	.uk-grid {
		margin: 0!important
	}

	.btUnidades1 {
	color: #7CBA43 !important;
	background: #196433;
	border: 0px solid #494949 !important;
			text-decoration: none!important;
	}

	.btUnidades1:hover {
	color: #196433 !important;
	background: #7CBA43;
	transition: all 0.4s ease 0s;
			text-decoration: none!important;
	}


	.btUnidades2 {
	color: #ffffff !important;
	background: #D45500;
	border: 1px solid #D45500 !important;
		text-decoration: none!important;
	}

	.btUnidades2:hover {
	color: #D45500 !important;
	background: #ffffff;
	border: 1px solid #D45500!important;
	transition: all 0.4s ease 0s;
			text-decoration: none!important;
	}



	.btUnidades3 {
	color:#196433 !important;
	background: #7CBA43;
	border: 1px solid #7CBA43 !important;
			text-decoration: none!important;
	}

	.btUnidades3:hover {
	color: #7CBA43 !important;
	background: #ffffff;
	border: 1px solid #7CBA43!important;
	transition: all 0.4s ease 0s;
			text-decoration: none!important;
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

JHTML::_('behavior.modal'); 

$user = JFactory::getUser();
$usr_id = $user->get('id');

	$groups = JAccess::getGroupsByUser($usr_id);
	$profileid=0;

	if(array_search(10,$groups,true)>0){
		#Proprietário
		$profileid=10;
	}
	elseif(array_search(12,$groups,true)>0){
		#Imobiliário
		$profileid=12;
	}
	elseif(array_search(13,$groups,true)>0){
		#Serviços
		$profileid=13;
	}

	echo '<div class="uk-child-width-1-24 uk-text-center uk-padding" uk-grid>';
	    echo '<h2 class="titulosUnidadesFont1 maiusculosS titulosUnidadesCor1">Unidades Produtivas</h2>';
	echo '</div>';


	echo '<div class="uk-child-width-1-24 uk-text-center" uk-grid>';
	    echo "<div id='map' style='width: 100%; height: 600px; position: relative;' class='leaflet-container leaflet-touch leaflet-retina leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom' tabindex='0'></div>";
	echo '</div>';




echo '<div id="accordion" class="column column-1" style="z-index:1;"></div>';
	

$host = 'localhost';
$rootid = '<id de usuário de banco de dados>';
$password = '<senha de acesso ao banco>';
$db = "<banco de dados>";

$mysqli1 = mysqli_connect($host, $rootid, $password, $db);

$query0= "SELECT * FROM get_activesubscribers WHERE user_id=".$usr_id.";";
	$stmt0 = $mysqli1->query($query0);

$ups="";
	foreach ($stmt0 as $row0) {
		$ups = $row0['bizobj_ups'];
		$doc = $row0['bizobj_doc'];
		$car = $row0['bizobj_car'];
		$geo = $row0['bizobj_geo'];
	}

	
	
	if (is_null($ups) && $profileid=10){
			$app = JFactory::getApplication();	
#			$app->enqueueMessage('Para entrar na área de Unidades Produtivas, necessita de ter uma subscrição ativa. Por favor escolha o plano que melhor atende suas necessidades.');
#			$app->redirect(JRoute::_('/index.php/nossos-planos'));
		}

		
	if (is_null($ups)  && $profileid=12){
				$app = JFactory::getApplication();	
#				$app->enqueueMessage('Para entrar na área de Unidades Produtivas, necessita de ter uma subscrição ativa, da categoria de "Imobiliário". Por favor escolha o plano que melhor atende suas necessidades.');
#				$app->redirect(JRoute::_('/index.php/nossos-planos'));
			}

	
	if (is_null($ups)  && $profileid=13){
				$app = JFactory::getApplication();	
#				$app->enqueueMessage('Para entrar na área de Unidades Produtivas, necessita de ter uma subscrição ativa, da categoria de "Prestador de Serviços". Por favor escolha o plano que melhor atende suas necessidades.');
#				$app->redirect(JRoute::_('/index.php/nossos-planos'));
			}




# PROPRIAS UPs

$query1 = "SELECT PK0001UnidadeProdutiva, user_id, strnomecomum, strareatotal, strendereco, strcep, strmunicipio, strlatcent, strlngcent 
			FROM <banco de dados>.tchnfrm_0001unidadesprodutivas
			WHERE user_id=".$usr_id.";";

$stmt1 = $mysqli1->query($query1);



$upcount=0; 


	echo '<div class="uk-child-width-1-24 uk-text-center uk-padding" uk-grid>';
	    echo '<h2 class="titulosUnidadesFont1 maiusculosN titulosUnidadesCor2">Minhas Unidades</h2>';
	echo '</div>';



$markers="";


echo '<div id="1" class="uk-grid-small uk-child-width-1-4@s" uk-grid>';
foreach ($stmt1 as $row) {
	$upcount=$upcount+1;
	
    #row['PK0001UnidadeProdutiva']
	$mylat=$row['strlatcent'];
	$mylng=$row['strlngcent'];
	$mynome=utf8_encode($row['strnomecomum']);
	
	
	if ($upcount<=$ups){
		$mylink="<a target=_new href=/index.php/detalhe-unidade-produtiva?cod=".base64_encode($row['PK0001UnidadeProdutiva'].":".$row['strnomecomum']).">";
	}
	else
	{
		 $mylink='<a id="UP"onclick="shortsubsups()">';
	}
	
	
	
	$markers.="L.marker([$mylat, $mylng],{icon: PlaasIcon}).addTo(fazenda).bindPopup('$mylink<b>$mynome</b></a>').openPopup();";
	
			echo $mylink;



				echo'<div class="uk-card uk-card-default uk-card-body uk-text-center btUnidades1 maiusculosS fontUnidadesbt1 titulosUnidadesFont1 fontUnidades1">'.utf8_encode($row['strnomecomum']).'</div>';
	echo " </a>";
    
}
echo '</div id="1">';

# FIM PROPRIAS UPs



# OUTRAS UPs

$query2 = "SELECT PK0001UnidadeProdutiva, user_id, strnomecomum, strareatotal, strendereco, strcep, strmunicipio, strlatcent, strlngcent 
			FROM <banco de dados>.tchnfrm_0001unidadesprodutivas
			INNER JOIN tchnfrm_0007uppermissoes ON PK0001UnidadeProdutiva=fK0001UnidadeProdutiva
			WHERE deleted=0 and NOW()<=strtermino and strrecipiente=".$usr_id.";";

$stmt2 = $mysqli1->query($query2);


	echo '<div class="uk-child-width-1-24 uk-text-center uk-padding" uk-grid>';
	    echo '<h2 class="titulosUnidadesFont1 maiusculosN titulosUnidadesCor3">Outras Unidades</h2>';
	echo '</div>';



echo '<div id="1" class="uk-grid-small uk-child-width-1-4@s" uk-grid>';

foreach ($stmt2 as $row) {
	$upcount=$upcount+1;
	
    #row['PK0001UnidadeProdutiva']
	$mylat=$row['strlatcent'];
	$mylng=$row['strlngcent'];
	$mynome=utf8_encode($row['strnomecomum']);
	
	
	if ($upcount<=$ups){
		$mylink="<a target=_new href=/index.php/detalhe-unidade-produtiva?cod=".base64_encode($row['PK0001UnidadeProdutiva'].":".$row['strnomecomum']).">";
	}
	else
	{
		 $mylink='<a class="uk-padding-small uk-padding-remove-vertical" id="UP" onclick="shortsubsvw()">';
	}
	
	
	
	$markers.="L.marker([$mylat, $mylng],{icon: PlaasIcon1}).addTo(fazenda).bindPopup('$mylink<b>$mynome</b></a>').openPopup();";
	
	
	
		echo $mylink;
	

	 echo'<div class="uk-card uk-card-default uk-card-body btUnidades2 uk-text-center maiusculosS fontUnidadesbt1 titulosUnidadesFont1 fontUnidades1">'.utf8_encode($row['strnomecomum']).'</div>';

		
		
	echo "</a>";

}
    echo '</div id="1">';

# FIM OUTRAS UPs


	echo '<div id="1" class="uk-text-center uk-padding-large">';

	if($upcount<$ups)
		{  echo '<a href="/index.php/form-unidades-produtivas?fk0001unidadeprodutiva=0" id="editup" class="modal edocman-modal button" rel="{handler: '."'frame'".', size: {x: 1200, y: 600}}" style="background-color: rgb(45, 93, 0);color:#ffffff;">';
		}
	else
		{ 
			echo '<div class="uk-text-center"><a id="UP" onclick="shortsubsups()">'; 
		}

			 echo'<button class="btUnidades3 fontUnidadesbt1  maiusculosS titulosUnidadesFont1 uk-padding-small uk-width-1-3"><span uk-icon="icon: plus; ratio: 1"></span>

	 NOVA UNIDADE </button>';
		
		echo '</a></div>';

		echo '</div id="1">';



	
	
	?>

<script>	
			var fazenda;
		    var indios;
			var estradas;
			var sigef;
			var car;
			var municipios
			
			
			
			
			var fazendaControl;

			//SET UP MAP
			var osmAttr='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
				osmUrl='https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';

			var esAttr='Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community',
				esUrl='https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}';

			var osmap = L.tileLayer(osmUrl, {id: 'OpenStreetMap', attribution: osmAttr}),
				esri  = L.tileLayer(esUrl, {id: 'esri',   attribution: esAttr});

			var map = L.map('map', {
				center: [-13, -58],
				zoom: 5,
				layers: [esri], 
				drawControl: false
			});

			var baseLayers = {
				"ESRI": esri
			};
			// SETUP MAP
			
			//SET UP  CONTROLS

			var baseControl = L.control.layers(baseLayers).addTo(map);
	
			L.control.scale ({maxWidth:240, metric:true, imperial:false, position: 'bottomleft'}).addTo (map);
            L.control.polylineMeasure ({position:'bottomleft', unit:'metres', showBearings:false, clearMeasurementsOnStop: false, showClearControl: true, showUnitControl: true}).addTo (map);

			// END SET UP CONTROLS
				
			
			// SETUP SHAPES
			
			addShapes();
			
			// END SETUP SHAPES
		
		
			// Initialise the FeatureGroup to store editable layers


var drawPluginOptions = {
  position: 'topright',
  draw: {
    polygon: {
      allowIntersection: true, // Restricts shapes to simple polygons
      drawError: {
        color: '#e1e100', // Color the shape will turn when intersects
        message: '<strong>Oh snap!<strong> you can\'t draw that!' // Message that will show when intersect
      },
      shapeOptions: {
        color: '#97009c'
      }
    },
    // disable toolbar item by setting it to false
    polyline: true,
    circle: true, // Turns off this drawing tool
    rectangle: true,
    marker: true,
    },
  edit: {
    featureGroup: editableLayers, //REQUIRED!!
    remove: true
  }
};

var editableLayers = new L.FeatureGroup();

map.addLayer(editableLayers);


//var drawControl = new L.Control.Draw(drawPluginOptions);
//map.addControl(drawControl);



// Initialise the draw control and pass it the FeatureGroup of editable layers
//var drawControl = new L.Control.Draw(drawPluginOptions);
//map.addControl(drawControl);



		
		  
		  function addShapes() {
			  
				var mylaranja = {"color": "#ff7800","weight": 1,"opacity": 0.85};
				//fazenda
				var myverde = {"color": "#09d32f","weight": 1,"opacity": 0.85};
				//areas certificadas - não temos
				var mycinzento = {"color": "#787b78","weight": 1,"opacity": 0.85};
				// Cadastro ambiental
				var myazul = {"color": "#370589","weight": 1,"opacity": 0.85};
				//terra indigenas
				var myvermelho = {"color": "#ed3c2e","weight": 1,"opacity": 0.85};
				//rodovias f6f90e
				var myamarelo = {"color": "#000000","weight": 3,"opacity": 0.85};
				//municipios
				var mybranco = {"color": "#ffffff","weight": 1,"opacity": 1};
			  
			  	var PlaasIcon = L.icon({
						iconUrl: 'http://www.plaas.com.br/images/plaas/plaasmarker.svg',
						iconSize:     [36, 56], // size of the icon
						iconAnchor:   [18, 56], // point of the icon which will correspond to marker's location
						popupAnchor:  [0, 3] // point from which the popup should open relative to the iconAnchor
						});		
				var PlaasIcon1 = L.icon({
						iconUrl: 'http://www.plaas.com.br/images/plaas/plaasmarker1.svg',
						iconSize:     [36, 56], // size of the icon
						iconAnchor:   [18, 56], // point of the icon which will correspond to marker's location
						popupAnchor:  [0, 3] // point from which the popup should open relative to the iconAnchor
						});		
				var PlaasIcon2 = L.icon({
						iconUrl: 'http://www.plaas.com.br/images/plaas/plaasmarker2.svg',
						iconSize:     [36, 56], // size of the icon
						iconAnchor:   [18, 56], // point of the icon which will correspond to marker's location
						popupAnchor:  [0, 3] // point from which the popup should open relative to the iconAnchor
						});		
				var PlaasIcon3 = L.icon({
						iconUrl: 'http://www.plaas.com.br/images/plaas/plaasmarker3.svg',
						iconSize:     [36, 56], // size of the icon
						iconAnchor:   [18, 56], // point of the icon which will correspond to marker's location
						popupAnchor:  [0, 3] // point from which the popup should open relative to the iconAnchor
						});		
				var PlaasIcon4 = L.icon({
						iconUrl: 'http://www.plaas.com.br/images/plaas/plaasmarker4.svg',
						iconSize:     [36, 56], // size of the icon
						iconAnchor:   [18, 56], // point of the icon which will correspond to marker's location
						popupAnchor:  [0, 3] // point from which the popup should open relative to the iconAnchor
						});	
		  
				


				//ADD UNIDADE PRODUTIVA
				{
				console.log("Adding layer");
				fazenda = L.layerGroup();
				
	
				
				<?php
/**
* @copyright (c) 2019 Ayeda Inovação - Todos os direitos reservados 

* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author Ricardo Carvalho <carvalhorj@gmail.com>
*
* Todo o código incluido neste arquivo está distribuído sob a licença GNU GPL v2 ou superiores* Mais informações em http://www.ayeda.com.br/index.php/sobre-a-ayeda/uso-de-codigo-livre
*/ echo $markers;?>
				}
				//END ADD UNIDADE PRODUTIVA
				
				
				
				
				
				
				//ADD CONTROLS
				var overlays = {
				
					"Minhas Fazendas": fazenda
					
				};

		
				map.addLayer(fazenda);
				
	

				map.removeControl(baseControl);
				fazendaControl = L.control.layers(baseLayers, overlays).addTo(map);
				//END ADD CONTROLS
			}
			
		
			



			
				
</script>




