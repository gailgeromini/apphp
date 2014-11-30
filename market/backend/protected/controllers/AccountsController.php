<?php

class AccountsController extends CController
{

    public function __construct()
    {
        parent::__construct();
        // block access to this controller for not-logged users
        CRefactorAdmin::handleAdminLogin();
        CRefactorProfile::handleAdminMember(CAuth::getLoggedId());
        $this->view->setMetaTags('title', 'Admin Account');
        $this->view->activeLink = 'accounts' ;
    }

    public function indexAction($page = null)
    {

        $cRequest = A::app()->getRequest();
        $session = A::app()->getSession();
        $model = new Accounts();
        $act =  isset($_REQUEST['act']) ? trim($_REQUEST['act']) : "";
        if($cRequest->getPost('act') == 'search'){
            $this->view->type = $cRequest->getPost('type');
            $this->view->category = $cRequest->getPost('category');
            $this->view->country = $cRequest->getPost('country');
            $this->view->used = $cRequest->getPost('paypal_used');
            $this->view->status = $cRequest->getPost('paypal_status');
            $this->view->extension = $cRequest->getPost('extension');
            $this->view->pemail= isset($_REQUEST['pemail']) ? $cRequest->getPost('pemail') : "";
            $this->view->pbalance= isset($_REQUEST['pbalance']) ? $cRequest->getPost('pbalance') : "";
            $session->set('pawhere',$model::buildCAWhere($this->view->category, $this->view->country, $this->view->type ,$this->view->used, $this->view->extension,$this->view->pemail,$this->view->pbalance));
        }
        if($cRequest->getPost('action') == 'Add Type'){
            $data = array(
                "image_map_name"   => $cRequest->getPost('txtType'),
                "image_map_uri"  => $cRequest->getPost('txtImage'),
                "item_type_id" => 3
            );
            $model->insert($data);
        }
        $this->view->currentPage = isset($page) ? $page : 1;
        $this->view->targetPath = 'accounts/index';
        $this->view->pageSize = 20;
        $model->buildRefactorPaging(
            $this->view->targetPath, 	//  set targetPath
            $this->view->currentPage, // set currentPage
            $this->view->pageSize, // set pageSize
            A::app()->getSession()->get('pawhere')
        );
        // assigned gridviews with page range
        $this->view->cDroplist=$model::cDropList(A::app()->getSession()->get('pacountry'));
        $this->view->tDroplist=$model::tDropList(A::app()->getSession()->get('patype'));
        $this->view->ctDroplist=$model::ctDropList(A::app()->getSession()->get('pacategory'));
        $this->view->uDroplist=$model::uDropList(A::app()->getSession()->get('pause'));
        $this->view->paypals =$model->gridviews;
        // assigned pagination html
        $this->view->pagination=$model->pagination;
        $this->view->render('accounts/index');

    }
}