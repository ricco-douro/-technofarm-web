	<script>
			
	
	function remove(btnid){
		
		console.log(btnid);
		
		// Declaração de Variáveis
		var aprovadop   = document.getElementById("btn"+btnid);
		var permissao   = document.getElementById("td"+btnid);
		
		var xmlreq = CriaRequest();
      
        // Iniciar uma requisição
		var myurl="http://www.plaas.com.br/components/com_jumi/files/unidadesprodutivas/removerpermissao.php?cod=" + btnid;
	
		xmlreq.open("GET",myurl , true);
      
		// Atribui uma fun��o para ser executada sempre que houver uma mudan�a de ado
		xmlreq.onreadystatechange = function(){
          
			// Verifica se foi conclu�do com sucesso e a conexão fechada (readyState=4)
			if (xmlreq.readyState == 4) {
              
				// Verifica se o arquivo foi encontrado com sucesso
				if (xmlreq.status == 200) {
					aprovadop.setAttribute ("style","border-radius: 4px;color:#ffffff;background-color: #e50000;border:1px solid #660000;");
					aprovadop.innerHTML='Removido';
					permissao.innerHTML='&nbsp;';
				}
				else{
					
				}
			}
		};
     xmlreq.send(null);

	}


	/**
  * Função para criar um objeto XMLHTTPRequest
  */
 function CriaRequest() {
     try{
         request = new XMLHttpRequest();        
     }catch (IEAtual){
          
         try{
             request = new ActiveXObject("Msxml2.XMLHTTP");       
         }catch(IEAntigo){
          
             try{
                 request = new ActiveXObject("Microsoft.XMLHTTP");          
             }catch(falha){
                 request = false;
             }
         }
     }
      
     if (!request) 
         alert("Seu Navegador não suporta Ajax!");
     else
         return request;
 }
  
</script>	
<style>
.rmvbtn_before{
	border-radius: 4px;color:#e50000;border:1px solid #660000;padding:3px;
}
.rmvbtn_after{
	border-radius: 4px;color:#ffffff;background-color: #e50000;border:1px solid #660000;padding:3px;
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


$host = 'localhost';
$rootid = '<id de usuário de banco de dados>';
$password = '<senha de acesso ao banco>';
$db = "<banco de dados>";

$mysqli1 = mysqli_connect($host, $rootid, $password, $db);

$query2= "SELECT pk0007uppermissoes, id, strpermissao, strnomecomum, name, DATE_FORMAT(strtermino, '%Y-%m-%d') as strtermino
FROM tchnfrm_0007uppermissoes
inner join tchnfrm_0001unidadesprodutivas on fk0001unidadeprodutiva=PK0001UnidadeProdutiva
inner join tchnfrm_users on id = strrecipiente
where deleted=0 and fk0001unidadeprodutiva=".$upid[0];

$stmt2 = $mysqli1->query($query2);

?>

<div class="uk-text-left" uk-grid>
    <div class="uk-input uk-width-1-3">
        <div class="uk-card uk-card-default">
        	
			<label for="strmatriz" class="titulosUnidadesFont1">
				<span class="">Quem</span>
				
			</label>	        	

        </div>
    </div>
    <div class="uk-input uk-width-1-3">
        <div class="uk-card uk-card-default">
			
			<label for="strnomecomum" class="titulosUnidadesFont1">
				<span class="">Permissão</span>
			</label>	        	

        </div>
    </div>
	    <div class="uk-input uk-width-1-6">
        <div class="uk-card uk-card-default">
			
			<label for="strnomecomum" class="titulosUnidadesFont1">
				<span class="">Data Final</span>
			</label>	        	

        </div>
    </div>
	    <div class="uk-input uk-width-1-6">
        <div class="uk-card uk-card-default">
			
			<label for="strnomecomum" class="titulosUnidadesFont1">
				<span class="">Remover</span>
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


echo "<table width='100%'></tr>";

foreach ($stmt2 as $row0) {
	
	
	echo '<div class="uk-text-left" uk-grid>
    <div class="uk-input uk-width-1-3">
        <div class="uk-card uk-card-default">
        	
			<label for="strmatriz" class="titulosUnidadesFont1">
				<span class="">'. utf8_encode($row0['name']).'</span>
				
			</label>	        	

        </div>
    </div>
    <div class="uk-input uk-width-1-3">
        <div class="uk-card uk-card-default">
			
			<label for="strnomecomum" class="titulosUnidadesFont1">
				<span class="">';
						switch ($row0['strpermissao']) {
		case 0:
			echo "Pode ver detalhes de sua Unidade Produtiva";
			break;
		case 1:
			echo "Pode baixar e subir documentos para sua Unidade Produtiva";
			break;
		case 2:
			echo "Pode editar detalhes de sua Unidade Produtiva";
			break;
	}
				echo '</span>
			</label>	        	

        </div>
    </div>
	    <div class="uk-input uk-width-1-6">
        <div class="uk-card uk-card-default">
			
			<label for="strnomecomum" class="titulosUnidadesFont1">
				<span class="">'. $row0['strtermino'] .'</span>
			</label>	        	

        </div>
    </div>
	    <div class="uk-input uk-width-1-6">
        <div class="uk-card uk-card-default">
			
			<label for="strnomecomum" class="titulosUnidadesFont1">';
				if ($strowner==$usr_id)	{
					echo '<span id="btn'. $row0['pk0007uppermissoes'] . '" onclick="remove('. $row0['pk0007uppermissoes'] . ');" class="font-nexa-bold"><img src="/components/com_jumi/files/fonts/font-awesome-4.7.0/css/solid/trash-alt.svg" style="width:22px;margin-top:-5px;fill:#ff0000;"></span></span>';
				}
			echo '</label>	        	

        </div>
    </div>
</div>
';


}

echo "</table>";

?>