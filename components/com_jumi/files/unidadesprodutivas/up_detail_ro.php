	<script type="text/javascript" src="/components/com_jumi/files/js/jquery.1.8.2.min.js"></script>

	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="https://code.highcharts.com/highcharts-more.js"></script>
	<script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
	<script type="text/javascript" src="/components/com_jumi/files/hcthemes/ogmt_branco.js"></script>  

	<link href="/components/com_jumi/files/fonts/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" >
	<link href="/components/com_jumi/files/fonts/Icon-font-7-stroke-1.2.0/pe-icon-7-stroke/css/pe-icon-7-stroke.css" rel="stylesheet" >
	<link href="/components/com_jumi/files/fonts/Icon-font-7-stroke-1.2.0/pe-icon-7-stroke/css/helper.css" rel="stylesheet" >
	<link href="/components/com_jumi/files/linearicons/icon-font.min.css" rel="stylesheet" >
	<link href="/components/com_jumi/files/css/theme.css?v=1494269822" rel="stylesheet" type="text/css" id="theme-style-css">
	<link href="/components/com_jumi/files/css/ip-custom.css?v=1.5.0" rel="stylesheet" type="text/css" id="ip-custom-css">
	<link href="/components/com_jumi/files/css/ip-responsive.css?v=1.5.0" rel="stylesheet" type="text/css" id="ip-responsive-css">
	<link href="/components/com_jumi/files/css/NexaBold-Regular.css?v=1.5.0" rel="stylesheet" type="text/css" id="nexabold-css">
	<link href="/components/com_jumi/files/css/NexaLight-Regular.css?v=1.5.0" rel="stylesheet" type="text/css" id="nexalight-css">
	
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- <link rel="shortcut icon" type="image/x-icon" href="docs/images/favicon.ico" /> -->

	<link rel="stylesheet" href="/components/com_jumi/files/leaflet/leaflet.css" integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ==" crossorigin=""/>
	<script src="/components/com_jumi/files/leaflet/leaflet.js"></script>   
	<script src="/components/com_jumi/files/leaflet/shapefile/shp.js"></script>
    <script src="/components/com_jumi/files/leaflet/shapefile/leaflet.shpfile.js"></script>
	<script src="https://teastman.github.io/Leaflet.pattern/leaflet.pattern.js"></script>
	<script src="https://harrywood.co.uk/maps/examples/leaflet/leaflet-plugins/layer/vector/KML.js"></script>
	<script src="https://rawgithub.com/ismyrnow/Leaflet.groupedlayercontrol/master/src/leaflet.groupedlayercontrol.js"></script>
	
	
	
	  <link rel="stylesheet" href="/libraries/leaflet/css/Leaflet.PolylineMeasure.css" />
      <script src="/libraries/leaflet/js/Leaflet.PolylineMeasure.js"></script>
	  
	  <link  rel="stylesheet" href="/components/com_jumi/files/leaflet/draw/leaflet.draw-src.css" />
	   <script src="/components/com_jumi/files/leaflet/draw/leaflet.draw-src.js"></script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>

	function nosubscription(){ 
			 swal("Desculpe!", "Você não tem uma subscrição ativa! Visite nossa página de planos e adquira o plano que melhor lhe atende.", "error");
	}
	
	function shortsubsups(){ 
			 swal("Desculpe!", "Você tem uma subscrição que não lhe permite adicionar novas unidades produtivas ! Visite nossa página de planos e adquira o plano que melhor lhe atende.", "error");
	}
	
	function shortsubsdoc(){ 
			 swal("Desculpe!", "Você chegou no limite de documentos permitidos pela sua subscrição e isso não lhe permite adicionar novos documentos! Visite nossa página de planos e adquira o plano que melhor lhe atende para continuar constituindo seu acervo documental.", "error");
	}
	
	
