<?phpclass DashboardController extends Controller{	public function actionIndex()	{		$this->render('index');	}		// view a stored page, just like in front end 	public function actionViewPage($url_keyword)	{		$page = Page::model()->find(array(			'condition'=>'url_keyword = :uk', 			'params'=>array(':uk'=>$url_keyword))		);		if ($page === null)			throw new CHttpException(404,'The requested page does not exist.');					$this->render('viewPage',array(			'model'=>$page,		));	}}