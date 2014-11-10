<?php

class SignupController extends CController
{
    
	public function __construct()
	{
        parent::__construct();
        
        $this->view->errorField = '';
		$this->view->actionMessage = '';
		$this->view->username = '';
		$this->view->password = '';
		$this->view->repassword = '';
		$this->view->email = '';
		$this->view->captcha = '';
		A::app()->view->setTemplate('micro');
		$this->view->setMetaTags('title', 'Signup');
    }

	public function indexAction()
	{
		CAuth::handleLogged('index');

		$this->view->render('signup/index');	
	}

	public function runAction()
	{
		$cRequest = A::app()->getRequest();
		$this->view->username = $cRequest->getPost('username');
		$this->view->password = $cRequest->getPost('password');
		$this->view->repassword = $cRequest->getPost('repassword');
		$this->view->email = $cRequest->getPost('email');
		$this->view->captcha = $cRequest->getPost('captcha');
		$msg = '';
		$errorType = '';        		
        
		if($cRequest->getPost('act') == 'signup'){

            // perform login form validation
            $result = CWidget::formValidation(array(
                'fields'=>array(
                    'username'=>array('label'=>'Username', 'validation'=>array('required'=>true, 'type'=>'any')),
                    'password'=>array('label'=>'Password', 'validation'=>array('required'=>true, 'type'=>'any')),
                	'repassword'=>array('label'=>'Re-pass', 'validation'=>array('required'=>true, 'type'=>'any')),
                	'email'=>array('label'=>'Email', 'validation'=>array('required'=>true, 'type'=>'any')),
                	'captcha'=>array('label'=>'Captcha', 'validation'=>array('required'=>true, 'type'=>'any')),
                ),            
            ));
            
            if($result['error']){
				 $msg = $result['errorMessage'];
                 $this->view->errorField = $result['errorField'];
               	 $errorType = 'validation';     
				         
            }elseif(trim(strtolower($this->view->captcha)) != $_SESSION['captcha'])
            	{
            		$msg = 'Wrong captcha was entered ! Please re-enter.';
					$this->view->errorField = $result['errorField'];
					$errorType = 'validation';
            	}
            else{
				$model = new Signup();
				if($model->Signup($this->view->email, $this->view->username, $this->view->password, $this->view->repassword)){
				  	 $model->putSignup($this->view->username, $this->view->password, $this->view->email,hash('sha256', CHash::getRandomString(20)));
				  	 $this->redirect('signup/success');
				}else{
					$msg = $model->errorMsg;
					$this->view->errorField  = $result['errorField'];
					$errorType = 'validation';
				}                
            }
			if(!empty($msg)){				
				$this->view->username = $cRequest->getPost('username', 'string');
				$this->view->password = $cRequest->getPost('password', 'string');				
				$this->view->actionMessage = CWidget::message($errorType, $msg);
			}			
        }
		$this->view->render('signup/index');	
	}
	public function successAction(){
		CAuth::handleLogged('index');
		$this->view->actionMessage = 'You have successfully registered with us .Email has been sent the confirmation';
		$this->view->render('signup/success');
	}
	public function activeAction($token=null){
		CAuth::handleLogged('index');
		$token = CFilter::sanitize('string',$token);
		$model = new Signup();
		if($model->isActive($token)){
	
			$this->view->actionMessage = 'Email has been confirmed , your account activated';
			$this->view->Mtype = "success";
		}else{
			$this->view->actionMessage = 'We could not find the validation request you are attempting to verify.';
			$this->view->Mtype = "error";
		}
		$this->view->render('signup/active');
	}
    
}