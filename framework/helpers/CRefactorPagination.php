<?php
/**
 * CRefactorPagination helper class file
 * @project Blzone Marketplace
 * @author TOM <BLZONE MARKETPLACE>
 * @link https://www.blzone.ru/
 * @copyright Copyright (c) 2013 ApPHP Framework
 * @version refactor pagination (1.0)
 */	

class CRefactorPagination extends CModel
{

	public static $resultsPagi;
	
	public static $pagination;
	
	/**
	 * Draws gridview pagination
	 * Usage: (in View file)
	 * CRefactorPagination::parsePagi($currentPage,$pageSize,$sqlStr);
	 * CRefactorPagination::dataGrid;
	 * CRefactorPagination::pagination;
	 */
	
	public static function parsePagi($targetPath,$currentPage,$pageSize,$sqlStr)
	{	
		if($currentPage){
			$start = ($currentPage - 1) * $pageSize;       // first item to display on this page
		}else{
			$start = 0;							    // if no page var is given, set start to 0
		}
		$CModel = new CModel();
		
		$selectsql="$sqlStr LIMIT $start, $pageSize";
		
		self::$resultsPagi = $CModel->db->select($selectsql);  // list item to display on per page
		 
		self::$pagination =  CWidget::pagination(array( // parse paginations
				'targetPath'   => $targetPath,
				'currentPage'  => $currentPage,
				'pageSize'     => $pageSize,
				'totalRecords' => count($CModel->db->select($sqlStr)),
				'linkType'     => 1,
				'paginationType' => 'fullNumbers'
		));
	}
	
}