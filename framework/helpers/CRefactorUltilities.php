<?php
/**
 * Ultilities helper class file
 * Last Update : Note here ... (Please put desc under each method)
 */	  

class CRefactorUltilities extends CModel
{
	public static function replSOject($obj,$len = null,$string = null){
		
		return substr(trim($obj),0,$len).$string;
		
	}
	
	public static function replIObject($ojb){
		if($ojb == 1){
			return CHtml::image("templates/default/files/images/yes_x16.png"); // display icon yes or true...
		}else return CHtml::image("templates/default/files/images/no_x16.png"); // display icon no or false...
	}
	
	public static function flagsObject($ojb){
		if(isset($ojb)){
			return CHtml::image("templates/default/files/images/flags/".$ojb.".png"); // display icon flags
		}else return CHtml::image("templates/default/files/images/flags/unknow.png"); // unknow
	}
	
	public static function itemExpire($time_used)
	{
		$current=date('Y-m-d H:i:s', time() );
		$diff = strtotime($current) - strtotime($time_used);
		$acDays = floor($diff/(60));
		if($acDays <= CConfig::get('setting.time_exp') ){
			return true;
		}else return false;
		 
	}
	
}