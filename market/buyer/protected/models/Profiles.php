<?php

class Profiles extends CModel
{

	var $errorMsg = '';
	
	public function __construct()
    {
        parent::__construct();
    }
   
    
    public function save($password)
    {
        $result = $this->db->update(
            'users',
            array(
                'user_password' => ((CConfig::get('password.encryption')) ? CHash::create(CConfig::get('password.encryptAlgorithm'), $password, CConfig::get('password.hashKey')) : $password),
            ),
            'user_id = '.(int)CAuth::getLoggedId()
        );
        return $result;
    }
    public function isActive($email){
    	$this->activeEmail($email);
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
    public function putSentActiveToken(){
    	$user = CRefactorProfile::getProfile(CAuth::getLoggedId());
    	$subject = "Active Code Opps";
    	$message =
    		"<p>Dear customers,<br/>
    		As you requested, Your active token are as follows:<br/>
    		Active Token : <b>".$user['user_active_token']."</b><br/>
    				Click the link <a href='http://www.opps.sx/buyer/signup/active?token=".$user['user_active_token']."'>Active Link</a><br/>
    				To change your password, please visit this service and goto setting account<br/>
    				All the best,<br/>
    				Thank you for choosing <b>Opps</b>
    				</p>";
    	CMailer::smtpMailer($user['user_email'], $subject, $message,array('from'=>'Opps SignUp'));
    	return true;
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
    public function activeEmail($email){
    	CRefactorValidator::setSummary(CRefactorValidator::isBetweenlength($email, 48, 10, 'Email'));
    	CRefactorValidator::setSummary(CRefactorValidator::isEmail($email, 'Email'));
    	CRefactorValidator::setSummary(self::isActiveEmail($email));
    }
  
}	