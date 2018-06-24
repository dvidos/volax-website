<?php

class LightboxLinkWidget extends CWidget
{
	public $htmlOptions = array();
	public $href = '';
	public $group = '';
	public $html = '';
	
	protected $assetsPath = '';
	protected $assetsUrl = '';
	
	public function init()
	{
		parent::init();
		
		$this->assetsPath = Yii::getPathOfAlias('application.components.LightboxLinkWidget');
		$this->assetsUrl = Yii::app()->getAssetManager()->publish($this->assetsPath, false, -1, false);
	}
	
	public function run()
	{
		Yii::app()->clientScript->registerCoreScript('jquery');
		Yii::app()->clientScript->registerScriptFile($this->assetsUrl . '/js/lightbox.js', CClientScript::POS_END);
		Yii::app()->clientScript->registerCssFile($this->assetsUrl . '/css/lightbox.css');
		
		echo chtml::tag('a', array_merge($this->htmlOptions, array(
			'href'=>$this->href,
			'data-lightbox'=>$this->group,
		)), $this->html);
	}
}