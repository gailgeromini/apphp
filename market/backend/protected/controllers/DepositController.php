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
        $cRequest = A::app()->getRequest();
        $session = A::app()->getSession();
        $model = new Deposit();
        $act =  isset($_REQUEST['act']) ? trim($_REQUEST['act']) : "";
      
        if($cRequest->getPost('act') == 'search'){
            $this->view->type = $cRequest->getPost('type');
            $this->view->extension = $cRequest->getPost('extension');
            $session->set('paywhere',$model::buildPAYWhere($this->view->type,$this->view->extension));
        }
        if($cRequest->getPost('action') == 'View All'){
        	$model::removePWhere();
        }
		$this->view->currentPage = isset($page) ? $page : 1;
 		$this->view->modelUri = 'deposit/index';
 		$this->view->pageSize = 20;
 		// call refactor pagination class
		$model->buildRefactorPaging(
				$this->view->modelUri, 	//  set targetPath
				$this->view->currentPage, // set currentPage
				$this->view->pageSize, // set pageSize
                A::app()->getSession()->get('paywhere')
				);
        $this->view->cDroplist=$model::cDropList(A::app()->getSession()->get('paytype'));
        // assigned gridviews with page range
		$this->view->history =$model->gridviews; 
		// assigned pagination html
		$this->view->pagination=$model->pagination;
		$this->view->render('deposit/index');
	}

}