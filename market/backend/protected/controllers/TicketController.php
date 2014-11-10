<?php

class TicketController extends CController
{
	public function __construct()
	{
        parent::__construct();
        // block access to this controller for not-logged users
        CRefactorAdmin::handleAdminLogin();
        CRefactorProfile::handleAdminMember(CAuth::getLoggedId());
        $this->view->setMetaTags('title', 'Ticket');
        $this->view->activeLink= 'ticket' ;
    }
	
	public function indexAction($page=null)
	{
	
        $model = new Ticket();
		$this->view->currentPage = isset($page) ? $page : 1;
		$this->view->targetPath = 'ticket/index';
		$this->view->pageSize = 20;
		$model->buildTicketRefactorPaging(
				$this->view->targetPath, 	//  set targetPath
				$this->view->currentPage, // set currentPage
				$this->view->pageSize, // set pageSize
				''
		);
		$this->view->ticket =$model->gridviews;
		// assigned pagination html
		$this->view->pagination=$model->pagination;
		$this->view->render('ticket/index');
  
	}
	public function viewAction($id=null){
		$this->view->ticketID = $id;
		if($this->view->ticketID){
			$model = new Ticket();
				$cRequest = A::app()->getRequest();
				if($cRequest->getPost('btn') == 'Comment'){
					$this->view->comment = CFilter::sanitize('string',trim($cRequest->getPost('comment')));
					$model->commentPosting($this->view->comment, $this->view->ticketID);
				}
				if($cRequest->getPost('btn') == 'Replacement'){
					$this->view->comment = CFilter::sanitize('string',trim($cRequest->getPost('comment')));
					$model->commentReplacement($this->view->comment, $this->view->ticketID);
				}
				$this->view->ticketContent = $model->buildTicketDetailByTicketId($this->view->ticketID);
				$this->view->comment =  $model->buildTicketCommentByTicketId($this->view->ticketID);
		}
		else{
			$this->view->Messages = 'You are trying to view unexisting ticket <code>Missing Ticket ID</code>.';
			$this->view->Mtype = 'error';
		}
		$this->view->render('ticket/view');
		
	}
}