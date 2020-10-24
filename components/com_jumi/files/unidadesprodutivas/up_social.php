	<script type="text/javascript" src="/components/com_jumi/files/js/jquery.1.8.2.min.js"></script>

	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="https://code.highcharts.com/highcharts-more.js"></script>
	<script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
	<script type="text/javascript" src="/components/com_jumi/files/hcthemes/ogmt_branco.js"></script>  

	<!-- link href="/components/com_jumi/files/fonts/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" >
	<link href="/components/com_jumi/files/fonts/Icon-font-7-stroke-1.2.0/pe-icon-7-stroke/css/pe-icon-7-stroke.css" rel="stylesheet" >
	<link href="/components/com_jumi/files/fonts/Icon-font-7-stroke-1.2.0/pe-icon-7-stroke/css/helper.css" rel="stylesheet" >
	<link href="/components/com_jumi/files/linearicons/icon-font.min.css" rel="stylesheet" >

	<link href="/components/com_jumi/files/css/ip-custom.css?v=1.5.0" rel="stylesheet" type="text/css" id="ip-custom-css">
	<link href="/components/com_jumi/files/css/ip-responsive.css?v=1.5.0" rel="stylesheet" type="text/css" id="ip-responsive-css">

	<!-- link href="https://ismyrnow.github.io/leaflet-groupedlayercontrol/src/leaflet.groupedlayercontrol.css" rel="stylesheet" type="text/css" -->
	
	



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
	
	<!-- script src="https://ismyrnow.github.io/leaflet-groupedlayercontrol/src/leaflet.groupedlayercontrol.js"></script -->
	<!-- view-source:http://ismyrnow.github.io/leaflet-groupedlayercontrol/example/advanced.html -->
	
	
	  <link rel="stylesheet" href="/libraries/leaflet/css/Leaflet.PolylineMeasure.css" />
      <script src="/libraries/leaflet/js/Leaflet.PolylineMeasure.js"></script>
	  
	  <link  rel="stylesheet" href="/components/com_jumi/files/leaflet/draw/leaflet.draw-src.css" />
	   <script src="/components/com_jumi/files/leaflet/draw/leaflet.draw-src.js"></script>




	
<style>
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
	
</style>



<?php
/**
* @copyright (c) 2019 Ayeda Inovação - Todos os direitos reservados 

* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author Ricardo Carvalho <carvalhorj@gmail.com>
*
* Todo o código incluido neste arquivo está distribuído sob a licença GNU GPL v2 ou superiores
* Mais informações em http://www.ayeda.com.br/index.php/sobre-a-ayeda/uso-de-codigo-livre
*/


echo '<div class="row" style="margin-top:-15px;">';


$host = 'localhost';
$rootid = 'root';
$password = '';
$db = 'plaas1707';

$mysqli1 = mysqli_connect($host, $rootid, $password, $db);

$query1 = "SELECT * 
			FROM tchnfrm_0001unidadesprodutivas 
			WHERE PK0001UnidadeProdutiva = 18";

$stmt1 = $mysqli1->query($query1);


echo '<div class="row" style="margin-top:-15px;">';

echo '<div id="accordion" class="column column-1" style="z-index:1;"></div>';

