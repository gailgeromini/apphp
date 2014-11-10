<?php

class News extends CModel
{
    var $limited = '';
    
	public function __construct()
    {
        parent::__construct();
    }

    public function blockNews($number='')
    {
    	
    	$result = $this->db->select('
            SELECT news_title,news_content,news_create
            FROM '.CConfig::get('db.prefix').'news ORDER BY news_id DESC LIMIT '.$number.''
        );
        if(!empty($result)){
        	
            return $result;
            
        }else{
        	
            return false;        
        }        
    }  

}
