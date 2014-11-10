<?php

class Console extends CModel
{


	
	public function __construct()
	{
		parent::__construct();
	
	}

	public function buildLogsConsole(){
		$CModel = new CModel();
		$row = $CModel->db->select("
            SELECT * FROM logs
			LEFT JOIN users ON
			logs.user_id = users.user_id WHERE DATE(logs_date)=DATE(NOW()) ORDER BY logs_id DESC"
		);
		if(!empty($row)){
			return $row;
		}else return false;
	}
	

}
