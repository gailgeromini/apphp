<?php

class Signup extends CModel
{
    var $errorMsg = '';
    
    
	public function __construct()
    {
        parent::__construct();

    }
	
    public function Signup($email, $user, $pass, $repass)
    {
    	$this->isSignup($email, $user, $pass, $repass);
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
	public function putSignup($username,$pass,$email,$token){
		$result = $this->db->insert(
				'users',
				 array(
						'user_name' => $username,
						'user_password' => ((CConfig::get('password.encryption')) ? CHash::create(CConfig::get('password.encryptAlgorithm'), $pass, CConfig::get('password.hashKey')) : $pass),
				 		'user_email'=>$email,
				 		'user_active_token'=>$token
				 		)
		);
		if($result){
			$subject = "Welcome to Opps";
			$message =
			"<p>Dear customers,<br/>
			As you requested, Your account are as follows:<br/>
			User Name : <b>$username</b><br/>
			Password : <b>".CRefactorUltilities::replSOject($pass,4,'*****')."</b><br/>
			Click the link <a href='http://www.opps.sx/buyer/signup/active/token/$token'>Active Link</a><br/>
			To change your password, please visit this service and goto setting account<br/>
			All the best,<br/>
			Thank you for choosing <b>Opps</b>
			</p>";
			CMailer::smtpMailer($email, $subject, $message,array('from'=>'Opps SignUp'));
			return true;
		}else return false;
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
    public function isActive($token){
    	$model = new CModel();
    	$result = $model->db->select('
            SELECT *
            FROM users
            WHERE user_active_token = :user_active_token AND user_status=:user_status',
    			array(
    					':user_active_token' => $token,
    					':user_status' => 0,
    			)
    	);
    	if(!empty($result)){
    		return $this->updateActiveToken($token);
    	}else{
    		return false;
    	}
    }
    public function updateActiveToken($token){
    	$CModel = new CModel();
    	$CModel->db->update('users',
    			array(
    					'user_status' => 1,
    			),
    			"user_active_token='".$token."'"
    	);
    	if($CModel->db->getErrorMessage()){
    		return false;
    	}else return true;
    }
    public static function isSignup($email,$user,$pass,$repass)
    {
    	CRefactorValidator::setSummary(CRefactorValidator::isBetweenlength($user, 48, 4, 'Username'));
    	CRefactorValidator::setSummary(self::isUnique($user,CConfig::get('db.prefix').'.users','user_name','Username'));
    	CRefactorValidator::setSummary(CRefactorValidator::isBetweenlength($email, 48, 10, 'Email'));
    	CRefactorValidator::setSummary(CRefactorValidator::isEmail($email, 'Email'));
    	CRefactorValidator::setSummary(self::isUnique($email,CConfig::get('db.prefix').'.users','user_email','Email'));
    	CRefactorValidator::setSummary(CRefactorValidator::isConfirm($pass, $repass, 'Confirm Password'));
    }
    
   
}
