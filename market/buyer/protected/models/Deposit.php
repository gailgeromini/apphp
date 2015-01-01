<?php

class Deposit extends CModel
{

	public $gridviews;
	
	public $pagination;
	
	public function __construct()
	{
		parent::__construct();
	
	}

	public function buildRefactorPaging($targetPath,$currentPage,$pageSize,$userid){
		CRefactorPagination::parsePagi($targetPath,$currentPage,$pageSize,'
		SELECT payment_amount
			 , payment_date
			 , payment_account
			 , payment_updated
			 , payment_commis
			 , payment_batch
			 , case when payment_method_id = 1 then "templates/default/files/images/pm40x16.png"
			   else "templates/default/files/images/btc40x16.png"
			   end as payment_method
		 FROM '.CConfig::get('db.prefix') .'.payments
		 WHERE user_id = '.$userid.'
		 ORDER BY payment_date DESC'
		);
		$this->gridviews=CRefactorPagination::$resultsPagi;
		$this->pagination=CRefactorPagination::$pagination;
	}
	
	public function depositPerfectMethod($eVoucher_number,$eVoucher_code){
		 $eVoucher_number = CFilter::sanitize('alphanumeric', $eVoucher_number);
		 $eVoucher_code = CFilter::sanitize('alphanumeric', $eVoucher_code);
		 if($this->paymentDuplicateCheck(strtoupper(hash('sha256', $eVoucher_number)))){
		 		$Perfect = CRefactorProfile::getPerfect();
		 		$CRefactorPerfectMethod = new CRefactorPerfectMethod($Perfect['Payee_Id'],$Perfect['PassPhrase'],$Perfect['Payee_Account']);
		 		$data = json_decode($CRefactorPerfectMethod->eVoucherUsage($eVoucher_number,$eVoucher_code,$Perfect['Payee_Proxies']),true);
		 		if(isset($data['error'])){ // error return
		 			CRefactorWriteLogs::WriteLogs("There is a problem parsing data : #".$eVoucher_number."",CAuth::getLoggedId(),1); //
					return array (
		 				'message' => "There is a problem parsing data : <code>#".$eVoucher_number."</code>",
		 				'type'=> 'alert',
		 			);
		 		}elseif(isset($data['ev_error'])){
		 			CRefactorWriteLogs::WriteLogs($data['ev_error'].'',CAuth::getLoggedId(),1); //
		 			return array (
		 					'message' => $data['ev_error'],
		 					'type'=> 'alert',
		 			);
		 		}
		 		elseif(isset($data['ev_amount'])){ // bacth found
		 			if($data['ev_amount'] >= CConfig::get('pm_method.perfect_min_pay')){
		 			$payment = array(
		 					'user_id' => CAuth::getLoggedId(),
		 					'payment_amount' => $data['ev_amount'],
		 					'payment_batch' => $eVoucher_number,
		 					'payment_token' => strtoupper(hash('sha256', $eVoucher_number)),
		 					'payment_account' => 'e-Voucher',
		 					'payment_updated' => CRefactorProfile::getBalance(CAuth::getLoggedId()) + $data['ev_amount'],
		 					'payment_method_id' => 1,
		 				);
		 			$this->updateCredits($data['ev_amount']);
		 			$this->logsPayment($payment);
		 			CRefactorWriteLogs::WriteLogs('Once Deposit has been successful : '.$data['ev_amount'].'$',CAuth::getLoggedId(),1); // write logs login
		 				return array (
		 					'message' => "Once Deposit has been successful : <code>". $data['ev_amount']."$</code>",
		 					'type'=> 'success',
		 				);
		 			}else{ 
					CRefactorWriteLogs::WriteLogs('Minimum payment could not process : '.$data['ev_amount'].'$',CAuth::getLoggedId(),1); // write logs login
						return array (
		 				'message' => "We could not process the hash code request , Minimum payment is : <code>".CConfig::get('pm_method.perfect_min_pay')."$</code>",
		 				'type'=> 'error',
						);
					}
		 		}else return array (
		 			'message' => "We could not process the hash code request : <code>#".$eVoucher_number."</code>",
		 			'type'=> 'error',
		 		);
		 	
		  }else return array (
		 			'message' => "Duplicate batch number : <code>#".$eVoucher_number."</code>",
		 			'type'=> 'notice',
		  );
		
	 }
	 
