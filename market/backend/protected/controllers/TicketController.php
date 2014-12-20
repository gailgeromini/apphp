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

        $cRequest = A::app()->getRequest();
        $session = A::app()->getSession();
        $model = new Ticket();
        $act =  isset($_REQUEST['act']) ? trim($_REQUEST['act']) : "";
        if($cRequest->getPost('act') == 'search'){
            $this->view->type = $cRequest->getPost('type');
            $this->view->status = $cRequest->getPost('status');
            $this->view->extension = $cRequest->getPost('extension');
            $session->set('ticketwhere',$model::buildPAYWhere($this->view->type,$this->view->status,$this->view->extension));
        }
        if($cRequest->getPost('action') == 'View All'){
            $model::removePWhere();
        }
		$this->view->currentPage = isset($page) ? $page : 1;
		$this->view->targetPath = 'ticket/index';
		$this->view->pageSize = 20;
		$model->buildTicketRefactorPaging(
				$this->view->targetPath, 	//  set targetPath
				$this->view->currentPage, // set currentPage
				$this->view->pageSize, // set pageSize
            A::app()->getSession()->get('ticketwhere')

        );
		$this->view->ticket =$model->gridviews;
		// assigned pagination html
		$this->view->pagination=$model->pagination;
        $this->view->cDroplist=$model::cDropList(A::app()->getSession()->get('tickettype'));
        $this->view->sDropList=$model::sDropList(A::app()->getSession()->get('ticketstatus'));
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