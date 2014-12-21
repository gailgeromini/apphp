<?php

class IndexController extends CController
{

	public function __construct()
	{
        parent::__construct();
        // block access to this controller for not-logged users
        CAuth::handleLogin();
        // block access to this controller for inactive users
        CRefactorProfile::handleActiveMember(CAuth::getLoggedId());
        // block access to this controller for banned users
        CRefactorProfile::handleBannedMember(CAuth::getLoggedId());
               
        $this->view->setMetaTags('title', 'Welcome ACUG');
        $this->view->activeLink= 'index' ;
		
      		
    }
	
	public function indexAction()
	{

		$model = new News();
		// retrieve all records from DB
		$records = $model->blockNews(5);
		$records1 = $model->blockNews1(5);
		// assign data to the view variables
		$this->view->records = $records;
		$this->view->records1 = $records1;
		// pass prepared data to the view file
		$this->view->render('index/index');
	}
	
}