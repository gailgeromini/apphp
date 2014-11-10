<?php

class ForgotController extends CController
{
    
	public function __construct()
	{
        parent::__construct();
		
        $this->view->errorField = '';
		$this->view->actionMessage = '';
		$this->view->username = '';
		$this->view->password = '';
		A::app()->view->setTemplate('micro');
		$this->view->setMetaTags('title', 'Forgot');
    }

	public function indexAction()
	{
		CAuth::handleLogged('index');

		$this->view->render('forgot/index');	
	}
	public function runAction()
	{
		$cRequest = A::app()->getRequest();
		$this->view->femail = $cRequest->getPost('femail');
		$msg = '';
		$errorType = '';        		
        
		if($cRequest->getPost('act') == 'send'){

            // perform login form validation
            $result = CWidget::formValidation(array(
                'fields'=>array(
                    'femail'=>array('label'=>'Email', 'validation'=>array('required'=>true, 'type'=>'any')),
                ),            
            ));
            if($result['error']){
				 $msg = $result['errorMessage'];
				 $this->view->errorField = $result['errorField'];
               	 $errorType = 'validation';     
				         
            }else{
				$model = new Forgot();				
				if($model->forgot($this->view->femail)){
					$model->putForgot(hash('sha256', CHash::getRandomString(10)),$this->view->femail);
					$this->redirect('forgot/success');	
				}else{
					$msg = $model->errorMsg;
					$this->view->errorField  = $result['errorField'];
					$errorType = 'validation';
				}                
            }
			if(!empty($msg)){				
				$this->view->femail = $cRequest->getPost('femail', 'string');
				$this->view->actionMessage = CWidget::message($errorType, $msg);
			}			
        }
		$this->view->render('forgot/index');	
	}
	
	public function successAction(){
		CAuth::handleLogged('index');
		$this->view->actionMessage = 'A new password has been sent to your email address';
		$this->view->render('forgot/success');
	}
	
	public function resetAction($key=null){
		CAuth::handleLogged('index');
		$key = CFilter::sanitize('string',$key);
		$model = new Forgot();
		if($model->isReset($key,CHash::getRandomString(10))){
	
			$this->view->actionMessage = 'Your password has been successfully reset.';
			$this->view->Mtype = "success";
		}else{
			$this->view->actionMessage = 'We could not find the validation request you are attempting to verify.';
			$this->view->Mtype = "error";
		}
		$this->view->render('forgot/reset');
	}
    
}