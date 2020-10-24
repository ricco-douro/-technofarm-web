<?php
/**
* @version      1.0
* @package      DJ Classifieds
* @subpackage   DJ Classifieds Payment Plugin
* @copyright    Copyright (C) 2010 DJ-Extensions.com LTD, All rights reserved.
* @license      http://www.gnu.org/licenses GNU/GPL
* @autor url    http://design-joomla.eu
* @autor email  contact@design-joomla.eu
* @Developer    Piotr Dobrakowski - piotr.dobrakowski@design-joomla.eu
* 
* 
* DJ Classifieds is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* DJ Classifieds is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with DJ Classifieds. If not, see <http://www.gnu.org/licenses/>.
* 
*/
defined('_JEXEC') or die('Restricted access');
jimport('joomla.event.plugin');
$lang = JFactory::getLanguage();
$lang->load('plg_djclassifiedspayment_djcfMercadoPago',JPATH_ADMINISTRATOR);

require_once(dirname(__FILE__).'/djcfMercadoPago/lib/mercadopago.php');

class plgdjclassifiedspaymentdjcfMercadoPago extends JPlugin
{
    public function __construct(& $subject, $config)
    {
        parent::__construct($subject, $config);
        
        $this->loadLanguage('plg_djcfMercadoPago');
        
        $params["plugin_name"] = "djcfMercadoPago";
        $params["logo"] = "mercadopago_logo.png";
		$params["payment_method"] = JText::_("PLG_DJCLASSIFIEDSPAYMENT_DJCFMERCADOPAGO_PAYMENT_METHOD");
        $params["description"] = JText::_("PLG_DJCLASSIFIEDSPAYMENT_DJCFMERCADOPAGO_PAYMENT_METHOD_DESC");
		$params["test_mode"] = $this->params->get("test_mode", '1');
		$params["client_id"] = $this->params->get("client_id", null);
		$params["client_secret"] = $this->params->get("client_secret", null);
        $params["currency_code"] = $this->params->get("currency_code", "USD");

        $this->params = $params;
    }
	
