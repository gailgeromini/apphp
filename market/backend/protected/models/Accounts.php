<?php

class Accounts extends CModel
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
            FROM paypals'
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

    public static function tDropList($default= null){
        $dropdown = '';
        $CModel = new CModel();
        $results = $CModel->db->select('
            SELECT image_map_name
            FROM image_mapping
            WHERE item_type_id = :item_type_id ',
            array(
                ':item_type_id' => 3,
            )
        );
        if(count($results) > 0){
            foreach ($results as $result) {
                $value=$result['image_map_name'];
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

    public static function uDropList($default= null){
        $obj = array ('0'=>'All Paypals','1'=>'Paypals Used','2'=>'Paypals Unuse') ;
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

        $order_by = 'paypal_id';
        if(A::app()->getSession()->get('pastatus') || A::app()->getSession()->get('pause')){
            $order_by = "paypal_used_date";
        }
        CRefactorPagination::parsePagi($targetPath,$currentPage,$pageSize,"
		SELECT *,
		AES_DECRYPT(paypal_info,'".CConfig::get('encryptKey')."')
		FROM paypals
		LEFT JOIN categories ON (paypals.cate_id = categories.category_id)
		LEFT OUTER JOIN users ON (paypals.paypal_used_by = users.user_id)
		LEFT OUTER JOIN image_mapping ON (paypals.type_id = image_mapping.image_map_id)
		WHERE paypal_id > 0 ".$where."
		ORDER BY ".$order_by." DESC"
        );
        $this->gridviews=CRefactorPagination::$resultsPagi;
        $this->pagination=CRefactorPagination::$pagination;
    }

    public static function buildCAWhere($category,$country,$type,$used,$extension,$email,$balance){
        /*
         * keep search condition
         */
        $session = A::app()->getSession();
        $session->set('pacategory',CFilter::sanitize('integer',trim($category)));
        $session->set('paountry',CFilter::sanitize('string',trim($country)));
        $session->set('patype',CFilter::sanitize('string',trim($type)));
        $session->set('pause',CFilter::sanitize('integer',trim($used)));
        $session->set('pemail',CFilter::sanitize('string',trim($email)));
        $session->set('pbalance',CFilter::sanitize('string',trim($balance)));
        $session->set('paextension',trim($extension));
        // ----------------------->
        $CWhere = '';

        if(!empty($category) && $category != 0){
            $CWhere .= " AND cate_id ='".CFilter::sanitize('integer',trim($category))."'"; // search by category
        }
        if(!empty($country) && $country != 'all'){
            $CWhere .= " AND paypal_country LIKE '%".CFilter::sanitize('string',trim($country))."%'"; // search like country
        }
        if(!empty($type)){
            $CWhere .= " AND paypal_type = '".CFilter::sanitize('string',trim($type))."'"; // search by card type
        }
        if(!empty($used) && $used == 1){
            $CWhere .= " AND paypal_used_by > 0"; // search by card type
        }
        if(!empty($used) && $used == 2){
            $CWhere .= " AND paypal_used_by < 0"; // search by card type
        }
        if(!empty($email)){
            $CWhere .= " AND paypal_is_email = 1"; // search by email login
        }
        if(!empty($balance)){
            $CWhere .= " AND paypal_balance not like '%0.00%' AND paypal_balance not like '%0,00%'"; // search by balance available
        }

        if(!empty($extension) && $extension != ''){

            $extension = explode(",",$extension);

            if(isset($extension[0])){
                $CWhere .= " AND users.user_name LIKE '".trim($extension[0])."%'"; // search by card bin
            }

        }
        return trim($CWhere);
    }

    public function insert($data)
    {
        $CModel = new CModel();
        $CModel->db->insert('image_mapping',$data);
    }

    public static function removePWhere(){
        $session = A::app()->getSession();
        unset($_SESSION['pacategory']);
        unset($_SESSION['paountry']);
        unset($_SESSION['patype']);
        unset($_SESSION['pause']);
        unset($_SESSION['pemail']);
        unset($_SESSION['pbalance']);
        unset($_SESSION['paextension']);
    }
}