</script>


	
<style>
@media screen {
		#map {
			width: 100%;
			height: 400px;
		}
   
    header#header {
        visibility: hidden;
        height: 0px;
        margin-top: 0px;
    }
    
	#maininner {
        width: 100%;
        margin-top: -50px;
    }
    
	h1.title {
        visibility: hidden;
        height: 0px;
    }
    
	.isblog #system .item {
        padding: 1px; 
        border: 0px solid #ddd; 
        border-bottom-color: #bbb; 
        border-radius: 6px; 
        box-shadow: 0 0px 0px 0px rgba(0, 0, 0, 0.1);
    }
	
	.button{
    background-color: #21ba45;
    color: #fff;
    text-shadow: none;
    background-image: none;
	cursor: pointer;
    display: inline-block;
    min-height: 60px;
    outline: 0;
    border: none;
    vertical-align: baseline;
    font-family: 'NexaBold-Regular';
    margin: 0 .25em 0 0;
    padding: .78571429em 1.5em .78571429em;
    text-transform: none;
    text-shadow: none;
    font-weight: 700;
    line-height: 1em;
    font-style: normal;
    text-align: center;
    text-decoration: none;
    border-radius: 4.00000006px;
    -webkit-box-shadow: 0 0 0 1px transparent inset, 0 0 0 0 rgba(34,36,38,.15) inset;
    box-shadow: 0 0 0 1px transparent inset, 0 0 0 0 rgba(34,36,38,.15) inset;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    -webkit-transition: opacity .1s ease,background-color .1s ease,color .1s ease,background .1s ease,-webkit-box-shadow .1s ease;
    transition: opacity .1s ease,background-color .1s ease,color .1s ease,background .1s ease,-webkit-box-shadow .1s ease;
    transition: opacity .1s ease,background-color .1s ease,color .1s ease,box-shadow .1s ease,background .1s ease;
    transition: opacity .1s ease,background-color .1s ease,color .1s ease,box-shadow .1s ease,background .1s ease,-webkit-box-shadow .1s ease;
    will-change: '';
    -webkit-tap-highlight-color: transparent;
	}
	
	.leaflet-control-layers-expanded .leaflet-control-layers-list {
    display: block;
    position: relative;
    text-align: left;
	}

}	
	
@media print {

	.tm-header, .uk-visible@m{
		display:none;
	}

	#map {
		width: 100px;
		height: 100px;
	}

	.tm-footer, .uk-section-muted, .uk-section{
		display:none;
	}

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
if ($usr_id==0){
	
		#header('Location: /index.php/socialplaas');
	}

$b64=filter_input(INPUT_GET, 'cod', FILTER_DEFAULT);
$upid=explode(":", base64_decode(filter_input(INPUT_GET, 'cod', FILTER_DEFAULT)));

echo '<div class="row" style="margin-top:-15px;">';

echo '<div class="column column-22 cor11b">
					<div style="text-align:center; padding-top:10px;"> 
					<span class="font-nexa-bold" style="color:#9fa29f;font-size:40px;">
					<!-- img src="/images/technofarm/technofarm_symbol.svg" width="80px" ><BR/-->
					
					'.utf8_encode($upid[1]).'</span></div>
					</div>';
echo '<div class="column column-2 cor11b">
					
					<img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=http://www.plaas.com.br/index.php/folder-resumo?cod='.$b64.'" width="80px" ><BR/>';
					
					
					
					
echo '</div>';

$host = 'localhost';
$rootid = '<id de usuário de banco de dados>';
$password = '<senha de acesso ao banco>';
$db = "<banco de dados>";

$mysqli1 = mysqli_connect($host, $rootid, $password, $db);

	$query0= "SELECT * FROM get_activesubscribers WHERE user_id=".$usr_id.";";
	$stmt0 = $mysqli1->query($query0);


	foreach ($stmt0 as $row0) {
		$ups = $row0['bizobj_ups'];
		$doc = $row0['bizobj_doc'];
		$car = $row0['bizobj_car'];
		$geo = $row0['bizobj_geo'];
	}





$query1 = "SELECT * 
			FROM <banco de dados>.tchnfrm_0001unidadesprodutivas
			WHERE PK0001UnidadeProdutiva=".$upid[0]." limit 1;";

$stmt1 = $mysqli1->query($query1);


echo '<div class="row" style="margin-top:-15px;">';

echo '<div id="accordion" class="column column-1 cor11b" style="z-index:1;"></div>';

