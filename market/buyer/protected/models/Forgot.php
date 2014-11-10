<?php

class Forgot extends CModel
{
    var $errorMsg = '';
    
    
	public function __construct()
    {
        parent::__construct();

    }
	
    public function forgot($email)
    {
    	$this->isForgot($email);
    	$sums = CRefactorValidator::getSummary();
    	try {
    		foreach($sums as $sum)
    		{
    			if ($sum != 1)
    			{
    				throw new Exception($sum);
    			}
    		}
    	
    	} catch (Exception $e) {
    		$this->errorMsg = $e->getMessage();
    		return false;
    	}
    	return true;
    }   
	public function putForgot($key,$email){
		$result = $this->createPassKey($key,$email);
		if($result){
			$subject = "Opps reset password";
			$message =
			"<p>Dear customers,<br/>
			As you requested, Your reset password are as follows:<br/>
			Click the link <a href='http://www.opps.sx/buyer/forgot/reset/key/$key'>to reset</a><br/>
			To change your password, please visit this service and goto setting account<br/>
			All the best,<br/>
			Thank you for choosing <b>Opps!</b>
			</p>";
			CMailer::smtpMailer($email, $subject, $message,array('from'=>'Opps reset password'));
			return true;
		}else return false;
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
    public function updateResetPass($key,$random){
    	$CModel = new CModel();
    	$CModel->db->update('users',
    			array(
    					'is_reset_password' => NULL,
    					'user_password' => ((CConfig::get('password.encryption')) ? CHash::create(CConfig::get('password.encryptAlgorithm'), $random, CConfig::get('password.hashKey')) : $random),
    			),
    			"is_reset_password='".$key."'"
    	);
    	if($CModel->db->getErrorMessage()){
    		return false;
    	}else return true;
    }
    
    public function isReset($key,$random){
    	$model = new CModel();
    	$result = $model->db->select('
            SELECT *
            FROM users
            WHERE is_reset_password = :is_reset_password',
    			array(
    					':is_reset_password' => $key,
    			)
    	);
    	if(!empty($result)){
    		$subject = "Opps received password";
    		$message =
    		"<p>Dear customers,<br/>
    		As you requested, Your reset password : $random<br/>
    		To change your password, please visit this service and goto setting account<br/>
    		All the best,<br/>
    		Thank you for choosing <b>Opps</b>
    		</p>";
    		CMailer::smtpMailer($result[0]['user_email'], $subject, $message,array('from'=>'Opps received password'));
    		return $this->updateResetPass($key,$random);
    	}else{
    		return false;
    	}
    }
    
    public function createPassKey($key,$email){
    	$CModel = new CModel();
    	$CModel->db->update('users',
    			array(
    					'is_reset_password' => $key,
    			),
    			"user_email='".$email."'"
    	);
    	if($CModel->db->getErrorMessage()){
    		return false;
    	}else return true;
    }
    public static function isForgot($email)
    {
    	CRefactorValidator::setSummary(CRefactorValidator::isBetweenlength($email, 48, 10, 'Email'));
    	CRefactorValidator::setSummary(CRefactorValidator::isEmail($email, 'Email'));
    	CRefactorValidator::setSummary(self::isForgotUnique($email, 'Email'));
    }
    
   
}
