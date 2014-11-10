<?php

class OrderController extends CController
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
        $this->view->setMetaTags('title', 'Opps Orders');
        $this->view->activeLink= 'order' ;
    }
	
	public function indexAction()
	{
        $model = new Order();
        $this->view->newest =$model->builNewestOrder();
        $cRequest = A::app()->getRequest();
        if($cRequest->getPost('btn')){
        	$this->view->items = $cRequest->getPost('items');
        	$message = self::actionPageOrder($cRequest->getPost('btn'),$this->view->items);
        	$this->view->Messages = $message["message"];
 			$this->view->Mtype = $message["type"];
        }
        $this->view->render('order/index');
  
	}
	
	public function cardsAction($page=null)
	{
		$model = new Order();
		$this->view->currentPage = isset($page) ? $page : 1;
		$this->view->targetPath = 'order/cards';
		$this->view->pageSize = 10;
		$model->buildCartsOrderRefactorPaging(
				$this->view->targetPath, 	//  set targetPath
				$this->view->currentPage, // set currentPage
				$this->view->pageSize, // set pageSize
				'',
				1
		);
		$cRequest = A::app()->getRequest();
		if($cRequest->getPost('btn')){
			$this->view->items = $cRequest->getPost('items');
			$message = self::actionPageOrder($cRequest->getPost('btn'),$this->view->items);
			$this->view->Messages = $message["message"];
			$this->view->Mtype = $message["type"];
		}
		$this->view->cards =$model->gridviews;
		// assigned pagination html
		$this->view->pagination=$model->pagination;
		$this->view->render('order/cards');
	
	}
	public function bulkAction($file=null,$page=null)
	{
		$model = new Order();
		$this->view->currentPage = isset($page) ? $page : 1;
		$file = isset($file) ? trim($file) : "null";
		$this->view->targetPath = 'order/bulk/file/';
		$this->view->pageSize = 10;
		if(!empty($file) && $file != "null"){
				$return_file=file_get_contents("http://www.opps.sx/buyer/tmp/".$file);
				header("Content-type: text/txt");
				echo $return_file;
				exit();
		}
		$model->buildBulkOrderRefactorPaging(
				$this->view->targetPath, 	//  set targetPath
				$this->view->currentPage, // set currentPage
				$this->view->pageSize, // set pageSize
				''
		);
		$this->view->bulk =$model->gridviews;
		// assigned pagination html
		$this->view->pagination=$model->pagination;
		$this->view->render('order/bulk');
	
	}
	public function paypalsAction($page=null)
	{
		$model = new Order();
		$this->view->currentPage = isset($page) ? $page : 1;
		$this->view->targetPath = 'order/paypals';
		$this->view->pageSize = 10;
		$model->buildCartsOrderRefactorPaging(
				$this->view->targetPath, 	//  set targetPath
				$this->view->currentPage, // set currentPage
				$this->view->pageSize, // set pageSize
				'',
				2
		);
		$cRequest = A::app()->getRequest();
		if($cRequest->getPost('btn')){
			$this->view->items = $cRequest->getPost('items');
			$message = self::actionPageOrder($cRequest->getPost('btn'),$this->view->items);
			$this->view->Messages = $message["message"];
			$this->view->Mtype = $message["type"];
		}
		$this->view->paypals =$model->gridviews;
		// assigned pagination html
		$this->view->pagination=$model->pagination;
		$this->view->render('order/paypals');
	
	}
	private static function actionPageOrder($act,$list){
		$model = new Order();
		if($act == 'Cronjob Check'){
			
			return $model->addToCheckValid($list);
		}else return false;
	}
	
}