    function onProcessPayment()
    {
        $ptype = JRequest::getVar('ptype','');
        $id = JRequest::getInt('id','0');
        $html="";

            
        if($ptype == $this->params["plugin_name"])
        {
            $action = JRequest::getVar('pactiontype','');
            switch ($action)
            {
                case "process" :
                $html = $this->process($id);
                break;
                case "notify" :
                $html = $this->_notify_url();
                break;
                case "paymentmessage" :
                $html = $this->_paymentsuccess();
                break;
                default :
                $html =  $this->process($id);
                break;
            }
        }
        return $html;
    }
	

    
    function process($id)
    {
		JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');		
		jimport( 'joomla.database.table' );
		$db 	= JFactory::getDBO();
		$app 	= JFactory::getApplication();
		$Itemid = JRequest::getInt("Itemid",'0');
		$par 	= JComponentHelper::getParams( 'com_djclassifieds' );
		$user 	= JFactory::getUser();
		$ptype	= JRequest::getVar('ptype');
		$type	= JRequest::getVar('type','');
		$row 	= JTable::getInstance('Payments', 'DJClassifiedsTable');	
		
		/*
		 if($type=='plan'){        	        	
        	$query ="SELECT p.* FROM #__djcf_plans p "
        			."WHERE p.id=".$id." LIMIT 1";
        	$db->setQuery($query);
        	$plan = $db->loadObject();
			
        	if(!isset($plan)){
        		$message = JText::_('COM_DJCLASSIFIEDS_SUBSCRIPTION_PLAN');
        		$redirect="index.php?option=com_djclassifieds&view=plans";
        	}        						 	
        					 
       		$row->item_id = $id;
       		$row->user_id = $user->id;
      		$row->method = $ptype;
       		$row->status = 'Start';
      		$row->ip_address = $_SERVER['REMOTE_ADDR'];
       		$row->price = $plan->price;
       		$row->type=3;        	
       		$row->store();

       		$amount = $plan->price;
      		$itemname = $plan->name;
       		$payment_id = $row->id;
       		$item_cid = '';			
        }else if($type=='prom_top'){        	        	
        	$query ="SELECT i.* FROM #__djcf_items i "
        			."WHERE i.id=".$id." LIMIT 1";
        	$db->setQuery($query);
        	$item = $db->loadObject();
        	if(!isset($item)){
        		$message = JText::_('COM_DJCLASSIFIEDS_WRONG_AD');
        		$redirect="index.php?option=com_djclassifieds&view=items&cid=0";
        	}        						 
        					 
       		$row->item_id = $id;
       		$row->user_id = $user->id;
      		$row->method = $ptype;
       		$row->status = 'Start';
      		$row->ip_address = $_SERVER['REMOTE_ADDR'];
       		$row->price = $par->get('promotion_move_top_price',0);
       		$row->type=2;        	
       		$row->store();

       		$amount = $par->get('promotion_move_top_price',0);
      		$itemname = $item->name;
       		$payment_id = $row->id;
       		$item_cid = '&cid='.$item->cat_id;       	
        }else if($type=='points'){
			$query ="SELECT p.* FROM #__djcf_points p "				   
				   ."WHERE p.id=".$id." LIMIT 1";
			$db->setQuery($query);
			$points = $db->loadObject();
			if(!isset($points)){
				$message = JText::_('COM_DJCLASSIFIEDS_WRONG_POINTS_PACKAGE');
				$redirect="index.php?option=com_djclassifieds&view=items&cid=0";
			}			
				$row->item_id = $id;
				$row->user_id = $user->id;
				$row->method = $ptype;
				$row->status = 'Start';
				$row->ip_address = $_SERVER['REMOTE_ADDR'];
				$row->price = $points->price; 
				$row->type=1;
				
				$row->store();		
			
			$amount = $points->price;
			$itemname = $points->name;
			$payment_id = $row->id;
			$item_cid = '';
		}else{
			$query ="SELECT i.*, c.price as c_price FROM #__djcf_items i "
				   ."LEFT JOIN #__djcf_categories c ON c.id=i.cat_id "
				   ."WHERE i.id=".$id." LIMIT 1";
			$db->setQuery($query);
			$item = $db->loadObject();
			//die($item->pay_type);
			if(!isset($item)){
				$message = JText::_('COM_DJCLASSIFIEDS_WRONG_AD');
				$redirect="index.php?option=com_djclassifieds&view=items&cid=0";
			}
			
				$amount = 0;
				
				if(strstr($item->pay_type, 'cat')){			
					$amount += $item->c_price/100; 
				}
				
				if(strstr($item->pay_type, 'type')){
					if(!defined("DS")){ define('DS',DIRECTORY_SEPARATOR);}
					require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_djclassifieds'.DS.'lib'.DS.'djpayment.php');
					$itype = DJClassifiedsPayment::getTypePrice($item->user_id,$item->type_id);
					$amount += $itype->price;
				}
				
				$query = "SELECT * FROM #__djcf_days d "
						."WHERE d.days=".$item->exp_days." LIMIT 1";
				$db->setQuery($query);
				$day = $db->loadObject();
				
				
				if(strstr($item->pay_type, 'duration_renew')){
					$amount += $day->price_renew;
				}else if(strstr($item->pay_type, 'duration')){			
					$amount += $day->price;
				}
				
				if(strstr($item->pay_type, 'extra_img_renew')){					
					if($day->img_price_default){
						$amount += $par->get('img_price_renew','0')*$item->extra_images_to_pay;
					}else{
						$amount += $day->img_price_renew*$item->extra_images_to_pay;
					}										
				}else if(strstr($item->pay_type, 'extra_img')){
					if($day->img_price_default){
						$amount += $par->get('img_price','0')*$item->extra_images_to_pay;
					}else{
						$amount += $day->img_price*$item->extra_images_to_pay;
					}										
				}
				
				if(strstr($item->pay_type, 'extra_chars_renew')){
					if($day->char_price_default){
						$amount += $par->get('desc_char_price_renew','0')*$item->extra_chars_to_pay;
					}else{
						$amount += $day->char_price_renew*$item->extra_chars_to_pay;
					}										
				}else if(strstr($item->pay_type, 'extra_chars')){					
					if($day->char_price_default){
						$amount += $par->get('desc_char_price','0')*$item->extra_chars_to_pay;
					}else{
						$amount += $day->char_price*$item->extra_chars_to_pay;
					}
				}
				
				$query = "SELECT p.* FROM #__djcf_promotions p "
					."WHERE p.published=1 ORDER BY p.id ";
				$db->setQuery($query);
				$promotions=$db->loadObjectList();
				foreach($promotions as $prom){
					if(strstr($item->pay_type, $prom->name)){	
						$amount += $prom->price; 
					}	
				}
				
					$row->item_id = $id;
					$row->user_id = $user->id;
					$row->method = $ptype;
					$row->status = 'Start';
					$row->ip_address = $_SERVER['REMOTE_ADDR'];
					$row->price = $amount;
					$row->type=0;
				
				$row->store();					
			
			$itemname = $item->name;
			$payment_id = $row->id;
			$item_cid = '&cid='.$item->cat_id;
		}
		*/
		
		$pdetails = DJClassifiedsPayment::processPayment($id,$type,$ptype);
		
		$payment_id = $pdetails['item_id'];
		$itemname = $pdetails['itemname'];
		$item_cid = $pdetails['item_cid'];
		$amount = $pdetails['amount'];

/* API step 1 START */

		$payment_title = 'Item ID: '.$id.' ('.$itemname.')';
		$payment_reason = $type ? $type : $item->pay_type;
		$currency_code = $this->params['currency_code'];
		
		$successURL = JRoute::_(JURI::root().'index.php?option=com_djclassifieds&task=processPayment&ptype='.$this->params["plugin_name"].'&pactiontype=paymentmessage&id='.$id.'&cid='.$item->cat_id.'&Itemid='.$Itemid);
		$errorURL = JRoute::_(JURI::root().'index.php?option=com_djclassifieds&task=paymentReturn&r=error&id='.$id.'&cid='.$item->cat_id.'&Itemid='.$Itemid);
		$pendingURL = JRoute::_(JURI::root().'index.php?option=com_djclassifieds&task=paymentReturn&r=pending&id='.$id.'&cid='.$item->cat_id.'&Itemid='.$Itemid);
		
		$preference = array(
			"external_reference" => $payment_id,
			"items" => array(
				array(
					"id" => $id,
					"title" => $payment_title,
					"description" => $payment_reason,
					"quantity" => 1,
					"unit_price" => (float)number_format($amount,2,'.',''), 
					"currency_id" => $currency_code,
					"picture_url" => "",
				),
			),
			"payer" => array(
				"name" => $user->name,
				"email" => $user->email,
			),
			"back_urls" => array(
				"success" => $successURL,
				"failure" => $errorURL,
				"pending" => $pendingURL,
			)
		);
		
		$mp = new MP($this->params["client_id"], $this->params["client_secret"]);
		$response = $mp->create_preference($preference);
		          
		if($response['status'] != '201') {
			die('error');
		}
		  
		if($this->params["test_mode"]){
			header("Location: ".$response['response']['sandbox_init_point']);
		}else{
			header("Location: ".$response['response']['init_point']);
		}

/* API step 1 END */

    }
 
