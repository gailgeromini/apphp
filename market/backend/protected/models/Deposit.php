<?php

class Deposit extends CModel
{

	public $gridviews;
	
	public $pagination;
	
	public function __construct()
	{
		parent::__construct();
	
	}

	public function buildRefactorPaging($targetPath,$currentPage,$pageSize){
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
		 ORDER BY payment_date DESC'
		);
		$this->gridviews=CRefactorPagination::$resultsPagi;
		$this->pagination=CRefactorPagination::$pagination;
	}
}
