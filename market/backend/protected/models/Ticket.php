<?php

class Ticket extends CModel
{

	public $gridviews;
	
	public $pagination;
	
	public function __construct()
	{
		parent::__construct();
	
	}

    public static function cDropList($default= null){
        $dropdown = '';
        $CModel = new CModel();
        $results = $CModel->db->select('
            SELECT item_type_id,item_type_name
            FROM item_types
            limit 2'
        );
        if(count($results) > 0){
            foreach ($results as $result) {
                $value=$result['item_type_id'];
                $name=$result['item_type_name'];
                $attr = ($default != null && $value == $default ? "selected='selected'" : "");
                $dropdown .= "<option value='".$value."' $attr>".$name."</option>";
            }
            return "<option value='0'>All Type</option>".$dropdown;
        }else false;
    }

    public static function sDropList($default= null){
        $dropdown = '';
        $CModel = new CModel();
        $results = $CModel->db->select('
            SELECT ticket_status_id,ticket_status_name
            FROM ticket_status'
        );
        if(count($results) > 0){
            foreach ($results as $result) {
                $value=$result['ticket_status_id'];
                $name=$result['ticket_status_name'];
                $attr = ($default != null && $value == $default ? "selected='selected'" : "");
                $dropdown .= "<option value='".$value."' $attr>".$name."</option>";
            }
            return "<option value='0'>All Status</option>".$dropdown;
        }else false;
    }

	public function buildTicketRefactorPaging($targetPath,$currentPage,$pageSize,$where=null){
		CRefactorPagination::parsePagi($targetPath,$currentPage,$pageSize,'
		SELECT * FROM ticket
		LEFT JOIN users ON
		ticket.user_id = users.user_id
		LEFT JOIN ticket_status ON
		ticket.ticket_status_id = ticket_status.ticket_status_id
		WHERE ticket.ticket_id > 0 '.$where.'
		ORDER BY ticket_id DESC'
		);
		$this->gridviews=CRefactorPagination::$resultsPagi;
		$this->pagination=CRefactorPagination::$pagination;
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
				'ticket_status_id'=>'2',
				),
				'ticket_id='.$ticketId);
		if($CModel->db->getErrorMessage()){
			return false;
		}else return true;
	}
	public function commentReplacement($comment,$ticketId){
		$ticket = $this->buildTicketDetailByTicketId($ticketId);
		$CModel = new CModel();
		$CModel->db->insert('ticket_comments',array(
				'ticket_comment'=>$comment,
				'ticket_id'=>$ticketId,
				'user_id'=>CAuth::getLoggedId(),
		));
		$CModel->db->update('ticket',
				array(
						'ticket_status_id'=>'4',
				),
				'ticket_id='.$ticketId);
		$this->refundCredit($ticket['user_id'],$ticket['ticket_item_price']);
		CRefactorWriteLogs::WriteLogs('Refunded have been successfully processed with UID'.$ticket['user_id'].' : #'.$ticket['ticket_id'].'',1,1); // write logs
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
	
	public function buildTicketDetailByTicketId($ticketId){
		$CModel = new CModel();
		$row = $CModel->db->select("
            SELECT * FROM ticket
			LEFT JOIN users ON
			ticket.user_id = users.user_id
			LEFT JOIN ticket_status ON
			ticket.ticket_status_id = ticket_status.ticket_status_id
			WHERE ticket.ticket_id =:ticket_id",
				array(
						':ticket_id' => $ticketId,
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

    public static function buildPAYWhere($type,$status,$extension){
        /*
         * keep search condition
         */
        $session = A::app()->getSession();
        $session->set('tickettype',CFilter::sanitize('integer',trim($type)));
        $session->set('ticketstatus',CFilter::sanitize('integer',trim($status)));
        $session->set('ticketyextension',trim($extension));
        // ----------------------->
        $CWhere = '';

        if(!empty($type)){
            $CWhere .= " AND ticket_item_type = '".CFilter::sanitize('integer',trim($type))."'"; // search by payment type
        }

        if(!empty($status)){
            $CWhere .= " AND ticket.ticket_status_id = '".CFilter::sanitize('integer',trim($status))."'"; // search by payment type
        }

        if(!empty($extension) && $extension != ''){

            $extension = explode(",",$extension);

            if(isset($extension[0])){
                $CWhere .= " AND users.user_name LIKE '".trim($extension[0])."%'"; // search by user
            }
        }
        return trim($CWhere);
    }
    public static function removePWhere(){
        $session = A::app()->getSession();
        unset($_SESSION['tickettype']);
        unset($_SESSION['ticketyextension']);
    }
}
