<?php

class CardsController extends CController
{
    
	public function __construct()
	{
       parent::__construct();
       // block access to this controller for not-logged users
       CRefactorAdmin::handleAdminLogin();
       CRefactorProfile::handleAdminMember(CAuth::getLoggedId());
       $this->view->setMetaTags('title', 'Admin Card');
       $this->view->activeLink = 'cards' ;
    }
	
	public function indexAction($page = null)
	{
 		$cRequest = A::app()->getRequest();
		$session = A::app()->getSession();
		$model = new Cards();
		$act =  isset($_REQUEST['act']) ? trim($_REQUEST['act']) : "";
 		if($cRequest->getPost('act') == 'search'){
 			$this->view->type = $cRequest->getPost('type');
			$this->view->category = $cRequest->getPost('category');
			$this->view->country = $cRequest->getPost('country');
			$this->view->used = $cRequest->getPost('card_used');
			$this->view->status = $cRequest->getPost('card_status');
			$this->view->extension = $cRequest->getPost('extension');
			$session->set('cawhere',$model::buildCAWhere($this->view->category, $this->view->country, $this->view->type ,$this->view->used,$this->view->status, $this->view->extension));
 		}
		$this->view->currentPage = isset($page) ? $page : 1;
		$this->view->targetPath = 'cards/index';
		$this->view->pageSize = 20;
		$model->buildRefactorPaging(
				$this->view->targetPath, 	//  set targetPath
				$this->view->currentPage, // set currentPage
				$this->view->pageSize, // set pageSize
				A::app()->getSession()->get('cawhere')
			);
		// assigned gridviews with page range
		$this->view->cDroplist=$model::cDropList(A::app()->getSession()->get('cacountry'));
		$this->view->tDroplist=$model::tDropList(A::app()->getSession()->get('catype'));
		$this->view->ctDroplist=$model::ctDropList(A::app()->getSession()->get('cacategory'));
		$this->view->sDroplist=$model::sDropList(A::app()->getSession()->get('castatus'));
		$this->view->uDroplist=$model::uDropList(A::app()->getSession()->get('cause'));
		$this->view->cards =$model->gridviews; 
		// assigned pagination html
		$this->view->pagination=$model->pagination;
		$this->view->render('cards/index');
		
	}
	
}