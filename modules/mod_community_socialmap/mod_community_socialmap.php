<?php

#O arquivo mod_helloworld.php executará três tarefas:

#Incluir o arquivo helper.php que contém a classe a ser usada para coletar os dados necessários
#Invocar o método apropriado da classe helper para a recuperação dos dados
#Incluir o template para exibir o resultado.

// No direct access
defined('_JEXEC') or die;
// Include the syndicate functions only once
require_once dirname(__FILE__) . '/helper.php';

$hello = modHelloWorldHelper::getHello($params);
require JModuleHelper::getLayoutPath('mod_community_socialmap');