echo '</div>';
	echo '<div id="body" class="column column-24 cor11b">';
		echo '<div class="row" id="1" style="text-align:center;  border: 1px solid #9fa29f; margin-bottom:10px; padding:10px;z-index:1000;">';
				echo "<div id='2' class='column column-24 cor11b' style='text-align:center;'>";
					echo "<div id='map' style='width: 100%; height: 600px; position: relative;' class='leaflet-container leaflet-touch leaflet-retina leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom' tabindex='0'></div>";
				echo "</div id='2'>";
		
		
				
		#UNIDADES PRODUTIVAS
		foreach ($stmt1 as $row1) {			
			$lat=$row1['strlatcent'];
			$lng=$row1['strlngcent'];
			
			$latsede=$row1['strlatsede'];
			$lngsede=$row1['strlngsede'];
			$strmun = $row1['strmunicipio'];
		}


				echo '<div class="row">
					<div class="column column-24 cor11b">
						<div style="text-align:center; padding-top:10px;"> 
							<span class="font-nexa-bold" style="color:#9fa29f;font-size:30px;">
								Informações da Unidade
							</span>
						</div>
					</div>
				 </div>';		
		

	
	
		$query2 = "SELECT  * FROM tchnfrm_0002shpuploads
				   where fk0001Unidadeprodutiva =".$upid[0]."  
				   order by created DESC limit 1;";

		$stmt2 = $mysqli1->query($query2);

		foreach ($stmt2 as $row2) {			
		
			$ext=substr($row2['strarquivoshp'], -3);
			
			if (strtolower($ext)=='zip'){
			
				$shapefile=$row2['strarquivoshp'];
				$loadup="var shpfile2 = new L.Shapefile('/shpuploads/$shapefile', 
									{
									style: myazul,
									onEachFeature: function(feature, layer) {
											if (feature.properties) {
													layer.bindPopup(Object.keys(feature.properties).map(function(k) {
														console.log(Object.keys(feature.properties));
														console.log(feature.properties[k]);
													return k + ': ' + feature.properties[k];
													}).join('<br />'), {maxHeight: 200});
												}
										}
									}
								)
				
						shpfile2.addTo(fazenda);";
			}
			else if (strtolower($ext)=='kml'){
				$kmlfile=$row2['strarquivoshp'];
				
				$loadup="var kmlLayer = new L.KML('/shpuploads/$kmlfile', {async: true});
                                                              
				kmlLayer.on('loaded', function(e) { 
						map.fitBounds(e.target.getBounds());
						kmllayer.bindPopup(Object.keys(feature.properties).map(function(k) {
													return k + ': ' + feature.properties[k];
													}).join('<br />'), {
													maxHeight: 200
													});
							});
                                                
						kmlLayer.addTo(fazenda);";
				
			}			
		}
		
		include 'inc_up_basicdetails.php';
		
		//GET SHAPES MUNICIPAIS
		
		$query3 = "SELECT PK0006shapesmunicipais, strshape, struuid,
							strdescricao, ad0003_layercode, 
							ad0003layershortcode, ad0003_layerhex, 
							ad0003_layerweight, ad0003_layeropacity, 
							ad0003_layerdash, ad0003layerstroke, 
							ad0003layerhatchangle
					FROM get_shapesmunicipais
					WHERE strextension='zip'
					and strmunicipio =$strmun";
		# echo $query3;

		$stmt3 = $mysqli1->query($query3);

		$shapelayers="";
		
		$overlays="";
		$layeradds="";
		
		foreach ($stmt3 as $row3) {		
				$pk0006shapesmunicipais=$row3['PK0006shapesmunicipais'];
				$strshape=$row3['struuid'];
				$strdescricao=UTF8_ENCODE($row3['strdescricao']); 
				$ad0003_layercode=$row3['ad0003_layercode'];
				$ad0003layershortcode=$row3['ad0003layershortcode'];
				$ad0003_layerhex=$row3['ad0003_layerhex'];
				$ad0003_layerweight=$row3['ad0003_layerweight'];
				$ad0003_layeropacity=$row3['ad0003_layeropacity'];
				$ad0003_layerdash=$row3['ad0003_layerdash'];
				$ad0003layerstroke=$row3['ad0003layerstroke'];
				$ad0003layerhatchangle=$row3['ad0003layerhatchangle'];

				$layershortref=$ad0003layershortcode.$pk0006shapesmunicipais;
				
				$overlays='"'.$strdescricao.'":'.$layershortref.",
				".$overlays;
				
				$layeradds="map.addLayer($layershortref);
				".$layeradds;
				
				$shapelayers.="//ADD $strdescricao
								{
								$layershortref = L.layerGroup();
								
								";
							
				if ($ad0003layerhatchangle>0)
								{ 
									$shapelayers.="var ".$layershortref."stripes = new L.StripePattern({color: '#$ad0003_layerhex', angle: 15}); ".$layershortref."stripes.addTo(map);";
								}
								
								
									
				$shapelayers.="var shpfile$pk0006shapesmunicipais = new L.Shapefile('/shpmunicipais/$strshape',
				
									{fillOpacity: $ad0003_layeropacity, stroke: $ad0003layerstroke, color:'#$ad0003_layerhex',weight: $ad0003_layerweight,";

									
				if ($ad0003layerhatchangle>0)
								{ 
									$shapelayers.="fillPattern:".$layershortref."stripes,";
								}
								
									
									
				$shapelayers.="dashArray:'$ad0003_layerdash',
										onEachFeature: function(feature, layer) {
											if (feature.properties) {
												layer.bindPopup(Object.keys(feature.properties).map(function(k) {
													return k + ': ' + feature.properties[k];
														}).join('<br />'), 
															{
															maxHeight: 200
															});
											}}});
								shpfile$pk0006shapesmunicipais.addTo($layershortref);
								}  
							//END ADD $strdescricao 
							
							";
				
		}
		

		
		
		
		
		
		
		
	
		#DOCUMENTAÇÂO
		
		include 'inc_up_docheader_ro.php';
	
				 
		$query4 = "SELECT 
						cats.id as catid, cats.alias as catalias, 
						docs.title, '0-nenhum' as downlink, 
						usrs.name, docs.created_time, cats.fk0001unidadeprodutiva
						from tchnfrm_edocman_categories cats 
					left join tchnfrm_edocman_document_category link on cats.id=link.category_id
					left join <banco de dados>.tchnfrm_edocman_documents docs  on docs.id=link.document_id
					left join tchnfrm_users usrs on docs.created_user_id=usrs.id
					where cats.fk0001unidadeprodutiva=".$upid[0].";";

		$stmt4 = $mysqli1->query($query4);
		$doccount=0;
		$catlink="";
		
		foreach ($stmt4 as $row4) {		
			$doccount+=1;
		if ($catlink==""){	
			$catlink=$row4['catid']."-".$row4['catalias'];
			}
			include 'inc_up_docdetail_ro.php';
		}
				 
		# ÁREAS
		
		#include 'inc_up_areasheader.php';
		
		#$query5 = "SELECT * from tchnfrm_0003upareas where fk0001unidadeprodutiva=".$upid[0]." order by created DESC LIMIT 1;";

		#$stmt5 = $mysqli1->query($query5);
	
		#foreach ($stmt5 as $row5) {		
		
		#}
		
		#include 'inc_up_areasdetail.php';
	
		
		include 'inc_up_ativosheader.php';
		
		
		$query6 = "SELECT * from tchnfrm_0005ativosdefazenda where fk0001unidadeprodutiva=".$upid[0]." order by ad0005nome asc";

		$stmt6 = $mysqli1->query($query6);
	
		$markers="";
	
		foreach ($stmt6 as $row6) {	
			include 'inc_up_ativosdetail.php';	
			
			$mylat=$row6['ad0005lat'];
			$mylng=$row6['ad0005lng'];
			$mynome=utf8_encode($row6['ad0005nome']);
			$myfoto=$row6['ad0005imageurl'];
			$markers.="L.marker([$mylat, $mylng]).addTo(fazenda).bindPopup('<b>$mynome</b><br><img src=/farm_assets/$myfoto>').openPopup();";
		
		}
		
		#echo $markers;
		
		
		
		
		
		echo '</div id="1">';

	echo '</div id="body" >';
	

