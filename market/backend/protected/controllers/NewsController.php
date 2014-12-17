<?php

class NewsController extends CController
{

    public function __construct()
    {
        parent::__construct();
        // block access to this controller for not-logged users
        CRefactorAdmin::handleAdminLogin();
        CRefactorProfile::handleAdminMember(CAuth::getLoggedId());
        $this->view->setMetaTags('title', 'Admin News');
        $this->view->activeLink = 'news' ;
    }

    public function indexAction($page = null)
    {

        $cRequest = A::app()->getRequest();
        $session = A::app()->getSession();
        $model = new News();
        if($cRequest->getPost('action') == 'Add Type'){
            $data = array(
                "image_map_name"   => $cRequest->getPost('txtType'),
                "image_map_uri"  => $cRequest->getPost('txtImage'),
                "item_type_id" => 3
            );
            $model->insert($data);
        }
        $this->view->currentPage = isset($page) ? $page : 1;
        $this->view->targetPath = 'news/index';
        $this->view->pageSize = 20;
        $model->buildRefactorPaging(
            $this->view->targetPath, 	//  set targetPath
            $this->view->currentPage, // set currentPage
            $this->view->pageSize
        );
        // assigned gridviews with page range
        $this->view->news =$model->gridviews;
        // assigned pagination html
        $this->view->pagination=$model->pagination;
        $this->view->render('news/index');

    }
}