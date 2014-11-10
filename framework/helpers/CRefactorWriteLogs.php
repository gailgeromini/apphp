<?php
/**
 * Cprofile helper class file
 *
 * 
 */	  

class CRefactorWriteLogs extends CModel
{
    
	public static function WriteLogs($message,$uid,$level)
	{
		$CModel = new CModel();
		$CModel->db->insert('logs',
				array(
						'logs_message' => $message,
						'user_id'=> $uid ,
						'logs_level' => $level,
				)
		);
		if($CModel->db->getErrorMessage()){
			return false;
		}else return true;
		
	}

}
