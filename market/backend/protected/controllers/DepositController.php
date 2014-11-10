<?php

class DepositController extends CController
{
	public function __construct()
	{
		parent::__construct();
		// block access to this controller for not-logged users
		CRefactorAdmin::handleAdminLogin();
		CRefactorProfile::handleAdminMember(CAuth::getLoggedId());

		$this->view->setMetaTags('title', 'Deposit');
		$this->view->activeLink= 'deposit' ;
	}

	public function indexAction($page=null)
	{
		$this->view->currentPage = isset($page) ? $page : 1;
 		$this->view->modelUri = 'deposit/index';
 		$this->view->pageSize = 20;
 		$model = new Deposit();
 		// call refactor pagination class
		$model->buildRefactorPaging(
				$this->view->modelUri, 	//  set targetPath
				$this->view->currentPage, // set currentPage
				$this->view->pageSize // set pageSize 
				);
		// assigned gridviews with page range
		$this->view->history =$model->gridviews; 
		// assigned pagination html
		$this->view->pagination=$model->pagination;
		$this->view->render('deposit/index');
	}

}