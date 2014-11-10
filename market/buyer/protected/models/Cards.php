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
            FROM cards
            WHERE card_used_by = :card_used_by ',
				array(
						':card_used_by' => 0,
						)
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
	
	public static function restrictCardsAddedtoCart(){
		
		$CModel = new CModel();
		$results = $CModel->db->select('
            SELECT cart_item
            FROM carts
            WHERE user_id = :user_id AND cart_type = :cart_type ',
			array(
						':cart_type' => 1,
						':user_id' => CAuth::getLoggedId(), 
			)
		);
		if(count($results) >0){
			$cart_item_list = '';
			foreach ($results as $result){
				$cart_item_list .= ",'".$result['cart_item']."'";
			}
			return "AND card_id NOT IN (".substr($cart_item_list,1).")";
		}else return false;
	}
	
	public function buildRefactorPaging($targetPath,$currentPage,$pageSize,$where=null){
		CRefactorPagination::parsePagi($targetPath,$currentPage,$pageSize,"
		SELECT *,
		AES_DECRYPT(card_info,'".CConfig::get('encryptKey')."')
		FROM cards
		LEFT JOIN categories ON (cards.cate_id = categories.category_id) LEFT OUTER JOIN image_mapping ON (cards.card_type = image_mapping.image_map_id)
		WHERE card_used_by = 0 ".self::restrictCardsAddedtoCart().$where."
		ORDER BY card_id DESC"
		);
		$this->gridviews=CRefactorPagination::$resultsPagi;
		$this->pagination=CRefactorPagination::$pagination;
	}
	private function getPriceByCardId($cId){
		$CModel = new CModel();
		$row = $CModel->db->select('
            SELECT * FROM cards
			LEFT JOIN categories ON (cards.cate_id = categories.category_id)
			WHERE cards.card_id = :card_id ',
			array(
						':card_id' => $cId,
			)
		);
		return ($row[0]['card_price'] + A::app()->getSession()->get('fee')) - (($row[0]['card_price'] * $row[0]['discount']) / 100);
	}
	private function restrictDuplicateCart($cId){
		$CModel = new CModel();
		$row = $CModel->db->select('
            SELECT * FROM carts
			WHERE cart_item = :cart_item AND user_id =:user_id AND cart_type = :cart_type',
				array(
						':cart_item' => $cId,
						':user_id' => CAuth::getLoggedId(),
						':cart_type' =>1,
				)
		);
		if($row){
			return false;
		}else return true;
	}
	public function addToCarts($ojbArray){

		if(!empty($ojbArray)){
			$CModel = new CModel();
			try {
				$list = '';
				foreach($ojbArray as $ojb){
					if(!$this->restrictDuplicateCart($ojb)){
						
						throw new Exception("Your card(s) really added . Try different card(s) !");
						
					}else{
						$data= array(
								'cart_item'=>$ojb,
								'cart_price'=>$this->getPriceByCardId($ojb),
								'cart_type'=>1,
								'user_id'=>CAuth::getLoggedId(),
						);
						$CModel->db->insert('carts',$data);
						$list .=$ojb.",";
					}
					
				}
				return array (
						'message'=>count($ojbArray)." card(s) was successfully added to your cart. <a href='cart'><code>Checkout your cart</code></a>",
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
			 			'message'=>"As you requested , your card(s) does not found. Try different card(s) !",
			 			'type'=> 'error',
			 		);
	}
	public static function buildCWhere($category,$country,$type,$extension){
		/*
		 * keep search condition
		 */
		$session = A::app()->getSession();
		$session->set('fee','');
		$session->set('ccategory',CFilter::sanitize('integer',trim($category))); 
		$session->set('ccountry',CFilter::sanitize('string',trim($country)));
		$session->set('ctype',CFilter::sanitize('integer',trim($type)));
		$session->set('cextension',trim($extension));
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
		if(!empty($extension) && $extension != ''){
			
			$session->set('fee',CConfig::get('fee')); // Added fee to search
			
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
	public static function removeCWhere(){
		$session = A::app()->getSession();
		unset($_SESSION['fee']);
		unset($_SESSION['ccategory']);
		unset($_SESSION['ccountry']);
		unset($_SESSION['ctype']);
		unset($_SESSION['cextension']);
		unset($_SESSION['cwhere']);
	
	
	}
	
}
