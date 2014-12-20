<?php
class Cart extends CModel
{
	private static $excluded = '';

	public function __construct()
	{
		parent::__construct();
	
	}
	
	public static function getDetailByCartItemId($id,$type){
		$CModel = new CModel();
		switch ($type){
			case 1: $table = 'cards';
					$item_id = 'card_id';
					break;
			case 2: $table = 'paypals';
					$item_id = 'paypal_id';
					break;
		}
		$row = $CModel->db->select("
            SELECT * FROM ".$table."
			WHERE ".$item_id." = :item_id",
				array(
						':item_id' => $id,
				)
		);
		return $row[0];
	}
	
	private static function ItemExisting($id,$type){
		$CModel = new CModel();
		switch ($type){
			case 1: $table = 'cards';
			$item_id = 'card_id';
			$used_by = 'card_used_by';
			break;
			case 2: $table = 'paypals';
			$item_id = 'paypal_id';
			$used_by = 'paypal_used_by';
			break;
		}
		$row = $CModel->db->select("
            SELECT * FROM ".$table."
			WHERE ".$item_id." =:id AND  ".$used_by."=:used_by",
				array(
						':id' => $id,
						':used_by' => 0,
				)
		);
		if(!empty($row)){
			return true;
		}else return false;
	}
	
	public function cartItemExisting($id){
		$CModel = new CModel();
		$row = $CModel->db->select("
            SELECT * FROM carts
			WHERE cart_id =:cart_id AND user_id =:user_id",
				array(
						':cart_id' => $id,
						':user_id' => CAuth::getLoggedId(),
				)
		);
		if(!empty($row)){
			return true;
		}else return false;
	}
	
	public function buildCardsToCart(){
		$CModel = new CModel();
		$row = $CModel->db->select("
		SELECT * FROM carts 
		WHERE is_paid = :is_paid AND user_id = :user_id AND cart_type = :cart_type
		ORDER BY cart_id DESC",
		array(
				':is_paid' => 0,
				':cart_type' => 1,
				':user_id' => CAuth::getLoggedId(),
			)
		);
		return $row;
	}
	
	public function buildPaypalsToCart(){
		$CModel = new CModel();
		$row = $CModel->db->select("
		SELECT * FROM carts
		WHERE is_paid = :is_paid AND user_id = :user_id AND cart_type = :cart_type
		ORDER BY cart_id DESC",
				array(
						':is_paid' => 0,
						':cart_type' => 2,
						':user_id' => CAuth::getLoggedId(),
				)
		);
		return $row;
	}
	
	public function buildAccountsToCart(){
		$CModel = new CModel();
		$row = $CModel->db->select("
		SELECT * FROM carts
		LEFT JOIN image_mapping ON (carts.cart_item = image_mapping.image_map_id)
		WHERE is_paid = :is_paid AND user_id = :user_id AND cart_type = :cart_type
		ORDER BY cart_id DESC",
				array(
						':is_paid' => 0,
						':cart_type' => 3,
						':user_id' => CAuth::getLoggedId(),
				)
		);
		return $row;
	}
	
