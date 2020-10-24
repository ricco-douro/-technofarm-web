<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <link rel="stylesheet" href="/libraries/leaflet/css/semantic.css">
    <link rel="stylesheet" href="/libraries/leaflet/css/filepicker.css">
    <link rel="stylesheet" href="/libraries/leaflet/css/dropzone.min.css">
    <link rel="stylesheet" href="/libraries/leaflet/css/selectize.css">
    <link rel="stylesheet" href="/libraries/leaflet/css/leaflet.css">
	<link rel="stylesheet" href="/libraries/leaflet/css/leaflet.draw.css">

	<link rel="stylesheet" href="/libraries/leaflet/css/L.EasyButton.css">
    <link rel="stylesheet" href="/libraries/leaflet/css/L.SimpleGraticule.css">
    <link rel="stylesheet" href="/libraries/leaflet/css/L.Control.Zoominfo.css">
    <link rel="stylesheet" href="/libraries/leaflet/css/leaflet-measure.css">
    <link rel="stylesheet" href="/libraries/leaflet/css/leaflet.awesome-markers.css">
    <link rel="stylesheet" href="/libraries/leaflet/css/Leaflet.Photo.css">
    <link rel="stylesheet" href="/libraries/leaflet/css/MarkerCluster.css">
    <link rel="stylesheet" href="/libraries/leaflet/css/MarkerCluster.Default.css">
    <link rel="stylesheet" href="/libraries/leaflet/css/site.css">
	
	<script src="/libraries/leaflet/js/leaflet-src.js"></script>
    <script src="/libraries/leaflet/js/Leaflet.draw.js"></script>
    <script src="/libraries/leaflet/js/Leaflet.Draw.Event.js"></script>
    <script src="/libraries/leaflet/js/Toolbar.js"></script>
    <script src="/libraries/leaflet/js/Tooltip.js"></script>
    <script src="/libraries/leaflet/js/GeometryUtil.js"></script>
    <script src="/libraries/leaflet/js/LatLngUtil.js"></script>
    <script src="/libraries/leaflet/js/LineUtil.Intersect.js"></script>
    <script src="/libraries/leaflet/js/Polygon.Intersect.js"></script>
    <script src="/libraries/leaflet/js/Polyline.Intersect.js"></script>
    <script src="/libraries/leaflet/js/TouchEvents.js"></script>
    <script src="/libraries/leaflet/js/DrawToolbar.js"></script>
    <script src="/libraries/leaflet/js/Draw.Feature.js"></script>
    <script src="/libraries/leaflet/js/Draw.SimpleShape.js"></script>
    <script src="/libraries/leaflet/js/Draw.Polyline.js"></script>
    <script src="/libraries/leaflet/js/Draw.Marker.js"></script>
    <script src="/libraries/leaflet/js/Draw.Circle.js"></script>
    <script src="/libraries/leaflet/js/Draw.CircleMarker.js"></script>
    <script src="/libraries/leaflet/js/Draw.Polygon.js"></script>
    <script src="/libraries/leaflet/js/Draw.Rectangle.js"></script>
    <script src="/libraries/leaflet/js/EditToolbar.js"></script>
    <script src="/libraries/leaflet/js/EditToolbar.Edit.js"></script>
    <script src="/libraries/leaflet/js/EditToolbar.Delete.js"></script>
    <script src="/libraries/leaflet/js/Control.Draw.js"></script>
    <script src="/libraries/leaflet/js/Edit.Poly.js"></script>
    <script src="/libraries/leaflet/js/Edit.SimpleShape.js"></script>
    <script src="/libraries/leaflet/js/Edit.Rectangle.js"></script>
    <script src="/libraries/leaflet/js/Edit.Marker.js"></script>
    <script src="/libraries/leaflet/js/Edit.CircleMarker.js"></script>
    <script src="/libraries/leaflet/js/Edit.Circle.js"></script>
	<script src="/libraries/leaflet/js/jquery-3.1.1.min.js"></script>
    <script src="/libraries/leaflet/js/jquery.mask.js"></script>
    <script src="/libraries/leaflet/js/jquery.signalR-2.2.1.min.js"></script>
    <script src="/libraries/leaflet/js/semantic.js"></script>
    <script src="/libraries/leaflet/js/selectize.js"></script>
    <script src="/libraries/leaflet/js/leaflet-src.js"></script>
    <script src="/libraries/leaflet/js/L.EasyButton.js"></script>
    <script src="/libraries/leaflet/js/L.SimpleGraticule.js"></script>
    <script src="/libraries/leaflet/js/L.Control.Zoominfo.js"></script>
    <script src="/libraries/leaflet/js/leaflet-measure.min.js"></script>
    <script src="/libraries/leaflet/js/leaflet.awesome-markers.js"></script>     
    <script src="/libraries/leaflet/js/leaflet.markercluster.js"></script>
    <script src="/libraries/leaflet/js/Leaflet.Photo.js"></script>
    <script src="/libraries/leaflet/js/bundle.js"></script>
	
	
	
