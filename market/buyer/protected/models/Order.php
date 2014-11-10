<?php

class Order extends CModel
{

	public $gridviews;
	
	public $pagination;
	
	public function __construct()
	{
		parent::__construct();
	
	}
	
	public function buildCartsOrderRefactorPaging($targetPath,$currentPage,$pageSize,$where=null,$type){
		CRefactorPagination::parsePagi($targetPath,$currentPage,$pageSize,"
		SELECT * FROM carts
		WHERE is_paid = 1 AND cart_type = ".$type." AND user_id = ".CAuth::getLoggedId()."
		ORDER BY cart_id DESC"
		);
		$this->gridviews=CRefactorPagination::$resultsPagi;
		$this->pagination=CRefactorPagination::$pagination;
	}
	public function buildBulkOrderRefactorPaging($targetPath,$currentPage,$pageSize,$where=null){
		CRefactorPagination::parsePagi($targetPath,$currentPage,$pageSize,"
		SELECT * FROM bulks
		WHERE m_used_by = ".CAuth::getLoggedId()."
		ORDER BY m_id DESC"
					);
					$this->gridviews=CRefactorPagination::$resultsPagi;
					$this->pagination=CRefactorPagination::$pagination;
	}
	public static function getItemByCartId($id,$type){
		$CModel = new CModel();
		switch ($type){
			case 1: $table = 'cards';
					$item_id = 'card_id';
					$time_used = 'card_used_date';
					$status = 'card_used_status';
					$img_id = 'card_type';
					$info = 'card_info';
				break;
			case 2: $table = 'paypals';
					$item_id = 'paypal_id';
					$time_used = 'paypal_used_date';
					$status = 'paypal_used_status';
					$img_id = 'type_id';
					$info = 'paypal_info';
				break;
		}
		$row = $CModel->db->select("
            SELECT *,
			".$status." AS item_status ,
			".$time_used." AS time_used ,
			AES_DECRYPT(".$info.",'".CConfig::get('encryptKey')."') AS full_info 
			FROM ".$table."
			LEFT JOIN categories ON (".$table.".cate_id = categories.category_id) LEFT OUTER JOIN image_mapping ON (".$table.".$img_id = image_mapping.image_map_id)
			WHERE ".$item_id." = :item_id ",
				array(
						':item_id' => $id,
				)
		);
		return $row[0];
	}
	
	private function newestSession(){
		$CModel = new CModel();
		$row = $CModel->db->select("
		SELECT cart_session FROM carts
		WHERE is_paid = :is_paid AND user_id = :user_id GROUP BY (cart_session)
		ORDER BY cart_id DESC LIMIT 1",
				array(
						':is_paid' => 1,
						':user_id' => CAuth::getLoggedId(),
				)
		);
		if($row){
			return $row[0]['cart_session'];
		}else return false;
	}
	private function getCartById($cart_id){
		$CModel = new CModel();
		$row = $CModel->db->select("
		SELECT cart_item,cart_type,cart_session,cart_price FROM carts
		WHERE is_paid = :is_paid AND user_id = :user_id AND cart_id= :cart_id LIMIT 1",
				array(
						':is_paid' => 1,
						':cart_id' => $cart_id,
						':user_id' => CAuth::getLoggedId(),
				)
		);
		if($row){
			return $row[0];
		}else return false;
	}
	public function builNewestOrder(){
		$CModel = new CModel();
		$row = $CModel->db->select("
		SELECT * FROM carts
		WHERE is_paid = :is_paid AND user_id = :user_id AND cart_session LIKE :cart_session
		ORDER BY cart_id DESC",
				array(
						':is_paid' => 1,
						':cart_session' => $this->newestSession(),
						':user_id' => CAuth::getLoggedId(),
				)
		);
		return $row;
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
	public function addToCheckValid($ojbArray){
	
		if(!empty($ojbArray)){
			$CModel = new CModel();
			try {
				$list = '';
				foreach($ojbArray as $ojb){
					$cart = $this->getCartById($ojb);
					$items = $this->getItemByCartId($cart['cart_item'], $cart['cart_type']);
					if(!CRefactorUltilities::itemExpire($items['time_used'])){
						throw new Exception("Your item(s) really expired . <code>".CConfig::get('setting.time_exp')." minute since you got item(s)</code>");
					}else{
						if($items['item_status'] != 0){
							
							throw new Exception("Your item(s) really added to process , Try different item(s) !");
						}else{
							switch ($cart['cart_type']){
								case 1:
									$data= array(
											'processor_infor'=>$items['full_info'],
											'processor_session'=>$cart['cart_session'],
											'processor_item_id'=>$cart['cart_item'],
											'processor_item_price'=>$cart['cart_price'],
											'processor_type'=>$cart['cart_type'],
											'user_id'=>CAuth::getLoggedId(),
									);
									$CModel->db->insert('cron_tasks_processor',$data);
									$CModel->db->update('cards', array(
											'card_used_status' => 1
											),'card_id='.$cart['cart_item']);
									$list .=$ojb.",";
									CRefactorWriteLogs::WriteLogs('Cronjob has been submitted successfully : #'.$cart['cart_item'].'',CAuth::getLoggedId(),1); // write logs login
									break;
								case 2 :
									$data= array(
											'ticket_subject'=>"Session <code>".$cart['cart_session']."</code>",
											'ticket_priority'=>2,
											'ticket_item_info'=>$items['full_info'],
											'ticket_item_id'=>$cart['cart_item'],
											'ticket_item_price'=>$cart['cart_price'],
											'ticket_item_type'=>$cart['cart_type'],
											'user_id'=>CAuth::getLoggedId(),
									);
									$CModel->db->insert('ticket',$data);
									$ticketId=$this->finddingTicketID($cart['cart_item'],$cart['cart_type']);
									$CModel->db->update('paypals', array(
											'paypal_used_status' => $ticketId
									),'paypal_id='.$cart['cart_item']);
									$list .=$ojb.",";
									CRefactorWriteLogs::WriteLogs('Ticket has been submitted successfully : #'.$ticketId.'',CAuth::getLoggedId(),1); // write logs login
									break;
							}
						}
					}
				}
				
				return array (
						'message'=>count($ojbArray)." Item(s) was successfully added to process. Will take about 30-45 seconds , but will finish quicker .",
						'type'=> 'success',
				);}
				catch (Exception $e) {
					return array (
							'message'=>$e->getMessage(),
							'type'=> 'error',
					);
				}
	
		 }else
			return array (
					'message'=>"As you requested , your item(s) does not found. Try different item(s) !",
					'type'=> 'error',
			);
	}
}
