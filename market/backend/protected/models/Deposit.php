<?php

class Deposit extends CModel
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
            SELECT distinct (payment_account)
            FROM payments'
        );
        if(count($results) > 0){
            foreach ($results as $result) {
                $value=$result['payment_account'];
                $name=$result['payment_account'];
                $attr = ($default != null && $value == $default ? "selected='selected'" : "");
                $dropdown .= "<option value='".$value."' $attr>".$name."</option>";
            }
            return "<option value='0'>All Payment Type</option>".$dropdown;
        }else false;
    }

	public function buildRefactorPaging($targetPath,$currentPage,$pageSize,$where=null){
		CRefactorPagination::parsePagi($targetPath,$currentPage,$pageSize,'
		SELECT payment_amount
			 , payment_date
			 , payment_account
			 , user_name
			 , payment_updated
			 , payment_commis
			 , payment_batch
			 , case when payment_method_id = 1 then "templates/default/files/images/pm40x16.png"
			   else "templates/default/files/images/btc40x16.png"
			   end as payment_method
		 FROM payments
		 LEFT JOIN users ON (payments.user_id = users.user_id)
		 WHERE payment_id > 0 '.$where.'
		 ORDER BY payment_date DESC'
		);
		$this->gridviews=CRefactorPagination::$resultsPagi;
		$this->pagination=CRefactorPagination::$pagination;
	}
    public static function buildPAYWhere($type,$extension){
        /*
         * keep search condition
         */
        $session = A::app()->getSession();
        $session->set('paytype',CFilter::sanitize('string',trim($type)));
        $session->set('payextension',trim($extension));
        // ----------------------->
        $CWhere = '';

        if(!empty($type)){
            $CWhere .= " AND payment_account = '".CFilter::sanitize('string',trim($type))."'"; // search by payment type
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
        unset($_SESSION['paytype']);
        unset($_SESSION['payextension']);
    }
}
