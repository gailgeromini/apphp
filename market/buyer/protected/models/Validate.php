<?php

class Validate extends CModel
{
   
	public function __construct()
    {
        parent::__construct();

    }
	
    //Checking unique value from database
    public function isUnique($value,$table,$column,$label) {
    	$model = new CModel();
    	$result = $model->db->select('
            SELECT '.$column.'
            FROM '.CConfig::get('db.prefix').''.$table.'
            WHERE '.$column.' = :column',
    			array(
    					':column' => $value
    			)
    	);
    	if(count($result) == 0){
    		return true;
    	}else{
    		CRefactorValidator::$summary[]=CRefactorValidator::MsgError(5,$label);
    		return false;
    	}
    }
    public function isForgotUnique($email,$label) {
    	$model = new CModel();
    	$result = $model->db->select('
            SELECT *
            FROM users
            WHERE user_email = :user_email AND user_status = :user_status' ,
    			array(
    					':user_email' => $email,
    					':user_status' => 1,
    			)
    	);
    	if(!empty($result)){
    		return true;
    	}else{
    		CRefactorValidator::$summary[]=CRefactorValidator::MsgError(10,$label);
    		return false;
    	}
    }
    public static function isActiveEmail($email) {
    	$model = new CModel();
    	$result = $model->db->select('
            SELECT *
            FROM users
            WHERE user_email = :user_email AND user_status = :user_status' ,
    			array(
    					':user_email' => $email,
    					':user_status' => 1,
    			)
    	);
    	if(empty($result)){
    		return true;
    	}else{
    		CRefactorValidator::$summary[]=CRefactorValidator::MsgError(9,'Email');
    		return false;
    		
    	}
    }
    public static function validActiveEmail($email)
    {
    	CRefactorValidator::setSummary(self::isActiveEmail($email));
    }
    public static function validEmail($email)
    {
    	CRefactorValidator::setSummary(self::isUnique($email,CConfig::get('db.prefix').'.users','user_email',''));
    }
    public static function validForgotEmail($email)
    {
    	CRefactorValidator::setSummary(self::isForgotUnique($email,'Email'));
    }
    public static function validUser($user)
    {
    	CRefactorValidator::setSummary(self::isUnique($user,CConfig::get('db.prefix').'.users','user_name',''));
    }
    public static function validate($email='',$user='',$actEmail='',$fEmail=''){
    	if(!empty($email))
		{
			self::validEmail($email);
		}
		if(!empty($user))
		{
			self::validUser($user);
		}
		if(!empty($actEmail))
		{
			self::validActiveEmail($actEmail);
		}
		if(!empty($fEmail)){
			self::validForgotEmail($fEmail);
		}
		return reset(CRefactorValidator::getSummary());
    }
    
   
    
   
}