    function _notify_url()
    {
    	
		$db = JFactory::getDBO();
		$par= JComponentHelper::getParams( 'com_djclassifieds' );
		$txn_id	= JRequest::getVar('id','0');

/* API step 4 START */

		$mp = new MP($this->params["client_id"], $this->params["client_secret"]);
		
		if($this->params["test_mode"]){
			$mp->sandbox_mode(true);
		}

		$paymentInfo = $mp->get_payment_info($txn_id);

		if($paymentInfo['status'] != '200'){
			return;
		}

		$payment_id	= $paymentInfo['response']['collection']['external_reference'];
		$status = $paymentInfo['response']['collection']['status'];

			if($status=='approved'){
				
				//$this->_setPaymentCompleted($payment_id, $txn_id);
				//$amount = $paymentInfo['response']['collection']['transaction_amount'];

				$query ="SELECT price FROM #__djcf_payments WHERE id=".$payment_id." AND method='".$this->params['plugin_name']."'";
				$db->setQuery($query);
				$amount=$db->loadResult();
				
				if(DJClassifiedsPayment::completePayment($payment_id, $amount)){
					$query = "UPDATE #__djcf_payments SET status='Completed', transaction_id='".$txn_id."' "
							."WHERE id=".$payment_id." AND method='".$this->params['plugin_name']."'";			
					$db->setQuery($query);
					$db->query();
				}
								
			}else if($status=='refunded' || $status=='cancelled'){
					
				$query = "UPDATE #__djcf_payments SET status='Cancelled', transaction_id='".$txn_id."' "
						."WHERE id=".$payment_id." AND method='".$this->params['plugin_name']."'";					
				$db->setQuery($query);
				$db->query();
				
			}else{
				
				$query = "UPDATE #__djcf_payments SET status='Pending', transaction_id='".$txn_id."' "
						."WHERE id=".$payment_id." AND method='".$this->params['plugin_name']."'";					
				$db->setQuery($query);
				$db->query();
				
			}
 
/* API step 4 END */

    }

