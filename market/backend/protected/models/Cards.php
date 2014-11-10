<?php

class Cards extends CModel
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
            SELECT distinct (card_country)
            FROM cards'
		);
		if(count($results) > 0){
		foreach ($results as $result) {
			$value=$result['card_country'];
			$name=$result['card_country'];
			$attr = ($default != null && $value == $default ? "selected='selected'" : "");
			$dropdown .= "<option value='".$value."' $attr>".$name."</option>";
			
		}
		return "<option value='all'>All Country</option>".$dropdown;
		}else false;
	}
	
	public static function tDropList($default= null){
		$dropdown = '';
		$CModel = new CModel();
		$results = $CModel->db->select('
            SELECT image_map_id,image_map_name
            FROM image_mapping
            WHERE item_type_id = :item_type_id ',
				array(
						':item_type_id' => 1,
				)
		);
		if(count($results) > 0){
		foreach ($results as $result) {
			$value=$result['image_map_id'];
			$name=$result['image_map_name'];
			$attr = ($default != null && $value == $default ? "selected='selected'" : "");
			$dropdown .= "<option value='".$value."' $attr>".$name."</option>";
				
		}
		return "<option value='0'>All Type</option>".$dropdown;
		}else false;
	}
	
	public static function ctDropList($default= null){
		$dropdown = '';
		$CModel = new CModel();
		$results = $CModel->db->select('
            SELECT category_id,category_name
            FROM categories
            WHERE item_type_id = :item_type_id ',
				array(
						':item_type_id' => 1,
				)
		);
		if(count($results) > 0){
		foreach ($results as $result) {
			$value=$result['category_id'];
			$name=$result['category_name'];
			$attr = ($default != null && $value == $default ? "selected='selected'" : "");
			$dropdown .= "<option value='".$value."' $attr>".$name."</option>";
	
		}
		return "<option value='0'>All Category</option>".$dropdown;
		}else false;
	}
	
	public static function sDropList($default= null){
			$obj = array ('0'=>'All Status','1'=>'Approved','2'=>'Declined','3'=>'Ticket Submited') ;
			$count = count($obj);
			for($i=0;$i<$count;$i++){
				$value=$i;
				$name=$obj[$i];
				$attr = ($default != null && $value == $default ? "selected='selected'" : "");
				$dropdown .= "<option value='".$value."' $attr>".$name."</option>";
			}
			return $dropdown;
					
	}
	public static function uDropList($default= null){
		$obj = array ('0'=>'All Cards','1'=>'Card Used') ;
		$count = count($obj);
		for($i=0;$i<$count;$i++){
			$value=$i;
			$name=$obj[$i];
			$attr = ($default != null && $value == $default ? "selected='selected'" : "");
			$dropdown .= "<option value='".$value."' $attr>".$name."</option>";
		}
		return $dropdown;
			
	}
	public function buildRefactorPaging($targetPath,$currentPage,$pageSize,$where=null){
		
		$order_by = 'card_id';
		if(A::app()->getSession()->get('castatus') || A::app()->getSession()->get('cause')){
			$order_by = "card_used_date";
		}
		CRefactorPagination::parsePagi($targetPath,$currentPage,$pageSize,"
		SELECT *,
		AES_DECRYPT(card_info,'".CConfig::get('encryptKey')."')
		FROM cards
		LEFT JOIN categories ON (cards.cate_id = categories.category_id)
		LEFT OUTER JOIN users ON (cards.card_used_by = users.user_id)
		LEFT OUTER JOIN image_mapping ON (cards.card_type = image_mapping.image_map_id)
		WHERE card_id > 0 ".$where."
		ORDER BY ".$order_by." DESC"
		);
		$this->gridviews=CRefactorPagination::$resultsPagi;
		$this->pagination=CRefactorPagination::$pagination;
	}
	
	public static function buildCAWhere($category,$country,$type,$used,$status,$extension){
		/*
		 * keep search condition
		 */
		$session = A::app()->getSession();
		$session->set('cacategory',CFilter::sanitize('integer',trim($category))); 
		$session->set('caountry',CFilter::sanitize('string',trim($country)));
		$session->set('catype',CFilter::sanitize('integer',trim($type)));
		$session->set('cause',CFilter::sanitize('integer',trim($used)));
		$session->set('castatus',CFilter::sanitize('integer',trim($status)));
		$session->set('caextension',trim($extension));
		// ----------------------->
		$CWhere = '';
		
		if(!empty($category) && $category != 0){
			$CWhere .= " AND cate_id ='".CFilter::sanitize('integer',trim($category))."'"; // search by category 
		}
		if(!empty($country) && $country != 'all'){
			$CWhere .= " AND card_country LIKE '%".CFilter::sanitize('string',trim($country))."%'"; // search like country 
		}
		if(!empty($type) && $type != 0){
			$CWhere .= " AND card_type = '".CFilter::sanitize('integer',trim($type))."'"; // search by card type 
		}
		if(!empty($used) && $used != 0){
			$CWhere .= " AND card_used_by > 0"; // search by card type
		}
		if(!empty($status) && $status != 0){
			switch ($status){
				case 1: $CWhere .= " AND card_used_status = 2"; // search by status approved
						break;
				case 2: $CWhere .= " AND card_used_status = 3"; // search by status declined
						break;
				case 3: $CWhere .= " AND card_used_status > 4"; // search by status declined
						break;
			}
		}
		if(!empty($extension) && $extension != ''){
			
			$extension = explode(",",$extension);
			
			if(isset($extension[0])){
				$CWhere .= " AND card_digital LIKE '".trim($extension[0])."%'"; // search by card bin 
			}
			if(isset($extension[1])){
				$CWhere .= " AND card_state LIKE '".trim($extension[1])."%'"; // search by state
			}
			if(isset($extension[2])){
				$CWhere .= " AND card_zipcode LIKE '".trim($extension[2])."%'"; // search by zip
			}
			if(isset($extension[3])){
				$CWhere .= " AND card_city LIKE '".trim($extension[3])."%'"; // search city 
			}
			
		}
		return trim($CWhere);
	}
	
}
