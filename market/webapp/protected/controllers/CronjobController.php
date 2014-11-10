<?php
ini_set('display_errors',1); 
error_reporting(E_ALL);

class CronjobController extends CController
{

	public $CRefactorPerfectMethod;
	public $Perfect;
	public function __construct()
	{
        parent::__construct();
       $this->Perfect = CRefactorProfile::getPerfect();
	   $this->CRefactorPerfectMethod = new CRefactorPerfectMethod($this->Perfect['Payee_Id'],$this->Perfect['PassPhrase'],$this->Perfect['Payee_Account']);
		 	
    }
	
	public function indexAction()
	{
		$model = new Cronjob();
		$model->cronjobProcess();
		$this->view->render('cronjob/index');
	}
	public function amountAction()
	{
		$message =  CFilter::sanitize('string', $this->CRefactorPerfectMethod->perfectCheckBalance($this->Perfect['Payee_Proxies']));// Check balance amount available
		die($message);
	}
	public function withdrawalAction()
	{
		$amt =  CFilter::sanitize('string', $this->CRefactorPerfectMethod->perfectCheckBalance($this->Perfect['Payee_Proxies']));// Check balance amount available
		switch($amt){
			case "Error: API is disabled for this IP" : 
					$subject = "Error: API is disabled for this IP";
					$message =
					"<p>Dear customers,<br/>
						Error: API is disabled for this IP,<br/>
						Email was sent from <b>Cronjob</b>
					</p>";
					CMailer::smtpMailer('pcpatricia600@gmail.com', $subject, $message);
				  break;
			case "Error: Payer_Account is blocked" :
					$subject = "Error: Payer_Account is blocked";
					$message =
					"<p>Dear customers,<br/>
						Error: Payer_Account is blocked,<br/>
						Email was sent from <b>Cronjob</b>
					</p>";
					CMailer::smtpMailer('pcpatricia600@gmail.com', $subject, $message);
				  break;
			default :
				if($amt >= CConfig::get('min_withdaw')){ // compare balance results
					$amount = ($amt - CConfig::get('fee_withdaw'));
					$message =  $this->CRefactorPerfectMethod->perfectCreateEVoucher($amount,$this->Perfect['Payee_Proxies']); // Create eVoucher when balance > 50$
					$return = json_decode($message,true);
					$subject = "Create e-Vourcher ".$return["ev_batch"];
					$message =
					"<p>Dear,<br/>
					e-Voucher : <b>".$return["ev_num"]."</b><br/>
					Activation code : <b>".$return["ev_code"]."</b><br/>
					Amount: <b>".$return["ev_amount"]."</b><br/>
					All the best,
					</p>";
					CMailer::smtpMailer('pcpatricia600@gmail.com', $subject, $message);
					CRefactorWriteLogs::WriteLogs('e-Voucher has been created successfully : #'.$return["ev_batch"].' '.$return["ev_amount"].'$',1,1); // write logs
				 }else $message = "Money not enough to create Voucher";
		 }
		 $this->view->returnMessage = $message;
		 $this->view->render('cronjob/withdrawal');
	}
}