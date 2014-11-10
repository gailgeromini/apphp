<?php

/**
 * Cprofile helper class file
 *
 *
 */

class CRefactorAdmin extends CModel
{

	
	public static function handleAdminLogin()
	{
		if(APPHP_MODE == 'test') return '';
		if(A::app()->getSession()->get('loggedIn') == false){
			session_destroy();
			header('location: http://www.opps.sx/buyer/login/index');
			exit;
		}
	}
	
	public static function getTotalAdminTicket()
	{
		$CModel = new CModel();
		$result = $CModel->db->select("
            SELECT *
            FROM ticket
            WHERE ticket_status_id IN ('1','3')"
		);
		if(empty($result)){
			return 0;
		}else return count($result);
		
	}
	public static function getTotalPayment()
	{
		$CModel = new CModel();
		$result = $CModel->db->select("
            SELECT *
            FROM payments"
		);
		if(empty($result)){
			return 0;
		}else return count($result);
	
	}
	public static function getTotalUserActive()
	{
		$CModel = new CModel();
		$result = $CModel->db->select("
            SELECT *
            FROM users WHERE user_status=1"
		);
		if(empty($result)){
			return 0;
		}else return count($result);
	
	}
	public static function getTotalCardUnUsed()
	{
		$CModel = new CModel();
		$result = $CModel->db->select("
            SELECT *
            FROM cards WHERE card_used_by=0"
		);
		if(empty($result)){
			return 0;
		}else return count($result);
	
	}
	public static function getTotalPaypalUnUsed()
	{
		$CModel = new CModel();
		$result = $CModel->db->select("
            SELECT *
            FROM paypals WHERE paypal_used_by=0"
		);
		if(empty($result)){
			return 0;
		}else return count($result);
	
	}
}