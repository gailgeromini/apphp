<?php

class Login extends CModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login($username, $password)
    {
        $result = $this->db->select('
            SELECT user_id, ac_role_id
            FROM '.CConfig::get('db.prefix').'users
            WHERE user_name = :user AND user_password = :password',
            array(
                ':user' => $username,
                ':password' => ((CConfig::get('password.encryption')) ? CHash::create(CConfig::get('password.encryptAlgorithm'), $password, CConfig::get('password.hashKey')) : $password)
            )
        );
        if(!empty($result)){
            $session = A::app()->getSession();
            $session->set('role', $result[0]['ac_role_id']);
            $session->set('loggedIn', true);
            $session->set('loggedId', $result[0]['user_id']);
            return true;
        }else{
            return false;        
        }        
    }    
}