	/*
	private function _setPaymentCompleted($id, $txn_id) {
		
		$db = JFactory::getDBO();
		$par 	= JComponentHelper::getParams( 'com_djclassifieds' );
		
		$query = "SELECT p.*  FROM #__djcf_payments p "
				."WHERE p.id='".$id."' ";
		$db->setQuery($query);
		$payment = $db->loadObject();
		
		if($payment){

			if($payment->type==3){ //subscription plans			
						$query = "SELECT p.*  FROM #__djcf_plans p WHERE p.id='".$payment->item_id."' ";					
						$db->setQuery($query);
						$plan = $db->loadObject();
						$registry = new JRegistry();
						$registry->loadString($plan->params);
						$plan_params = $registry->toObject();
																												
						$date_start = date("Y-m-d H:i:s");
						$date_exp = '';
						if($plan_params->days_limit){
							$date_exp_time = time()+$plan_params->days_limit*24*60*60;
							$date_exp = date("Y-m-d H:i:s",$date_exp_time) ;
						}
						$query = "INSERT INTO #__djcf_plans_subscr (`user_id`,`plan_id`,`adverts_limit`,`adverts_available`,`date_start`,`date_exp`,`plan_params`) "
								."VALUES ('".$payment->user_id."','".$plan->id."','".$plan_params->ad_limit."','".$plan_params->ad_limit."','".$date_start."','".$date_exp."','".addslashes($plan->params)."')";					
						$db->setQuery($query);
						$db->query();						
						$message = JText::_('COM_DJCLASSIFIEDS_STATUS_CHANGED_SUBSCRIPTION_PLAN_ADDED');
																						
			}else if($payment->type==2){

				$date_sort = date("Y-m-d H:i:s");
				$query = "UPDATE #__djcf_items SET date_sort='".$date_sort."' "
						."WHERE id=".$payment->item_id." ";
				$db->setQuery($query);
				$db->query();
			}else if($payment->type==1){

				$query = "SELECT p.points  FROM #__djcf_points p WHERE p.id='".$payment->item_id."' ";					
				$db->setQuery($query);
				$points = $db->loadResult();
				
				$query = "INSERT INTO #__djcf_users_points (`user_id`,`points`,`description`) "
						."VALUES ('".$payment->user_id."','".$points."','".JText::_('COM_DJCLASSIFIEDS_POINTS_PACKAGE')." - ".$this->params['payment_method']." <br />".JText::_('COM_DJCLASSIFIEDS_PAYMENT_ID').': '.$payment->id."')";					
				$db->setQuery($query);
				$db->query();																		
			}else{

				$query = "SELECT c.*  FROM #__djcf_items i, #__djcf_categories c "
						."WHERE i.cat_id=c.id AND i.id='".$payment->item_id."' ";					
				$db->setQuery($query);
				$cat = $db->loadObject();
				
				$this->applyPromotions($payment->item_id);

				$pub=0;
				if(($cat->autopublish=='1') || ($cat->autopublish=='0' && $par->get('autopublish')=='1')){						
					$pub = 1;							 						
				}
				
				if($pub){
					if(!defined("DS")){ define('DS',DIRECTORY_SEPARATOR);}
					require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_djclassifieds'.DS.'lib'.DS.'djnotify.php');
					DJClassifiedsNotify::notifyUserPublication($payment->item_id,'1');
				}
		
				$query = "UPDATE #__djcf_items SET payed=1, pay_type='', published='".$pub."' "
						."WHERE id=".$payment->item_id." ";					
				$db->setQuery($query);
				$db->query();			
			}
			$dispatcher = JDispatcher::getInstance();
			$dispatcher->trigger('onAfterPaymentStatusChange', array($payment));
		}

	}
	
	private function applyPromotions($item_id){
			$app = JFactory::getApplication();
			$db = JFactory::getDBO();
			
			$query = "SELECT * FROM #__djcf_items_promotions WHERE item_id = ".$item_id." ORDER BY id";
			$db->setQuery($query);
			$old_promotions = $db->loadObjectList('prom_id');
			
			$query = "SELECT * FROM #__djcf_items WHERE id = ".$item_id." LIMIT 1";
			$db->setQuery($query);
			$item = $db->loadObject();
			
			$query = "SELECT * FROM #__djcf_promotions";
			$db->setQuery($query);
			$promotions = $db->loadObjectList('id');
			
			if($item->pay_type){
				$query = "INSERT INTO #__djcf_items_promotions(`item_id`,`prom_id`,`date_exp`,`days`) VALUES ";
				$ins=0;
				$pay_type = explode(',', $item->pay_type);
				foreach($pay_type as $pay_t){
					if(strstr($pay_t, 'p_')){
						$days_left = 0;
						$pay_prom = explode('_', $pay_t);
						$prom_id = $pay_prom[2];
						$prom_days = $pay_prom[3];
						if($prom_id){
							if(isset($old_promotions[$prom_id])){
								if($old_promotions[$prom_id]->date_exp>=date("Y-m-d H:i:s")){																																
									$days_left = strtotime($old_promotions[$prom_id]->date_exp)-time();								
								}
								$query_del = "DELETE FROM #__djcf_items_promotions WHERE item_id=".$item->id." AND prom_id=".$prom_id." ";
								$db->setQuery($query_del);
								$db->query();
							}
							$prom_exp_date = date("Y-m-d G:i:s",mktime(date("G"), date("i"), date("s")+$days_left, date("m")  , date("d")+$pay_prom[3], date("Y")));
							$query .= "('".$item->id."','".$prom_id."','".$prom_exp_date."','".$prom_days."'), ";
							$ins++;
						}
						//print_r($pay_prom);
					}
				}
				//echo $query;die();
				if($ins){
					$query = substr($query, 0, -2).';';
					$db->setQuery($query);
					$db->query();
				}
			}
			
			$date_now = date("Y-m-d H:i:s"); 
			$query = "SELECT * FROM #__djcf_items_promotions WHERE item_id = ".$item_id." AND date_exp>'".$date_now."' ORDER BY id";
			$db->setQuery($query);
			$new_promotions = $db->loadObjectList('prom_id');
			
			$new_prom = '';
			foreach($new_promotions as $prom){
				$new_prom .= $promotions[$prom->prom_id]->name.',';
			}
			
			if(strstr($new_prom, 'p_first')){
				$special = 1;
			}else{ 
				$special = 0;
			}
			
			$query = "UPDATE #__djcf_items SET promotions='".$new_prom."', special='".$special."' WHERE id=".$item_id."  ";
			$db->setQuery($query);
			$db->query();
			
			//echo '<pre>';echo $new_prom;print_r($new_promotions);die();
			
			return $new_promotions; 						
			
		}
	*/

