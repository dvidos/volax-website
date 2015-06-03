<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to 'column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	
	
	
	public function beforeAction($action)
	{
		$this->setDefaultTheme();
		
		return parent::beforeAction($action);
	}
	
	function setDefaultTheme()
	{
		$theme_name = '';
		
		if (Yii::app()->request->userAgent != null && 
			(stripos(Yii::app()->request->userAgent, 'mobile') !== false || 
			stripos(Yii::app()->request->userAgent, 'tablet') !== false))
			$theme_name = 'mobile';
		
		// if requested, save to session
		if (@$_REQUEST['theme'] == 'mobile' || @$_REQUEST['theme'] == 'none')
			$theme_name = @$_REQUEST['theme'];
		
		// if detected or requested, save to session for later
		if (!empty($theme_name))
			Yii::app()->session['theme_name'] = $theme_name;
		
		// set the theme from session (either detected, requested, or saved in session)
		Yii::app()->theme = Yii::app()->session['theme_name'];
	}
}