	 public function depositBatchMethod($batchNumber){
	 	$batch_number = CFilter::sanitize('alphanumeric', $batchNumber);
	 	if($this->paymentDuplicateCheck(strtoupper(hash('sha256', $batch_number)))){
	 		$CRefactorPerfectMethod = new CRefactorPerfectMethod('','','');
	 		$data = json_decode($CRefactorPerfectMethod->perfectUsage($batch_number));
	 		if(isset($data['error'])){ // error return
	 			CRefactorWriteLogs::WriteLogs("There is a problem with batch number : #".$batch_number."",CAuth::getLoggedId(),1); //
	 			return array (
	 					'message' => "There is a problem parsing batch number : <code>#".$batch_number."</code>",
	 					'type'=> 'alert',
	 			);
	 		}elseif(isset($data['ev_amount'])){ // bacth found
	 			if($data['pm_amount'] >= CConfig::get('pm_method.perfect_min_pay')){
	 				$payment = array(
	 						'user_id' => CAuth::getLoggedId(),
	 						'payment_amount' => $data['pm_amount'],
	 						'payment_batch' => $batch_number,
	 						'payment_token' => strtoupper(hash('sha256', $batch_number)),
	 						'payment_account' => 'Perfect Money',
	 						'payment_updated' => CRefactorProfile::getBalance(CAuth::getLoggedId()) + $data['ev_amount'],
	 						'payment_method_id' => 1,
	 				);
	 				$this->updateCredits($data['ev_amount']);
	 				$this->logsPayment($payment);
	 				CRefactorWriteLogs::WriteLogs('Once Deposit has been successful : '.$data['pm_amount'].'$',CAuth::getLoggedId(),1); // write logs login
	 				return array (
	 						'message' => "Once Deposit has been successful : <code>". $data['pm_amount']."$</code>",
	 						'type'=> 'success',
	 				);
	 			}else{
	 				CRefactorWriteLogs::WriteLogs('Minimum payment could not process : '.$data['pm_amount'].'$',CAuth::getLoggedId(),1); // write logs login
	 				return array (
	 						'message' => "We could not process the hash batch request , Minimum payment is : <code>".CConfig::get('pm_method.perfect_min_pay')."$</code>",
	 						'type'=> 'error',
	 				);
	 			}
	 		}else return array (
	 				'message' => "We could not process the hash batch request : <code>#".$batch_number."</code>",
	 				'type'=> 'error',
	 		);
	 
	 	}else return array (
	 			'message' => "Duplicate batch number : <code>#".$batch_number."</code>",
	 			'type'=> 'notice',
	 	);
	 
	 }
	 public function depositBitcoinMethod($address){
	 	$btc_address = CFilter::sanitize('string', $address);
	 	if($this->paymentDuplicateCheck(strtoupper(hash('sha256', $btc_address)))){
	 		$data = json_decode(CRefactorBitcoinMethod::BitcoinUsage($btc_address),true);
	 		if(isset($data['error'])){ // error return
	 			return array (
	 					'message' => "Not exist address : <code>#".$btc_address."</code>",
	 					'type'=> 'alert',
	 			);
	 		}elseif(isset($data['btc_amount']) && $data['btc_amount'] != 0){ // bacth found
	 			$btc_to_usd = CRefactorBitcoinMethod::BtcToUsd($data['btc_amount']);
	 			if($btc_to_usd >= CConfig::get('btc_method.bitcoin_min_pay')){
	 				$payment = array(
	 						'user_id' => CAuth::getLoggedId(),
	 						'payment_amount' => $btc_to_usd,
	 						'payment_batch' => CRefactorUltilities::replSOject(time(),8),
	 						'payment_token' => strtoupper(hash('sha256', $data['hash_code'])),
	 						'payment_account' => 'Bitcoin E-USD',
	 						'payment_updated' => CRefactorProfile::getBalance(CAuth::getLoggedId()) + $btc_to_usd,
	 						'payment_method_id' => 2,
	 				);
	 				$this->updateCredits($btc_to_usd);
	 				$this->logsPayment($payment);
	 				unset($_SESSION['btc_address']);
	 				unset($_SESSION['btc_rate']);
	 				CRefactorWriteLogs::WriteLogs('Once Deposit has been successful : '.$data['btc_amount'].'BTC',CAuth::getLoggedId(),1); // write logs login
	 				return array (
	 						'message' => "Once Deposit has been successful : <code>". $data['btc_amount']."BTC</code>  {0$ Commision}",
	 						'type'=> 'success',
	 				);
	 			}else return array (
	 					'message' => "We could not process the hash code request , Minimum payment is : <code>".CConfig::get('btc_method.bitcoin_min_pay')."$</code>",
	 					'type'=> 'error',
	 			);
	 		}else return array (
	 				'message' => "We could not process address : <code>#".$btc_address."</code>",
	 				'type'=> 'error',
	 		);
	 
	 	}else return array (
	 			'message' => "Duplicate process address : <code>#".$btc_address."</code>",
	 			'type'=> 'notice',
	 	);
	 
	 }
	public function updateCredits($balance){
		$CModel = new CModel();
		$CModel->db->update('users',
				array(
						'user_credits' => ((CRefactorProfile::getBalance(CAuth::getLoggedId())) + $balance),
				),
				"user_id='".CAuth::getLoggedId()."'"
		);
		if($CModel->db->getErrorMessage()){
			return false;
		}else return true;
	}
	public function logsPayment($ojbArray){
		$CModel = new CModel();
		$CModel->db->insert('payments',$ojbArray);
		if($CModel->db->getErrorMessage()){
			return false;
		}else return true;
	}
	public function paymentDuplicateCheck($token){
		$CModel = new CModel();
		$row = $CModel->db->select("
            SELECT * FROM payments
			WHERE payment_token =:payment_token",
				array(
						':payment_token' => $token,
				)
		);
		if(empty($row)){
			return true;
		}else return false;
	}
	
}
