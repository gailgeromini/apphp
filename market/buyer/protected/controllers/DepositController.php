<?php

class DepositController extends CController
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

		$this->view->setMetaTags('title', 'Deposit');
		$this->view->activeLink= 'deposit' ;
	}

	public function indexAction()
	{
		$model = new Deposit();
		$session = A::app()->getSession();
		$cRequest = A::app()->getRequest();
		if($session->get('btc_address')==''){
			$session->set('btc_address',CFilter::sanitize('string',CRefactorBitcoinMethod::btcReceiveAddress(CConfig::get('btc_method.btc_address'))));
		}
		if($session->get('btc_rate')==''){
			$session->set('btc_rate',CRefactorBitcoinMethod::BtcToUsd(1));
		}
		if($cRequest->getPost('act') == 'deposit'){
			if($cRequest->getPost('EV_NUMBER') && $cRequest->getPost('EV_CODE')){
				$message=$model->depositPerfectMethod($cRequest->getPost('EV_NUMBER'),$cRequest->getPost('EV_CODE'));
				$this->view->Messages = $message["message"];
				$this->view->Mtype = $message["type"];
			}elseif($cRequest->getPost('BTC_ADDRESS')){
				$message=$model->depositBitcoinMethod($session->get('btc_address'));
				$this->view->Messages = $message["message"];
				$this->view->Mtype = $message["type"];
			}elseif($cRequest->getPost('BATCH_NUMBER')){
				$message=$model->depositBatchMethod($cRequest->getPost('BATCH_NUMBER'));
				$this->view->Messages = $message["message"];
				$this->view->Mtype = $message["type"];
			}
		}
		$this->view->render('deposit/index');
	}

	public function historyAction($page=null)
	{

		$this->view->currentPage = isset($page) ? $page : 1;
 		$this->view->modelUri = 'deposit/history';
 		$this->view->pageSize = 10;
 		$model = new Deposit();
 		// call refactor pagination class
		$model->buildRefactorPaging(
				$this->view->modelUri, 	//  set targetPath
				$this->view->currentPage, // set currentPage
				$this->view->pageSize, // set pageSize 
				CAuth::getLoggedId()
				);
		// assigned gridviews with page range
		$this->view->history =$model->gridviews; 
		// assigned pagination html
		$this->view->pagination=$model->pagination;
		$this->view->render('deposit/history');
	}
}