<?php

class Ticket extends CModel
{

	public $gridviews;
	
	public $pagination;
	
	public function __construct()
	{
		parent::__construct();
	
	}
	
	public function buildTicketRefactorPaging($targetPath,$currentPage,$pageSize,$where=null){
		CRefactorPagination::parsePagi($targetPath,$currentPage,$pageSize,"
		SELECT * FROM ticket
		LEFT JOIN users ON
		ticket.user_id = users.user_id
		LEFT JOIN ticket_status ON
		ticket.ticket_status_id = ticket_status.ticket_status_id
		WHERE users.user_id = ".CAuth::getLoggedId()."
		ORDER BY ticket_id DESC"
		);
		$this->gridviews=CRefactorPagination::$resultsPagi;
		$this->pagination=CRefactorPagination::$pagination;
	}
	public function ticketOwnerExisting($ticketId){
		$CModel = new CModel();
		$row = $CModel->db->select("
            SELECT * FROM ticket
			WHERE ticket_id =:ticket_id AND user_id =:user_id",
				array(
						':ticket_id' => $ticketId,
						':user_id' => CAuth::getLoggedId(),
				)
		);
		if(!empty($row)){
			return true;
		}else return false;
	}
	public function commentPosting($comment,$ticketId){
		$CModel = new CModel();
		$CModel->db->insert('ticket_comments',array(
				'ticket_comment'=>$comment,
				'ticket_id'=>$ticketId,
				'user_id'=>CAuth::getLoggedId(),
		));
		$CModel->db->update('ticket',
				array(
				'ticket_status_id'=>'3',
				),
				'ticket_id='.$ticketId);
		if($CModel->db->getErrorMessage()){
			return false;
		}else return true;
	}
	public function buildTicketDetailByTicketId($ticketId){
		$CModel = new CModel();
		$row = $CModel->db->select("
            SELECT * FROM ticket
			LEFT JOIN users ON
			ticket.user_id = users.user_id
			LEFT JOIN ticket_status ON
			ticket.ticket_status_id = ticket_status.ticket_status_id
			WHERE ticket.ticket_id =:ticket_id AND ticket.user_id =:user_id",
				array(
						':ticket_id' => $ticketId,
						':user_id' => CAuth::getLoggedId(),
				)
		);
		if(!empty($row)){
			return $row[0];
		}else return false;
	}
	public function buildTicketCommentByTicketId($ticketId){
		$CModel = new CModel();
		$row = $CModel->db->select("
            SELECT * FROM ticket_comments
			LEFT JOIN users ON
			ticket_comments.user_id = users.user_id
			WHERE ticket_id =".$ticketId." ORDER BY ticket_comment_id ASC"
		);
		if(!empty($row)){
			return $row;
		}else return false;
	}
	public static function getItemByTypeId($id,$type){
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

}