</head>

<?php
/**
* @copyright (c) 2019 Ayeda Inovação - Todos os direitos reservados 

* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author Ricardo Carvalho <carvalhorj@gmail.com>
*
* Todo o código incluido neste arquivo está distribuído sob a licença GNU GPL v2 ou superiores* Mais informações em http://www.ayeda.com.br/index.php/sobre-a-ayeda/uso-de-codigo-livre
*/

$shp=$_GET["shp"];
$lat=$_GET["lat"];
$lng=$_GET["lng"];
$zom=$_GET["zom"];

if(is_null($lat)) {$lat=-58.3;}
if(is_null($lng)) {$lng=-10.3;}
if(is_null($zom)) {$zom=10;}

?>
<!-- 
http://localhost/leaftlet/default.php?fazvaleverde.zip&zom=12&lat=-58.28&lng=-10.28
https://leaflet-extras.github.io/leaflet-providers/preview/

-->

<html>
  <head>
    <title></title>
    <link rel="stylesheet" href="/libraries/leaflet/core/leaflet.css">
    <script src="/libraries/leaflet/core/leaflet.js"></script>
	
	
    <script src="https://cdn.rawgit.com/calvinmetcalf/shapefile-js/gh-pages/dist/shp.js"></script>
    <script src="https://cdn.rawgit.com/calvinmetcalf/leaflet.shapefile/gh-pages/leaflet.shpfile.js"></script>
    
    <style>
      #map {height: 600px;width: 600px;}
      input {margin-top:10px;}
    </style>
  </head>
  <body>
	<div id="map" style="width: 800px; height: 600px; border: 1px solid rgb(204, 204, 204); position: relative;" class="leaflet-container leaflet-touch leaflet-retina leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom" tabindex="0">
		<div class="leaflet-pane leaflet-map-pane" style="transform: translate3d(0px, 0px, 0px);">
			<div class="leaflet-pane leaflet-tile-pane">
				<div class="leaflet-layer " style="z-index: 1; opacity: 1;">
					<div class="leaflet-tile-container leaflet-zoom-animated" style="z-index: 18; transform: translate3d(0px, 0px, 0px) scale(1);">
						
						</div>]
				</div>
			</div>
			
			<div class="leaflet-pane leaflet-shadow-pane"></div>
			<div class="leaflet-pane leaflet-overlay-pane"></div>
			<div class="leaflet-pane leaflet-marker-pane"></div>
			<div class="leaflet-pane leaflet-tooltip-pane"></div>
			<div class="leaflet-pane leaflet-popup-pane"></div>
			<div class="leaflet-proxy leaflet-zoom-animated"></div>
		</div>
		
		
		<div class="leaflet-control-container">
			<div class="leaflet-top leaflet-left">
				<div class="leaflet-control-zoom leaflet-bar leaflet-control">
					<a class="leaflet-control-zoom-in" href="#" title="Zoom in" role="button" aria-label="Zoom in">+</a>
					<a class="leaflet-control-zoom-out" href="#" title="Zoom out" role="button" aria-label="Zoom out">−</a></div>
					<div class="leaflet-control-layers leaflet-control-layers-expanded leaflet-control" aria-haspopup="true">
					<a class="leaflet-control-layers-toggle" href="#" title="Layers"></a>
						<form class="leaflet-control-layers-list">
							<div class="leaflet-control-layers-base">
								<label>
									<div>
										<input type="radio" class="leaflet-control-layers-selector" name="leaflet-base-layers" checked="checked">
										<span> osm</span>
									</div>
								</label>
								<label>
									<div>
										<input type="radio" class="leaflet-control-layers-selector" name="leaflet-base-layers">
										<span> google</span>
									</div>
								</label>
							</div>
							<div class="leaflet-control-layers-separator"></div>
							<div class="leaflet-control-layers-overlays">
								<label>
									<div>
										<input type="checkbox" class="leaflet-control-layers-selector" checked="">
											<span> drawlayer</span>
									</div>
								</label>
							</div>
						</form>
					</div>
						<div class="leaflet-draw leaflet-control">
						<div class="leaflet-draw-section">
							<div class="leaflet-draw-toolbar leaflet-bar leaflet-draw-toolbar-top">
								<a class="leaflet-draw-draw-polyline" href="#" title="Draw a polyline"><span class="sr-only">Draw a polyline</span></a>
								<a class="leaflet-draw-draw-polygon" href="#" title="Draw a polygon"><span class="sr-only">Draw a polygon</span></a>
								<a class="leaflet-draw-draw-rectangle" href="#" title="Draw a rectangle"><span class="sr-only">Draw a rectangle</span></a>
								<a class="leaflet-draw-draw-circle" href="#" title="Draw a circle"><span class="sr-only">Draw a circle</span></a>
								<a class="leaflet-draw-draw-marker" href="#" title="Draw a marker"><span class="sr-only">Draw a marker</span></a>
								<a class="leaflet-draw-draw-circlemarker" href="#" title="Draw a circlemarker"><span class="sr-only">Draw a circlemarker</span></a>
							</div>
							
							<ul class="leaflet-draw-actions"></ul>
						</div>
						
						<div class="leaflet-draw-section">
							<div class="leaflet-draw-toolbar leaflet-bar">
								<a class="leaflet-draw-edit-edit leaflet-disabled" href="#" title="No layers to edit"><span class="sr-only">Edit layers</span></a>
								<a class="leaflet-draw-edit-remove leaflet-disabled" href="#" title="No layers to delete"><span class="sr-only">Delete layers</span></a>
							</div>
							
							<ul class="leaflet-draw-actions"></ul>
						</div>
					</div>
					</div>
					<div class="leaflet-top leaflet-right"></div>
					<div class="leaflet-bottom leaflet-left"></div>
					<div class="leaflet-bottom leaflet-right">
						</div>
			</div>
	</div>


