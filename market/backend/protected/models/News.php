<?php

class News extends CModel
{

    public $gridviews;

    public $pagination;

    public function __construct()
    {
        parent::__construct();

    }

    public function buildRefactorPaging($targetPath,$currentPage,$pageSize,$where=null){

        $order_by = 'news_id';
        CRefactorPagination::parsePagi($targetPath,$currentPage,$pageSize,"
		SELECT *
	    FROM news".$where."
		ORDER BY ".$order_by." DESC"
        );
        $this->gridviews=CRefactorPagination::$resultsPagi;
        $this->pagination=CRefactorPagination::$pagination;
    }
}
