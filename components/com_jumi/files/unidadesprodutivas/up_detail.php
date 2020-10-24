	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBD81Pt26QEFq6uBs0Ar-ReZYArkV4bM9E" async defer></script>
	<script src="https://maps.googleapis.com/maps/api/js" async defer></script>
	
	
	<link href="/components/com_jumi/files/fonts/font-awesome-4.7.0/css/font-awesome.css" rel="stylesheet">
	
	<!-- <link href="/components/com_jumi/files/css/ip-custom.css?v=1.5.0" rel="stylesheet" type="text/css" id="ip-custom-css"> -->
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- <link rel="shortcut icon" type="image/x-icon" href="docs/images/favicon.ico" /> -->


<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"
  integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
  crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet-src.js"
  integrity="sha512-+ZaXMZ7sjFMiCigvm8WjllFy6g3aou3+GZngAtugLzrmPFKFK7yjSri0XnElvCTu/PrifAYQuxZTybAEkA8VOA=="
  crossorigin=""></script>
  
  	<script src="https://www.plaas.com.br/components/com_jumi/files/leaflet/shapefile/shp.js"></script>
    <script src="https://www.plaas.com.br//components/com_jumi/files/leaflet/shapefile/leaflet.shpfile.js"></script>
	<script src="https://teastman.github.io/Leaflet.pattern/leaflet.pattern.js"></script>
	<script src="https://harrywood.co.uk/maps/examples/leaflet/leaflet-plugins/layer/vector/KML.js"></script>
	<script src="https://rawgithub.com/ismyrnow/Leaflet.groupedlayercontrol/master/src/leaflet.groupedlayercontrol.js"></script>
  

	<script type="text/javascript" src="https://ivansanchez.gitlab.io/Leaflet.GridLayer.GoogleMutant/Leaflet.GoogleMutant.js"></script>
	
	
	
	  <link rel="stylesheet" href="/libraries/leaflet/css/Leaflet.PolylineMeasure.css" />
      <script src="/libraries/leaflet/js/Leaflet.PolylineMeasure.js"></script>
	  
	  <link  rel="stylesheet" href="/components/com_jumi/files/leaflet/draw/leaflet.draw-src.css" />
	   <script src="/components/com_jumi/files/leaflet/draw/leaflet.draw-src.js"></script>

<script src='https://unpkg.com/leaflet.gridlayer.googlemutant@latest/Leaflet.GoogleMutant.js'></script>



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

	function myFunction() {
			/* Get the text field */
			var copyText = document.getElementById("myInput");
			var btn = document.getElementById("mybtn");
			copyText.select();
			document.execCommand("copy");
			alert("Copied the text: " + copyText.value);
			btn.innerHTML="Link Copiado!";
}
	
</script>


	
<style>
		/*#map {
			width: 100%;
			height: 400px;
		}
		*/
			#map {
