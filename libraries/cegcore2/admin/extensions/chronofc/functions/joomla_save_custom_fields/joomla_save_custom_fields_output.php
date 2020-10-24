<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	if(strlen($function['itemid_provider']) == 0){
		$this->Parser->debug[$function['name']]['_error'] = rl('Aborting, no item id value.');
		$this->set($function['name'], false);
		return;
	}
	
	$itemid = $this->Parser->parse($function['itemid_provider'], true);
	
	if(!empty($function['fields'])){
		
		$Field_Model = new \G2\L\Model(['name' => 'Field', 'table' => '#__fields']);
		$FieldValue_Model = new \G2\L\Model(['name' => 'FieldValue', 'table' => '#__fields_values']);
		
		list($fields_data, $fields) = $this->Parser->multiline($function['fields']);
		
		if(is_array($fields_data)){
			foreach($fields_data as $fields_line){
				if(!empty($fields_line['name']) AND !empty($fields_line['value'])){
					$field_data = $Field_Model->where('Field.name', $fields_line['name'])->select('first');
					if(!empty($field_data)){
						
						$FieldValue_Model
						->where('field_id', $field_data['Field']['id'])
						->where('item_id', $itemid)
						->delete();
						
						$FieldValue_Model->insert([
							'field_id' => $field_data['Field']['id'],
							'item_id' => $itemid,
							'value' => $this->Parser->parse($fields_line['value'], true),
						]);
						
						$this->Parser->debug[$function['name']]['info'][] = rl('The field named "%s" value has been saved as "%s"', [$fields_line['name'], $fields_line['value']]);
					}
				}
			}
		}
	}