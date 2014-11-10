<?php

class ProfileController extends CController
{

	public function __construct()
	{
        parent::__construct();

        // block access to this controller for not-logged users
		CAuth::handleLogin();		
		A::app()->view->setTemplate('none');
    }

   	public function editAction()
	{
		
		$cRequest = A::app()->getRequest();
        $this->view->password = $cRequest->getPost('password');
        $model = new CRefactorProfile();
        $info = $model->getProfile(CAuth::getLoggedId());
		if($cRequest->getPost('act') == 'send'){
			if(!$this->view->password){
				$this->redirect('./index');
			}else{
				if(strlen($this->view->password) > 5){
					$CModel = new Profiles();
					$CModel->save($this->view->password);
					echo "<script type='text/javascript'>alert('Your password has been changed. Use this password to login to the system from now.');location = '".A::app()->getRequest()->getBaseUrl()."'</script>";
					exit;
				}else{
					echo "<script type='text/javascript'>alert('Password must be contain at least 6 characters');location = '".A::app()->getRequest()->getBaseUrl()."'</script>";
					exit;
				}
			}
			
        }else{
            $this->view->username = $info['user_name'];
            $this->view->email = $info['user_email'];
        }
        
       
        $this->view->render('profile/edit');		
    }

}