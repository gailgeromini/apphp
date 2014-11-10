<?php

class LoginController extends CController
{
    
	public function __construct()
	{
        parent::__construct();
		
        $this->view->errorField = '';
		$this->view->actionMessage = '';
		$this->view->username = '';
		$this->view->password = '';
		A::app()->view->setTemplate('micro');
		$this->view->setMetaTags('title', 'Sign In');
    }

	public function indexAction()
	{
		CAuth::handleLogged('index');

		$this->view->render('login/index');	
	}

	public function logoutAction()
	{
        A::app()->getSession()->endSession();
        $this->redirect('login/index');
	}
	
	public function runAction()
	{
		$cRequest = A::app()->getRequest();
		$this->view->username = $cRequest->getPost('username');
		$this->view->password = $cRequest->getPost('password');
		$msg = '';
		$errorType = '';        		
        
		if($cRequest->getPost('act') == 'send'){

            // perform login form validation
            $result = CWidget::formValidation(array(
                'fields'=>array(
                    'username'=>array('label'=>'Username', 'validation'=>array('required'=>true, 'type'=>'any')),
                    'password'=>array('label'=>'Password', 'validation'=>array('required'=>true, 'type'=>'any')),
                ),            
            ));
            
            if($result['error']){
				 $msg = $result['errorMessage'];
               	 $errorType = 'validation';     
				         
            }else{
				$model = new Login();				
				if($model->login($this->view->username, $this->view->password)){
					CRefactorWriteLogs::WriteLogs('Login success',CAuth::getLoggedId(),1); // write logs login
					$this->redirect('index');	
				}else{
					$msg = 'Wrong username or password ! Please re-enter.';
					$errorType = 'validation';
				}                
            }
        
			if(!empty($msg)){				
				$this->view->username = $cRequest->getPost('username', 'string');
				$this->view->password = $cRequest->getPost('password', 'string');				
				$this->view->actionMessage = CWidget::message($errorType, $msg);
			}			
        }
		$this->view->render('login/index');	
	}
    
}