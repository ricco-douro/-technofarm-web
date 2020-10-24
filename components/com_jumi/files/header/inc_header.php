
<?php
/**
* @copyright (c) 2019 Ayeda Inovação - Todos os direitos reservados 

* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author Ricardo Carvalho <carvalhorj@gmail.com>
*
* Todo o código incluido neste arquivo está distribuído sob a licença GNU GPL v2 ou superiores* Mais informações em http://www.ayeda.com.br/index.php/sobre-a-ayeda/uso-de-codigo-livre
*/			
if (	(strpos($_SERVER['REQUEST_URI'], 'socialplaas') >0)
		or (strpos($_SERVER['REQUEST_URI'], 'novo-anuncio') >0)
		or (strpos($_SERVER['REQUEST_URI'], 'nossos-planos') >0) 
		or (strpos($_SERVER['REQUEST_URI'], 'unidades-produtivas') >0)
		or (strpos($_SERVER['REQUEST_URI'], 'detalhe-unidade-produtiva') >0)
		or (strpos($_SERVER['REQUEST_URI'], 'edocman') >0)
		or (strpos($_SERVER['REQUEST_URI'], 'documentos') >0)
		or (strpos($_SERVER['REQUEST_URI'], 'classificados') >0)
		or (strpos($_SERVER['REQUEST_URI'], 'assinatura') >0)
		or (strpos($_SERVER['REQUEST_URI'], 'fale-conosco') >0)
	
	
		
		
		) {
?>

<style>
 nav.uk-navbar {display: none;} 
 ul.uk-subnav{display:none;} 
 
 #container    {
    width:100%;  
    min-height:300px;
    margin:auto;
}

.jomsocial-wrapper {
    position: relative;
    overflow: visible;
    width: 100%;
    min-height: 300px;
    margin-top: -25px;
	
}

	.fundo01{
		background-image: url('/components/com_jumi/files/images/img5.png'); 
		background-color:#000000; 
		box-sizing: border-box; 
		min-height: calc(100vh - 1048px);
	}

	.uk-nav-default > li > a {
    	color: #fff;
    	text-transform: none!important;
		display: inline-block !important;
		padding: 0 5px 0 5px !important;    	
	}

	.uk-nav-default > li.uk-active > a {
		color: #196433 !important;
		background-color: #7CBA43 !important;
		display: inline-block !important;
		padding: 0 5px 0 5px !important;
	}

	.nav-tabs, .nav-pills {
    	line-height: 25px!important;
    }

	.uk-nav-default > li > a:hover, 
	.uk-nav-default > li > a:focus {
	    color: #ffffff;
		background-color: #196433 !important;
	}   

	.cor01{
		background-color:#196433!important;
		height: 10px!important;
	}	 

	.cor02{
		background-color:#7CBA43!important;
		height: 10px!important;
	}

	.cor03{
		background-color:#CDDC94!important;
		height: 10px!important;
	}		

</style>
<div id="plaas-header">
<div class="uk-child-width-1-2@s uk-child-width-1-3@m fundo01 uk-flex-middle" uk-grid style="min-height: 300px">
        <div class="uk-card uk-text-center" style="vertical-align:"><a href="https://www.plaas.com.br"><img src="/images/plaas/logo.21.4.white.svg" alt="Logo" width="300px"></a></div>
        <div class="uk-card"><?php
/**
* @copyright (c) 2019 Ayeda Inovação - Todos os direitos reservados 

* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author Ricardo Carvalho <carvalhorj@gmail.com>
*
* Todo o código incluido neste arquivo está distribuído sob a licença GNU GPL v2 ou superiores* Mais informações em http://www.ayeda.com.br/index.php/sobre-a-ayeda/uso-de-codigo-livre
*/ echo JHtml::_('content.prepare', '{loadposition ric_1}'); ?></div>
        <div class="uk-card uk-text-center"><?php
/**
* @copyright (c) 2019 Ayeda Inovação - Todos os direitos reservados 

* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author Ricardo Carvalho <carvalhorj@gmail.com>
*
* Todo o código incluido neste arquivo está distribuído sob a licença GNU GPL v2 ou superiores* Mais informações em http://www.ayeda.com.br/index.php/sobre-a-ayeda/uso-de-codigo-livre
*/ echo JHtml::_('content.prepare', '{loadposition ric_2}'); ?></div>
</div>
<div class="uk-child-width-1-2@s uk-child-width-1-3@m uk-flex-middle" uk-grid style="margin: 0; padding: 0">
        <div class="uk-card cor01"></div>
        <div class="uk-card cor02"></div>
        <div class="uk-card cor03"></div>
</div>

<!-- 				<div class="uk-section-default uk-section-overlap uk-position-relative uk-light" >
        			<div  class="uk-background-norepeat uk-background-cover uk-background-center-center uk-section uk-padding-remove-vertical uk-flex uk-flex-middle" uk-height-viewport="offset-top: true;offset-bottom: ! +">
						<div class="uk-position-cover" style="background-color: rgba(0, 0, 0, 0.14);"></div>
						<div class="uk-width-1-1">
							<div class="uk-container uk-container-small uk-position-relative">
								<div class="uk-grid-collapse uk-flex-middle uk-margin-remove-vertical uk-grid" uk-grid="">
									<div class="uk-width-expand uk-first-column">
										<div class="uk-margin uk-scrollspy-inview uk-animation-slide-bottom-small" uk-scrollspy-class="" style=""></div>
									</div>
									<div class="uk-width-expand">
										<div>&nbsp;</div>
									</div>
									<div class="uk-width-expand">
										<div class="uk-panel uk-scrollspy-inview uk-animation-slide-bottom-small" uk-scrollspy-class="" style=""></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div> -->
<?php
/**
* @copyright (c) 2019 Ayeda Inovação - Todos os direitos reservados 

* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author Ricardo Carvalho <carvalhorj@gmail.com>
*
* Todo o código incluido neste arquivo está distribuído sob a licença GNU GPL v2 ou superiores* Mais informações em http://www.ayeda.com.br/index.php/sobre-a-ayeda/uso-de-codigo-livre
*/ } ?>
</div>