echo '</div>';


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
				
			//map.attributionControl.setPrefix('Maps by PLAAS Geoserver'); // Don't show the 'Powered by Leaflet' text.
         
			var map = L.map('map', {
				center: [<?PHP echo $lat; ?>, <?PHP echo $lng; ?>],
				zoom: 12,
				layers: [esri], 
				drawControl: true
			});

			var baseLayers = {
				"OpenStreetMap": osmap,
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



map.on('draw:created', function(e) {
  var type = e.layerType,
    layer = e.layer;

  if (type === 'marker') {
	map.addLayer(layer);
	//var newMarker = new L.marker(e.latlng).addTo(map);
	map.on('click', function(e)  
		{
		tempLatitude = e.latlng.lat;
		tempLongitude = e.latlng.lng;
		console.log(e.latlng.lat + ', ' + e.latlng.lng)
		layer.bindPopup(e.latlng.lat + ', ' + e.latlng.lng);
		//var newMarker = new L.marker(e.latlng).addTo(map);
		//console.log(map.mouseEventToLatLng(ev.originalEvent));
		//var newMarker = new L.marker(e.latlng).addTo(map);
		//var latlng = map.mouseEventToLatLng(ev.originalEvent);
		//console.log(latlng.lat + ', ' + latlng.lng);
		//layer.bindPopup(latlng.lat + ', ' + latlng.lng);


	});
  }
   if (type === 'polygon') {
      map.addLayer(layer);
	  var seeArea = L.GeometryUtil.geodesicArea(layer.getLatLngs()[0]);
	  layer.bindPopup(((seeArea/10000).toFixed(4)).toLocaleString()+' ha');	
    }



  editableLayers.addLayer(layer);
});
		
		  
		  function addShapes() {
			  
				var myazul = {"color": "#370589","weight": 1,"opacity": 0.85};
				
				
				<?echo $shapelayers;?>
				
						
					var PlaasIcon = L.icon({
						iconUrl: 'http://www.plaas.com.br/images/plaas/plaasmarker.svg',
						//shadowUrl: 'leaf-shadow.png',

						iconSize:     [36, 56], // size of the icon
						// shadowSize:   [50, 64], // size of the shadow
						iconAnchor:   [18, 56], // point of the icon which will correspond to marker's location
						//shadowAnchor: [4, 62],  // the same for the shadow
						popupAnchor:  [0, 3] // point from which the popup should open relative to the iconAnchor
						});		
			
					fazenda = L.layerGroup();

				
					//var kmlLayer = new L.KML("/shpuploads/20190322171001_reunidas-brasnorte3.kml", {async: true});  
					//kmlLayer.on("loaded", function(e) {map.fitBounds(e.target.getBounds());	});
					
					L.marker([<?PHP echo $lat; ?>, <?PHP echo $lng; ?>],{icon: PlaasIcon}).addTo(fazenda).bindPopup('<b><?php
/**
* @copyright (c) 2019 Ayeda Inovação - Todos os direitos reservados 

* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author Ricardo Carvalho <carvalhorj@gmail.com>
*
* Todo o código incluido neste arquivo está distribuído sob a licença GNU GPL v2 ou superiores* Mais informações em http://www.ayeda.com.br/index.php/sobre-a-ayeda/uso-de-codigo-livre
*/ echo utf8_encode($upid[1]); ?></b>').openPopup();                
					
				
					<?php
/**
* @copyright (c) 2019 Ayeda Inovação - Todos os direitos reservados 

* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author Ricardo Carvalho <carvalhorj@gmail.com>
*
* Todo o código incluido neste arquivo está distribuído sob a licença GNU GPL v2 ou superiores* Mais informações em http://www.ayeda.com.br/index.php/sobre-a-ayeda/uso-de-codigo-livre
*/ echo $loadup;?>
					
					var ativos;
					<?php
/**
* @copyright (c) 2019 Ayeda Inovação - Todos os direitos reservados 

* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author Ricardo Carvalho <carvalhorj@gmail.com>
*
* Todo o código incluido neste arquivo está distribuído sob a licença GNU GPL v2 ou superiores* Mais informações em http://www.ayeda.com.br/index.php/sobre-a-ayeda/uso-de-codigo-livre
*/ echo $markers;?>
					
					map.addLayer(fazenda);
					fazenda.on("loaded", function(e) {map.fitBounds(e.target.getBounds());	});
			
					var groupedOverlays = {
						"<strong>Unidade Produtiva</strong>": {
							"<?php
/**
* @copyright (c) 2019 Ayeda Inovação - Todos os direitos reservados 

* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author Ricardo Carvalho <carvalhorj@gmail.com>
*
* Todo o código incluido neste arquivo está distribuído sob a licença GNU GPL v2 ou superiores* Mais informações em http://www.ayeda.com.br/index.php/sobre-a-ayeda/uso-de-codigo-livre
*/ echo utf8_encode($upid[1]); ?>": fazenda	
						},
						"<br/><strong>Bases de Referência</strong>": {
							<?echo $overlays;?>
						}
					};

					// Use the custom grouped layer control, not "L.control.layers"
					map.removeControl(baseControl);
					fazendaControl = L.control.groupedLayers(baseLayers, groupedOverlays).addTo(map);
	
				}
				
</script>

<style>
	
ul.uk-subnav {
    display: none;
}

</style>