	public function deteleFromCarts($id){
	
			$CModel = new CModel();
			try {
					if($id == 'all'){
						$aWhere = 'user_id ='.CAuth::getLoggedId().' AND is_paid =0' ;
						$CModel->db->delete('carts',$aWhere);
						return array (
								'message'=> 'All item(s) was successfully deleted from your cart.',
								'type'=> 'success',
						);
					}
					elseif($this->cartItemExisting($id)){
						$CModel = new CModel();
						$aWhere = 'user_id ='.CAuth::getLoggedId().' AND cart_id ='.$id ;
						$CModel->db->delete('carts',$aWhere);
						return array (
								'message'=> "Item(s) #".$id." was successfully deleted from your cart.",
								'type'=> 'success',
						);
					    }else return array (
								'message'=>'You are trying to delete unexisting item from your cart.',
								'type'=> 'error',
						);
				}
				catch (Exception $e) {
					return array (
							'message'=>$e->getMessage(),
							'type'=> 'error',
					);
				}

	}
	private static function getCartItemId(){
			$CModel = new CModel();
			$row = $CModel->db->select("
			SELECT * FROM carts
			WHERE is_paid = :is_paid AND user_id = :user_id
			ORDER BY cart_id DESC",
					array(
							':is_paid' => 0,
							':user_id' => CAuth::getLoggedId(),
					)
			);
			return $row;
	}
	private static function checkValidCredits(){
		   $CModel = new CModel();
		   $rows = $CModel->db->select("
			SELECT * FROM carts
			WHERE is_paid = :is_paid AND user_id = :user_id",
		   		array(
		   				':is_paid' => 0,
		   				':user_id' => CAuth::getLoggedId(),
		   		)
		   );
		   $totalPrice= 0;
		   foreach ($rows as $row){
		   	$totalPrice = $totalPrice + $row['cart_price'];
		   }
		   if($totalPrice > CRefactorProfile::getBalance(CAuth::getLoggedId())){
		   		return array (
		   			'message'=>'Your credit does not enough to purchase this cart ('.$totalPrice.'$) , Please trying remove item(s) or deposit to continue checkout  .',
		   			'type'=> 'error',
		   		);
		   }else return false;
	}
	private static function processUpdateCredit($price){
		$CModel = new CModel();
		$CModel->db->update('users',
				 array(
		   				'user_credits' => ((CRefactorProfile::getBalance(CAuth::getLoggedId())) - $price),
		   		), 
				"user_id='".CAuth::getLoggedId()."'"
		);
		if($CModel->db->getErrorMessage()){
			return false;
		}else return true;
	}
	private static function processRemoveCart($id){
		$CModel = new CModel();
		$aWhere = 'user_id ='.CAuth::getLoggedId().' AND cart_id ='.$id ;
		$CModel->db->delete('carts',$aWhere);
		if($CModel->db->getErrorMessage()){
			return false;
		}else return true;
	}
	private static function validationAccountCheckout($type,$quantity){
		$CModel = new CModel();
		$rows = $CModel->db->select("
			SELECT * FROM accounts
			WHERE account_used_by = :account_used_by AND account_type = :account_type",
		   		array(
		   				':account_used_by' => 0,
		   				':account_type' => $type,
		   		)
		);
		if(count($rows) >= $quantity){
			return true;
		}else return false;
	}
	private static function accountCheckout($type,$cart_id,$quantity){
		if(self::validationAccountCheckout($type,$quantity)){
			$CModel = new CModel();
			$count = 0;
			for($i=0;$i < $quantity;$i++){
					$data = array(
							'account_used_date' => date( 'Y-m-d H:i:s', time() ),
							'account_used_by'=>CAuth::getLoggedId(),
	                        'cart_id'=>$cart_id,
					);
	                $aWhere = "account_used_by =0 AND account_type =".$type." LIMIT 1";
					$CModel->db->update('accounts',
							$data,
							$aWhere
					);
			}		
			if($CModel->db->getErrorMessage()){
				return false;
			}
		}else{
			self::processRemoveCart($cart_id); // remove cart id
			self::$excluded = 1;
			return false;
		}
		
	}
	private static function processUpdateItem($id,$type,$cart_id,$quantity,$cart_session){
		$CModel = new CModel();
		switch ($type){
			case 1: $table = 'cards';
					$item_id = 'card_id';
					$used_by = 'card_used_by';
					$data = array(
								'card_used_date'=> date( 'Y-m-d H:i:s', time() ),
								'card_used_by'=>CAuth::getLoggedId(),
							);
				break;
			case 2: $table = 'paypals';
					$item_id = 'paypal_id';
					$used_by = 'paypal_used_by';
					$data = array(
								'paypal_used_date' => date( 'Y-m-d H:i:s', time() ),
								'paypal_used_by'=>CAuth::getLoggedId(),
						);
				break;
			case 3:
					return self::accountCheckout($id,$cart_id,$quantity); // return method processUpdateAccount()
				break;
		}
		if(self::ItemExisting($id, $type)){ // check item valid or invalid
			return $CModel->db->update($table,
					$data,
					$item_id.'='.$id.' AND '.$used_by.'=0'
			);
		}else{
			self::processRemoveCart($cart_id); // remove cart id
			self::$excluded = 1;
			return false;
		}
	}
	private static function processUpdateCart($id,$cart_session){
		$CModel = new CModel();
		$CModel->db->update('carts',
				array(
						'is_paid' => 1,
						'cart_session'=> $cart_session ,
				),
				"cart_id='".$id."'"
		);
		if($CModel->db->getErrorMessage()){
			return false;
		}else return true;
	}
	private static function processCheckOut($ojbArray,$cart_session){
		
		if(self::processUpdateItem($ojbArray['cart_item'], $ojbArray['cart_type'],$ojbArray['cart_id'],$ojbArray['cart_quantity'],$cart_session)){
			self::processUpdateCredit($ojbArray['cart_price']);// update balance user
			self::processUpdateCart($ojbArray['cart_id'],$cart_session); // update cart 
		}else return false;
		 
	}
	public function checkOutFromCarts(){
			try {
				if(self::checkValidCredits()){
					return self::checkValidCredits();
				}else{
					$row = self::getCartItemId();
					if(count($row) > 0){
						$cart_session = CHash::getRandomString(8);
						CRefactorWriteLogs::WriteLogs('Completed its checkout : #'.$cart_session.'',CAuth::getLoggedId(),1); // write logs login
						foreach($row as $r){
							self::processCheckOut($r,$cart_session);
						}
						if(self::$excluded > 0){
							return array (
									'message'=>'Completed its checkout. However some item(s) are excluded from cart (can be used for other people)',
									'type'=> 'alert',
							);
						}else{
							return array (
									'message'=>'Your cart has successfully completed its checkout.',
									'type'=> 'success',
							);
						}
					}else return array (
							'message'=>'You are trying to checkout unexisting item from your cart.',
							'type'=> 'error',
					);
					
				}
			}
			catch (Exception $e) {
				return array (
						'message'=>$e->getMessage(),
						'type'=> 'error',
				);
			}
		
	}
}
