<?php

class IndexController extends CController
{

	public function __construct()
	{
        parent::__construct();
        // block access to this controller for not-logged users
        CRefactorAdmin::handleAdminLogin();
        CRefactorProfile::handleAdminMember(CAuth::getLoggedId());
        
        $this->view->setMetaTags('title', 'WikiShop ADMIN');
        $this->view->activeLink= 'index' ;
		
      		
    }
	
	public function indexAction()
	{
		$this->view->render('index/index');
	}
	
}