<?php

class Paypals extends CModel
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
            SELECT distinct (paypal_country)
            FROM paypals
            WHERE paypal_used_by = :paypal_used_by ',
				array(
						':paypal_used_by' => 0,
						)
		);
		if(count($results) > 0){
		foreach ($results as $result) {
			$value=$result['paypal_country'];
			$name=$result['paypal_country'];
			$attr = ($default != null && $value == $default ? "selected='selected'" : "");
			$dropdown .= "<option value='".$value."' $attr>".$name."</option>";
			
		}
		return "<option value='all'>All Country</option>".$dropdown;
		}else false;
	}
	
	public static function typeDropList($default= null){
		$dropdown = '';
		$CModel = new CModel();
		$results = $CModel->db->select('
            SELECT distinct (paypal_type)
            FROM paypals
             WHERE paypal_used_by = :paypal_used_by ',
				array(
						':paypal_used_by' => 0,
				)
		);
		if(count($results) > 0){
		foreach ($results as $result) {
			$value=$result['paypal_type'];
			$name=$result['paypal_type'];
			$attr = ($default != null && $value == $default ? "selected='selected'" : "");
			$dropdown .= "<option value='".$value."' $attr>".$name."</option>";
				
		}
		return "<option value='all'>All Type</option>".$dropdown;
		}else false;
	}
	
	public static function catDropList($default= null){
		$dropdown = '';
		$CModel = new CModel();
		$results = $CModel->db->select('
            SELECT category_id,category_name
            FROM categories
            WHERE item_type_id = :item_type_id ',
				array(
						':item_type_id' => 2,
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
	
	public static function restrictPaypalsAddedtoCart(){
		
		$CModel = new CModel();
		$results = $CModel->db->select('
            SELECT cart_item
            FROM carts
            WHERE user_id = :user_id AND cart_type = :cart_type ',
			array(
						':cart_type' => 2,
						':user_id' => CAuth::getLoggedId(), 
			)
		);
		if(count($results) >0){
			$cart_item_list = '';
			foreach ($results as $result){
				$cart_item_list .= ",'".$result['cart_item']."'";
			}
			return "AND paypal_id NOT IN (".substr($cart_item_list,1).")";
		}else return false;
	}
	
	public function buildRefactorPaging($targetPath,$currentPage,$pageSize,$where=null){
		CRefactorPagination::parsePagi($targetPath,$currentPage,$pageSize,"
		SELECT *,
		AES_DECRYPT(paypal_info,'".CConfig::get('encryptKey')."')
		FROM paypals
		LEFT JOIN categories ON (paypals.cate_id = categories.category_id) LEFT OUTER JOIN image_mapping ON (paypals.type_id = image_mapping.image_map_id)
		WHERE paypal_used_by = 0 ".self::restrictPaypalsAddedtoCart().$where."
		ORDER BY paypal_id DESC"
		);
		$this->gridviews=CRefactorPagination::$resultsPagi;
		$this->pagination=CRefactorPagination::$pagination;
	}
	private function getPriceByPaypalId($pId){
		$CModel = new CModel();
		$row = $CModel->db->select('
            SELECT * FROM paypals
			LEFT JOIN categories ON (paypals.cate_id = categories.category_id)
			WHERE paypals.paypal_id = :paypal_id ',
			array(
						':paypal_id' => $pId,
			)
		);
		return ($row[0]['paypal_price'] + A::app()->getSession()->get('fee')) - (($row[0]['paypal_price'] * $row[0]['discount']) / 100);
	}
	private function restrictDuplicateCart($pId){
		$CModel = new CModel();
		$row = $CModel->db->select('
            SELECT * FROM carts
			WHERE cart_item = :cart_item AND user_id =:user_id AND cart_type = :cart_type',
				array(
						':cart_item' => $pId,
						':user_id' => CAuth::getLoggedId(),
						':cart_type' =>2,
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
				foreach($ojbArray as $ojb){
					if(!$this->restrictDuplicateCart($ojb)){
						
						throw new Exception("Your paypal(s) really added . Try different card(s) !");
						
					}else{
						$data= array(
								'cart_item'=>$ojb,
								'cart_price'=>$this->getPriceByPaypalId($ojb),
								'cart_type'=>2,
								'user_id'=>CAuth::getLoggedId(),
						);
						$CModel->db->insert('carts',$data);
						$list .=$ojb.",";
					}
					
				}
				return array (
						'message'=>count($ojbArray)." paypal(s) was successfully added to your cart. <a href='cart'><code>Checkout your cart</code></a>",
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
			 			'message'=>"As you requested , your paypal(s) does not found. Try different paypal(s) !",
			 			'type'=> 'error',
			 		);
	}
	public static function buildPWhere($category,$country,$type,$email,$balance){
		/*
		 * keep search condition
		 */
		$session = A::app()->getSession();
		$session->set('fee','');
		$session->set('pcategory',CFilter::sanitize('integer',trim($category))); 
		$session->set('pcountry',CFilter::sanitize('string',trim($country)));
		$session->set('ptype',CFilter::sanitize('string',trim($type)));
		$session->set('pemail',CFilter::sanitize('string',trim($email)));
		$session->set('pbalance',CFilter::sanitize('string',trim($balance)));
		// ----------------------->
		$CWhere = '';
		
		if(!empty($category) && $category != 0){
			$CWhere .= " AND cate_id ='".CFilter::sanitize('integer',trim($category))."'"; // search by category 
		}
		if(!empty($country) && $country != 'all'){
			$CWhere .= " AND paypal_country LIKE '%".CFilter::sanitize('string',trim($country))."%'"; // search like country 
		}
		if(!empty($type) && $type != 'all'){
			$session->set('fee',CConfig::get('fee')); // Added fee to search
			$CWhere .= " AND paypal_type LIKE '%".CFilter::sanitize('string',trim($type))."%'"; // search by card type 
		}
		if(!empty($email)){
			$CWhere .= " AND paypal_is_email = 1"; // search by email login
		}
		if(!empty($balance)){
			$CWhere .= " AND paypal_balance not like '%0.00%' AND paypal_balance not like '%0,00%'"; // search by balance available
		}
		return trim($CWhere);
	}
	
	public static function removePWhere(){
		$session = A::app()->getSession();
		unset($_SESSION['fee']);
		unset($_SESSION['pcategory']);
		unset($_SESSION['pcountry']);
		unset($_SESSION['ptype']);
		unset($_SESSION['pemail']);
		unset($_SESSION['pbalance']);
		unset($_SESSION['pwhere']);
		
		
	}
	
	
	
}
