<?php

class ErrorController extends CController
{
	public function __construct()
	{
        parent::__construct();
        // block access to this controller for not-logged users
        CAuth::handleLogin();
        $this->view->activeLink= '' ;
		
      		
    }
	
	public function indexAction()
	{
        $this->view->header = 'Error 404: page not found!';
        $this->view->text = 'THE PAGE YOU WERE LOOKING FOR COULD NOT BE FOUND
		<br><br>
		This could be the result of the page being removed, the name being changed or the
		page being temporarily unavailable.
		
		<br><br>
		TROUBLESHOOTING
		<ul>
			<li>If you spelled the URL manually, double check the spelling</li>
			<li>Go to our website\'s home page, and navigate to the content in question</li>
			<li>Alternatively, you can search our website below</li>
		</ul>
		';
		
        $this->view->render('error/index');        
    }	
    
    public function inactiveAction()
    {
    	$cRequest = A::app()->getRequest();
    	if($cRequest->getPost('act_email')){
    		$model = new Profiles();
    		if($model->isActive($cRequest->getPost('act_email'))){
    			$model->putSentActiveToken();
    			$msg = "Successfully ,Email has been sent the confirmation";
    			$this->view->Mtype = 'success';
    		}else{
    			$msg = $model->errorMsg;
    			$this->view->Mtype = 'error';
    		}
    		$this->view->Messages = $msg;
    	}
    	$model = new CRefactorProfile();
    	$info = $model->getProfile(CAuth::getLoggedId());
    	$this->view->email = $info['user_email'];
    	$this->view->header = 'Message : in a restricted area!';
    	$this->view->text = 'THE PAGE YOU WERE LOOKING FOR IS IN A RESTRICTED AREA 
		<br><br>
		This could be the result of the page being removed, You are not authorized to access this page.
    	Your email has not been <strong>confirmed</strong> yet.     	
    	<br><br>
		TROUBLESHOOTING'
		;
    	$this->view->render('error/inactive');
    }
    
    public function bannedAction()
    {
    	
    	$this->view->header = 'Message : Your account has been banned.';
    	$this->view->text = 'THE PAGE YOU WERE LOOKING FOR IS IN A RESTRICTED AREA 
		<br><br>
		Your account has been <strong>banned</strong>.
		<br><br>
		TROUBLESHOOTING
		<ul>
			<li>Please contact your service provider if you feel this is incorrect !</li>
		</ul>
		';
    
    	$this->view->render('error/banned');
    }
    
    public function undepositedAction()
    {
    	$this->view->header = 'Not Payment : deposit credits to solve that problem!';
    	$this->view->text = 'THE PAGE YOU WERE LOOKING FOR IS IN A RESTRICTED AREA
		<br><br>
		This web page is restricted based on your security preferences.Please deposit credits <a href="deposit"><b>[ Add Funds ]</b><a> to solve that problem
		<br><br>
		TROUBLESHOOTING
		<ul>
    		<li>Please deposit credits <a href="deposit"><b>[ Add Funds ]</b><a> to solve that problem !</li>
			<li>Please contact your service provider if you feel this is incorrect !</li>
		</ul>
		';
    
    	$this->view->render('error/undeposited');
    }
}