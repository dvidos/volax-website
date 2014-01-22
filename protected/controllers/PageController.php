<?php

class PageController extends Controller
{
	public function actionView($url_keyword)
	{
		$page = Page::model()->find(array(
			'condition'=>'url_keyword = :uk', 
			'params'=>array(':uk'=>$url_keyword))
		);
		if ($page === null)
			throw new CHttpException(404,'The requested page does not exist.');
			
		$this->render('view',array(
			'model'=>$page,
		));
	}
}