echo '</div>';
	echo '<div id="body" class="column column-24 ">';
		echo '<div class="row" id="1" style="text-align:center;  border: 1px solid #9fa29f; margin-bottom:10px; padding:10px;z-index:1000;">';
				echo "<div id='2' class='column column-24 ' style='text-align:center;'>";
					echo "<div id='map' style='width: 100%; height: 600px; position: relative;' class='leaflet-container leaflet-touch leaflet-retina leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom' tabindex='0'></div>";
				echo "</div id='2'>";
				
				echo "Esta é uma propriedade de exemplo e não tem nenhuma relação com a realidade fundiária da região onde está implantada.";
		
		
				
		#UNIDADES PRODUTIVAS
		foreach ($stmt1 as $row1) {			
			$lat=$row1['strlatcent'];
			$lng=$row1['strlngcent'];
			
			$latsede=$row1['strlatsede'];
			$lngsede=$row1['strlngsede'];
			$strmun = $row1['strmunicipio'];
		}

	
			
		

	
	
		$query2 = "SELECT  * FROM tchnfrm_0002shpuploads
				   where fk0001Unidadeprodutiva =18  
				   order by created DESC limit 1;";

		$stmt2 = $mysqli1->query($query2);

		foreach ($stmt2 as $row2) {			
				
			$ext=substr($row2['strarquivoshp'], -3);
			
			if (strtolower($ext)=='zip'){
				$shapefile=$row2['strarquivoshp'];
			}
			else if (strtolower($ext)=='kml'){
				$kmlfile=$row2['strarquivoshp'];
			}	
		
		}
		

		
		//GET SHAPES MUNICIPAIS
		
		$query3 = "SELECT PK0006shapesmunicipais, strshape, struuid, 
							strdescricao, ad0003_layercode, 
							ad0003layershortcode, ad0003_layerhex, 
							ad0003_layerweight, ad0003_layeropacity, 
							ad0003_layerdash, ad0003layerstroke, 
							ad0003layerhatchangle
					FROM get_shapesmunicipais
					WHERE strmunicipio =$strmun";

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
				
				$shapelayers.="
				//ADD 
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
												layer.bindPopup('<img src=/images/plaas/logo.21.4.color.svg style=width:90px;><br/>'+Object.keys(feature.properties).map(function(k) {
													return k + ': ' + feature.properties[k];
														}).join('<br />'), 
															{
															maxHeight: 200
															});
											}}});
								shpfile$pk0006shapesmunicipais.addTo($layershortref);
								}  
							//END ADD 
							
							
							
							";
				
		}
		
		#ATIVOS DE FAZENDA
		
		$query6 = "SELECT * from tchnfrm_0005ativosdefazenda where fk0001unidadeprodutiva=18 order by ad0005nome asc";

		$stmt6 = $mysqli1->query($query6);
	
		$markers="";
	
		foreach ($stmt6 as $row6) {
			
			$mylat=$row6['ad0005lat'];
			$mylng=$row6['ad0005lng'];
			$mynome=utf8_encode($row6['ad0005nome']);
			$myfoto=$row6['ad0005imageurl'];
			$markers.="L.marker([$mylat, $mylng]).addTo(kmlLayer).bindPopup('<b>$mynome</b><br><img src=/farm_assets/$myfoto>').openPopup();
			";
		
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
				//center: [<?PHP echo $lat; ?>, <?PHP echo $lng; ?>],
				zoom: 8,
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


				
				// TEST ADD KML
				
				var kmlLayer = new L.KML("/shpuploads/20190322171001_reunidas-brasnorte3.kml", {async: true});
                                                              
		
				kmlLayer.on('loaded', function(e) { 
						map.fitBounds(e.target.getBounds());
						kmllayer.bindPopup('<img src=/images/plaas/logo.21.4.color.svg style=width:90px;><br/>'+Object.keys(feature.properties).map(function(k) {
													return k + ': ' + feature.properties[k];
													}).join('<br />'), {
													maxHeight: 200
													});
							});
				
				
				
				
				
                L.marker([-12.208695, -57.967304],{icon: PlaasIcon}).addTo(kmlLayer).bindPopup('<img src=/images/plaas/logo.21.4.color.svg style=width:90px;><br/><b>Fazendas Reunidas Luso Brasileiras</b><br/>Proprietário: <span style="filter: blur(3px);">Ricardo Carvalho</span><br/><a href="http://www.plaas.com.br/index.php/jomsocial/999:ricardo-carvalho/profile">Entre em contato com proprietário no PLAAS</a><br/><a href="http://www.plaas.com.br/index.php/folder-resumo?cod=MTg6RmF6ZW5kYXMgUmV1bmlkYXMgTHVzby1CcmFzaWxlaXJhcw==" target="_up">Acesse o perfil da propriedade no PLAAS</a><br/>Município de Brasnorte<br/>Área: 2.212 Ha').openPopup();
			                      
				map.addLayer(kmlLayer);
				
				
				


				var ativos;
				<?php
/**
* @copyright (c) 2019 Ayeda Inovação - Todos os direitos reservados 

* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author Ricardo Carvalho <carvalhorj@gmail.com>
*
* Todo o código incluido neste arquivo está distribuído sob a licença GNU GPL v2 ou superiores* Mais informações em http://www.ayeda.com.br/index.php/sobre-a-ayeda/uso-de-codigo-livre
*/ echo $markers;?>
			
				//// TEST ADD KML 
	
				// Overlay layers are grouped
					var groupedOverlays = {
						"<strong>Unidade Produtiva</strong>": {
							"Luso Brasileiras": kmlLayer	
						},
						"<br/><strong>Bases de Referência</strong>": {
							<?echo $overlays;?>
						}
					};

				// Use the custom grouped layer control, not "L.control.layers"
				map.removeControl(baseControl);
				fazendaControl = L.control.groupedLayers(baseLayers, groupedOverlays).addTo(map);
	
	
	
	
	
	
				}
			
	function sleep(milliseconds) {
		var start = new Date().getTime();
			for (var i = 0; i < 1e7; i++) {
				if ((new Date().getTime() - start) > milliseconds){
					break;
				}
			}
	}




			
				
</script>