/*    margin: 32px; */
/*    width: auto; */
/*    overflow: visible; */
		width: calc( 80vw );
		height: calc( 80vh);
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

	@import url('https://fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap');


	.fundoUnidades1{
		background-color: #CCDC93!important
	}
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
		text-transform:uppercase!important;
	}

	.maiusculosN{
		text-transform: none!important;
	}

	.fontUnidades1{
		font-size: 18px!important;
		line-height: 18px!important;	}

	.fontUnidades2{
		font-size: 16px!important;
		line-height: 16px!important;
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

$user = JFactory::getUser();
$usr_id = $user->get('id');

if ($usr_id==0){
	
		header('Location: /index.php/socialplaas');
	}

$b64=filter_input(INPUT_GET, 'cod', FILTER_DEFAULT);
$upid=explode(":", base64_decode($b64));



echo '
<meta property="og:title" content="' . utf8_encode($upid[1]) . '"/>
<meta property="og:description" content="' . utf8_encode($upid[1]) . '" />
<meta property="og:type" content="article"/>
<meta property="og:email" content="info@plaas.com.br";/>
<meta property="og:url" content="'.str_replace('" ','&quot;',juri::current()).'"="">
<meta property="og:site_name" content="PLAAS Agrotech Ltda"/>
<meta property="og:image" content="http://www.plaas.com.br/android-icon-192x192.png"/>
<meta property="og:image:width" content="192" />
<meta property="og:image:height" content="192" />
';


	echo '<div class="uk-child-width-1-24 uk-text-center uk-padding" uk-grid>';
	    echo '<h2 class="titulosUnidadesFont1 maiusculosN titulosUnidadesCor1">'.utf8_encode($upid[1]).'</h2>';
	echo '</div>';





// echo '<div class="row" style="margin-top:-35px;">';

// echo '<div class="column column-24">
// 					<div style="text-align:center; padding-top:10px;"> 
// 					<span class="font-nexa-bold" style="color:#9fa29f;font-size:40px;">
// 					<!-- img src="/images/technofarm/technofarm_symbol.svg" width="80px" ><BR/-->
// 					'.utf8_encode($upid[1]).'</span></div>
// 					</div>';
// echo '</div>';

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


$query1 = "SELECT 
				PK0001UnidadeProdutiva, user_id, created, modified, strmatriz, strnomecomum, strareatotal, strendereco, strcep, strestado, strlatcent, 
				strlngcent, strlatsede, strlngsede, strcerrado, strpantanal, stramazonia, strcaatinga, stratlantica, strpampa, strorigemdominial, 
				strtituloorigem, estado, strmunicipio, upshare, pk0002municipio, proper(ad0002municipio) as ad0002municipio, ad0002longitude, ad0002latitude, fk0001estado
			FROM <banco de dados>.tchnfrm_0001unidadesprodutivas
			inner join td0002municipios on pk0002municipio = strmunicipio
			WHERE PK0001UnidadeProdutiva=".$upid[0]."
			
			limit 1;";

$stmt1 = $mysqli1->query($query1);



	echo '<div class="uk-child-width-1-24 uk-text-center" uk-grid>';
	    echo "<div id='map' style='width: 100%; height: 600px; position: relative;' class='leaflet-container leaflet-touch leaflet-retina leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom' tabindex='0'></div>";
	echo '</div>';

	echo'
	<div class="row">
		<div class="column column-24">
			<p class="titulosUnidadesFont1 maiusculosN titulosUnidadesCor1"><span style="margin-top:10px;">Link Público:</span>	
			<input id="myInput" size="80" value="http://www.plaas.com.br/index.php/folder-resumo?cod='.$b64.'" style="min-width:650px;margin-top:5px;">
			<button  id="mybtn" onclick="myFunction()" style="padding-top:5px;margin-top:5px;">Clique e copie </button>
			</p>

	    
		<div  class="column column-1">
			
	    </div>

	</div></div>
	';
	

	echo '<div id="body" class="column column-20 ">';
		echo '<div class="row" id="1" style="text-align:center; margin-bottom:10px; padding:10px;z-index:1000;">';
				echo "<div id='2' class='column column-24 ' style='text-align:center;'>";
					echo "</div id='2'>";
		
		
		
		$lat=-13.650257682017168;
		$lng=-55.81933580338955;
		
	
				
		#UNIDADES PRODUTIVAS
		foreach ($stmt1 as $row1) {			
			if($row1['strlatcent']!=0){
				$lat=$row1['strlatcent'];
			}
			else{
				$lat=$row1['ad0002latitude'];
			}
			if($row1['strlngcent']!=0){
			$lng=$row1['strlngcent'];
			}
			else{
				$lng=$row1['ad0002longitude'];
			}
			
			$latsede=$row1['strlatsede'];
			$lngsede=$row1['strlngsede'];
			$strmun = $row1['strmunicipio'];
			$strowner=$row1['user_id'];
			$municipio=utf8_encode($row1['ad0002municipio']);
		}


	echo'
	<div class="uk-grid-collapse uk-text-center" uk-grid>
	    <div class="fundoUnidades1 uk-padding-small">
	         <div class="titulosUnidadesFont1 maiusculosN fontUnidades1 titulosUnidadesCor1">Informações da Unidade</div>
	    </div>
	</div>
	';



/*				echo '<div class="row">
					<div class="column column-24">
						<div style="text-align:center; padding-top:10px;"> 
							<span class="font-nexa-bold" style="color:#9fa29f;font-size:30px;">
								Informações da Unidade
							</span>
						</div>
					</div>
				 </div>';	*/	
		

	
	
		$query2 = "SELECT  * FROM tchnfrm_0002shpuploads
				   where fk0001Unidadeprodutiva =".$upid[0]." 
				   order by created DESC limit 1;";

		$stmt2 = $mysqli1->query($query2);
		
		$up_row_cnt = $stmt2->num_rows;
		
		$loadup="";
		$shp="";
		$ext="";
		$zoom=6;
		if($up_row_cnt>0) {
			$zoom=12;
		foreach ($stmt2 as $row2) {			
		
			$ext=substr($row2['strarquivoshp'], -3);
			$shp=$row2['strarquivoshp'];
			$upname=$upid[1];
			if (strtolower($ext)=='zip'){
			
				$shapefile=$row2['strarquivoshp'];
				$loadup="var shpfile2 = new L.Shapefile('/shpuploads/$shapefile', 
									{
									style: myazul,
									onEachFeature: function(feature, layer) {
											if (feature.properties) {
													layer.bindPopup('<img src=/images/plaas/logo.21.4.color.svg style=width:90px;><br/>'+Object.keys(feature.properties).map(function(k) {
														console.log(Object.keys(feature.properties));
														console.log(feature.properties[k]);
													return k + ': ' + feature.properties[k];
													}).join('<br />'), {maxHeight: 200});
												}
										}
									}
								)
						/*
							shpfile2.on('loaded', function(e) { 
								console.log(e.target.getBounds());
								map.fitBounds(e.target.getBounds());		
							});
						*/
						shpfile2.addTo(fazenda);
						L.marker([$lat, $lng],{icon: PlaasIcon}).addTo(fazenda).bindPopup('<b><?php
/**
* @copyright (c) 2019 Ayeda Inovação - Todos os direitos reservados 

* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author Ricardo Carvalho <carvalhorj@gmail.com>
*
* Todo o código incluido neste arquivo está distribuído sob a licença GNU GPL v2 ou superiores* Mais informações em http://www.ayeda.com.br/index.php/sobre-a-ayeda/uso-de-codigo-livre
*/ echo utf8_encode($upname); ?></b>').openPopup();                

						";
			}
			else if (strtolower($ext)=='kml'){
				$kmlfile=$row2['strarquivoshp'];
				
				$loadup="var kmlLayer = new L.KML('/shpuploads/$kmlfile', {async: true});
                                                              
				kmlLayer.on('loaded', function(e) { 
						map.fitBounds(e.target.getBounds());
						kmllayer.bindPopup('<img src=/images/plaas/logo.21.4.color.svg style=width:90px;><br/>'+Object.keys(feature.properties).map(function(k) {
													return k + ': ' + feature.properties[k];
													}).join('<br />'), {
													maxHeight: 200
													});
							});
							/*
							kmlLayer.on('loaded', function(e) { 
								console.log(e.target.getBounds());
								map.fitBounds(e.target.getBounds());	
								
							});
							*/
						kmlLayer.addTo(fazenda);
						L.marker([$lat, $lng],{icon: PlaasIcon}).addTo(fazenda).bindPopup('<b><?php
/**
* @copyright (c) 2019 Ayeda Inovação - Todos os direitos reservados 

* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author Ricardo Carvalho <carvalhorj@gmail.com>
*
* Todo o código incluido neste arquivo está distribuído sob a licença GNU GPL v2 ou superiores* Mais informações em http://www.ayeda.com.br/index.php/sobre-a-ayeda/uso-de-codigo-livre
*/ echo utf8_encode($upname); ?></b>').openPopup();                

						";
				
			}			
		}
		}
		
		else{
			
			$lat=-13.650257682017168;
			$lng=-55.81933580338955;
			
			$loadup="var shpfile2 = new L.Shapefile('/shpuploads/mt_plaas.zip', 
									{
									style: myplaas,
									onEachFeature: function(feature, layer) {
											if (feature.properties) {
													layer.bindPopup('<img src=/images/plaas/logo.21.4.color.svg style=width:90px;><br/>'+Object.keys(feature.properties).map(function(k) {
														console.log(Object.keys(feature.properties));
														console.log(feature.properties[k]);
													return k + ': ' + feature.properties[k];
													}).join('<br />'), {maxHeight: 200});
												}
										}
									}
								)
						/*
							shpfile2.on('loaded', function(e) { 
								console.log(e.target.getBounds());
								map.fitBounds(e.target.getBounds());		
							});
						*/
						shpfile2.addTo(fazenda);";
			
		}
		
		


		include 'inc_up_basicdetails.php';
		echo '<br/>';
	echo'
	<div class="uk-grid-collapse uk-text-center" uk-grid>
	    <div class="fundoUnidades1 uk-padding-small">
	         <div class="titulosUnidadesFont1 maiusculosN fontUnidades1 titulosUnidadesCor1">Documentação da Unidade</div>
	    </div>
	</div>
	';
	
		
		//GET SHAPES MUNICIPAIS
		
		$query3 = "SELECT pk0003layerstyle, PK0006shapesmunicipais, strshape, struuid,
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
				
				if ($row3['pk0003layerstyle'] ==1){	
						$strdescricao=$municipio; 
				}
				else
				{
						$strdescricao=UTF8_ENCODE($row3['strdescricao']); 
				}
				
				
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
												layer.bindPopup('<img src=/images/plaas/logo.21.4.color.svg style=width:90px;><br/>'+Object.keys(feature.properties).map(function(k) {
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
		
		# LIMITROFES
		
		//GET limitrofes
		
		$query7 = "select 
					PK0006shapesmunicipais, FK0006UserId, strmunicipio, strshape, strdescricao, strfilename, strextension, strcor, struuid, 
					pk0008municipioslimitrofes, fk0002municipio_from, fk0002municipio_to, pk0002municipio, proper(ad0002municipio) as ad0002municipio, 
					fk0001estado, pk0003layerstyle, ad0003_layercode, ad0003layershortcode, ad0003_layerhex, ad0003_layerweight, 
					ad0003_layeropacity, ad0003_layerdash, ad0003layerstroke, ad0003layerhatchangle, ad0003_layerload, ad0003_layercolor
				from tchnfrm_0006shapesmunicipais sm
				inner  join tchnfrm_0008municipioslimitrofes  ml  on sm.strmunicipio= ml.fk0002municipio_to
				inner join td0002municipios m on m.pk0002municipio = ml.fk0002municipio_to 
				inner join <banco de dados>.td0003layerstyles on pk0003layerstyle = 20
				where sm.strcor=1 and fk0002municipio_from=$strmun";
				
		$stmt7 = $mysqli1->query($query7);

		$shapelayers1="";
		
		$overlays1="";
		$layeradds1="";
		
		foreach ($stmt7 as $row7) {		
				$pk0006shapesmunicipais=$row7['PK0006shapesmunicipais'];
				$strshape=$row7['struuid'];
				$strdescricao=utf8_encode($row7['ad0002municipio']); 
				$ad0003_layercode=$row7['ad0003_layercode'];
				$ad0003layershortcode=$row7['ad0003layershortcode'];
				$ad0003_layerhex=$row7['ad0003_layerhex'];
				$ad0003_layerweight=$row7['ad0003_layerweight'];
				$ad0003_layeropacity=$row7['ad0003_layeropacity'];
				$ad0003_layerdash=$row7['ad0003_layerdash'];
				$ad0003layerstroke=$row7['ad0003layerstroke'];
				$ad0003layerhatchangle=$row7['ad0003layerhatchangle'];

				$layershortref1=$ad0003layershortcode.$pk0006shapesmunicipais;
				
				$overlays1.='"'.$strdescricao.'":'.$layershortref1.",".$overlays1;
				
				$layeradds1="map.addLayer($layershortref1);
				".$layeradds1;
				
				$shapelayers1.="//ADD $strdescricao
								{
								$layershortref1 = L.layerGroup();
								
								";
							
				if ($ad0003layerhatchangle>0)
								{ 
									$shapelayers1.="var ".$layershortref1."stripes = new L.StripePattern({color: '#$ad0003_layerhex', angle: 15}); ".$layershortref1."stripes.addTo(map);";
								}
								
								
									
				$shapelayers1.="var shpfile$pk0006shapesmunicipais = new L.Shapefile('/shpmunicipais/$strshape',
				
									{fillOpacity: $ad0003_layeropacity, stroke: $ad0003layerstroke, color:'#$ad0003_layerhex',weight: $ad0003_layerweight,";

									
				if ($ad0003layerhatchangle>0)
								{ 
									$shapelayers1.="fillPattern:".$layershortref1."stripes,";
								}
								
									
									
				$shapelayers1.="dashArray:'$ad0003_layerdash'});
								shpfile$pk0006shapesmunicipais.addTo($layershortref1);
								}  
							//END ADD $strdescricao 
							
							";
				
		}
		

		#LIMITROFES
		
		
		
		
		
		
		
		

		#DOCUMENTAÇÂO
		
		?>
<div class="uk-text-left" uk-grid>
    <div class="uk-input uk-width-1-3">
        <div class="uk-card uk-card-default">
        	
			<label for="strmatriz" class="titulosUnidadesFont1">
				<span class="">Titulo</span>
				
			</label>	        	

        </div>
    </div>
    <div class="uk-input uk-width-1-4">
        <div class="uk-card uk-card-default">
			
			<label for="strnomecomum" class="titulosUnidadesFont1">
				<span class="">Subido em</span>
			</label>	        	

        </div>
    </div>
	    <div class="uk-input uk-width-1-4">
        <div class="uk-card uk-card-default">
			
			<label for="strnomecomum" class="titulosUnidadesFont1">
				<span class="">Subido por</span>
			</label>	        	

        </div>
    </div>
	    <div class="uk-input uk-width-1-6">
        <div class="uk-card uk-card-default">
			
			<label for="strnomecomum" class="titulosUnidadesFont1">
				<span class="">Ações</span>
			</label>	        	

        </div>
    </div>
	
	
</div>
<?php
/**
* @copyright (c) 2019 Ayeda Inovação - Todos os direitos reservados 

* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author Ricardo Carvalho <carvalhorj@gmail.com>
*
* Todo o código incluido neste arquivo está distribuído sob a licença GNU GPL v2 ou superiores* Mais informações em http://www.ayeda.com.br/index.php/sobre-a-ayeda/uso-de-codigo-livre
*/		
				 
		$query4 = "SELECT 
						cats.id as catid, cats.alias as catalias, 
						docs.title, concat(docs.id,'-',docs.alias) as downlink, 
						usrs.name, docs.created_time, cats.fk0001unidadeprodutiva
						from tchnfrm_edocman_categories cats 
					left join tchnfrm_edocman_document_category link on cats.id=link.category_id
					left join <banco de dados>.tchnfrm_edocman_documents docs  on docs.id=link.document_id
					left join tchnfrm_users usrs on docs.created_user_id=usrs.id
					where cats.fk0001unidadeprodutiva=".$upid[0].";";



		$stmt4 = $mysqli1->query($query4);
		$doccount=0;
		$catlink="";
		
		if ($ext<>""){
			echo '<div class="uk-text-left" uk-grid>
    <div class="uk-input uk-width-1-3">
        <div class="uk-card uk-card-default">
        	
			<label for="strmatriz" class="titulosUnidadesFont1">
				<span class="">Shape da Unidade Produtiva</span>
				
			</label>	        	

        </div>
    </div>
    <div class="uk-input uk-width-1-4">
        <div class="uk-card uk-card-default">
			
			<label for="strnomecomum" class="titulosUnidadesFont1">
				<span class=""></span>
			</label>	        	

        </div>
    </div>
	    <div class="uk-input uk-width-1-4">
        <div class="uk-card uk-card-default">
			
			<label for="strnomecomum" class="titulosUnidadesFont1">
				<span class="">'.$upid[1].'</span>
			</label>	        	

        </div>
    </div>
	    <div class="uk-input uk-width-1-6">
        <div class="uk-card uk-card-default">
			
			<span class="font-nexa-light" style="color:#9fa29f;font-size:12px;">
								<a href="/shpuploads/'.$shp.'">
								
								<img src="/components/com_jumi/files/fonts/font-awesome-4.7.0/css/solid/cloud-download-alt.svg" style="width:22px;margin-top:-5px;">
							</a>
						</span>	
		
			<span class="font-nexa-light" style="color:#9fa29f;font-size:12px;">
								<a  href="https://api.whatsapp.com/send?text=Veja a shape da '.utf8_encode($upid[1]).'. Segue o link: http://www.plaas.com.br/shpuploads/'.$shp.'" target="_blank">
								<img src="/components/com_jumi/files/fonts/font-awesome-4.7.0/css/brands/whatsapp.svg" style="width:19px;margin-top:-5px;">
							</a>
						</span>


        </div>
    </div>
	
	
</div>';
			
			
		}
		
		
		foreach ($stmt4 as $row4) {		
			$doccount+=1;
		if ($catlink==""){	
			$catlink=$row4['catid']."-".$row4['catalias'];
			}
			#include 'inc_up_docdetail.php';

			if ($row4['title']<>""){
			
			echo '<div class="uk-text-left" uk-grid>
    <div class="uk-input uk-width-1-3">
        <div class="uk-card uk-card-default">
        	
			<label for="strmatriz" class="titulosUnidadesFont1">
				<span class="">'.UTF8_ENCODE($row4['title']).'</span>
				
			</label>	        	

        </div>
    </div>
    <div class="uk-input uk-width-1-4">
        <div class="uk-card uk-card-default">
			
			<label for="strnomecomum" class="titulosUnidadesFont1">
				<span class="">'.$row4['created_time'].'</span>
			</label>	        	

        </div>
    </div>
	    <div class="uk-input uk-width-1-4">
        <div class="uk-card uk-card-default">
			
			<label for="strnomecomum" class="titulosUnidadesFont1">
				<span class="">'.utf8_encode($row4['name']).'</span>
			</label>	        	

        </div>
    </div>
	    <div class="uk-input uk-width-1-6">
        <div class="uk-card uk-card-default">
		
		
			
			<span class="font-nexa-light" style="color:#9fa29f;font-size:12px;">
								<!-- a href="/index.php/documentos/'.$row4['downlink'].'/download">
							
								
								<img src="/components/com_jumi/files/fonts/font-awesome-4.7.0/css/solid/cloud-download-alt.svg" style="width:22px;margin-top:-5px;">
							</a -->
							<a href="/index.php/component/edocman/'.$row4['downlink'].'/download?Itemid=">
								
								
								<img src="/components/com_jumi/files/fonts/font-awesome-4.7.0/css/solid/cloud-download-alt.svg" style="width:22px;margin-top:-5px;">
							</a>
							
							
						</span>	
			<span class="font-nexa-light" style="color:#9fa29f;font-size:12px;">
								<a  href="https://api.whatsapp.com/send?text=Veja o documento '.UTF8_ENCODE($row4['title']).' da '.utf8_encode($upid[1]).'. Segue o link: http://www.plaas.com.br/index.php/component/edocman/'.$row4['downlink'].'/download?Itemid= Se você ainda não é cliente PLAAS, faça o seu cadastro no nosso site http://www.plaas.com.br/socialplaas para ter acesso ao documento" target="_blank">
								<img src="/components/com_jumi/files/fonts/font-awesome-4.7.0/css/brands/whatsapp.svg" style="width:19px;margin-top:-5px;">
							</a>
						</span>



        </div>
    </div>
	
	
</div>';


			}
		}
			
			






			
		# ÁREAS
		
		#include 'inc_up_areasheader.php';
		
		#$query5 = "SELECT * from tchnfrm_0003upareas where fk0001unidadeprodutiva=".$upid[0]." order by created DESC LIMIT 1;";

		#$stmt5 = $mysqli1->query($query5);
	
		#foreach ($stmt5 as $row5) {		
		
		#}
		
		#include 'inc_up_areasdetail.php';
	
		echo '<br/>';
	echo'
	<div class="uk-grid-collapse uk-text-center" uk-grid>
	    <div class="fundoUnidades1 uk-padding-small">
	         <div class="titulosUnidadesFont1 maiusculosN fontUnidades1 titulosUnidadesCor1">Ativos da Unidade</div>
	    </div>
	</div>
	';		
		
		
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
		
				echo '<br/>';
	echo'
	<div class="uk-grid-collapse uk-text-center" uk-grid>
	    <div class="fundoUnidades1 uk-padding-small">
	         <div class="titulosUnidadesFont1 maiusculosN fontUnidades1 titulosUnidadesCor1">Permissões atribuídas nesta Unidade</div>
	    </div>
	</div>
	';
		
	
		
		include 'inc_up_permissionsdetail.php';	
		
		
		
		echo '</div id="1">';

	echo '</div id="body" >';
	
	
	
			$edit=false;
			foreach ($stmt2 as $row0) {

				if($usr_id==$row0['id'] && $row0['strpermissao']==2){
					$edit=true;
				}

			}
	
	
	
	if ($strowner==$usr_id or $edit)	
		{


			?>
			
			<div id="1" class="uk-grid-small uk-child-width-1-4@s uk-grid" uk-grid="">
						<a class="uk-padding-small uk-padding-remove-vertical edocman-modal"  
						href="/index.php/form-shape-file-upload?fk0001Unidadeprodutiva=<?php
/**
* @copyright (c) 2019 Ayeda Inovação - Todos os direitos reservados 

* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author Ricardo Carvalho <carvalhorj@gmail.com>
*
* Todo o código incluido neste arquivo está distribuído sob a licença GNU GPL v2 ou superiores* Mais informações em http://www.ayeda.com.br/index.php/sobre-a-ayeda/uso-de-codigo-livre
*/ echo $upid[0];?>&strunidadeprodutiva=<?php
/**
* @copyright (c) 2019 Ayeda Inovação - Todos os direitos reservados 

* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author Ricardo Carvalho <carvalhorj@gmail.com>
*
* Todo o código incluido neste arquivo está distribuído sob a licença GNU GPL v2 ou superiores* Mais informações em http://www.ayeda.com.br/index.php/sobre-a-ayeda/uso-de-codigo-livre
*/ echo utf8_encode($upid[1]);?>" 
						
						rel="{handler: 'iframe', size: {x: 880, y: 500}}">
						<div class="uk-card uk-card-default uk-card-body btUnidades1 uk-text-center maiusculosS 
						fontUnidadesbt1 titulosUnidadesFont1 fontUnidades1">
						Adicionar arquivo GEO</div>
						</a>
						<a class="uk-padding-small uk-padding-remove-vertical"
						
						<?php
/**
* @copyright (c) 2019 Ayeda Inovação - Todos os direitos reservados 

* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author Ricardo Carvalho <carvalhorj@gmail.com>
*
* Todo o código incluido neste arquivo está distribuído sob a licença GNU GPL v2 ou superiores* Mais informações em http://www.ayeda.com.br/index.php/sobre-a-ayeda/uso-de-codigo-livre
*/ 	
		
						if($doccount >= $doc){
							echo 'onclick="shortsubsdoc();" ' ; 
						}
						else
							{
							echo "href='/index.php/component/edocman/$catlink' target='_edocman_new'";
							}
					?>		
						
						
						>
						<div class="uk-card uk-card-default uk-card-body btUnidades1 uk-text-center maiusculosS 
						fontUnidadesbt1 titulosUnidadesFont1 fontUnidades1">Anexar Documentos</div>
						</a>
						
						
						<a class="uk-padding-small uk-padding-remove-vertical edocman-modal" 
						<?php
/**
* @copyright (c) 2019 Ayeda Inovação - Todos os direitos reservados 

* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author Ricardo Carvalho <carvalhorj@gmail.com>
*
* Todo o código incluido neste arquivo está distribuído sob a licença GNU GPL v2 ou superiores* Mais informações em http://www.ayeda.com.br/index.php/sobre-a-ayeda/uso-de-codigo-livre
*/ if ($strowner == $usr_id){?>
							href="/index.php/perms?fk0001unidadeprodutiva= <?php
/**
* @copyright (c) 2019 Ayeda Inovação - Todos os direitos reservados 

* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author Ricardo Carvalho <carvalhorj@gmail.com>
*
* Todo o código incluido neste arquivo está distribuído sob a licença GNU GPL v2 ou superiores* Mais informações em http://www.ayeda.com.br/index.php/sobre-a-ayeda/uso-de-codigo-livre
*/ echo $upid[0];?>" id="addmanagers" class="edocman-modal"
							rel="{handler: 'iframe', size: {x: 600, y: 500}}" 
						<?php
/**
* @copyright (c) 2019 Ayeda Inovação - Todos os direitos reservados 

* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author Ricardo Carvalho <carvalhorj@gmail.com>
*
* Todo o código incluido neste arquivo está distribuído sob a licença GNU GPL v2 ou superiores* Mais informações em http://www.ayeda.com.br/index.php/sobre-a-ayeda/uso-de-codigo-livre
*/ } ?>

						>
						<div class="uk-card uk-card-default uk-card-body btUnidades2 uk-text-center maiusculosS 
						fontUnidadesbt1 titulosUnidadesFont1 fontUnidades1">Atribuir permissões</div>
						</a>
						
						
						
						<a class="uk-padding-small uk-padding-remove-vertical edocman-modal"  
						<a href="/index.php/editar-unidades-produtivas?fk0001unidadeprodutiva=<?php
/**
* @copyright (c) 2019 Ayeda Inovação - Todos os direitos reservados 

* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author Ricardo Carvalho <carvalhorj@gmail.com>
*
* Todo o código incluido neste arquivo está distribuído sob a licença GNU GPL v2 ou superiores* Mais informações em http://www.ayeda.com.br/index.php/sobre-a-ayeda/uso-de-codigo-livre
*/ echo $upid[0];?>" id="editup"  
						rel="{handler: 'iframe', size: {x: 1200, y: 600}}">
						<div class="uk-card uk-card-default uk-card-body btUnidades2 uk-text-center maiusculosS 
						fontUnidadesbt1 titulosUnidadesFont1 fontUnidades1">Alterar Dados</div>
						</a>
			</div>
			

			
			<div id="actions" class="column column-4" >

			<!-- div class="row3" id="3" style="text-align:center; margin-bottom:10px;">
					<a id="addareas" href="/index.php/form-up-areas?fk0001unidadeprodutiva=<?php
/**
* @copyright (c) 2019 Ayeda Inovação - Todos os direitos reservados 

* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author Ricardo Carvalho <carvalhorj@gmail.com>
*
* Todo o código incluido neste arquivo está distribuído sob a licença GNU GPL v2 ou superiores* Mais informações em http://www.ayeda.com.br/index.php/sobre-a-ayeda/uso-de-codigo-livre
*/ echo $upid[0];?>&strUnidadeProdutiva=<?php
/**
* @copyright (c) 2019 Ayeda Inovação - Todos os direitos reservados 

* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author Ricardo Carvalho <carvalhorj@gmail.com>
*
* Todo o código incluido neste arquivo está distribuído sob a licença GNU GPL v2 ou superiores* Mais informações em http://www.ayeda.com.br/index.php/sobre-a-ayeda/uso-de-codigo-livre
*/ echo utf8_encode($upid[1]);?>" class="edocman-modal button" rel="{handler: 'iframe', size: {x: 880, y: 650}}">Adicionar definições de área a esta unidade produtiva</a>
			</div id="3" -->

			<!-- div class="row3" id="4" style="text-align:center; margin-bottom:10px;">
				<a id="addidentificadores" class="edocman-modal button" rel="{handler: 'iframe', size: {x: 880, y: 500}}">Adicionar identificadores externos a esta Unidade Produtiva</a>
			</div id="4" -->

			<!-- div class="row3" id="5" style="text-align:center; margin-bottom:10px;">
				<a id="addexploracoes" class="edocman-modal button" rel="{handler: 'iframe', size: {x: 880, y: 500}}">Adicionar explorações a esta unidade produtiva</a>
			</div id="5 " -->
			


		</div id="actions">
	
	
	<?php
/**
* @copyright (c) 2019 Ayeda Inovação - Todos os direitos reservados 

* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author Ricardo Carvalho <carvalhorj@gmail.com>
*
* Todo o código incluido neste arquivo está distribuído sob a licença GNU GPL v2 ou superiores* Mais informações em http://www.ayeda.com.br/index.php/sobre-a-ayeda/uso-de-codigo-livre
*/ 
	
	}

echo '</div>';



?>
<br/>


<script>	
			var fazenda;
		    var indios;
			var estradas;
			var sigef;
			var car;
			var municipios;
			var fazendaControl;

			//SET UP MAP
			var osmAttr='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
				osmUrl='https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';

			var esAttr='Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community',
				esUrl='https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}';
			
			// valid values are 'roadmap', 'satellite', 'terrain' and 'hybrid'
			// var googleroads = L.gridLayer.googleMutant({type: 'roadmap'});
			// var googlesattelite = L.gridLayer.googleMutant({type: 'satellite'});
			var googleterrain = L.gridLayer.googleMutant({type: 'terrain'});
			var googlehybrid = L.gridLayer.googleMutant({type: 'hybrid'});
			
			var osmap = L.tileLayer(osmUrl, {id: 'OpenStreetMap', attribution: osmAttr}),
				esri  = L.tileLayer(esUrl, {id: 'esri',   attribution: esAttr});
         
			var map = L.map('map', {
				center: [<?PHP echo $lat; ?>, <?PHP echo $lng; ?>], 
				zoom: <?PHP echo $zoom; ?>,
				layers: [esri], 
				drawControl: true
			});
		
			var baseLayers = {	
				"Mapa Temático": osmap,
				"Satélite": esri,
				"Relevo":googleterrain, 
				"Híbrido":googlehybrid
			};
			
			var baseControl = L.control.layers(baseLayers).addTo(map);
	
			L.control.scale ({maxWidth:240, metric:true, imperial:false, position: 'bottomleft'}).addTo (map);
            L.control.polylineMeasure ({position:'bottomleft', unit:'metres', showBearings:false, clearMeasurementsOnStop: false, showClearControl: true, showUnitControl: true}).addTo (map);

			addShapes();
			
			
			var drawPluginOptions = {
				position: 'topright',
					draw: {
						polyline: true,
						circle: true, // Turns off this drawing tool
						rectangle: true,
						marker: true,
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
					},
					edit: {
							featureGroup: editableLayers, //REQUIRED!!
							remove: true
					}
			};

			var editableLayers = new L.FeatureGroup();

			map.addLayer(editableLayers);

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
								layer.bindPopup(e.latlng.lat + ', ' + e.latlng.lng);
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
				var myplaas={"color": "#ffffff","weight": 1,"opacity": 0.85, "fillOpacity":0.85,"stroke":1 , "weight": 2};

											
				
				
				
				<?echo $shapelayers;?>
				
				<?echo $shapelayers1;?>
				
						
					var PlaasIcon = L.icon({
						iconUrl: 'https://www.plaas.com.br/images/plaas/plaasmarker.svg',
						iconSize:     [36, 56], // size of the icon
						iconAnchor:   [18, 56], // point of the icon which will correspond to marker's location
						popupAnchor:  [0, 3] // point from which the popup should open relative to the iconAnchor
						});		
					var PlaasIcon1 = L.icon({
						iconUrl: 'https://www.plaas.com.br/images/plaas/plaasmarker1.svg',
						iconSize:     [36, 56], // size of the icon
						iconAnchor:   [18, 56], // point of the icon which will correspond to marker's location
						popupAnchor:  [0, 3] // point from which the popup should open relative to the iconAnchor
						});		
					var PlaasIcon2 = L.icon({
						iconUrl: 'https://www.plaas.com.br/images/plaas/plaasmarker2.svg',
						iconSize:     [36, 56], // size of the icon
						iconAnchor:   [18, 56], // point of the icon which will correspond to marker's location
						popupAnchor:  [0, 3] // point from which the popup should open relative to the iconAnchor
						});		
					var PlaasIcon3 = L.icon({
						iconUrl: 'https://www.plaas.com.br/images/plaas/plaasmarker3.svg',
						iconSize:     [36, 56], // size of the icon
						iconAnchor:   [18, 56], // point of the icon which will correspond to marker's location
						popupAnchor:  [0, 3] // point from which the popup should open relative to the iconAnchor
						});		
					var PlaasIcon4 = L.icon({
						iconUrl: 'https://www.plaas.com.br/images/plaas/plaasmarker4.svg',
						iconSize:     [36, 56], // size of the icon
						iconAnchor:   [18, 56], // point of the icon which will correspond to marker's location
						popupAnchor:  [0, 3] // point from which the popup should open relative to the iconAnchor
						});	
			
			
			
					fazenda = L.layerGroup();

				
					//var kmlLayer = new L.KML("/shpuploads/20190322171001_reunidas-brasnorte3.kml", {async: true});  
					//kmlLayer.on("loaded", function(e) {map.fitBounds(e.target.getBounds());	});
					

						
						
				
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
						}, 
						"<br/><strong>Municípios Limítrofes</strong>": {
							<?echo $overlays1;?>
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