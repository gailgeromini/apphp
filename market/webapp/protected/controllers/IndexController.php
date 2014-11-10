<?php

class IndexController extends CController
{

	public function __construct()
	{
        parent::__construct();

    }
	
	public function indexAction()
	{
		$this->view->render('index/index');
	}
}