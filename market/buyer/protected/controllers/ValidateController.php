<?php

class ValidateController extends CController
{

	public function __construct()
	{
    	 A::app()->view->setTemplate('none'); // none html template
    	 $this->view->username = '';
    	 $this->view->email = '';
    }

   	public function actAction()
	{
		$this->view->username = isset($_REQUEST['username']) ? trim($_REQUEST['username']) : "";
		$this->view->email = isset($_REQUEST['email']) ? trim($_REQUEST['email']) : "";
		$this->view->act_email = isset($_REQUEST['act_email']) ? trim($_REQUEST['act_email']) : "";
		$this->view->forgot_email = isset($_REQUEST['femail']) ? trim($_REQUEST['femail']) : "";
		$model = new Validate();
 		header("Expires: Mon, 26 Jul 2020 05:00:00 GMT" );
 		header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
 		header("Cache-Control: no-cache, must-revalidate" );
 		header("Pragma: no-cache" );
 		header("Content-type: text/x-json");
		if($model->validate($this->view->email,$this->view->username,$this->view->act_email,$this->view->forgot_email) != 1){
			echo false;
		}else {
			echo $model->validate($this->view->email,$this->view->username,$this->view->act_email,$this->view->forgot_email);
		}
    }

}