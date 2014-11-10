<?php

class Users extends CModel
{

	public $gridviews;
	
	public $pagination;
	
	public function __construct()
	{
		parent::__construct();
	
	}

	public function buildRefactorPaging($targetPath,$currentPage,$pageSize,$where=null){
		CRefactorPagination::parsePagi($targetPath,$currentPage,$pageSize,"
		 SELECT *
		 FROM users
		 LEFT JOIN ac_role ON (users.ac_role_id = ac_role.ac_role_id)
		 WHERE 1=1 ".$where."
		 ORDER BY users.user_id DESC"
		);
		$this->gridviews=CRefactorPagination::$resultsPagi;
		$this->pagination=CRefactorPagination::$pagination;
	}
	
	public static function buildUWhere($extension){
		
		/*
		 * keep search condition
		*/
		$session = A::app()->getSession();
		$session->set('users_ext',trim($extension));
		// ----------------------->
		
		$CWhere = '';
		
		if(!empty($extension) && $extension != ''){
				
			$extension = explode(",",$extension);
				
			if(isset($extension[0])){
				$CWhere .= " AND user_name LIKE '".trim($extension[0])."%'"; // search by username
			}
			if(isset($extension[1])){
				$CWhere .= " AND user_email LIKE '".trim($extension[1])."%'"; // search by email
			}
				
		}
		return trim($CWhere);
	}
	public function deteleUsers($id){
		$CModel = new CModel();
		$aWhere = 'user_id ='.$id ;
		$CModel->db->delete('users',$aWhere);
		$cWhere = 'card_used_by ='.$id ;
		$pWhere = 'paypal_used_by ='.$id ;
		$CModel->db->delete('ticket_comments',$aWhere);
		$CModel->db->delete('ticket',$aWhere);
		$CModel->db->delete('payments',$aWhere);
		$CModel->db->delete('logs',$aWhere);
		$CModel->db->delete('carts',$aWhere);
		$CModel->db->delete('cards',$cWhere);
		$CModel->db->delete('paypals',$pWhere);
		return array (
				'message'=> "User #".$id." was successfully deleted from your data.",
				'type'=> 'success',
		 );

     }
     public static function aDropList($default= null){
     	$obj = array ('0'=>'Deactived','1'=>'Actived') ;
     	$count = count($obj);
     	for($i=0;$i<$count;$i++){
     		$value=$i;
     		$name=$obj[$i];
     		$attr = ($default != null && $value == $default ? "selected='selected'" : "");
     		$dropdown .= "<option value='".$value."' $attr>".$name."</option>";
     	}
     	return $dropdown;
     		
     }
     public static function sDropList($default= null){
     	$obj = array ('1'=>'Administrator','2'=>'Active Member','3'=>'Banned Member') ;
     	$count = count($obj);
     	for($i=0;$i<$count;$i++){
     		$value=$i+1;
     		$name=$obj[$i+1];
     		$attr = ($default != null && $value == $default ? "selected='selected'" : "");
     		$dropdown .= "<option value='".$value."' $attr>".$name."</option>";
     	}
     	return $dropdown;
     	 
     }
	public function getDetailUserByUserId($id){
		$CModel = new CModel();
		$row = $CModel->db->select("
		 		 SELECT *
				 FROM users
				 LEFT JOIN ac_role ON (users.ac_role_id = ac_role.ac_role_id)
				 WHERE users.user_id=:user_id
				",
				array(
						':user_id' => $id,
				)
		);
		return $row[0];
	}
	public function updateUserByUserId($id,$email,$password,$credit,$role_id,$status){
		$CModel = new CModel();
		if(!empty($password)){
			$array =  array (
					'user_password' =>((CConfig::get('password.encryption')) ? CHash::create(CConfig::get('password.encryptAlgorithm'), $password, CConfig::get('password.hashKey')) : $password),
					'user_email' =>$email,
					'user_status' =>$status,
					'ac_role_id' =>$role_id,
					'user_credits' =>$credit,
				);
		}else{
			$array =  array (
					'user_email' =>$email,
					'user_status' =>$status,
					'ac_role_id' =>$role_id,
					'user_credits' =>$credit,
			);
		}
		$CModel->db->update('users', $array,"user_id='".$id."'");
		return array (
					'message'=> "User(s) #".$id." was successfully update.",
					'type'=> 'success',
		);
	}
}