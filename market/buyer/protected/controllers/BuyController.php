<?php

class BuyController extends CController
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
       // restricted access to this controller for undeposited users
       CRefactorProfile::handleUndepositedMember(CAuth::getLoggedId());
       $this->view->setMetaTags('title', 'Opps Buying');
       $this->view->activeLink = 'buy' ;
    }
	
	public function indexAction()
	{
		$this->redirect('./buy/cards'); // paypals defaults 
	}
	
	public function cardsAction($page = null)
	{
 		$cRequest = A::app()->getRequest();
		$session = A::app()->getSession();
		$model = new Cards();
		$act =  isset($_REQUEST['act']) ? trim($_REQUEST['act']) : "";
 		if($cRequest->getPost('act') == 'search'){
 			$this->view->type = $cRequest->getPost('type');
			$this->view->category = $cRequest->getPost('category');
			$this->view->country = $cRequest->getPost('country');
			$this->view->extension = $cRequest->getPost('extension');
			$session->set('cwhere',$model::buildCWhere($this->view->category, $this->view->country, $this->view->type , $this->view->extension));
 		}
 		elseif($act == 'addcarts'){
 			$listCards = $_REQUEST['cards'];
 			$message = $model->addToCarts($listCards);
 			$this->view->Messages = $message["message"];
 			$this->view->Mtype = $message["type"];
 		}
 		if($cRequest->getPost('action') == 'Show All Cards'){
 			$model::removeCWhere();
 		}
		$this->view->currentPage = isset($page) ? $page : 1;
		$this->view->targetPath = 'buy/cards';
		$this->view->pageSize = 20;
		$model->buildRefactorPaging(
				$this->view->targetPath, 	//  set targetPath
				$this->view->currentPage, // set currentPage
				$this->view->pageSize, // set pageSize
				A::app()->getSession()->get('cwhere')
			);
		// assigned gridviews with page range
		$this->view->cDroplist=$model::cDropList(A::app()->getSession()->get('ccountry'));
		$this->view->tDroplist=$model::tDropList(A::app()->getSession()->get('ctype'));
		$this->view->ctDroplist=$model::ctDropList(A::app()->getSession()->get('ccategory'));
		$this->view->cards =$model->gridviews; 
		// assigned pagination html
		$this->view->pagination=$model->pagination;
		$this->view->render('buy/cards');
		
	}
	public function paypalsAction($page  =null,$all = null)
	{
		$cRequest = A::app()->getRequest();
		$session = A::app()->getSession();
		$model = new Paypals();
		$act =  isset($_REQUEST['act']) ? trim($_REQUEST['act']) : "";
 		if($cRequest->getPost('act') == 'search'){
 			$this->view->ptype = $cRequest->getPost('ptype');
 			$this->view->pcategory = $cRequest->getPost('pcategory');
 			$this->view->pcountry = $cRequest->getPost('pcountry');
 			$this->view->pemail= isset($_REQUEST['pemail']) ? $cRequest->getPost('pemail') : "";
 			$this->view->pbalance= isset($_REQUEST['pbalance']) ? $cRequest->getPost('pbalance') : "";
 			$session->set('pwhere',$model::buildPWhere($this->view->pcategory,$this->view->pcountry,$this->view->ptype,$this->view->pemail,$this->view->pbalance));
 		}
		if($act == 'addcarts'){
			$listPaypals = $_REQUEST['paypals'];
			$message = $model->addToCarts($listPaypals);
			$this->view->Messages = $message["message"];
			$this->view->Mtype = $message["type"];
		}
		if($cRequest->getPost('action') == 'Show All Paypals'){
			$model::removePWhere();
		}
		$this->view->currentPage = isset($page) ? $page : 1;
		$this->view->targetPath = 'buy/paypals';
		$this->view->pageSize = 20;
		$model->buildRefactorPaging(
				$this->view->targetPath, 	//  set targetPath
				$this->view->currentPage, // set currentPage
				$this->view->pageSize, // set pageSize
				A::app()->getSession()->get('pwhere')
		);
	//assigned gridviews with page range
		$this->view->cDroplist=$model::cDropList(A::app()->getSession()->get('pcountry'));
		$this->view->typeDroplist=$model::typeDropList(A::app()->getSession()->get('ptype'));
		$this->view->catDroplist=$model::catDropList(A::app()->getSession()->get('pcategory'));
		$this->view->paypals =$model->gridviews;
 		// assigned pagination html
 		$this->view->pagination=$model->pagination;
		$this->view->render('buy/paypals');
	
	}
	
    public function accountsAction($page = null)
    {
        $cRequest = A::app()->getRequest();
        $session = A::app()->getSession();
        $model = new Accounts();
        $act =  isset($_REQUEST['act']) ? trim($_REQUEST['act']) : "";
        if($cRequest->getPost('act') == 'search'){
            $this->view->type = $cRequest->getPost('type');
            $this->view->category = $cRequest->getPost('category');
            $this->view->country = $cRequest->getPost('country');
            $session->set('awhere',$model::buildAWhere($this->view->category, $this->view->country, $this->view->type));
        }
        elseif($act == 'addcarts'){
            $listCards = $_REQUEST['cards'];
            $message = $model->addToCarts($listCards);
            $this->view->Messages = $message["message"];
            $this->view->Mtype = $message["type"];
        }
        if($cRequest->getPost('action') == 'Show All Accounts'){
            $model::removeAWhere();
        }
        $this->view->currentPage = isset($page) ? $page : 1;
        $this->view->targetPath = 'buy/accounts';
        $this->view->pageSize = 20;
        $model->buildRefactorPaging(
            $this->view->targetPath, 	//  set targetPath
            $this->view->currentPage, // set currentPage
            $this->view->pageSize, // set pageSize
            A::app()->getSession()->get('awhere')
        );
        // assigned gridviews with page range
        $this->view->cDroplist=$model::cDropList(A::app()->getSession()->get('acountry'));
        $this->view->tDroplist=$model::tDropList(A::app()->getSession()->get('atype'));
        $this->view->ctDroplist=$model::ctDropList(A::app()->getSession()->get('acategory'));
        $this->view->accounts =$model->gridviews;
        // assigned pagination html
        $this->view->pagination=$model->pagination;
        $this->view->render('buy/accounts');

    }
}