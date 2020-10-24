<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>

	function nosubscription(){ 
			
			 swal("Desculpe!", "Você não tem uma subscrição ativa! Visite nossa página de planos e adquira o plano que melhor lhe atende.", "error");
			
			document.getElementById("button24").style.display = "none";
			document.getElementById("area_fields_2").innerHTML = "";
			document.getElementById("area_fields_2").innerHTML = "<div class='field  four wide required'><br/><br/><br/><h3 style='font-weigth:600;'> Por favor, feche esta janela e visite nossa página de Planos.</h3></div>";
			}
	
	
	function shortsubscar(){ 
			 swal("Desculpe!", "Você tem uma subscrição que não lhe permite adicionar novos CAR para mobile ! Visite nossa página de planos e adquira o plano que melhor lhe atende.", "error");
			document.getElementById("button24").style.display = "none";
			document.getElementById("area_fields_2").innerHTML = "";
			document.getElementById("area_fields_2").innerHTML = "<div class='field  four wide required'><br/><br/><br/><h3 style='font-weigth:600;'> Por favor, feche esta janela e visite nossa página de Planos.</h3></div>";
			

	}
	
</script>


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
$rootid = '<id de usuário de banco de dados>';
$password = '<senha de acesso ao banco>';
$db = "<banco de dados>";

$mysqli1 = mysqli_connect($host, $rootid, $password, $db);

$query0= "SELECT * FROM get_activesubscribers WHERE user_id=".$usr_id.";";
$stmt0 = $mysqli1->query($query0);

$bookcar = 0;

	foreach ($stmt0 as $row0) {
		$bookcar = $row0['bizobj_car'];
		
	}
if ($bookcar==0){
	
	echo '<script>window.onload = nosubscription;</script>';
	
	}


$query1= "SELECT count(*) as car_count FROM tchnfrm_chronoforms_data_frmcarimobiliario where user_id=".$usr_id.";";

$stmt1 = $mysqli1->query($query1);

$currcar = 0;

	foreach ($stmt1 as $row1) {
		$currcar = $row1['car_count'];
		
	}
	
IF ($currcar>=$bookcar){
	echo '<script>window.onload = shortsubscar;</script>';
}









;
?>