<script>


var m = L.map('map', {center: [<?php
/**
* @copyright (c) 2019 Ayeda Inovação - Todos os direitos reservados 

* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author Ricardo Carvalho <carvalhorj@gmail.com>
*
* Todo o código incluido neste arquivo está distribuído sob a licença GNU GPL v2 ou superiores* Mais informações em http://www.ayeda.com.br/index.php/sobre-a-ayeda/uso-de-codigo-livre
*/ echo $lng; ?>, <?php
/**
* @copyright (c) 2019 Ayeda Inovação - Todos os direitos reservados 

* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author Ricardo Carvalho <carvalhorj@gmail.com>
*
* Todo o código incluido neste arquivo está distribuído sob a licença GNU GPL v2 ou superiores* Mais informações em http://www.ayeda.com.br/index.php/sobre-a-ayeda/uso-de-codigo-livre
*/ echo $lat; ?>],zoom: 11}, {drawControl: true});


	/*
	//OPENSTREETMAP
	//L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
	//	attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
	//}).addTo(m);	
	
	//WATERCOLOUR
	L.tileLayer('https://stamen-tiles-{s}.a.ssl.fastly.net/watercolor/{z}/{x}/{y}.{ext}', {
		attribution: 'Map tiles by <a href="http://stamen.com">Stamen Design</a>, <a href="http://creativecommons.org/licenses/by/3.0">CC BY 3.0</a> &mdash; Map data &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
		subdomains: 'abcd',
		minZoom: 1,
		maxZoom: 16,
		ext: 'png'
	}).addTo(m);

	//GOOGLE MAPS AT NIGHT
	L.tileLayer('https://map1.vis.earthdata.nasa.gov/wmts-webmerc/VIIRS_CityLights_2012/default/{time}/{tilematrixset}{maxZoom}/{z}/{y}/{x}.{format}', {
		attribution: 'Imagery provided by services from the Global Imagery Browse Services (GIBS), operated by the NASA/GSFC/Earth Science Data and Information System (<a href="https://earthdata.nasa.gov">ESDIS</a>) with funding provided by NASA/HQ.',
		minZoom: 1,
		maxZoom: 8,
		format: 'jpg',
		time: '',
		tilematrixset: 'GoogleMapsCompatible_Level'
	}).addTo(m);
*/

	// ESRI TERRAIN
	L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
		attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
	}).addTo(m);
	

	L.marker([-10.3, -58.3]).addTo(m).bindPopup("<b>Fazenda Vale Verde</b>").openPopup();



		/*
    var shpfile = new L.Shapefile('/shpuploads/20190222095702_fazvaleverde.zip', {
        onEachFeature: function(feature, layer) {
            if (feature.properties) {
                layer.bindPopup(Object.keys(feature.properties).map(function(k) {
                    return k + ": " + feature.properties[k];
                }).join("<br />"), {
                    maxHeight: 200
                });
            }
        }
    });
	
	*/
	var shpfile = new L.Shapefile('ftp://geoftp.ibge.gov.br/cartas_e_mapas/bases_cartograficas_continuas/bc250/versao2017/shapefile/Transporte_v2017.zip', {
        onEachFeature: function(feature, layer) {
            if (feature.properties) {
                layer.bindPopup(Object.keys(feature.properties).map(function(k) {
                    return k + ": " + feature.properties[k];
                }).join("<br />"), {
                    maxHeight: 200
                });
            }
        }
    });
	
	//ftp://geoftp.ibge.gov.br/cartas_e_mapas/bases_cartograficas_continuas/bc250/versao2017/shapefile/Transporte_v2017.zip
	
	
    shpfile.addTo(m);
    shpfile.once("data:loaded", function() {
        console.log("finished loaded shapefile");
    });
     // initialize stylable leaflet control widget
