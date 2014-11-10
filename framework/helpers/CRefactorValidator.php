<?php
/**
 * CRefactorValidator helper class file
 *
 * @project Blzone Marketplace
 * @author TOM <BLZONE MARKETPLACE>
 * @link https://www.blzone.ru/
 * @copyright Copyright (c) 2013 ApPHP Framework
 * @version refactor code (1)
 *
 * PUBLIC:					PROTECTED:					PRIVATE:		
 * ----------               ----------                  ----------
 * 
 * 
 * STATIC:
 * ---------------------------------------------------------------
 * 
 */	  

class CRefactorValidator
{
	
	static $summary;

	public static function isConfirm($value,$revalue,$label) {
	
		if($value != $revalue){
			self::$summary[] = self::MsgError(7,$label);
			return false;
		}
		else return true;
	}
	public static function isEmail($value,$label){
		if(CValidator::isEmail($value)){
			return true;
		}
		else{
			self::$summary[] = self::MsgError(2,$label);
			return false;
		}
	}
	public static function isBetweenlength($value,$max,$min,$label) {
	
		if(CValidator::validateMaxlength($value, $max)){
			
			if(CValidator::validateMinlength($value, $min)){
				return true;
			}else{
				self::$summary[] = self::MsgError(6,$label);
				return false;
			}
			
		}else{
			self::$summary[] = self::MsgError(4,$label);
			return false;
		}
	}
	//Set errors into $sumary
	public static function setSummary($the_msg)
	{
		if($the_msg)
			self::$summary[] = $the_msg;
	}
	//Get errors from $summary
	public static function getSummary(){

		return self::$summary;
		
	}
	public static function MsgError($num,$fieldname) {
		$fieldname = str_replace("_",  " ",  $fieldname);
		$msg[0] = "Please correct the following error(s):";
		$msg[1] = "The field ".$fieldname." is empty.";
		$msg[2] = "The e-mail address in field ".$fieldname." is not valid.";
		$msg[3] = "The value in field ".$fieldname." is not valid.";
		$msg[4] = "The text in field ".$fieldname." is too long.";
		$msg[5] = "The value in field ".$fieldname." really existing.";
		$msg[6] = "The value in field ".$fieldname." is too short.";
		$msg[7] = "The value in field ".$fieldname." is not match.";
		$msg[8] = "The value in field ".$fieldname." really active.";
		$msg[9] ="Your entered ".$fieldname." is already associated with another account";
		$msg[10] ="Your entered ".$fieldname." is not associated";
		return $msg[$num] ;
	}
    
}