<?php

class Accounts extends CModel
{

    public $gridviews;

    public $pagination;

    public function __construct()
    {
        parent::__construct();

    }

    public static function tDropList($default= null){
        $dropdown = '';
        $CModel = new CModel();
        $results = $CModel->db->select('
            SELECT image_map_id,image_map_name
            FROM image_mapping
            WHERE item_type_id = :item_type_id ',
            array(
                ':item_type_id' => 3,
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
    
	public static function countRestrictAnumber($total,$account_type){
		
		$CModel = new CModel();
		$results = $CModel->db->select('
            SELECT *
            FROM carts
            WHERE user_id = :user_id AND cart_type = :cart_type AND cart_item = :cart_item AND is_paid = :is_paid',
			array(
						':cart_type' => 3,
						':user_id' => CAuth::getLoggedId(), 
						':cart_item' => $account_type,
						':is_paid' => 0
			)
		);
		if(count($results) >0){
			$count=0;
			foreach($results as $rows){
				$count = $count + $rows['cart_quantity'];
			}
			return $total - $count;
		}else return $total;
	}
	
    public static function ctDropList($default= null){
        $dropdown = '';
        $CModel = new CModel();
        $results = $CModel->db->select('
            SELECT category_id,category_name
            FROM categories
            WHERE item_type_id = :item_type_id ',
            array(
                ':item_type_id' => 3,
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



    public function buildRefactorPaging($targetPath,$currentPage,$pageSize,$where=null){
        CRefactorPagination::parsePagi($targetPath,$currentPage,$pageSize,"
        SELECT
        *, COUNT(*) AS numbers,
        AES_DECRYPT(account_info,'".CConfig::get('encryptKey')."')
        FROM accounts
        LEFT JOIN categories ON (accounts.cate_id = categories.category_id) LEFT JOIN image_mapping ON (accounts.account_type = image_mapping.image_map_id)
        WHERE account_used_by = 0 ".$where."
        group by accounts.account_type"
        );
        $this->gridviews=CRefactorPagination::$resultsPagi;
        //var_dump($this->gridviews);die;
        $this->pagination=CRefactorPagination::$pagination;
        //var_dump($this->pagination);die;

    }
    private function getPriceByItemId($cId,$num){
        $CModel = new CModel();
        $row = $CModel->db->select('
            SELECT * FROM accounts
			LEFT JOIN categories ON (accounts.cate_id = categories.category_id) LEFT JOIN image_mapping ON (accounts.account_type = image_mapping.image_map_id)
			WHERE accounts.account_type = :account_type  group by accounts.account_type',
            array(
                ':account_type' => $cId,
            )
        );
        return (($row[0]['account_price'] + A::app()->getSession()->get('fee')) - (($row[0]['account_price'] * $row[0]['discount']) / 100)) * $num;
    }
    
	private function accountNumber($type){
		$CModel = new CModel();
		$row = $CModel->db->select('
            SELECT * FROM accounts
			WHERE account_type = :account_type AND account_used_by = 0',
				array(
						':account_type' => $type,
				)
		);
		return count($row);
	}
    public function addToCarts($ojbArray){
		
        if(!empty($ojbArray)){
            $CModel = new CModel();
            try {
                $list = '';
                foreach($ojbArray as $key => $ojb){
                		if($ojb > $this->accountNumber($key)){
                			throw new Exception("Quantity are excluded from my items (can be used for other people). Enter value no more than ".$this->accountNumber($key)." please !");
                		}
                        $data= array(
                            'cart_item'=>$key,
                            'cart_price'=>$this->getPriceByItemId($key,$ojb),
                        	'cart_quantity'=>$ojb,
                            'cart_type'=>3,
                            'user_id'=>CAuth::getLoggedId(),
                        );
                        $CModel->db->insert('carts',$data);
                        $list .=$ojb.",";
                }
                return array (
                    'message'=>count($ojbArray)." account(s) was successfully added to your cart. <a href='cart'><code>Checkout your cart</code></a>",
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
                'message'=>"As you requested , your account(s) does not found. Try different account(s) !",
                'type'=> 'error',
        );
    }
    public static function buildAWhere($category,$type){
        /*
         * keep search condition
         */
        $session = A::app()->getSession();
        $session->set('fee','');
        $session->set('acategory',CFilter::sanitize('integer',trim($category)));
        $session->set('atype',CFilter::sanitize('integer',trim($type)));
        // ----------------------->
        $CWhere = '';

        if(!empty($category) && $category != 0){
            $CWhere .= " AND cate_id ='".CFilter::sanitize('integer',trim($category))."'"; // search by category
        }
        if(!empty($type) && $type != 0){
            $CWhere .= " AND account_type = '".CFilter::sanitize('integer',trim($type))."'"; // search by card type
        }
        return trim($CWhere);
    }
    public static function removeAWhere(){
        $session = A::app()->getSession();
        unset($_SESSION['fee']);
        unset($_SESSION['acategory']);
        unset($_SESSION['atype']);
        unset($_SESSION['awhere']);
    }

}
