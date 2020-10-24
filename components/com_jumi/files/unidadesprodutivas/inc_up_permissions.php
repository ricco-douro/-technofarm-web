<style>
tm-header-mobile uk-hidden@m{
	display:none;
}
nav.uk-navbar-container.uk-navbar {
    display: none;
}
</style>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
function notice_0(){ 
swal("Sucesso!", "O contato que você indicou recebeu suas permissões.", "success");
}

function notice_1(){ 
swal("Desculpe!", "O Profissional que você indicou não possui uma subscrição ativa do PLAAS! Recomende que ele visite nossa página de planos e adquira o plano que melhor lhe atende.", "error");
}

function notice_2(){ 
swal("Desculpe!", "O contato que você indicou não possui o perfil necessário para receber esse tipo de permissão.", "error");
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







if(isset($jumi[0]))
{$upid = $jumi[0];}

if(isset($jumi[1]))
{$strrecipient = $jumi[1];}

if(isset($jumi[2]))
{$strpermissao = $jumi[2];}

if(isset($jumi[3]))
{$strtermino = $jumi[3];}

#$user =& JFactory::getUser();
#$usr_id = $user->get('id');

	$groups = JAccess::getGroupsByUser($strrecipient);
	$profile="";

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



$host = 'localhost';
$rootid = '<id de usuário de banco de dados>';
$password = '<senha de acesso ao banco>';
$db = "<banco de dados>";

$mysqli1 = mysqli_connect($host, $rootid, $password, $db);
$allow=false;



				$query0= "SELECT *
							FROM <banco de dados>.get_activesubscribers 
							WHERE user_id=".$strrecipient;
				$stmt0 = $mysqli1->query($query0);
				$row_cnt = $stmt0->num_rows;
				if ($row_cnt>0)
					{$msg=0; echo '<body onload="notice_0();">';}
				else
					{$msg=1; echo '<body onload="notice_1();">';}

if ($msg==0){
	$query1= "call upd_permissoes($upid ,$strrecipient ,$strpermissao, '$strtermino');";
	$stmt1 = $mysqli1->query($query1);
}


$query2= "SELECT pk0007uppermissoes, strpermissao, strnomecomum, name, DATE_FORMAT(strtermino, '%Y-%m-%d') as strtermino
FROM tchnfrm_0007uppermissoes
inner join tchnfrm_0001unidadesprodutivas on fk0001unidadeprodutiva=PK0001UnidadeProdutiva
inner join tchnfrm_users on id =strrecipiente
where deleted=0 and fk0001unidadeprodutiva=".$upid;

$stmt2 = $mysqli1->query($query2);

echo "<table width='100%'><tr><td><h3>Permissões atribuídas a esta Unidade Produtiva</h3></td></tr>";

foreach ($stmt2 as $row0) {

	switch ($row0['strpermissao']) {
		case 0:
        echo "<tr><td style='font-size:15px;'>". utf8_encode($row0['name']). " pode ver detalhes de sua Unidade Produtiva até ". $row0['strtermino'] . "</td></tr>";
        break;
		case 1:
        echo "<tr><td style='font-size:15px;'>". utf8_encode($row0['name']). " pode baixar e subir documentos para sua Unidade Produtiva até ".$row0['strtermino'] . "</td></tr>";
        break;
		case 2:
        echo "<tr><td style='font-size:15px;'>". utf8_encode($row0['name']). " pode editar detalhes de sua Unidade Produtiva até ".  $row0['strtermino'] . "</td></tr>";
        break;
	}

}

echo "</table>";

?>