
<?php
/**
* @copyright (c) 2019 Ayeda Inovação - Todos os direitos reservados 

* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author Ricardo Carvalho <carvalhorj@gmail.com>
*
* Todo o código incluido neste arquivo está distribuído sob a licença GNU GPL v2 ou superiores* Mais informações em http://www.ayeda.com.br/index.php/sobre-a-ayeda/uso-de-codigo-livre
*/

			if ($row4['title']<>""){

			echo '<div class="row" style="margin-top:-20px;">
					<div class="column column-7 cor11b">
						<div style="text-align:center;"> 
							<span class="font-nexa-light" style="color:#9fa29f;font-size:12px;">
								'.UTF8_ENCODE($row4['title']).'&nbsp;
							</span>
						</div>
					</div>';
					
			echo '<div class="column column-7 cor11b">
						<div style="text-align:center;"> 
							<span class="font-nexa-light" style="color:#9fa29f;font-size:12px;">
								'.$row4['created_time'].'&nbsp;
							</span>
						</div>
					</div>';
			echo '<div class="column column-6 cor11b">
						<div style="text-align:center;"> 
							<span class="font-nexa-light" style="color:#9fa29f;font-size:12px;">
								'.$row4['name'].'&nbsp;
							</span>
						</div>
					</div>';
			echo '<div class="column column-1 cor11b">
						<div style="text-align:center;"> 
							<span class="font-nexa-bold" style="color:#9fa29f;font-size:12px;">
							<a href="/index.php/documentos/'.$row4['downlink'].'/download">
								<img src="/images/technofarm/download.svg" style="width:22px;margin-top:-5px;">
							</a>
							</span>
						</div>
					</div>';
			echo '<div class="column column-1 cor11b">
						<div style="text-align:center;"> 
							<span class="font-nexa-bold" style="color:#9fa29f;font-size:12px;">
							<a  class="edocman-modal" rel="{handler: '."'iframe'".', size: {x: 880, y: 500}}" href="/index.php/component/edocman/'.$catlink.'/'.$row4['downlink'].'?tmpl=component&Itemid=">
								<img src="/images/technofarm/info.svg" style="width:22px;margin-top:-5px;">
							</a>
							</span>
						</div>
					</div>
				 </div>';	 
			echo '<div class="column column-1 cor11b">
						<div style="text-align:center;"> 
							<span class="font-nexa-bold" style="color:#9fa29f;font-size:12px;">
							<a  class="edocman-modal" rel="{handler: '."'iframe'".', size: {x: 880, y: 500}}" href="/index.php/component/edocman/'.$catlink.'/'.$row4['downlink'].'?tmpl=component&Itemid=">
								<img src="/images/plaas/whatsapp.svg" style="width:22px;margin-top:-5px;">
							</a>
							</span>
						</div>
					</div>
				 </div>';	 
			}

?>