	private function _paymentsuccess() {
		
		$id     = JRequest::getInt("id",'0');
		$cid    = JRequest::getInt("cid",'0');
		$Itemid = JRequest::getInt("Itemid",'0');
		$txn_id = JRequest::get("collection_id",'0');
		
		//$this->_setPaymentCompleted($id, $txn_id);
		$db = JFactory::getDBO();
		$query ="SELECT price FROM #__djcf_payments WHERE id=".$id." AND method='".$this->params['plugin_name']."'";
		$db->setQuery($query);
		$amount=$db->loadResult();
				
		DJClassifiedsPayment::completePayment($id, $amount);	
		  	  
		$location = JRoute::_(JURI::root().'index.php?option=com_djclassifieds&task=paymentReturn&r=ok&id='.$id.'&cid='.$cid.'&Itemid='.$Itemid);
		  
		header("Location: $location");
		
	}
    
	
    function onPaymentMethodList($val)
    {
    	$type='';
    	if($val['type']){
    		$type='&type='.$val['type'];
    	}
        $html ='';
        if ($this->params['client_id'] != '' && $this->params['client_secret'] != '' && $this->params['currency_code'] != '') {
            $paymentLogoPath = JURI::root()."plugins/djclassifiedspayment/".$this->params["plugin_name"]."/".$this->params["plugin_name"]."/images/".$this->params["logo"];
            $form_action = JRoute :: _(JURI::root()."index.php?option=com_djclassifieds&task=processPayment&ptype=".$this->params["plugin_name"]."&pactiontype=process&id=".$val["id"].$type, false);
            $html ='<table cellpadding="5" cellspacing="0" width="100%" border="0">
                <tr>';
                    if($this->params["logo"] != ""){
                $html .='<td class="td1" width="160" align="center">
                        <img src="'.$paymentLogoPath.'" title="'. $this->params["payment_method"].'"/>
                    </td>';
                     }
                    $html .='<td class="td2">
                        <h2>'.$this->params['payment_method'].'</h2>
                        <p style="text-align:justify;">'.$this->params["description"].'</p>
                    </td>
                    <td class="td3" width="130" align="center">
                        <a class="button" style="text-decoration:none;" href="'.$form_action.'">'.JText::_('COM_DJCLASSIFIEDS_BUY_NOW').'</a>
                    </td>
                </tr>
            </table>';
        }

        return $html;
    }

}
?>