var control = L.control.UniForm(null, overlayMaps, {
        collapsed: false,
        position: 'topright'
    }
);



     // FeatureGroup is to store editable layers
      var drawnItems = new L.FeatureGroup();
      m.addLayer(drawnItems);

      var drawControl = new L.Control.Draw({
          edit: {
              featureGroup: drawnItems
          }
      });
      m.addControl(drawControl);


// add control widget to map and html dom.
control.addTo(m);

L.control.layers({
        'osm': osm.addTo(m),
        "google": L.tileLayer('http://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}', {
            attribution: 'google'
        })
    }, { 'drawlayer': drawnItems }, { position: 'topleft', collapsed: false }).addTo(map);
	
	
	
	
	
	
    map.addControl(new L.Control.Draw({
        edit: {
            featureGroup: drawnItems,
            poly: {
                allowIntersection: false
            }
        },
        draw: {
            polygon: {
                allowIntersection: false,
                showArea: true
            }
        }
    }));


    m.on(L.Draw.Event.CREATED, function (event) {
        var layer = event.layer;

        drawnItems.addLayer(layer);
    });






</script>



<script>
    var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            osmAttrib = '&copy; <a href="http://openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            osm = L.tileLayer(osmUrl, { maxZoom: 18, attribution: osmAttrib }),
            map = new L.Map('map', { center: new L.LatLng(51.505, -0.04), zoom: 13 }),
            drawnItems = L.featureGroup().addTo(map);
   

   
</script>

	
 </body>
 </html>
