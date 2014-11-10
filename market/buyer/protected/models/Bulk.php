<?php

class Bulk extends CModel
{

	public $gridviews;
	
	public $pagination;
	
	private static $excluded = '';
	
	public function __construct()
	{
		parent::__construct();
	
	}
	
	
	public function buildRefactorPaging($targetPath,$currentPage,$pageSize,$where=null){
		CRefactorPagination::parsePagi($targetPath,$currentPage,$pageSize,"
		SELECT * 
		FROM bulks
		WHERE m_used_by = 0
		ORDER BY m_id DESC"
		);
		$this->gridviews=CRefactorPagination::$resultsPagi;
		$this->pagination=CRefactorPagination::$pagination;
	}
	private function getBulkDetail($ojb){
		$CModel = new CModel();
		$row = $CModel->db->select("
			SELECT * FROM bulks
			WHERE m_used_by = :m_used_by AND m_id = :m_id",
				array(
						':m_used_by' => 0,
						':m_id' => $ojb,
				)
		);
		return $row[0];
	}
	
	private static function checkValidCredits($ojb){
		$CModel = new CModel();
		$rows = $CModel->db->select("
			SELECT * FROM bulks
			WHERE m_used_by = 0 AND m_id IN (".join(',',$ojb).")"
		);
		$totalPrice= 0;
		foreach ($rows as $row){
			$totalPrice = $totalPrice + $row['m_price'];
		}
		if($totalPrice > CRefactorProfile::getBalance(CAuth::getLoggedId())){
			return array (
					'message'=>'Your credit does not enough to purchase this bulk(s) ('.$totalPrice.'$) , Please trying uncheck some bulk(s) or deposit to continue checkout  .',
					'type'=> 'error',
			);
		}else return false;
	}
	
	private static function processUpdateItem($id,$file){
		$CModel = new CModel();
		$data = array(
					'm_file_path' => $file,
					'm_used_by' => CAuth::getLoggedId(),
		);
		return $CModel->db->update("bulks",
					$data,
					"m_id=".$id." AND m_used_by =0"
		);
		
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
	
	private static function renameBulkLink($file){
		$newfile = CHash::getRandomString(12);
		if(rename(APPHP_PATH.DS."bulk_upload".DS.$file,APPHP_PATH.DS."tmp".DS.$newfile.".txt") == true ){
			return $newfile.".txt";
		}
	}
	
	private static function processCheckOut($ojbArray,$file){
	
		if(self::processUpdateItem($ojbArray['m_id'],$file)){
			self::processUpdateCredit($ojbArray['m_price']);// update balance user
			CRefactorWriteLogs::WriteLogs('Completed bulk(s) checkout : #'.$file,CAuth::getLoggedId(),1); // write logs login
		}else return false;
			
	}
	
	public function bulkCheckout($ojbArray){
		
		if(!empty($ojbArray)){
			$CModel = new CModel();
			try {
				
					if(self::checkValidCredits($ojbArray)){
						return self::checkValidCredits($ojbArray);
					}else{
						$list = '';
						foreach($ojbArray as $ojb){
								$bulk = $this->getBulkDetail($ojb);
								$file = $this->renameBulkLink($bulk['m_file_path']);
								$this->processCheckOut($bulk,$file);
						}
						return array (
								'message'=>count($ojbArray)." bulk(s) was successfully  to checkout. <a href='order/bulk'><code>View download link</code></a>",
								'type'=> 'success',
						);
						
					 }
				}
			 	catch (Exception $e) {
				return array (
			 			'message'=>$e->getMessage(),
			 			'type'=> 'error',
			 	);
			}

		}else 
			 return array (
			 			'message'=>'You are trying to checkout unexisting item(s) from bulk email.',
						'type'=> 'error',
		);
	}
	
	
}
