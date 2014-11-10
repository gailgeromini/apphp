<?php

class Cronjob extends CModel
{

	private function electUsage($cc)
	{
		$user = CConfig::get('checker_api.elect_api_user');
		$pwd = CConfig::get('checker_api.elect_api_password');
		$gate = CConfig::get('checker_api.elect_api_gate');
		$status=file_get_contents(CConfig::get('checker_api.elect_api_uri').'?username='.$user.'&password='.$pwd.'&paygate='.$gate.'&cc='.urlencode($cc));
		if($status){
			return trim($status);
		}else return false;
		
	}
	
	private function apiNo1Usage($cc)
	{
		$user = CConfig::get('checker_api.apino1_api_user');
		$pwd = CConfig::get('checker_api.apino1_api_password');
		$gate = CConfig::get('checker_api.apino1_api_gate');
		$status=file_get_contents(CConfig::get('checker_api.apino1_api_uri').'?user='.$user.'&pass='.$pwd.'&code='.$gate.'&card='.urlencode($cc));
		if($status){
			return trim($status);
		}else return false;
		
	}
	
	private function getDataFromCronjob(){
		$CModel = new CModel();
		$row = $CModel->db->select("
            SELECT * FROM cron_tasks_processor
			WHERE processor_status =:processor_status LIMIT 1",
				array(
						':processor_status' => 0,
				)
		);
		if(!empty($row)){
			return $row;
		}else return false;
	}
	
	private function updateItemProcessed($id,$status){
		$CModel = new CModel();
		$CModel->db->update('cards',
				array(
						'card_used_status' => $status,
				),
				"card_id='".$id."'"
		);
		
	}
	
	private function updateCronjob($id){
		$CModel = new CModel();
		$CModel->db->update('cron_tasks_processor',
				array(
				'processor_status' => 1,
				),
				"processor_id='".$id."'"
		);
		if($CModel->db->getErrorMessage()){
			return false;
		}else return true;
	
	}
	
	private function createReportTicket($ojbArray){
		$CModel = new CModel();
		$CModel->db->insert('ticket',$ojbArray);
		if($CModel->db->getErrorMessage()){
			return false;
		}else return true;
	}
	
	private function refundCredit($uId,$credits){
		$CModel = new CModel();
		$CModel->db->update('users',
				array(
				'user_credits' => ((CRefactorProfile::getBalance($uId)) + $credits),
				),
				"user_id='".$uId."'"
		);
		if($CModel->db->getErrorMessage()){
			return false;
		}else return true;
	}
	public function finddingTicketID($itemID,$itemType){
		$CModel = new CModel();
		$row = $CModel->db->select("
		SELECT ticket_id FROM ticket
		WHERE ticket_item_id = :ticket_item_id AND ticket_item_type = :ticket_item_type
		",
				array(
						':ticket_item_id' => $itemID,
						':ticket_item_type' => $itemType,
				)
		);
		return $row[0]['ticket_id'];
	}
	public function cronjobProcess(){
		$list = $this->getDataFromCronjob();
		foreach ($list as $row){
			$status = (CConfig::get('checker_api.default') == 'elect')?$this->electUsage($row['processor_infor']):$this->apiNo1Usage($row['processor_infor']);
			switch ($status)
			{
				case "LIVE" :
					$this->updateCronjob($row['processor_id']); // remove cronjob
					$this->updateItemProcessed($row['processor_item_id'],2); //  update status item
					break;
				case "DIE" :
					$this->updateCronjob($row['processor_id']); // remove cronjob
					$this->updateItemProcessed($row['processor_item_id'],3); //  update status item
					$this->refundCredit($row['user_id'],$row['processor_item_price']); //  update credits
					CRefactorWriteLogs::WriteLogs('Refunded have been successfully processed with UID'.$row['user_id'].' : #'.$row['processor_item_id'].'',1,1); // write logs 
					break;
				case "EXPIRED" :
					$this->updateCronjob($row['processor_id']); // remove cronjob
					$this->updateItemProcessed($row['processor_item_id'],3); //  update status item
					$this->refundCredit($row['user_id'],$row['processor_item_price']); //  update credits
					CRefactorWriteLogs::WriteLogs('Refunded have been successfully processed with UID'.$row['user_id'].' : #'.$row['processor_item_id'].'',1,1); // write logs 
					break;
				default :
					$ticket = array(
							'ticket_subject'=>"Session <code>".$row['processor_session']."</code>",
							'ticket_priority'=>2,
							'ticket_item_info'=>$row['processor_infor'],
							'ticket_item_id'=>$row['processor_item_id'],
							'ticket_item_price'=>$row['processor_item_price'],
							'ticket_item_type'=>1,
							'user_id'=>$row['user_id'],
					);
					$this->updateCronjob($row['processor_id']); // remove cronjob
					$this->createReportTicket($ticket);
					$ticketId= $this->finddingTicketID($row['processor_item_id'],1);
					$this->updateItemProcessed($row['processor_item_id'],$ticketId); //  update status item
					CRefactorWriteLogs::WriteLogs('Ticket has been submitted successfully : #'.$ticketId.'',$row['user_id'],1); // write logs 
					break;
			}
		}
	}
}
