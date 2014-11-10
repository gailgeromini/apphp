<?php

/**
 * Cprofile helper class file
 *
 *
 */

class CRefactorProfile extends CModel
{

	public static function getProfile($id)
	{
		$CModel = new CModel();
		$result = $CModel->db->select('
            SELECT *
            FROM '.CConfig::get('db.prefix').'users
			LEFT JOIN '.CConfig::get('db.prefix').'ac_role
			ON '.CConfig::get('db.prefix').'users.ac_role_id = '.CConfig::get('db.prefix').'ac_role.ac_role_id
            WHERE '.CConfig::get('db.prefix').'users.user_id = :id',
				array(':id' => (int)$id)
		);
		if(count($result) > 0){
			return $result[0];
		}else{
			return array('user_name'=>'', 'user_password'=>'','user_password'=>'',
					'user_email'=>'','user_credits'=>'','user_sub_credits'=>'',
					'user_status'=>'','user_regdate'=>'','ac_role_id'=>'','ac_role_name'=>'');
		}
	}
	public static function getBalance($uid){
		$ojb=self::getProfile($uid);
		return $ojb['user_credits'];
	}
	public static function getTotalCarts($id)
	{
		$CModel = new CModel();
		$result = $CModel->db->select('
            SELECT *
            FROM '.CConfig::get('db.prefix').'carts
            WHERE '.CConfig::get('db.prefix').'user_id = :id AND is_paid = :is_paid',
				array(':id' => (int)$id, 'is_paid' => 0)
		);
		return count($result);
	}
	public static function getTicketReport($uid){
		$CModel = new CModel();
		$result = $CModel->db->select("
            SELECT *
            FROM ".CConfig::get('db.prefix')."ticket
            WHERE ".CConfig::get('db.prefix')."ticket_status_id NOT IN ('4','5') AND user_id = :user_id",
				array('user_id' => $uid)
		);
		return count($result);
	}
	public static function getPriceCarts($id)
	{
		$CModel = new CModel();
		$result = $CModel->db->select('
            SELECT cart_price
            FROM '.CConfig::get('db.prefix').'carts
            WHERE '.CConfig::get('db.prefix').'user_id = :id AND is_paid = :is_paid',
				array(':id' => (int)$id, 'is_paid' => 0)
		);
		$total = 0;
		foreach($result as $row){
			$total = $total + $row['cart_price'];
		}
		return $total;
	}
	public static function isAdmin($id){
		$Profile = self::getProfile($id);
		if($Profile['ac_role_id'] == 1){
			return true;
		}else return false;
	}
	public static function handleAdminMember($id){
		$Profile = self::getProfile($id);
		if(APPHP_MODE == 'test') return '';
		if($Profile['ac_role_id'] != 1){
			header('location: '.A::app()->getRequest()->getBaseUrl().'./error/');
			exit;
		}
	}
	public static function handleActiveMember($id)
	{
		$Profile = self::getProfile($id);
		if(APPHP_MODE == 'test') return '';
		if($Profile['user_status'] == 0){
			header('location: '.A::app()->getRequest()->getBaseUrl().'./error/inactive');
			exit;
		}
	}
	public static function handleBannedMember($id)
	{
		$Profile = self::getProfile($id);
		if(APPHP_MODE == 'test') return '';
		if($Profile['ac_role_id'] == 3){
			header('location: '.A::app()->getRequest()->getBaseUrl().'./error/banned');
			exit;
		}
	}
	public static function handleUndepositedMember($id){

		$CModel = new CModel();
		$result = $CModel->db->select('
            SELECT *
            FROM '.CConfig::get('db.prefix').'payments
            WHERE '.CConfig::get('db.prefix').'payments.user_id = :id',
				array(':id' => (int)$id)
		);
		if(count($result) == 0){
			header('location: '.A::app()->getRequest()->getBaseUrl().'./error/undeposited');
			exit;
		}else{
			return true;
		}
	}
	
	public static function getPerfect(){
		$CModel = new CModel();
		$rows = $CModel->db->select("
            SELECT * FROM configs
			WHERE type =:type AND enable =:enable",
				array(
						':type' => 'Perfect',
						':enable' => 1,
				)
		);
		if($rows){
			$perfect = array();
			foreach($rows as $row){
				$perfect[$row['config_name']] = $row['params'];
			}
			return $perfect;
		}else return false;
	}
}