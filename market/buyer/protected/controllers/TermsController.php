<?php

class TermsController extends CController
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
       $this->view->setMetaTags('title', 'Terms & Privacy');
       $this->view->activeLink = 'terms' ;
    }

	public function indexAction()
	{
		$this->redirect('terms/tos');
	}
	public function tosAction()
	{
		$this->view->render('terms/tos');
	}
	public function faqAction()
	{
		$this->view->render('terms/faq');
	}
}