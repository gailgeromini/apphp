<?php
/**
 * CrefactorPaymentMethod helper class file
 * Last Update : Note here ... (Please put desc under each method)
 */	  

class CRefactorPerfectMethod
{
	
	static $Payee_Id;
	static $PassPhrase;
	static $Payee_Account;
	static $Batch_Email;
	static $Batch_Password;
	
	function __construct($Payee_Id, $PassPhrase ,$Payee_Account){
		$this->Payee_Id = $Payee_Id;
		$this->PassPhrase = $PassPhrase;
		$this->Payee_Account = $Payee_Account;
		$this->Batch_Email = 'inull062';
		$this->Batch_Password = 'KRj7QLSt1qcj8tO';
	}
	
	private static function getBetween($string, $start, $end){
		$string = " ".$string;
		$ini = strpos($string,$start);
		if ($ini == 0) return "";
		$ini += strlen($start);
		$len = strpos($string,$end,$ini) - $ini;
		return substr($string,$ini,$len);
	}
	
	private function getBatchNumberEmail($batch_number){
	
		/* connect to gmail */
		$hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
		$username = $this->Batch_Email;
		$password = $this->Batch_Password;
	
		/* try to connect */
		$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());
	
		/* grab emails */
		$emails = imap_search($inbox,'BODY "'.$batch_number.'"');
		/* if emails are returned, cycle through each... */
		if($emails) {
	
			/* begin output var */
			$output = '';
	
			/* put the newest emails on top */
			rsort($emails);
	
			/* for every email... */
			foreach($emails as $email_number) {
	
				/* get information specific to this email */
				$overview = imap_fetch_overview($inbox,$email_number,0);
				$message = imap_fetchbody($inbox,$email_number,1);
				//var_dump($overview);
				/* output the email header information */
				$output.= '<div class="toggler '.($overview[0]->seen ? 'read' : 'unread').'">';
				$output.= '<span class="subject">'.$overview[0]->subject.'</span> ';
				$output.= '<span class="from">'.$overview[0]->from.'</span>';
				$output.= '<span class="date">on '.$overview[0]->date.'</span>';
				$output.= '</div>';
	
				/* output the email body */
				$output.= '<div class="body">'.$message.'</div>';
			}
			return  $output;
			imap_close($inbox);
		}
		/* close the connection */
	}
	
	public function perfectUsage($batch_number){
		$data = self::getBatchNumberEmail($batch_number);
		if(!$data){
			return json_encode(array(
						'error'=> 'false',
			 ));
		}
		else
		{
			if(strpos($data, "Batch: ".$batch_number."")){
				return json_encode(array(
						'pm_amount'=>self::getBetween($data, "The amount of ", " USD has been deposited to your Perfect Money account"),// get balance
						'pm_batch'=>$batch_number,
						'pm_account'=>self::getBetween($data, ". Accounts: ", "->U"),
				));
			}else return json_encode(array(
						'error'=> 'batch_number false',
			 ));
			
		}
	
	}
	
	private static function cookieUsage() {
		$cookie_file_path = dirname(__FILE__).'/../tmp/'.md5(time().rand(0,999));
		$fp = fopen($cookie_file_path,'wb');fclose($fp);
		return $cookie_file_path;
	}
	
	public function perfectCreateEVoucher($amount,$proxy_ip=null){
		$objArray = array(
				'AccountID' => $this->Payee_Id,
				'PassPhrase' => $this->PassPhrase,
				'Payer_Account' => $this->Payee_Account,
				'Amount' =>$amount
		);
		$url ="https://perfectmoney.is/acct/ev_create.asp";
		$cookie = self::cookieUsage();
		$CrefactorCURL = new CRefactorCURL();
		if($proxy_ip){
			$CrefactorCURL->set_proxy($proxy_ip,1); // set proxy 
		}
		$CrefactorCURL->set_user_agent('Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.4; en-US; rv:1.9.2.2) Gecko/20100316 Firefox/3.6.2');
		$CrefactorCURL->store_cookies($cookie);
		$result = $CrefactorCURL->send_post_data($url,$objArray,null,60);
		if(!$result){
			@unlink($cookie);
			return false;
	
		}elseif(strpos($result, "Error: API is disabled for this IP")){
	
			return "Error: API is disabled for this IP";
				
		}elseif(strpos($result, "Error: Payer_Account is blocked")){
	
			return "Error: Payer_Account is blocked";
				
		}else{
	
			$ev_amount = self::getBetween($result, "<input name='VOUCHER_AMOUNT' type='hidden' value='", "'>");
			$ev_num = self::getBetween($result, "<input name='VOUCHER_NUM' type='hidden' value='", "'>");
			$ev_code = self::getBetween($result, "<input name='VOUCHER_CODE' type='hidden' value='", "'>");
			$ev_batch = self::getBetween($result, "<input name='PAYMENT_BATCH_NUM' type='hidden' value='", "'>");
			return json_encode(array(
					'ev_amount'=>trim($ev_amount),
					'ev_num'=>$ev_num,
					'ev_code'=>$ev_code,
					'ev_batch'=>$ev_batch
			));
		}
	}

	public function eVoucherUsage($ev_num,$ev_code,$proxy_ip=null){
	
		$ojb = array(
				'AccountID' => $this->Payee_Id,
				'PassPhrase' => $this->PassPhrase,
				'Payee_Account' => $this->Payee_Account,
				'ev_number' => $ev_num,
				'ev_code' => $ev_code,
				);
		$url ="https://perfectmoney.is/acct/ev_activate.asp";
		$cookie = self::cookieUsage();
		$CrefactorCURL = new CRefactorCURL();
		if($proxy_ip){
			$CrefactorCURL->set_proxy($proxy_ip,1); // set proxy 
		}
		$CrefactorCURL->set_user_agent('Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.4; en-US; rv:1.9.2.2) Gecko/20100316 Firefox/3.6.2');
		$CrefactorCURL->store_cookies($cookie);
		$result = $CrefactorCURL->send_post_data($url,$ojb,null,60);
		if(!$result){
			return json_encode(array(
						'error'=> 'false',
			 ));
		}elseif(strpos($result, "Error: Can not login with passed AccountID and PassPhrase")){
			return json_encode(array(
					'ev_error'=> 'Error: Can not login with passed AccountID and PassPhrase',
			));
		}elseif(strpos($result, "Error: Invalid ev_number or ev_code") || strpos($result, "Error: Invalid ev_number") || strpos($result, "Error: Invalid ev_code")){
	
			return json_encode(array(
					'ev_error'=> 'Error: Invalid ev_number or ev_code',
			));
	
		}elseif(strpos($result, "Error: API is disabled for this IP")){
			return json_encode(array(
					'ev_error'=> 'Error: API is disabled for this IP',
			));
			
		}elseif(strpos($result, "Error: Payer_Account is blocked")){
			return json_encode(array(
					'ev_error'=> 'Error: Payer_Account is blocked',
			));
			
		}
		elseif(strpos($result, "VOUCHER_AMOUNT")){
			$ev_amount = self::getBetween($result, "<input name='VOUCHER_AMOUNT' type='hidden' value='", "'>");
			$ev_num = self::getBetween($result, "<input name='VOUCHER_NUM' type='hidden' value='", "'>");
			$ev_batch_num = self::getBetween($result, "<input name='PAYMENT_BATCH_NUM' type='hidden' value='", "'>");
			return json_encode(array(
					'ev_amount'=>trim($ev_amount),
					'ev_hash_code'=>md5($ev_num),
					'ev_batch'=>$ev_batch_num,
			));
		}else{
			return json_encode(array(
						'ev_error'=> $result,
			 ));
		}
	
	}
	public function perfectCheckBalance($proxy_ip=null){

		$objArray = array(
				'AccountID' => $this->Payee_Id,
				'PassPhrase' => $this->PassPhrase
		);
		$url ="https://perfectmoney.is/acct/balance.asp";
		$cookie = self::cookieUsage();
		$CrefactorCURL = new CRefactorCURL();
		if($proxy_ip){
			$CrefactorCURL->set_proxy($proxy_ip,1); // set proxy 
		}
		$CrefactorCURL->set_user_agent('Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.4; en-US; rv:1.9.2.2) Gecko/20100316 Firefox/3.6.2');
		$CrefactorCURL->store_cookies($cookie);
		$result = $CrefactorCURL->send_post_data($url,$objArray,null,60);
		if(!$result){
			
			@unlink($cookie);
			return false;
	
		}elseif(strpos($result, "Error: API is disabled for this IP")){
	
			return "Error: API is disabled for this IP";
				
		}elseif(strpos($result, "Error: Payer_Account is blocked")){
	
			return "Error: Payer_Account is blocked";
				
		}else{
			return trim(self::getBetween($result, "<input name='".$this->Payee_Account."' type='hidden' value='", "'>"));
		}
	}
	
}