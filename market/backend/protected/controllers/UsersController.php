<?php

class UsersController extends CController
{
	public function __construct()
	{
		parent::__construct();
		 // block access to this controller for not-logged users
        CRefactorAdmin::handleAdminLogin();
        CRefactorProfile::handleAdminMember(CAuth::getLoggedId());
		$this->view->setMetaTags('title', 'Users');
		$this->view->activeLink= 'users' ;
	}

	public function indexAction($page=null)
	{	$cRequest = A::app()->getRequest();
		$session = A::app()->getSession();
		$this->view->extension = $cRequest->getPost('extension');
		$model = new Users();
		if($cRequest->getPost('act') == 'search'){
			$session->set('uwhere',$model::buildUWhere($this->view->extension));
		}
		$this->view->currentPage = isset($page) ? $page : 1;
 		$this->view->targetPath = 'users/index';
 		$this->view->pageSize = 20;
 		// call refactor pagination class
		$model->buildRefactorPaging(
				$this->view->targetPath, 	//  set targetPath
				$this->view->currentPage, // set currentPage
				$this->view->pageSize, // set pageSize 
				A::app()->getSession()->get('uwhere')// set condition
				);
		// assigned gridviews with page range
		$this->view->users =$model->gridviews; 
		// assigned pagination html
		$this->view->pagination=$model->pagination;
		$this->view->render('users/index');
	}
	public function deleteAction($id = null){
	
		$this->view->userID = $id;
		
		if($this->view->userID){
			$model = new Users();
			$message=$model->deteleUsers($this->view->userID);
			$this->view->Messages = $message["message"];
			$this->view->Mtype = $message["type"];
		}else{
			$this->view->Messages = 'You are trying to delete unexisting user from your database.';
			$this->view->Mtype = 'error';
		}
		$this->view->render('users/delete');
	}
	public function editAction($id = null){
		$this->view->userID = $id;
		$cRequest = A::app()->getRequest();
		if(empty($id)){
			$this->view->Messages = 'You are trying to views unexisting user from your database.';
			$this->view->Mtype = 'error';
			$this->view->render('users/delete');

		}else{
			
			$model = new Users();
			if($cRequest->getPost('act') == 'save'){
				$this->view->extension = $cRequest->getPost('extension');
				$message=$model->updateUserByUserId($id, $cRequest->getPost('email'), $cRequest->getPost('password'), $cRequest->getPost('credit'), $cRequest->getPost('user_role'), $cRequest->getPost('user_status'));
				$this->view->Messages = $message["message"];
				$this->view->Mtype = $message["type"];
			}
			$users = $model->getDetailUserByUserId($id);
			$this->view->user_name=$users['user_name'];
			$this->view->user_email=$users['user_email'];
			$this->view->user_credits=$users['user_credits'];
			$this->view->sDroplist=$model::sDropList($users['ac_role_id']);
			$this->view->aDroplist=$model::aDropList($users['user_status']);
			$this->view->render('users/edit');
		}
		
	}
}