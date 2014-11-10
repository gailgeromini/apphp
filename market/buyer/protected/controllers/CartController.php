<?php

class CartController extends CController
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
        $this->view->setMetaTags('title', 'Opps Cart');
        $this->view->activeLink= '' ;
    }
	
	public function indexAction()
	{
		$model = new Cart();
		$this->view->cards =$model->buildCardsToCart();
		$this->view->paypals =$model->buildPaypalsToCart();
		$this->view->render('cart/index');
	}
	public function deleteAction($id = null){
		
		$this->view->ItemID = $id;
		if($this->view->ItemID){
			$model = new Cart();
			$message=$model->deteleFromCarts($this->view->ItemID);
			$this->view->Messages = $message["message"];
			$this->view->Mtype = $message["type"];
		}else{
			$this->view->Messages = 'You are trying to delete unexisting item from your cart.';
			$this->view->Mtype = 'error';
		}
		$model = new Cart();
		$this->view->cards =$model->buildCardsToCart();
		$this->view->paypals =$model->buildPaypalsToCart();
		$this->view->render('cart/index');
	}
	public function checkoutAction(){
		$model = new Cart();
		$message=$model->checkOutFromCarts();
		$this->view->Messages = $message["message"];
		$this->view->Mtype = $message["type"];
		$this->view->cards =$model->buildCardsToCart();
		$this->view->paypals =$model->buildPaypalsToCart();
		$this->view->render('cart/index');
	}
	
}