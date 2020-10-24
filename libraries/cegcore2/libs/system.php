<?php
/**
* ChronoCMS version 1.0
* Copyright (c) 2012 ChronoCMS.com, All rights reserved.
* Author: (ChronoCMS.com Team)
* license: Please read LICENSE.txt
* Visit http://www.ChronoCMS.com for regular updates and information.
**/
namespace G2\L;
/*** FILE_DIRECT_ACCESS_HEADER ***/
defined("GCORE_SITE") or die;
class System {
	
	public static function pdo(){
		if(phpversion('pdo') !== false AND in_array('mysql', \PDO::getAvailableDrivers())){
			return true;
		}else{
			return false;
		}
	}
}